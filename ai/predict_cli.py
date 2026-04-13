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

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("--image", help="Path to image file")
    parser.add_argument("--health", action="store_true", help="Check system readiness")
    args = parser.parse_args()

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
        confidence = float(predictions[top_idx])
        
        # Format Top 3 for frontend compatibility
        top_indices = predictions.argsort()[-3:][::-1]
        results_top3 = []
        for idx in top_indices:
            name = CLASS_NAMES[idx]
            results_top3.append({
                "class_name": name,
                "label": DISPLAY_LABELS.get(name, name.replace('___', ' ')),
                "confidence": float(predictions[idx] * 100)
            })

        best_match = results_top3[0]
        plant_name = best_match['class_name'].split('___')[0] if '___' in best_match['class_name'] else "Detected"

        result = {
            "label": best_match['label'],
            "plant": plant_name,
            "confidence": best_match['confidence'],
            "disease": label,
            "engine": engine,
            "info": get_disease_info(label),
            "top3": results_top3
        }

        # Safeguard: If confidence is less than 50%, suppress result advice
        if result['confidence'] < 50.0:
            result['label'] = "Unknown"
            result['plant'] = "Unknown"
            result['disease'] = "unknown"
            result['info'] = {
                "desc": "Disease not found. Please provide a clearer image of the plant leaf.",
                "irrigation": "N/A",
                "treatment": "N/A",
                "fertilizer": "N/A"
            }

        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({"error": str(e)}))

if __name__ == "__main__":
    main()
