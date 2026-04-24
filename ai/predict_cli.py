import os
import sys
import json
import argparse
import numpy as np

# Set Keras backend to tensorflow
os.environ["KERAS_BACKEND"] = "tensorflow"
# Suppress Keras/TF logs
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'

import keras
from PIL import Image

# Import TensorFlow components for TFLite
try:
    import tensorflow as tf
except ImportError:
    # If TF is not here, we can still use Keras for .keras models
    tf = None

# Configuration
MODEL_BASE_NAME = "plant_disease_model"
IMAGE_SIZE = (128, 128) 
DIR_PATH = os.path.dirname(__file__)
KERAS_MODEL_PATH = os.path.join(DIR_PATH, f"{MODEL_BASE_NAME}.keras")
TFLITE_MODEL_PATH = os.path.join(DIR_PATH, f"{MODEL_BASE_NAME}.tflite")

# Labels Mapping
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

DISPLAY_LABELS = {
    "Apple___Apple_scab": "Apple Scab", "Apple___Black_rot": "Apple Black Rot",
    "Apple___Cedar_apple_rust": "Apple Cedar Rust", "Apple___healthy": "Apple Healthy",
    "Blueberry___healthy": "Blueberry Healthy", "Cherry_(including_sour)___Powdery_mildew": "Cherry Powdery Mildew",
    "Cherry_(including_sour)___healthy": "Cherry Healthy", "Corn_(maize)___Cercospora_leaf_spot Gray_leaf_spot": "Corn Gray Leaf Spot",
    "Corn_(maize)___Common_rust_": "Corn Common Rust", "Corn_(maize)___Northern_Leaf_Blight": "Corn Northern Leaf Blight",
    "Corn_(maize)___healthy": "Corn Healthy", "Grape___Black_rot": "Grape Black Rot",
    "Grape___Esca_(Black_Measles)": "Grape Esca (Black Measles)", "Grape___Leaf_blight_(Isariopsis_Leaf_Spot)": "Grape Leaf Blight",
    "Grape___healthy": "Grape Healthy", "Orange___Haunglongbing_(Citrus_greening)": "Orange Citrus Greening",
    "Peach___Bacterial_spot": "Peach Bacterial Spot", "Peach___healthy": "Peach Healthy",
    "Pepper,_bell___Bacterial_spot": "Pepper Bacterial Spot", "Pepper,_bell___healthy": "Pepper Healthy",
    "Potato___Early_blight": "Potato Early Blight", "Potato___Late_blight": "Potato Late Blight",
    "Potato___healthy": "Potato Healthy", "Raspberry___healthy": "Raspberry Healthy",
    "Soybean___healthy": "Soybean Healthy", "Squash___Powdery_mildew": "Squash Powdery Mildew",
    "Strawberry___Leaf_scorch": "Strawberry Leaf Scorch", "Strawberry___healthy": "Strawberry Healthy",
    "Tomato___Bacterial_spot": "Tomato Bacterial Spot", "Tomato___Early_blight": "Tomato Early Blight",
    "Tomato___Late_blight": "Tomato Late Blight", "Tomato___Leaf_Mold": "Tomato Leaf Mold",
    "Tomato___Septoria_leaf_spot": "Tomato Septoria Leaf Spot", "Tomato___Spider_mites Two-spotted_spider_mite": "Tomato Spider Mites",
    "Tomato___Target_Spot": "Tomato Target Spot", "Tomato___Tomato_Yellow_Leaf_Curl_Virus": "Tomato Yellow Leaf Curl Virus",
    "Tomato___Tomato_mosaic_virus": "Tomato Mosaic Virus", "Tomato___healthy": "Tomato Healthy"
}

def get_disease_info(disease_name):
    # Basic info mapping
    info = {
        "desc": f"Identified as {DISPLAY_LABELS.get(disease_name, disease_name.replace('___', ' '))}",
        "irrigation": "Maintain standard irrigation and check soil moisture.",
        "treatment": "Consult an agricultural expert for specific pesticide recommendations.",
        "fertilizer": "Use balanced fertilizer suitable for the plant type."
    }
    
    if "healthy" in disease_name.lower():
        info["desc"] = "The plant appears to be healthy."
        info["treatment"] = "No treatment necessary. Continue normal care."
    elif "Late_blight" in disease_name:
        info["desc"] = "A serious disease caused by a water mold. Causes dark, water-soaked spots."
        info["treatment"] = "Apply fungicides containing chlorothalonil or copper."
    elif "Early_blight" in disease_name:
        info["desc"] = "Common fungal disease. Causes bullseye-shaped spots on leaves."
        info["treatment"] = "Use copper-based fungicides and remove infected lower leaves."
    elif "Bacterial_spot" in disease_name:
        info["desc"] = "Small, water-soaked spots on leaves that turn brown."
        info["treatment"] = "Copper sprays can help manage the spread."
    elif "Powdery_mildew" in disease_name:
        info["desc"] = "White, powdery fungal growth on the surface of leaves."
        info["treatment"] = "Apply sulfur-based fungicides or neem oil."
    
    return info

def predict_tflite(image_path):
    if tf is None:
        raise ImportError("TensorFlow required for TFLite inference.")
    interpreter = tf.lite.Interpreter(model_path=TFLITE_MODEL_PATH)
    interpreter.allocate_tensors()
    input_details = interpreter.get_input_details()
    output_details = interpreter.get_output_details()
    
    img = Image.open(image_path).convert('RGB').resize(IMAGE_SIZE)
    img_array = np.array(img, dtype=np.float32) / 255.0
    img_array = np.expand_dims(img_array, axis=0)
    
    interpreter.set_tensor(input_details[0]['index'], img_array)
    interpreter.invoke()
    return interpreter.get_tensor(output_details[0]['index'])[0]

def predict_keras(image_path):
    # Use keras.models.load_model (Keras 3) to avoid InputLayer errors
    model = keras.models.load_model(KERAS_MODEL_PATH)
    img = Image.open(image_path).convert('RGB').resize(IMAGE_SIZE)
    img_array = np.array(img, dtype=np.float32) / 255.0
    img_array = np.expand_dims(img_array, axis=0)
    
    predictions = model.predict(img_array, verbose=0)[0]
    return predictions

def is_likely_plant(image_path, threshold=0.12):
    """Checks if the image has enough green/yellow/brown pixels to be a plant."""
    try:
        img = Image.open(image_path).convert('RGB')
        img = img.resize((64, 64)) # Resize for speed
        img_array = np.array(img)
        r, g, b = img_array[:,:,0], img_array[:,:,1], img_array[:,:,2]
        
        # Green pixels
        green_mask = (g > r) & (g > b) & (g > 30)
        # Yellow/Brown pixels
        yellow_brown_mask = (r > 50) & (g > 40) & (r > b) & (g > b)
        
        plant_ratio = np.sum(green_mask | yellow_brown_mask) / (64 * 64)
        return plant_ratio >= threshold
    except:
        return True # Fallback to true if processing fails

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("--image", help="Path to image file")
    parser.add_argument("--health", action="store_true", help="Check system readiness")
    parser.add_argument("--lang", default="en", help="Preferred language (en, gu, hi)")
    args = parser.parse_args()
    
    lang = args.lang if args.lang in ["en", "gu", "hi"] else "en"

    # Multi-language translations for generic messages
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

    # Extended Multilingual Labels
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
        # Default advice mapping
        advice = {
            "en": {"desc": "Identified as " + ML_LABELS[disease_key]["en"], "irrigation": "Maintain standard irrigation.", "treatment": "Consult local expert."},
            "gu": {"desc": "ઓળખ: " + ML_LABELS[disease_key]["gu"], "irrigation": "નિયમિત પિયત ચાલુ રાખો અને જમીનમાં ભેજ તપાસો.", "treatment": "સ્થાનિક કૃષિ નિષ્ણાતની સલાહ લો."},
            "hi": {"desc": "पहचान: " + ML_LABELS[disease_key]["hi"], "irrigation": "नियमित सिंचाई जारी रखें और नमी की जाँच करें।", "treatment": "स्थानीय कृषि विशेषज्ञ से संपर्क करें।"}
        }
        
        # Specific overrides for diseases
        if "healthy" in disease_key.lower():
            advice["en"] = {"desc": "The plant appears to be healthy.", "irrigation": "Continue regular care.", "treatment": "No treatment required."}
            advice["gu"] = {"desc": "છોડ તંદુરસ્ત લાગે છે.", "irrigation": "સામાન્ય કાળજી અને પિયત ચાલુ રાખો.", "treatment": "કોઈ સારવારની જરૂર નથી."}
            advice["hi"] = {"desc": "पौधा स्वस्थ प्रतीत होता है।", "irrigation": "सामान्य देखभाल जारी रखें।", "treatment": "किसी उपचार की आवश्यकता नहीं है।"}
        elif "blight" in disease_key.lower():
            advice["en"]["treatment"] = "Apply copper-based fungicides immediately."
            advice["gu"]["treatment"] = "કોપર યુક્ત ફૂગનાશકનો તાત્કાલિક છંટકાવ કરો."
            advice["hi"]["treatment"] = "कॉपर आधारित कवकनाशी का तुरंत छिड़काव करें।"
            
        return advice.get(language, advice["en"])

    if args.health:
        ready = os.path.exists(KERAS_MODEL_PATH) or os.path.exists(TFLITE_MODEL_PATH)
        print(json.dumps({
            "status": "healthy" if ready else "error",
            "keras_ready": os.path.exists(KERAS_MODEL_PATH),
            "tflite_ready": os.path.exists(TFLITE_MODEL_PATH)
        }))
        return

    if not args.image or not os.path.exists(args.image):
        print(json.dumps({"error": "Invalid or missing image path"}))
        return

    # Check if the image is likely a plant
    if not is_likely_plant(args.image):
        print(json.dumps({
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
        }))
        return

    try:
        if os.path.exists(TFLITE_MODEL_PATH) and tf is not None:
            predictions = predict_tflite(args.image)
            engine = "TFLite"
        elif os.path.exists(KERAS_MODEL_PATH):
            predictions = predict_keras(args.image)
            engine = "Keras"
        else:
            print(json.dumps({"error": "No model file found."}))
            return

        top_idx = np.argmax(predictions)
        label = CLASS_NAMES[top_idx]
        confidence = float(predictions[top_idx] * 100)
        
        # Format Top 3 for frontend compatibility
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
            "engine": engine,
            "info": get_translated_advice(label, lang),
            "top3": results_top3
        }

        # Stricter Safeguards
        # 1. Low confidence overall
        # 2. "Healthy" label but low confidence (often happens with non-plants)
        is_healthy = "healthy" in label.lower()
        min_conf = 85.0 if is_healthy else 65.0

        if confidence < min_conf:
            result['label'] = UI_STRINGS[lang]["unknown"]
            result['plant'] = UI_STRINGS[lang]["unknown"]
            result['disease'] = "unknown"
            result['info'] = {
                "desc": UI_STRINGS[lang]["low_conf"],
                "irrigation": "N/A",
                "treatment": "N/A",
                "fertilizer": "N/A"
            }

        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({"error": str(e)}))

if __name__ == "__main__":
    main()
