"""
Plant Disease Detection – Flask Prediction API
===============================================
Based on: SPOTLESS TECH YouTube Playlist
Dataset:  New Plant Diseases Dataset (Kaggle – vipoooool)

Run this AFTER training the model:
  cd d:/SGP/Agricare/ai
  python predict_api.py

API Endpoint:
  POST http://localhost:5050/predict
  Body: multipart/form-data  { image: <file> }

Response:
  {
    "disease"    : "Tomato___Early_blight",
    "confidence" : 97.4,
    "label"      : "Tomato Early Blight",
    "plant"      : "Tomato",
    "info": {
        "desc"       : "...",
        "irrigation" : "...",
        "treatment"  : "...",
        "fertilizer" : "..."
    }
  }
"""

from flask import Flask, request, jsonify
from flask_cors import CORS
import tensorflow as tf
import numpy as np
from PIL import Image
import io
import os

app = Flask(__name__)
CORS(app)   # Allow cross-origin from PHP frontend

# ============================================================
# MODEL LOADING
# ============================================================
MODEL_PATH = os.path.join(os.path.dirname(__file__), 'plant_disease_model.keras')

print(f"[AI] Loading model from: {MODEL_PATH}")
model = tf.keras.models.load_model(MODEL_PATH)
print("[AI] Model loaded successfully")

# ============================================================
# 38 CLASS NAMES  (New Plant Diseases Dataset – Kaggle)
# ============================================================
CLASS_NAMES = [
    'Apple___Apple_scab',
    'Apple___Black_rot',
    'Apple___Cedar_apple_rust',
    'Apple___healthy',
    'Blueberry___healthy',
    'Cherry_(including_sour)___Powdery_mildew',
    'Cherry_(including_sour)___healthy',
    'Corn_(maize)___Cercospora_leaf_spot Gray_leaf_spot',
    'Corn_(maize)___Common_rust_',
    'Corn_(maize)___Northern_Leaf_Blight',
    'Corn_(maize)___healthy',
    'Grape___Black_rot',
    'Grape___Esca_(Black_Measles)',
    'Grape___Leaf_blight_(Isariopsis_Leaf_Spot)',
    'Grape___healthy',
    'Orange___Haunglongbing_(Citrus_greening)',
    'Peach___Bacterial_spot',
    'Peach___healthy',
    'Pepper,_bell___Bacterial_spot',
    'Pepper,_bell___healthy',
    'Potato___Early_blight',
    'Potato___Late_blight',
    'Potato___healthy',
    'Raspberry___healthy',
    'Soybean___healthy',
    'Squash___Powdery_mildew',
    'Strawberry___Leaf_scorch',
    'Strawberry___healthy',
    'Tomato___Bacterial_spot',
    'Tomato___Early_blight',
    'Tomato___Late_blight',
    'Tomato___Leaf_Mold',
    'Tomato___Septoria_leaf_spot',
    'Tomato___Spider_mites Two-spotted_spider_mite',
    'Tomato___Target_Spot',
    'Tomato___Tomato_Yellow_Leaf_Curl_Virus',
    'Tomato___Tomato_mosaic_virus',
    'Tomato___healthy',
]

# Human-readable display label mapping
DISPLAY_LABELS = {
    'Apple___Apple_scab'                                    : 'Apple Scab',
    'Apple___Black_rot'                                     : 'Apple Black Rot',
    'Apple___Cedar_apple_rust'                              : 'Apple Cedar Rust',
    'Apple___healthy'                                       : 'Apple Healthy',
    'Blueberry___healthy'                                   : 'Blueberry Healthy',
    'Cherry_(including_sour)___Powdery_mildew'              : 'Cherry Powdery Mildew',
    'Cherry_(including_sour)___healthy'                     : 'Cherry Healthy',
    'Corn_(maize)___Cercospora_leaf_spot Gray_leaf_spot'    : 'Corn Gray Leaf Spot',
    'Corn_(maize)___Common_rust_'                           : 'Corn Common Rust',
    'Corn_(maize)___Northern_Leaf_Blight'                   : 'Corn Northern Leaf Blight',
    'Corn_(maize)___healthy'                                : 'Corn Healthy',
    'Grape___Black_rot'                                     : 'Grape Black Rot',
    'Grape___Esca_(Black_Measles)'                          : 'Grape Esca (Black Measles)',
    'Grape___Leaf_blight_(Isariopsis_Leaf_Spot)'            : 'Grape Leaf Blight',
    'Grape___healthy'                                       : 'Grape Healthy',
    'Orange___Haunglongbing_(Citrus_greening)'              : 'Orange Citrus Greening',
    'Peach___Bacterial_spot'                                : 'Peach Bacterial Spot',
    'Peach___healthy'                                       : 'Peach Healthy',
    'Pepper,_bell___Bacterial_spot'                         : 'Pepper Bacterial Spot',
    'Pepper,_bell___healthy'                                : 'Pepper Healthy',
    'Potato___Early_blight'                                 : 'Potato Early Blight',
    'Potato___Late_blight'                                  : 'Potato Late Blight',
    'Potato___healthy'                                      : 'Potato Healthy',
    'Raspberry___healthy'                                   : 'Raspberry Healthy',
    'Soybean___healthy'                                     : 'Soybean Healthy',
    'Squash___Powdery_mildew'                               : 'Squash Powdery Mildew',
    'Strawberry___Leaf_scorch'                              : 'Strawberry Leaf Scorch',
    'Strawberry___healthy'                                  : 'Strawberry Healthy',
    'Tomato___Bacterial_spot'                               : 'Tomato Bacterial Spot',
    'Tomato___Early_blight'                                 : 'Tomato Early Blight',
    'Tomato___Late_blight'                                  : 'Tomato Late Blight',
    'Tomato___Leaf_Mold'                                    : 'Tomato Leaf Mold',
    'Tomato___Septoria_leaf_spot'                           : 'Tomato Septoria Leaf Spot',
    'Tomato___Spider_mites Two-spotted_spider_mite'         : 'Tomato Spider Mites',
    'Tomato___Target_Spot'                                  : 'Tomato Target Spot',
    'Tomato___Tomato_Yellow_Leaf_Curl_Virus'                : 'Tomato Yellow Leaf Curl Virus',
    'Tomato___Tomato_mosaic_virus'                          : 'Tomato Mosaic Virus',
    'Tomato___healthy'                                      : 'Tomato Healthy',
}

# ============================================================
# DISEASE INFORMATION DATABASE
# (treatment, irrigation, fertilizer advice per disease group)
# ============================================================
DISEASE_INFO = {
    'scab': {
        'desc'        : 'Fungal disease causing dark, scabby lesions on leaves and fruit surfaces.',
        'irrigation'  : 'Avoid wetting foliage. Use drip irrigation at the base.',
        'treatment'   : 'Apply captan or myclobutanil fungicide. Remove fallen leaves.',
        'fertilizer'  : 'Balanced NPK; avoid excess nitrogen which promotes soft tissue.',
    },
    'black_rot': {
        'desc'        : 'Fungal infection causing dark, rotting lesions on fruit and leaves.',
        'irrigation'  : 'Ensure proper drainage. Avoid standing water around roots.',
        'treatment'   : 'Use thiophanate-methyl or captan. Prune infected branches.',
        'fertilizer'  : 'High Potassium (K) for stronger cell walls and disease resistance.',
    },
    'rust': {
        'desc'        : 'Fungal infection appearing as orange or brown powdery pustules on leaves.',
        'irrigation'  : 'Water only at the base. Reduce humidity in enclosed spaces.',
        'treatment'   : 'Apply sulfur or triazole-based fungicides. Improve air circulation.',
        'fertilizer'  : 'Increase Potassium (K) levels; minimize Nitrogen (N) during infection.',
    },
    'powdery_mildew': {
        'desc'        : 'Fungal disease coating leaves with a white, powdery substance.',
        'irrigation'  : 'Avoid overhead watering. Water in the morning to allow leaf drying.',
        'treatment'   : 'Spray neem oil, potassium bicarbonate, or sulfur-based fungicides.',
        'fertilizer'  : 'Reduce high-nitrogen fertilizers. Apply balanced compost.',
    },
    'gray_leaf_spot': {
        'desc'        : 'Fungal lesions that appear as rectangular gray to tan spots between leaf veins.',
        'irrigation'  : 'Reduce irrigation frequency. Avoid prolonged leaf wetness.',
        'treatment'   : 'Apply strobilurin or triazole fungicides at early infection.',
        'fertilizer'  : 'Adequate Nitrogen boosts recovery; avoid Potassium deficiency.',
    },
    'blight': {
        'desc'        : 'Bacterial or fungal disease causing rapid browning and death of plant tissues.',
        'irrigation'  : 'Avoid overhead irrigation; keep leaves dry. Use furrow or drip systems.',
        'treatment'   : 'Use copper-based fungicides. Remove and destroy infected debris.',
        'fertilizer'  : 'Apply balanced NPK; excessive nitrogen worsens blight.',
    },
    'leaf_mold': {
        'desc'        : 'Fungal disease causing yellow spots on upper leaf surfaces and mold on underside.',
        'irrigation'  : 'Improve greenhouse ventilation. Avoid high humidity conditions.',
        'treatment'   : 'Apply chlorothalonil or copper-based fungicide sprays.',
        'fertilizer'  : 'Normal balanced fertilization; ensure Calcium adequacy.',
    },
    'septoria': {
        'desc'        : 'Fungal leaf spot disease with small circular spots and dark borders.',
        'irrigation'  : 'Water at base only. Keep foliage dry; do not water in evening.',
        'treatment'   : 'Apply mancozeb or chlorothalonil fungicides. Remove lower leaves.',
        'fertilizer'  : 'Micronutrients (Zinc/Boron) help boost natural plant immunity.',
    },
    'spider_mites': {
        'desc'        : 'Tiny arachnid pests causing stippled, discolored leaves and fine webbing.',
        'irrigation'  : 'Keep humidity moderate. Mites thrive in hot, dry conditions.',
        'treatment'   : 'Use neem oil, insecticidal soap, or abamectin-based acaricides.',
        'fertilizer'  : 'Avoid nitrogen over-fertilization; it attracts mite populations.',
    },
    'target_spot': {
        'desc'        : 'Fungal spots with concentric rings resembling a target on leaves.',
        'irrigation'  : 'Avoid leaf wetness. Use drip irrigation and mulch soil surface.',
        'treatment'   : 'Apply azoxystrobin or boscalid fungicides. Rotate crops.',
        'fertilizer'  : 'Balanced NPK with adequate Calcium and Magnesium.',
    },
    'virus': {
        'desc'        : 'Viral infection causing mosaic patterns, leaf curl, and stunted growth.',
        'irrigation'  : 'Avoid water stress; stressed plants are more susceptible to viruses.',
        'treatment'   : 'No cure; remove infected plants. Control aphid/whitefly vectors.',
        'fertilizer'  : 'Boost plant immunity with Silicon and Potassium supplements.',
    },
    'bacterial_spot': {
        'desc'        : 'Bacterial disease causing water-soaked, dark lesions with yellow halos.',
        'irrigation'  : 'Avoid overhead watering. Reduce splashing which spreads bacteria.',
        'treatment'   : 'Apply copper hydroxide bactericides. Avoid working when plants are wet.',
        'fertilizer'  : 'Calcium foliar sprays strengthen cell walls against bacteria.',
    },
    'greening': {
        'desc'        : 'Severe bacterial disease (HLB) causing yellowing, misshapen bitter fruit.',
        'irrigation'  : 'Maintain adequate uniform moisture. Avoid drought stress.',
        'treatment'   : 'No cure. Remove infected trees. Control Asian citrus psyllid vector.',
        'fertilizer'  : 'Intensive micronutrient foliar feeding can slow symptom progression.',
    },
    'esca': {
        'desc'        : 'Complex fungal disease causing leaf striping, wood decay in grapevines.',
        'irrigation'  : 'Reduce water stress; stagger irrigation during hot periods.',
        'treatment'   : 'No chemical cure. Remove infected wood. Use sodium arsenite (where legal).',
        'fertilizer'  : 'Balanced vine nutrition; avoid potassium deficiency in vineyards.',
    },
    'leaf_blight': {
        'desc'        : 'Fungal or bacterial blight causing large irregular brown patches on leaves.',
        'irrigation'  : 'Improve drainage. Avoid over-watering and use drip irrigation.',
        'treatment'   : 'Apply copper oxychloride or mancozeb. Remove infected plant debris.',
        'fertilizer'  : 'Balanced NPK fertilization; avoid excess nitrogen application.',
    },
    'leaf_scorch': {
        'desc'        : 'Fungal disease causing dark purple-edged lesions that kill leaf tissue.',
        'irrigation'  : 'Maintain consistent soil moisture. Avoid water stress and drought.',
        'treatment'   : 'Apply captan or myclobutanil fungicides in early spring.',
        'fertilizer'  : 'Avoid high nitrogen; increase Potassium for berry quality.',
    },
    'healthy': {
        'desc'        : 'The plant appears vigorous and shows no signs of disease or stress.',
        'irrigation'  : 'Maintain your standard optimal watering schedule.',
        'treatment'   : 'No treatment required. Continue regular monitoring.',
        'fertilizer'  : 'Low-dose balanced organic compost for sustained healthy growth.',
    },
}

def get_disease_info(class_name: str) -> dict:
    """Map a class name string to the appropriate disease info dictionary."""
    cn = class_name.lower()
    if 'healthy'          in cn: return DISEASE_INFO['healthy']
    if 'scab'             in cn: return DISEASE_INFO['scab']
    if 'black_rot'        in cn: return DISEASE_INFO['black_rot']
    if 'rust'             in cn: return DISEASE_INFO['rust']
    if 'powdery_mildew'   in cn: return DISEASE_INFO['powdery_mildew']
    if 'gray_leaf_spot'   in cn: return DISEASE_INFO['gray_leaf_spot']
    if 'blight'           in cn: return DISEASE_INFO['blight']
    if 'leaf_mold'        in cn: return DISEASE_INFO['leaf_mold']
    if 'septoria'         in cn: return DISEASE_INFO['septoria']
    if 'spider_mites'     in cn: return DISEASE_INFO['spider_mites']
    if 'target_spot'      in cn: return DISEASE_INFO['target_spot']
    if 'virus'            in cn or 'mosaic' in cn or 'curl' in cn: return DISEASE_INFO['virus']
    if 'bacterial_spot'   in cn: return DISEASE_INFO['bacterial_spot']
    if 'haunglongbing'    in cn: return DISEASE_INFO['greening']
    if 'esca'             in cn: return DISEASE_INFO['esca']
    if 'leaf_blight'      in cn: return DISEASE_INFO['leaf_blight']
    if 'leaf_scorch'      in cn: return DISEASE_INFO['leaf_scorch']
    # Fallback
    return DISEASE_INFO['healthy']

# ============================================================
# PREPROCESSING  (128×128 – matches training pipeline)
# ============================================================
def preprocess_image(image_bytes: bytes) -> np.ndarray:
    img = Image.open(io.BytesIO(image_bytes)).convert('RGB')
    img = img.resize((128, 128))
    arr = np.array(img, dtype=np.float32) / 255.0   # normalize to [0,1]
    return np.expand_dims(arr, axis=0)               # shape: (1, 128, 128, 3)

# ============================================================
# ROUTES
# ============================================================
@app.route('/health', methods=['GET'])
def health():
    return jsonify({'status': 'ok', 'model': 'Plant Disease CNN (New Plant Diseases Dataset)'})


@app.route('/predict', methods=['POST'])
def predict():
    if 'image' not in request.files:
        return jsonify({'error': 'No image field in request'}), 400

    file = request.files['image']
    if file.filename == '':
        return jsonify({'error': 'Empty filename'}), 400

    try:
        image_bytes = file.read()
        input_arr   = preprocess_image(image_bytes)

        predictions  = model.predict(input_arr, verbose=0)[0]   # shape: (38,)
        top_index    = int(np.argmax(predictions))
        confidence   = float(predictions[top_index]) * 100

        class_name   = CLASS_NAMES[top_index]
        display_name = DISPLAY_LABELS.get(class_name, class_name.replace('___', ' ').replace('_', ' '))
        plant_name   = display_name.split(' ')[0]    # First word is the plant name
        disease_info = get_disease_info(class_name)

        return jsonify({
            'disease'    : class_name,
            'label'      : display_name,
            'plant'      : plant_name,
            'confidence' : round(confidence, 2),
            'info'       : disease_info,
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500


if __name__ == '__main__':
    port = int(os.environ.get('PORT', '5050'))
    print("\nAgriCare Disease Detection API")
    print("   Dataset : New Plant Diseases Dataset (Kaggle - vipoooool)")
    print("   Classes : 38")
    print("   Model   : Custom CNN (128x128 input, 1500-neuron dense, softmax)")
    print(f"   Running : http://localhost:{port}\n")
    app.run(host='0.0.0.0', port=port, debug=False)
