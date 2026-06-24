// backend/src/services/visionService.js
const { exec } = require('child_process');
const path = require('path');
const fs = require('fs');
const FormData = require('form-data');
const axios = require('axios');
const config = require('../config/env');

const predictCliPath = path.join(__dirname, '../../../ai/predict_cli.py');
const aiDir = path.join(__dirname, '../../../ai');

// Probe candidates to find a working Python CLI executable with dependencies
const getPythonCommand = () => {
    return new Promise((resolve) => {
        const candidates = ['python', 'py', 'py -3', 'python3'];
        
        const checkCandidate = (index) => {
            if (index >= candidates.length) {
                return resolve('python'); // Default fallback
            }
            const cand = candidates[index];
            exec(`${cand} -c "import numpy, PIL, keras"`, (err) => {
                if (!err) {
                    return resolve(cand);
                } else {
                    checkCandidate(index + 1);
                }
            });
        };

        checkCandidate(0);
    });
};

const fallbackGeminiVision = async (imagePath, lang) => {
    const apiKeys = [...(config.geminiApiKeys || [])];
    if (apiKeys.length === 0 || !apiKeys[0] || apiKeys[0] === 'YOUR_GEMINI_API_KEY_HERE') {
        return { error: 'Python is not available on this server, and no Gemini API key is configured for fallback.' };
    }

    try {
        const base64Image = fs.readFileSync(imagePath, { encoding: 'base64' });
        const mimeType = imagePath.toLowerCase().endsWith('.png') ? 'image/png' : 'image/jpeg';

        const prompt = `You are an expert agronomist AI. Analyze the uploaded image of a plant/leaf.
Identify the plant and any disease/pest present. If it's healthy, label it as "Healthy".
Respond entirely in ${lang === 'gu' ? 'Gujarati' : lang === 'hi' ? 'Hindi' : 'English'}, except for the "disease" field which must be the formal English class name.

Return ONLY a valid JSON object matching this structure EXACTLY:
{
  "label": "Common Name of Pest/Disease (or Healthy)",
  "disease": "Scientific/Formal class name (e.g. Corn___Northern_Leaf_Blight)",
  "plant": "Name of the crop",
  "confidence": 0.95,
  "info": {
    "desc": "Short description of the condition",
    "irrigation": "Irrigation advice",
    "treatment": "Treatment or action required"
  }
}`;

        const payload = {
            contents: [{
                parts: [
                    { text: prompt },
                    { inlineData: { mimeType, data: base64Image } }
                ]
            }],
            generationConfig: {
                temperature: 0.2,
                responseMimeType: "application/json"
            }
        };

        // Try multiple models to avoid 'model not found' errors
        const fallbackModels = ['gemini-2.5-flash', 'gemini-2.0-flash', 'gemini-1.5-flash-latest', 'gemini-1.5-flash'];
        const apiKey = apiKeys[Math.floor(Math.random() * apiKeys.length)];
        
        let lastError = 'All models failed';
        for (const model of fallbackModels) {
            try {
                const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/${model}:generateContent?key=${apiKey}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                    signal: AbortSignal.timeout(30000)
                });

                const data = await response.json();
                if (!response.ok) {
                    lastError = data?.error?.message || 'Gemini Vision request failed.';
                    continue; // Try next model
                }

                const responseText = data.candidates[0].content.parts[0].text;
                const cleanedText = responseText.replace(/```json/g, '').replace(/```/g, '').trim();
                return JSON.parse(cleanedText);
            } catch (err) {
                lastError = err.message;
            }
        }
        
        return { error: lastError };

    } catch (err) {
        return { error: `AI Fallback Error: ${err.message}` };
    }
};

const callRemotePythonAPI = async (imagePath, lang) => {
    try {
        const formData = new FormData();
        formData.append('image', fs.createReadStream(imagePath));
        formData.append('lang', lang);

        // Remove trailing slash if present
        const apiUrl = process.env.PYTHON_API_URL.replace(/\/$/, '') + '/predict';

        const response = await axios.post(apiUrl, formData, {
            headers: formData.getHeaders(),
            timeout: 60000
        });

        return response.data;
    } catch (err) {
        console.error('Remote API Error:', err.response?.data || err.message);
        return { error: err.response?.data?.error || `Remote API Error: ${err.message}` };
    }
};

const runDiseaseDetection = async (imagePath, lang = 'en') => {
    const resolvedPath = path.resolve(imagePath);

    if (!fs.existsSync(resolvedPath)) {
        return { error: `Image file not found: ${imagePath}` };
    }

    // Tier 1: Dedicated Python Microservice (Option 3 architecture)
    if (process.env.PYTHON_API_URL) {
        console.log(`Forwarding image to dedicated Python API at ${process.env.PYTHON_API_URL}`);
        const result = await callRemotePythonAPI(resolvedPath, lang);
        
        // Return the result directly, whether it's a success or an error, so we can debug Render.
        return result;
    }

    const pythonCmd = await getPythonCommand();

    // Tier 2: Vercel Limitation Fallback
    // On Vercel, Python CLI script and Keras models won't exist. Fallback immediately to save time.
    if (!fs.existsSync(predictCliPath) || process.env.VERCEL === '1') {
        console.log("Vercel or missing Python script detected. Falling back to Gemini Vision.");
        return fallbackGeminiVision(resolvedPath, lang);
    }

    // Tier 3: Local CLI execution (localhost testing)
    return new Promise((resolve) => {
        const cmd = `${pythonCmd} "${predictCliPath}" --image "${resolvedPath}" --lang "${lang}"`;
        
        exec(cmd, { cwd: aiDir, timeout: 60000 }, async (error, stdout, stderr) => {
            if (error) {
                console.error(`Local Python CLI failed: ${stderr || error.message}. Falling back to Gemini...`);
                const fallbackResult = await fallbackGeminiVision(resolvedPath, lang);
                return resolve(fallbackResult);
            }

            try {
                const result = JSON.parse(stdout.trim());
                resolve(result);
            } catch (jsonErr) {
                console.error(`Invalid JSON returned from Python CLI: ${stdout}`);
                const fallbackResult = await fallbackGeminiVision(resolvedPath, lang);
                resolve(fallbackResult);
            }
        });
    });
};

module.exports = {
    runDiseaseDetection
};
