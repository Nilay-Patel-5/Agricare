// backend/src/services/visionService.js
const { exec } = require('child_process');
const path = require('path');
const fs = require('fs');

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

const runDiseaseDetection = async (imagePath, lang = 'en') => {
    const pythonCmd = await getPythonCommand();
    const resolvedPath = path.resolve(imagePath);

    if (!fs.existsSync(resolvedPath)) {
        return { error: `Image file not found: ${imagePath}` };
    }

    if (!fs.existsSync(predictCliPath)) {
        return { error: `Prediction CLI script not found at: ${predictCliPath}` };
    }

    return new Promise((resolve) => {
        const cmd = `${pythonCmd} "${predictCliPath}" --image "${resolvedPath}" --lang "${lang}"`;
        
        exec(cmd, { cwd: aiDir, timeout: 120000 }, (error, stdout, stderr) => {
            if (error) {
                console.error(`CLI execution error: ${stderr || error.message}`);
                return resolve({ error: stderr.trim() || error.message });
            }

            try {
                const result = JSON.parse(stdout.trim());
                resolve(result);
            } catch (jsonErr) {
                console.error(`Invalid JSON returned from Python CLI: ${stdout}`);
                resolve({ error: 'Invalid JSON response from AI prediction engine.' });
            }
        });
    });
};

module.exports = {
    runDiseaseDetection
};
