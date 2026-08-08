// backend/src/services/visionService.js
const { spawn } = require('child_process');
const path = require('path');
const fs = require('fs');
const FormData = require('form-data');
const axios = require('axios');

const tfPython = 'C:\\Users\\nilay\\tf-env\\Scripts\\python.exe';
const apiScript = path.join(__dirname, '../../../ai/api.py');
const aiDir = path.join(__dirname, '../../../ai');

let isStartingService = false;

// Auto-start local microservice if not already running
const ensureMicroserviceRunning = () => {
    return new Promise((resolve) => {
        if (isStartingService) return resolve(false);
        if (!fs.existsSync(tfPython) || !fs.existsSync(apiScript)) return resolve(false);

        isStartingService = true;
        console.log("Auto-starting Keras model microservice (ai/api.py)...");

        const child = spawn(tfPython, [apiScript], {
            cwd: aiDir,
            detached: true,
            stdio: 'ignore'
        });
        child.unref();

        // Give the service 4 seconds to warm up and bind port 8000
        setTimeout(() => {
            isStartingService = false;
            resolve(true);
        }, 4000);
    });
};

const getNotFoundResponse = (lang = 'en') => {
    const notFoundTitles = {
        en: "No Plant Disease Detected",
        gu: "કોઈ રોગ જણાયો નથી",
        hi: "कोई रोग नहीं मिला"
    };
    const notFoundDescs = {
        en: "The uploaded image does not show recognizable plant leaf disease symptoms. Please upload a clear, focused, well-lit photo of the crop leaf to scan again.",
        gu: "અપલોડ કરેલી છબીમાં છોડના રોગના કોઈ સ્પષ્ટ લક્ષણો જણાયા નથી. કૃપા કરીને પાનનો સ્પષ્ટ, કેન્દ્રિત અને સારો પ્રકાશ હોય તેવો ફોટો અપલોડ કરી ફરી સ્કેન કરો.",
        hi: "अपलोड की गई तस्वीर में पौधे के किसी रोग के लक्षण नहीं मिले हैं। कृपया फसल की पत्ती की स्पष्ट, केंद्रित और अच्छी रोशनी वाली तस्वीर अपलोड करके पुनः स्कैन करें।"
    };
    const plantNames = {
        en: "Unknown",
        gu: "અજ્ઞાત",
        hi: "अज्ञात"
    };

    return {
        notFound: true,
        label: notFoundTitles[lang] || notFoundTitles.en,
        plant: plantNames[lang] || plantNames.en,
        confidence: 0.0,
        disease: "unknown",
        engine: "Keras Model (plant_disease_model.keras)",
        top3: [],
        info: {
            desc: notFoundDescs[lang] || notFoundDescs.en,
            irrigation: "N/A",
            treatment: "N/A"
        }
    };
};

/**
 * Run Disease Detection directly against the user's .keras model (plant_disease_model.keras).
 * Strictly tests the actual model. Gemini and all third-party AI fallbacks have been completely removed.
 * Enforces a strict 30-second timeout.
 */
const runDiseaseDetection = async (imagePath, lang = 'en') => {
    const resolvedPath = path.resolve(imagePath);

    if (!fs.existsSync(resolvedPath)) {
        return getNotFoundResponse(lang);
    }

    const MODEL_TIMEOUT_MS = 30000; // 30-second strict timeout

    // Target API endpoint for the local or dedicated Python microservice (ai/api.py)
    const microserviceUrl = process.env.PYTHON_API_URL 
        ? process.env.PYTHON_API_URL.replace(/\/$/, '') + '/predict'
        : 'http://127.0.0.1:8000/predict';

    const sendRequest = async () => {
        const formData = new FormData();
        formData.append('image', fs.createReadStream(resolvedPath));
        formData.append('lang', lang);

        const response = await axios.post(microserviceUrl, formData, {
            headers: formData.getHeaders(),
            timeout: MODEL_TIMEOUT_MS
        });

        return response.data;
    };

    try {
        const data = await sendRequest();
        if (data && !data.error) {
            return data;
        } else {
            return getNotFoundResponse(lang);
        }
    } catch (apiErr) {
        // If connection was refused, auto-start the service and retry once
        if (apiErr.code === 'ECONNREFUSED') {
            console.log("Model microservice is offline. Attempting auto-start...");
            await ensureMicroserviceRunning();
            try {
                const data = await sendRequest();
                if (data && !data.error) return data;
            } catch (retryErr) {
                console.warn(`Model retry failed: ${retryErr.message}`);
            }
        } else {
            console.warn(`Model service did not respond within timeout or returned error: ${apiErr.message}`);
        }
        return getNotFoundResponse(lang);
    }
};

module.exports = {
    runDiseaseDetection
};
