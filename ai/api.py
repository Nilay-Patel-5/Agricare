import os
import json
# pyrefly: ignore [missing-import]
import numpy as np
# pyrefly: ignore [missing-import]
from flask import Flask, request, jsonify
# pyrefly: ignore [missing-import]
from werkzeug.utils import secure_filename  
# pyrefly: ignore [missing-import]
import keras
# pyrefly: ignore [missing-import]
from PIL import Image
import gc

app = Flask(__name__)

# Configure Keras/TF
os.environ["KERAS_BACKEND"] = "tensorflow"
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'
os.environ["CUDA_VISIBLE_DEVICES"] = "-1"

import tensorflow as tf
tf.config.threading.set_inter_op_parallelism_threads(1)
tf.config.threading.set_intra_op_parallelism_threads(1)

# Paths
DIR_PATH = os.path.dirname(__file__)
MODEL_BASE_NAME = "plant_disease_model"
KERAS_MODEL_PATH = os.path.join(DIR_PATH, f"{MODEL_BASE_NAME}.keras")
IMAGE_SIZE = (128, 128)

# Load model globally on startup to prevent slow `re-loading on every request!
print("Loading Keras Model... This might take a few seconds.")
try:
    global_model = keras.models.load_model(KERAS_MODEL_PATH)
    print("Model loaded successfully!")
except Exception as e:
    print(f"Error loading model: {e}")
    global_model = None

CLASS_NAMES = [
    'Apple___Apple_scab', 'Apple___Black_rot', 'Apple___Cedar_apple_rust', 'Apple___healthy',
    'Blueberry___healthy', 'Cherry_(including_sour)___Powdery_mildew', 'Cherry_(including_sour)___healthy',
    'Corn_(maize)___Cercospora_leaf_spot Gray_leaf_spot', 'Corn_(maize)___Common_rust_',
    'Corn_(maize)___Northern_Leaf_Blight', 'Corn_(maize)___healthy', 'Grape___Black_rot',
    'Grape___Esca_(Black_Measles)', 'Grape___Leaf_blight_(Isariopsis_Leaf_Spot)', 'Grape___healthy',
    'Orange___Haunglongbing_(Citrus_greening)', 'Peach___Bacterial_spot', 'Peach___healthy',
    'Pepper,_bell___Bacterial_spot', 'Pepper,_bell___healthy', 'Potato___Early_blight',
    'Potato___Late_blight', 'Potato___healthy', 'Raspberry___healthy', 'Soybean___healthy',
    'Squash___Powdery_mildew', 'Strawberry___Leaf_scorch', 'Strawberry___healthy',
    'Tomato___Bacterial_spot', 'Tomato___Early_blight', 'Tomato___Late_blight', 'Tomato___Leaf_Mold',
    'Tomato___Septoria_leaf_spot', 'Tomato___Spider_mites Two-spotted_spider_mite',
    'Tomato___Target_Spot', 'Tomato___Tomato_Yellow_Leaf_Curl_Virus', 'Tomato___Tomato_mosaic_virus',
    'Tomato___healthy'
]

UI_STRINGS = {
    "en": {
        "non_plant": "This image does not appear to be a plant leaf. Please upload a clear photo of the infected crop.",
        "low_conf": "Low confidence in detection. Please provide a clearer image of the plant leaf.",
        "unknown": "Unknown", "detected": "Detected", "species": "SPECIES", "non_plant_detected": "Non-Plant detected"
    },
    "gu": {
        "non_plant": "આ છબી છોડના પાંદડા જેવી લાગતી નથી. કૃપા કરીને ચેપગ્રસ્ત પાકનો સ્પષ્ટ ફોટો અપલોડ કરો.",
        "low_conf": "તપાસમાં વિશ્વાસ ઓછો છે. કૃપા કરીને છોડના પાંદડાનો વધુ સ્પષ્ટ ફોટો પ્રદાન કરો.",
        "unknown": "અજ્ઞાત", "detected": "ઓળખાયેલ", "species": "જાતિ", "non_plant_detected": "છોડ નથી"
    },
    "hi": {
        "non_plant": "यह छवि पौधे की पत्ती जैसी नहीं लग रही है। कृपया संक्रमित फसल की स्पष्ट फोटो अपलोड करें।",
        "low_conf": "पहचान में सटीकता कम है। कृपया पौधे की पत्ती की अधिक स्पष्ट फोटो प्रदान करें.",
        "unknown": "अज्ञात", "detected": "पहचाना गया", "species": "प्रजाति", "non_plant_detected": "पौधा नहीं है"
    }
}

ML_LABELS = {
    "Apple___Apple_scab": {"en": "Apple Scab", "gu": "સફરજનનો સ્કેબ (કરૂડિયો)", "hi": "सेब का स्कैब"},
    "Apple___Black_rot": {"en": "Apple Black Rot", "gu": "સફરજનનો કાળો સડો", "hi": "सेब की काली सड़न"},
    "Apple___Cedar_apple_rust": {"en": "Apple Cedar Rust", "gu": "સફરજનનો ગેરુ", "hi": "सेब का रस्ट"},
    "Apple___healthy": {"en": "Apple Healthy", "gu": "સફરજન (તંદુરસ્ત)", "hi": "सेब (स्वस्थ)"},
    "Blueberry___healthy": {"en": "Blueberry Healthy", "gu": "બ્લુબેરી (તંદુરસ્ત)", "hi": "ब्लूबेरी (स्वस्थ)"},
    "Cherry_(including_sour)___Powdery_mildew": {"en": "Cherry Powdery Mildew", "gu": "ચેરીમાં સફેદ છારો", "hi": "चेरी पाउडर फफूंदी"},
    "Cherry_(including_sour)___healthy": {"en": "Cherry Healthy", "gu": "ચેરી (તંદુરસ્ત)", "hi": "चेरी (स्वस्थ)"},
    "Corn_(maize)___Cercospora_leaf_spot Gray_leaf_spot": {"en": "Corn Gray Leaf Spot", "gu": "મકાઈમાં રાખોડી ટપકાં", "hi": "मक्का धूसर पत्ता धब्बा"},
    "Corn_(maize)___Common_rust_": {"en": "Corn Common Rust", "gu": "મકાઈનો ગેરુ", "hi": "मक्का सामान्य रस्ट"},
    "Corn_(maize)___Northern_Leaf_Blight": {"en": "Corn Northern Leaf Blight", "gu": "મકાઈમાં સુકારો", "hi": "मक्का पत्ता झुलसा"},
    "Corn_(maize)___healthy": {"en": "Corn Healthy", "gu": "મકાઈ (તંદુરસ્ત)", "hi": "मक्का (स्वस्थ)"},
    "Grape___Black_rot": {"en": "Grape Black Rot", "gu": "દ્રાક્ષનો કાળો સડો", "hi": "अंगूर की काली सड़न"},
    "Grape___Esca_(Black_Measles)": {"en": "Grape Esca", "gu": "દ્રાક્ષમાં એસ્કા રોગ", "hi": "अंगूर एस्का"},
    "Grape___Leaf_blight_(Isariopsis_Leaf_Spot)": {"en": "Grape Leaf Blight", "gu": "દ્રાક્ષમાં સુકારો", "hi": "अंगूर पत्ता झुलसा"},
    "Grape___healthy": {"en": "Grape Healthy", "gu": "દ્રાક્ષ (તંદુરસ્ત)", "hi": "अंगूर (स्वस्थ)"},
    "Orange___Haunglongbing_(Citrus_greening)": {"en": "Citrus Greening", "gu": "સંતરામાં ગ્રીનિંગ રોગ", "hi": "संतरा सिट्रस ग्रीनिंग"},
    "Peach___Bacterial_spot": {"en": "Peach Bacterial Spot", "gu": "પીચમાં બેક્ટેરિયલ ટપકાં", "hi": "आड़ू बैक्टीरियल धब्बा"},
    "Peach___healthy": {"en": "Peach Healthy", "gu": "પીચ (તંદુરસ્ત)", "hi": "आड़ू (स्वस्थ)"},
    "Pepper,_bell___Bacterial_spot": {"en": "Pepper Bacterial Spot", "gu": "મરચીમાં બેક્ટેરિયલ ટપકાં", "hi": "मिर्च बैक्टीरियल धब्बा"},
    "Pepper,_bell___healthy": {"en": "Pepper Healthy", "gu": "મરચી (તંદુરસ્ત)", "hi": "मिर्च (स्वस्थ)"},
    "Potato___Early_blight": {"en": "Potato Early Blight", "gu": "બટાકાનો આગોતરો સુકારો", "hi": "आलू अगेती झुलसा"},
    "Potato___Late_blight": {"en": "Potato Late Blight", "gu": "બટાકાનો પાછોતરો સુકારો", "hi": "आलू पछेती झुलसा"},
    "Potato___healthy": {"en": "Potato Healthy", "gu": "બટાકા (તંદુરસ્ત)", "hi": "आलू (स्वस्थ)"},
    "Raspberry___healthy": {"en": "Raspberry Healthy", "gu": "રાસ્પબેરી (તંદુરસ્ત)", "hi": "रास्पबेरी (स्वस्थ)"},
    "Soybean___healthy": {"en": "Soybean Healthy", "gu": "સોયાબીન (તંદુરસ્ત)", "hi": "सोयाबीन (स्वस्थ)"},
    "Squash___Powdery_mildew": {"en": "Squash Powdery Mildew", "gu": "કોળામાં સફેદ છારો", "hi": "कद्दू पाउडर फफूंदी"},
    "Strawberry___Leaf_scorch": {"en": "Strawberry Leaf Scorch", "gu": "સ્ટ્રોબેરી પાનનો સુકારો", "hi": "स्ट्रॉबेरी पत्ता झुलसा"},
    "Strawberry___healthy": {"en": "Strawberry Healthy", "gu": "સ્ટ્રોબેરી (તંદુરસ્ત)", "hi": "स्ट्रॉबेरी (स्वस्थ)"},
    "Tomato___Bacterial_spot": {"en": "Tomato Bacterial Spot", "gu": "ટમેટામાં બેક્ટેરિયલ ટપકાં", "hi": "टमाटर बैक्टीरियल धब्बा"},
    "Tomato___Early_blight": {"en": "Tomato Early Blight", "gu": "ટમેટાનો આગોતરો સુકારો", "hi": "टमाटर अगेती झुलसा"},
    "Tomato___Late_blight": {"en": "Tomato Late Blight", "gu": "ટમેટાનો પાછોતરો સુકારો", "hi": "टमाटर पछेती झुलसा"},
    "Tomato___Leaf_Mold": {"en": "Tomato Leaf Mold", "gu": "ટમેટામાં પાનનો ફૂગ", "hi": "टमाटर पत्ता मोल्ड"},
    "Tomato___Septoria_leaf_spot": {"en": "Tomato Septoria Spot", "gu": "ટમેટામાં સેપ્ટોરિયા ટપકાં", "hi": "टमाटर सेप्टोरिया धब्बा"},
    "Tomato___Spider_mites Two-spotted_spider_mite": {"en": "Tomato Spider Mites", "gu": "ટમેટામાં લાલ કીડી (કરોળિયા)", "hi": "टमाटर मकड़ी कीट"},
    "Tomato___Target_Spot": {"en": "Tomato Target Spot", "gu": "ટમેટામાં ટાર્ગેટ ટપકાં", "hi": "टमाटर टारगेट धब्बा"},
    "Tomato___Tomato_Yellow_Leaf_Curl_Virus": {"en": "Tomato Yellow Leaf Curl", "gu": "ટમેટામાં કોકડવા (વાયરસ)", "hi": "टमाटर पीला पत्ता मरोड़"},
    "Tomato___Tomato_mosaic_virus": {"en": "Tomato Mosaic Virus", "gu": "ટમેટામાં મોઝેક વાયરસ", "hi": "टमाटर मोज़ेक वायरस"},
    "Tomato___healthy": {"en": "Tomato Healthy", "gu": "ટમેટા (તંદુરસ્ત)", "hi": "टमाटर (स्वस्थ)"}
}

def get_translated_advice(disease_key, language):
    advice = {
        "en": {"desc": "Identified as " + ML_LABELS[disease_key]["en"], "irrigation": "Maintain standard irrigation.", "treatment": "Consult local expert."},
        "gu": {"desc": "ઓળખ: " + ML_LABELS[disease_key]["gu"], "irrigation": "નિયમિત પિયત ચાલુ રાખો અને જમીનમાં ભેજ તપાસો.", "treatment": "સ્થાનિક કૃષિ નિષ્ણાતની સલાહ લો."},
        "hi": {"desc": "पहचान: " + ML_LABELS[disease_key]["hi"], "irrigation": "नियमित सिंचाई जारी रखें और नमी की जाँच करें।", "treatment": "स्थानीय कृषि विशेषज्ञ से संपर्क करें।"}
    }
    
    if "healthy" in disease_key.lower():
        advice["en"] = {"desc": "The plant appears to be healthy.", "irrigation": "Continue regular care.", "treatment": "No treatment required."}
        advice["gu"] = {"desc": "છોડ તંદુરસ્ત લાગે છે.", "irrigation": "સામાન્ય કાળજી અને પિયત ચાલુ રાખો.", "treatment": "કોઈ સારવારની જરૂર નથી."}
        advice["hi"] = {"desc": "पौधा स्वस्थ प्रतीत होता है।", "irrigation": "सामान्य देखभाल जारी रखें।", "treatment": "किसी उपचार की आवश्यकता नहीं है।"}
    elif "blight" in disease_key.lower():
        advice["en"]["treatment"] = "Apply copper-based fungicides immediately."
        advice["gu"]["treatment"] = "કોપર યુક્ત ફૂગનાશકનો તાત્કાલિક છંટકાવ કરો."
        advice["hi"]["treatment"] = "कॉपर आधारित कवकनाशी का तुरंत छिड़काव करें।"
        
    return advice.get(language, advice["en"])

def is_likely_plant(img_path, threshold=0.12):
    try:
        img = Image.open(img_path).convert('RGB')
        img = img.resize((64, 64))
        img_array = np.array(img)
        r, g, b = img_array[:,:,0], img_array[:,:,1], img_array[:,:,2]
        green_mask = (g > r) & (g > b) & (g > 30)
        yellow_brown_mask = (r > 50) & (g > 40) & (r > b) & (g > b)
        plant_ratio = np.sum(green_mask | yellow_brown_mask) / (64 * 64)
        return plant_ratio >= threshold
    except:
        return True

@app.route('/', methods=['GET'])
def health_check():
    return jsonify({"status": "healthy", "model_loaded": global_model is not None})

@app.route('/predict', methods=['POST'])
def predict():
    if not global_model:
        return jsonify({"error": "AI Model failed to load on server startup."}), 500

    if 'image' not in request.files:
        return jsonify({"error": "No image part in the request"}), 400

    file = request.files['image']
    if file.filename == '':
        return jsonify({"error": "No selected file"}), 400

    lang = request.form.get('lang', 'en')
    if lang not in ["en", "gu", "hi"]:
        lang = "en"

    # Save temp file
    temp_path = os.path.join(DIR_PATH, 'temp_upload.jpg')
    file.save(temp_path)

    try:
        if not is_likely_plant(temp_path):
            return jsonify({
                "label": UI_STRINGS[lang]["unknown"],
                "plant": UI_STRINGS[lang]["non_plant_detected"],
                "confidence": 0.0,
                "disease": "unknown",
                "info": {
                    "desc": UI_STRINGS[lang]["non_plant"],
                    "irrigation": "N/A",
                    "treatment": "N/A"
                },
                "top3": []
            })

        # Run Prediction
        img = Image.open(temp_path).convert('RGB').resize(IMAGE_SIZE)
        img_array = np.array(img, dtype=np.float32) / 255.0
        img_array = np.expand_dims(img_array, axis=0)
        
        # Prevent TF from allocating massive batch overhead for a single image
        predictions = global_model(img_array, training=False).numpy()[0]
        
        top_idx = np.argmax(predictions)
        label = CLASS_NAMES[top_idx]
        confidence = float(predictions[top_idx] * 100)
        
        top_indices = predictions.argsort()[-3:][::-1]
        results_top3 = []
        for idx in top_indices:
            name = CLASS_NAMES[idx]
            results_top3.append({
                "class_name": name,
                "label": ML_LABELS.get(name, {"en": name})[lang],
                "confidence": float(predictions[idx] * 100)
            })

        best_match = results_top3[0]
        plant_name = best_match['class_name'].split('___')[0] if '___' in best_match['class_name'] else UI_STRINGS[lang]["detected"]

        result = {
            "label": best_match['label'],
            "plant": plant_name,
            "confidence": confidence,
            "disease": label,
            "engine": "Keras API",
            "info": get_translated_advice(label, lang),
            "top3": results_top3
        }

        # Safeguards
        is_healthy = "healthy" in label.lower()
        min_conf = 85.0 if is_healthy else 65.0

        if confidence < min_conf:
            result['label'] = UI_STRINGS[lang]["unknown"]
            result['plant'] = UI_STRINGS[lang]["unknown"]
            result['disease'] = "unknown"
            result['info'] = {
                "desc": UI_STRINGS[lang]["low_conf"],
                "irrigation": "N/A",
                "treatment": "N/A"
            }

        return jsonify(result)

    except Exception as e:
        return jsonify({"error": str(e)}), 500
    finally:
        if os.path.exists(temp_path):
            os.remove(temp_path)
        gc.collect()

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=int(os.environ.get("PORT", 8000)))
