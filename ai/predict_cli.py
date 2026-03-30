import argparse
import json
import os
import sys
import tempfile
import zipfile

import h5py
import numpy as np
from PIL import Image
from tensorflow.keras.layers import Conv2D, Dense, Dropout, Flatten, MaxPool2D
from tensorflow.keras.models import Sequential

try:
    from tensorflow.keras.models import load_model
except (ImportError, ModuleNotFoundError, AttributeError):
    try:
        from keras.models import load_model
    except (ImportError, ModuleNotFoundError):
        from tensorflow.python.keras.models import load_model


MODEL_PATH = os.path.join(os.path.dirname(__file__), "plant_disease_model.keras")
IMAGE_SIZE = (128, 128)

CLASS_NAMES = [
    "Apple___Apple_scab", "Apple___Black_rot", "Apple___Cedar_apple_rust", "Apple___healthy",
    "Blueberry___healthy", "Cherry_(including_sour)___Powdery_mildew", "Cherry_(including_sour)___healthy",
    "Corn_(maize)___Cercospora_leaf_spot Gray_leaf_spot", "Corn_(maize)___Common_rust_",
    "Corn_(maize)___Northern_Leaf_Blight", "Corn_(maize)___healthy", "Grape___Black_rot",
    "Grape___Esca_(Black_Measles)", "Grape___Leaf_blight_(Isariopsis_Leaf_Spot)", "Grape___healthy",
    "Orange___Haunglongbing_(Citrus_greening)", "Peach___Bacterial_spot", "Peach___healthy",
    "Pepper,_bell___Bacterial_spot", "Pepper,_bell___healthy", "Potato___Early_blight",
    "Potato___Late_blight", "Potato___healthy", "Raspberry___healthy", "Soybean___healthy",
    "Squash___Powdery_mildew", "Strawberry___Leaf_scorch", "Strawberry___healthy",
    "Tomato___Bacterial_spot", "Tomato___Early_blight", "Tomato___Late_blight", "Tomato___Leaf_Mold",
    "Tomato___Septoria_leaf_spot", "Tomato___Spider_mites Two-spotted_spider_mite",
    "Tomato___Target_Spot", "Tomato___Tomato_Yellow_Leaf_Curl_Virus", "Tomato___Tomato_mosaic_virus",
    "Tomato___healthy",
]

DISPLAY_LABELS = {
    "Apple___Apple_scab": "Apple Scab",
    "Apple___Black_rot": "Apple Black Rot",
    "Apple___Cedar_apple_rust": "Apple Cedar Rust",
    "Apple___healthy": "Apple Healthy",
    "Blueberry___healthy": "Blueberry Healthy",
    "Cherry_(including_sour)___Powdery_mildew": "Cherry Powdery Mildew",
    "Cherry_(including_sour)___healthy": "Cherry Healthy",
    "Corn_(maize)___Cercospora_leaf_spot Gray_leaf_spot": "Corn Gray Leaf Spot",
    "Corn_(maize)___Common_rust_": "Corn Common Rust",
    "Corn_(maize)___Northern_Leaf_Blight": "Corn Northern Leaf Blight",
    "Corn_(maize)___healthy": "Corn Healthy",
    "Grape___Black_rot": "Grape Black Rot",
    "Grape___Esca_(Black_Measles)": "Grape Esca (Black Measles)",
    "Grape___Leaf_blight_(Isariopsis_Leaf_Spot)": "Grape Leaf Blight",
    "Grape___healthy": "Grape Healthy",
    "Orange___Haunglongbing_(Citrus_greening)": "Orange Citrus Greening",
    "Peach___Bacterial_spot": "Peach Bacterial Spot",
    "Peach___healthy": "Peach Healthy",
    "Pepper,_bell___Bacterial_spot": "Pepper Bacterial Spot",
    "Pepper,_bell___healthy": "Pepper Healthy",
    "Potato___Early_blight": "Potato Early Blight",
    "Potato___Late_blight": "Potato Late Blight",
    "Potato___healthy": "Potato Healthy",
    "Raspberry___healthy": "Raspberry Healthy",
    "Soybean___healthy": "Soybean Healthy",
    "Squash___Powdery_mildew": "Squash Powdery Mildew",
    "Strawberry___Leaf_scorch": "Strawberry Leaf Scorch",
    "Strawberry___healthy": "Strawberry Healthy",
    "Tomato___Bacterial_spot": "Tomato Bacterial Spot",
    "Tomato___Early_blight": "Tomato Early Blight",
    "Tomato___Late_blight": "Tomato Late Blight",
    "Tomato___Leaf_Mold": "Tomato Leaf Mold",
    "Tomato___Septoria_leaf_spot": "Tomato Septoria Leaf Spot",
    "Tomato___Spider_mites Two-spotted_spider_mite": "Tomato Spider Mites",
    "Tomato___Target_Spot": "Tomato Target Spot",
    "Tomato___Tomato_Yellow_Leaf_Curl_Virus": "Tomato Yellow Leaf Curl Virus",
    "Tomato___Tomato_mosaic_virus": "Tomato Mosaic Virus",
    "Tomato___healthy": "Tomato Healthy",
}

DISEASE_INFO = {
    "scab": {"desc": "Fungal disease causing dark, scabby lesions on leaves and fruit.", "irrigation": "Avoid wetting foliage.", "treatment": "Apply captan or myclobutanil.", "fertilizer": "Balanced NPK."},
    "black_rot": {"desc": "Fungal infection causing dark rotting lesions.", "irrigation": "Ensure drainage.", "treatment": "Use thiophanate-methyl.", "fertilizer": "High Potassium."},
    "rust": {"desc": "Orange or brown pustules.", "irrigation": "Water at base.", "treatment": "Sulfur fungicide.", "fertilizer": "High Potassium."},
    "powdery_mildew": {"desc": "White powdery coating.", "irrigation": "Avoid overhead watering.", "treatment": "Neem oil or sulfur.", "fertilizer": "Avoid high Nitrogen."},
    "gray_leaf_spot": {"desc": "Rectangular gray lesions.", "irrigation": "Reduce leaf wetness.", "treatment": "Apply strobilurins.", "fertilizer": "Balanced NPK."},
    "blight": {"desc": "Rapid browning and death of tissue.", "irrigation": "Keep leaves dry.", "treatment": "Copper fungicides.", "fertilizer": "Avoid excess Nitrogen."},
    "leaf_mold": {"desc": "Yellow spots on upper surface, mold on back.", "irrigation": "Increase ventilation.", "treatment": "Apply chlorothalonil.", "fertilizer": "Ensure Calcium."},
    "septoria": {"desc": "Small circular spots with dark borders.", "irrigation": "Water base only.", "treatment": "Apply mancozeb.", "fertilizer": "Micronutrients."},
    "spider_mites": {"desc": "Tiny pests causing stippling and webbing.", "irrigation": "Keep humidity moderate.", "treatment": "Neem oil or acaricides.", "fertilizer": "Avoid extra Nitrogen."},
    "target_spot": {"desc": "Concentric rings on leaves.", "irrigation": "Use mulch and drip.", "treatment": "Apply azoxystrobin.", "fertilizer": "Adequate Calcium."},
    "virus": {"desc": "Mosaic patterns and stunted growth.", "irrigation": "Avoid water stress.", "treatment": "No cure. Remove infected plants.", "fertilizer": "Silicon, Potassium."},
    "bacterial_spot": {"desc": "Water-soaked spots with yellow halos.", "irrigation": "Avoid splashing.", "treatment": "Copper bactericide.", "fertilizer": "Calcium foliar spray."},
    "greening": {"desc": "Citrus Greening (HLB). Misshapen bitter fruit.", "irrigation": "Maintain uniform moisture.", "treatment": "Remove trees. Control psyllids.", "fertilizer": "Micronutrient foliar feed."},
    "esca": {"desc": "Grapevine wood decay and leaf striping.", "irrigation": "Reduce water stress.", "treatment": "Remove infected wood.", "fertilizer": "Balanced nutrition."},
    "leaf_blight": {"desc": "Large irregular brown patches.", "irrigation": "Improve drainage.", "treatment": "Apply mancozeb.", "fertilizer": "Balanced NPK."},
    "leaf_scorch": {"desc": "Dark purple-edged lesions.", "irrigation": "Consistent moisture.", "treatment": "Apply myclobutanil.", "fertilizer": "Increase Potassium."},
    "healthy": {"desc": "Vigorous and healthy appearance.", "irrigation": "Optimal schedule.", "treatment": "No treatment needed.", "fertilizer": "Organic compost."},
}


def get_disease_info(class_name: str) -> dict:
    cn = class_name.lower()
    if "healthy" in cn:
        return DISEASE_INFO["healthy"]
    if "scab" in cn:
        return DISEASE_INFO["scab"]
    if "black_rot" in cn:
        return DISEASE_INFO["black_rot"]
    if "rust" in cn:
        return DISEASE_INFO["rust"]
    if "powdery_mildew" in cn:
        return DISEASE_INFO["powdery_mildew"]
    if "gray_leaf_spot" in cn:
        return DISEASE_INFO["gray_leaf_spot"]
    if "blight" in cn:
        return DISEASE_INFO["blight"]
    if "leaf_mold" in cn:
        return DISEASE_INFO["leaf_mold"]
    if "septoria" in cn:
        return DISEASE_INFO["septoria"]
    if "spider_mites" in cn:
        return DISEASE_INFO["spider_mites"]
    if "target_spot" in cn:
        return DISEASE_INFO["target_spot"]
    if "virus" in cn or "mosaic" in cn or "curl" in cn:
        return DISEASE_INFO["virus"]
    if "bacterial_spot" in cn:
        return DISEASE_INFO["bacterial_spot"]
    if "haunglongbing" in cn:
        return DISEASE_INFO["greening"]
    if "esca" in cn:
        return DISEASE_INFO["esca"]
    if "leaf_blight" in cn:
        return DISEASE_INFO["leaf_blight"]
    if "leaf_scorch" in cn:
        return DISEASE_INFO["leaf_scorch"]
    return DISEASE_INFO["healthy"]


def preprocess_image(image_path: str) -> np.ndarray:
    image = Image.open(image_path).convert("RGB")
    image = image.resize(IMAGE_SIZE)
    arr = np.array(image, dtype=np.float32) / 255.0
    return np.expand_dims(arr, axis=0)


def load_runtime_model():
    if not os.path.isfile(MODEL_PATH):
        raise FileNotFoundError(f"Model file not found: {MODEL_PATH}")
    try:
        return load_model(MODEL_PATH)
    except Exception:
        return load_weights_fallback_model()


def build_fallback_model():
    return Sequential([
        Conv2D(32, (3, 3), padding="same", activation="relu", input_shape=(128, 128, 3)),
        MaxPool2D(2, 2),
        Conv2D(64, (3, 3), padding="same", activation="relu"),
        MaxPool2D(2, 2),
        Conv2D(64, (3, 3), padding="same", activation="relu"),
        MaxPool2D(2, 2),
        Conv2D(64, (3, 3), padding="same", activation="relu"),
        MaxPool2D(2, 2),
        Conv2D(64, (3, 3), padding="same", activation="relu"),
        MaxPool2D(2, 2),
        Conv2D(64, (3, 3), padding="same", activation="relu"),
        MaxPool2D(2, 2),
        Flatten(),
        Dense(1500, activation="relu"),
        Dropout(0.4),
        Dense(len(CLASS_NAMES), activation="softmax"),
    ])


def load_weights_fallback_model():
    with zipfile.ZipFile(MODEL_PATH, "r") as archive:
        with tempfile.TemporaryDirectory() as temp_dir:
            weights_path = archive.extract("model.weights.h5", path=temp_dir)
            model = build_fallback_model()
            model.build((None, 128, 128, 3))

            with h5py.File(weights_path, "r") as weights_file:
                layer_groups = weights_file["layers"]
                for layer in model.layers:
                    if layer.name not in layer_groups:
                        continue

                    vars_group = layer_groups[layer.name].get("vars")
                    if vars_group is None or len(vars_group.keys()) == 0:
                        continue

                    ordered_weights = [vars_group[key][()] for key in sorted(vars_group.keys(), key=int)]
                    layer.set_weights(ordered_weights)

            return model


def build_response(model, image_path: str) -> dict:
    input_arr = preprocess_image(image_path)
    predictions = model.predict(input_arr, verbose=0)[0]
    top_indices = np.argsort(predictions)[::-1][:3]

    top_index = int(top_indices[0])
    class_name = CLASS_NAMES[top_index]
    display_name = DISPLAY_LABELS.get(class_name, class_name.replace("___", " "))
    confidence = float(predictions[top_index]) * 100.0
    plant_name = display_name.split(" ")[0]

    top3 = []
    for idx in top_indices:
        class_key = CLASS_NAMES[int(idx)]
        top3.append({
            "class_name": class_key,
            "label": DISPLAY_LABELS.get(class_key, class_key.replace("___", " ")),
            "confidence": round(float(predictions[int(idx)]) * 100.0, 2),
        })

    return {
        "disease": class_name,
        "label": display_name,
        "plant": plant_name,
        "confidence": round(confidence, 2),
        "info": get_disease_info(class_name),
        "top3": top3,
    }


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--image", help="Path to the image file")
    parser.add_argument("--health", action="store_true", help="Check local model availability")
    args = parser.parse_args()

    try:
        if args.health:
            print(json.dumps({
                "status": "online" if os.path.isfile(MODEL_PATH) else "offline",
                "model_path": MODEL_PATH,
            }))
            return 0

        if not args.image:
            raise ValueError("Missing --image argument")

        model = load_runtime_model()
        result = build_response(model, args.image)
        print(json.dumps(result))
        return 0
    except Exception as exc:
        print(json.dumps({"error": str(exc)}))
        return 1


if __name__ == "__main__":
    sys.exit(main())
