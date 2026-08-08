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
# Note: Do NOT throttle threads here — it slows down the one-time JIT compilation at startup

# Paths
DIR_PATH = os.path.dirname(__file__)
MODEL_BASE_NAME = "plant_disease_model"
KERAS_MODEL_PATH = os.path.join(DIR_PATH, f"{MODEL_BASE_NAME}.keras")
IMAGE_SIZE = (224, 224)   # EfficientNetB0 native size (v2 model)

# Load model globally on startup to prevent slow re-loading on every request!
print("Loading Keras Model... This might take a few seconds.")
try:
    global_model = keras.models.load_model(KERAS_MODEL_PATH)
    print("Model loaded successfully!")
    # Warmup: run one dummy inference so TensorFlow's JIT compilation
    # happens NOW at startup — not during the first user scan (which would cause a 10-40s delay).
    print("Warming up model... (first-time JIT compilation)")
    _dummy = np.zeros((1, IMAGE_SIZE[0], IMAGE_SIZE[1], 3), dtype=np.float32)
    _ = global_model(_dummy, training=False)
    del _dummy
    print("Model warmup complete! Ready to scan.")
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
        "title": "No Plant Disease Detected",
        "desc": "The uploaded image does not show recognizable plant leaf disease symptoms. Please upload a clear, focused, well-lit photo of the crop leaf to scan again.",
        "unknown": "No Plant Disease Detected",
        "detected": "Detected",
        "species": "SPECIES",
        "non_plant_detected": "Non-plant Image"
    },
    "gu": {
        "title": "કોઈ રોગ જણાયો નથી",
        "desc": "અપલોડ કરેલી છબીમાં છોડના રોગના કોઈ સ્પષ્ટ લક્ષણો જણાયા નથી. કૃપા કરીને પાનનો સ્પષ્ટ, કેન્દ્રિત અને સારો પ્રકાશ હોય તેવો ફોટો અપલોડ કરી ફરી સ્કેન કરો.",
        "unknown": "કોઈ રોગ જણાયો નથી",
        "detected": "ઓળખાયેલ",
        "species": "જાતિ",
        "non_plant_detected": "છોડ સિવાયની છબી"
    },
    "hi": {
        "title": "कोई रोग नहीं मिला",
        "desc": "अपलोड की गई तस्वीर में पौधे के किसी रोग के लक्षण नहीं मिले हैं। कृपया फसल की पत्ती की स्पष्ट, केंद्रित और अच्छी रोशनी वाली तस्वीर अपलोड करके पुनः स्कैन करें।",
        "unknown": "कोई रोग नहीं मिला",
        "detected": "पहचाना गया",
        "species": "प्रजाति",
        "non_plant_detected": "पौधे के अलावा अन्य छवि"
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

PRESCRIPTION_DB = {
    # Apple Diseases
    "Apple___Apple_scab": {
        "en": {
            "desc": "Fungal infection causing dark olive-green to black velvety spots on leaves, leading to premature leaf drop and fruit deformity.",
            "irrigation": "Avoid overhead sprinkler irrigation. Water early in the morning using drip lines so foliage dries quickly in sunlight.",
            "treatment": "Spray Captan 50% WP (2.5g/L) or Mancozeb 75% WP (2g/L) or Difenoconazole 25% EC (0.5ml/L). Repeat after 10-12 days if humid weather persists. Rake and destroy fallen leaves."
        },
        "gu": {
            "desc": "સફરજનના પાન અને ફળ પર કાળા-ઓલિવ લીલા મખમલી ડાઘા પાડતો ફૂગજન્ય રોગ, જેથી પાન ખરી પડે છે.",
            "irrigation": "ફુવારા પિયત બિલકુલ ટાળો. ટપક પદ્ધતિથી વહેલી સવારે પિયત આપો જેથી પાન તડકામાં ઝડપથી સુકાઈ જાય.",
            "treatment": "કેપ્ટાન 50% WP (2.5 ગ્રામ/લિટર) અથવા મેન્કોઝેબ 75% WP (2 ગ્રામ/લિટર) અથવા ડાયફેનોકોનાઝોલ (0.5 મિલી/લિટર) નો છંટકાવ કરો. ખરી પડેલા પાન બાળીને નાશ કરવો."
        },
        "hi": {
            "desc": "सेब के पत्तों और फलों पर काले-जैतूनी मखमली धब्बे बनाने वाला कवक रोग, जिससे पत्तियां समय से पहले गिर जाती हैं।",
            "irrigation": "ऊपरी फव्वारा सिंचाई से बचें। सुबह जल्दी ड्रिप द्वारा पानी दें ताकि धूप में पत्तियां जल्दी सूख जाएं।",
            "treatment": "कैप्टन 50% WP (2.5g/L) या मैंकोजेब 75% WP (2g/L) या डाइफेनोकोनाज़ोल (0.5ml/L) का छिड़काव करें। गिरे हुए संक्रमित पत्तों को नष्ट करें।"
        }
    },
    "Apple___Black_rot": {
        "en": {
            "desc": "Fungal disease causing 'frogeye' circular leaf spots with purple margins, branch cankers, and black mummified rotting fruit.",
            "irrigation": "Ensure good soil drainage. Avoid high canopy humidity by maintaining proper plant spacing and pruning dead wood.",
            "treatment": "Spray Copper Oxychloride 50% WP (3g/L) or Thiophanate-methyl 70% WP (1g/L). Prune out infected twigs 6 inches below cankers and sanitize pruning shears."
        },
        "gu": {
            "desc": "પાન પર ગોળાકાર ડાઘા, ડાળીઓમાં સડો અને ફળ કાળા પાડી સડાવી દેતો ગંભીર ફૂગજન્ય રોગ.",
            "irrigation": "જમીનમાં પાણીનો સારો નિકાલ રાખો. છોડ વચ્ચે પૂરતી જગ્યા રાખો જેથી હવાની અવરજવર સારી રહે.",
            "treatment": "કોપર ઓક્સીક્લોરાઇડ 50% WP (3 ગ્રામ/લિટર) અથવા થાયોફેનેટ-મિથાઇલ (1 ગ્રામ/લિટર) નો છંટકાવ કરો. સૂકી અને ચેપગ્રસ્ત ડાળીઓ કાપીને દૂર કરો."
        },
        "hi": {
            "desc": "पत्तों पर मेंढक की आंख जैसे गोल धब्बे, टहनियों में घाव और फलों को सड़ाने वाला कवक रोग।",
            "irrigation": "खेत में जल निकासी अच्छी रखें। पौधों की छंटाई करके धूप और हवा का संचार बढ़ाएं।",
            "treatment": "कॉपर ऑक्सीक्लोराइड 50% WP (3g/L) या थायोफिनेट मिथाइल (1g/L) का छिड़काव करें। संक्रमित सूखी टहनियों को काटकर नष्ट करें।"
        }
    },
    "Apple___Cedar_apple_rust": {
        "en": {
            "desc": "Fungal rust producing bright yellow-orange spots on upper leaf surfaces with small tube-like spore horns underneath.",
            "irrigation": "Irrigate at ground level only. Keep foliage completely dry during spring spore flight periods.",
            "treatment": "Apply Myclobutanil 10% WP (1g/L) or Mancozeb 75% WP (2.5g/L) or Propiconazole 25% EC (1ml/L) at pink bud stage. Remove nearby cedar trees if possible."
        },
        "gu": {
            "desc": "પાનની ઉપર ચમકતા પીળા-નારંગી ડાઘા અને પાનની નીચે નળી જેવા બીજાણુ બનાવતો ગેરુ રોગ.",
            "irrigation": "ફક્ત જમીન પર જ પાણી આપો. વસંત ઋતુમાં પાન ભીના ન થાય તેની ખાસ કાળજી રાખો.",
            "treatment": "માયક્લોબ્યુટાનિલ 10% WP (1 ગ્રામ/લિટર) અથવા પ્રોપિકોનાઝોલ 25% EC (1 મિલી/લિટર) અથવા મેન્કોઝેબનો છંટકાવ કરવો."
        },
        "hi": {
            "desc": "पत्तों की ऊपरी सतह पर चमकीले पीले-नारंगी धब्बे और निचली सतह पर सींग जैसी संरचनाएं बनाने वाला रस्ट रोग।",
            "irrigation": "केवल जमीन की सतह पर पानी दें। पत्तियों को पूरी तरह सूखा रखें।",
            "treatment": "माइक्लोब्यूटानिल 10% WP (1g/L) या प्रोपिकोनाज़ोल 25% EC (1ml/L) या मैंकोजेब (2.5g/L) का छिड़काव करें।"
        }
    },

    # Corn / Maize Diseases
    "Corn_(maize)___Cercospora_leaf_spot Gray_leaf_spot": {
        "en": {
            "desc": "Fungal pathogen forming rectangular, grayish-tan lesions running parallel to leaf veins, restricting photosynthesis.",
            "irrigation": "Avoid excessive overhead irrigation. Improve field drainage and avoid working in wet crops.",
            "treatment": "Spray Azoxystrobin + Difenoconazole (Amistar Top 1ml/L) or Pyraclostrobin (1ml/L) at first detection. Practice 2-year crop rotation with non-host crops."
        },
        "gu": {
            "desc": "મકાઈના પાનની નસોની સમાંતર લંબચોરસ રાખોડી-કથ્થઈ પટ્ટા બનાવતો ફૂગનો રોગ.",
            "irrigation": "વધુ પડતું પિયત ટાળો. ખેતરમાં પાણી ભરાવા ન દો અને પાન ભીના હોય ત્યારે ખેતમાં ન ફરવું.",
            "treatment": "એઝોક્સીસ્ટ્રોબિન + ડાયફેનોકોનાઝોલ (1 મિલી/લિટર) અથવા પાયરાક્લોસ્ટ્રોબિનનો છંટકાવ કરો. પાકની ફેરબદલી અપનાવો."
        },
        "hi": {
            "desc": "मक्का के पत्तों की नसों के समानांतर आयताकार धूसर-भूरे घाव बनाने वाला कवक रोग।",
            "irrigation": "अत्यधिक सिंचाई से बचें और खेत में उचित जल निकासी बनाए रखें।",
            "treatment": "एज़ोक्सीस्ट्रोबिन + डाइफेनोकोनाज़ोल (1ml/L) या पायराक्लोस्ट्रोबिन का छिड़काव करें। फसल चक्र अपनाएं।"
        }
    },
    "Corn_(maize)___Common_rust_": {
        "en": {
            "desc": "Fungal disease causing oval to elongated cinnamon-brown powdery pustules scattered over both leaf surfaces.",
            "irrigation": "Maintain standard soil moisture. Avoid sprinkler systems that elevate humidity in dense canopies.",
            "treatment": "Apply Propiconazole 25% EC (1ml/L) or Tebuconazole 25.9% EC (1ml/L) or Mancozeb (2.5g/L) when pustules appear on upper leaves before silking."
        },
        "gu": {
            "desc": "મકાઈના પાનની બંને બાજુ કથ્થઈ રંગની પાવડર જેવી ફોલ્લીઓ બનાવતો સામાન્ય ગેરુ રોગ.",
            "irrigation": "જમીનમાં જરૂરી ભેજ જાળવો. વધુ પડતો ભેજ પેદા કરે તેવા ફુવારા ન વાપરો.",
            "treatment": "પ્રોપિકોનાઝોલ 25% EC (1 મિલી/લિટર) અથવા ટેબુકોનાઝોલ (1 મિલી/લિટર) અથવા મેન્કોઝેબનો છંટકાવ કરવો."
        },
        "hi": {
            "desc": "पत्तियों के दोनों तरफ दालचीनी जैसे भूरे रंग के पाउडर वाले फफोले बनाने वाला रस्ट रोग।",
            "irrigation": "संतुलित सिंचाई करें। फसल के ऊपर नमी ज्यादा देर न टिकने दें।",
            "treatment": "प्रोपिकोनाज़ोल 25% EC (1ml/L) या टेबुकोनाज़ोल (1ml/L) या मैंकोजेब (2.5g/L) का छिड़काव करें।"
        }
    },
    "Corn_(maize)___Northern_Leaf_Blight": {
        "en": {
            "desc": "Destructive fungal disease producing large, cigar-shaped grayish-green to tan lesions (1-6 inches long).",
            "irrigation": "Manage irrigation carefully; avoid late evening watering that leaves crop wet overnight.",
            "treatment": "Spray Azoxystrobin (1ml/L) or Mancozeb 75% WP (2.5g/L) or Propiconazole (1ml/L). Plow under crop residue after harvest."
        },
        "gu": {
            "desc": "મકાઈમાં 1 થી 6 ઇંચ લાંબા સિગાર આકારના રાખોડી-લીલા મોટા ડાઘા પાડતો મોટો સુકારો રોગ.",
            "irrigation": "સાંજના સમયે પિયત ન આપો જેથી રાત્રે પાન ભીના ન રહે. નિયમિત પિયત આપો.",
            "treatment": "એઝોક્સીસ્ટ્રોબિન (1 મિલી/લિટર) અથવા મેન્કોઝેબ 75% WP (2.5 ગ્રામ/લિટર) નો છંટકાવ કરો. લણણી પછી પાકના અવશેષોનો નાશ કરવો."
        },
        "hi": {
            "desc": "पत्तियों पर 1 से 6 इंच लंबे सिगार के आकार के बड़े धूसर-भूरे धब्बे बनाने वाला पत्ता झुलसा रोग।",
            "irrigation": "देर शाम सिंचाई से बचें ताकि रात भर पत्तियां गीली न रहें।",
            "treatment": "एज़ोक्सीस्ट्रोबिन (1ml/L) या मैंकोजेब 75% WP (2.5g/L) या प्रोपिकोनाज़ोल का छिड़काव करें।"
        }
    },

    # Grape Diseases
    "Grape___Black_rot": {
        "en": {
            "desc": "Fungal infection creating reddish-brown leaf spots with black fruiting specks, turning grape berries into hard black mummies.",
            "irrigation": "Ensure open canopy with proper vine trellis training. Use drip irrigation exclusively.",
            "treatment": "Spray Mancozeb 75% WP (2.5g/L) or Myclobutanil (1g/L) or Kresoxim-methyl (1ml/L) from early shoot growth through post-bloom."
        },
        "gu": {
            "desc": "દ્રાક્ષના પાન પર લાલ-કથ્થઈ ડાઘા અને દ્રાક્ષના દાણાને કાળા સુકવી નાખતો કાળો સડો રોગ.",
            "irrigation": "વેલાની યોગ્ય છાંટણી રાખો જેથી હવા-ઉજાસ રહે. ફક્ત ટપક પિયત પદ્ધતિનો ઉપયોગ કરવો.",
            "treatment": "મેન્કોઝેબ 75% WP (2.5 ગ્રામ/લિટર) અથવા માયક્લોબ્યુટાનિલ (1 ગ્રામ/લિટર) નો ફૂલ અને ફળ બેસવાના સમયે છંટકાવ કરવો."
        },
        "hi": {
            "desc": "अंगूर के पत्तों पर लाल-भूरे धब्बे और अंगूर के दानों को सुखाकर काला करने वाला ब्लैक रॉट रोग।",
            "irrigation": "अंगूर की लताओं की सही छंटाई करें और केवल ड्रिप सिंचाई का उपयोग करें।",
            "treatment": "मैंकोजेब 75% WP (2.5g/L) या माइक्लोब्यूटानिल (1g/L) या क्रेसोक्सिम मिथाइल का छिड़काव करें।"
        }
    },
    "Grape___Esca_(Black_Measles)": {
        "en": {
            "desc": "Complex fungal vascular disease showing 'tiger-stripe' interveinal leaf necrosis and dark speckled spotting on berries.",
            "irrigation": "Avoid excessive irrigation stress; maintain balanced moisture throughout the root zone.",
            "treatment": "Protect pruning wounds with fungicidal sealant (e.g. Copper paste or Thiophanate-methyl). Prune out dead cordons during dry winter."
        },
        "gu": {
            "desc": "દ્રાક્ષના પાનની નસો વચ્ચે વાઘના પટ્ટા જેવો સુકારો અને દાણા પર કાળા ટપકાં કરતો એસ્કા રોગ.",
            "irrigation": "પિયતમાં વધુ પડતો તણાવ ન આપો. મૂળ વિસ્તારમાં સમાન ભેજ જાળવો.",
            "treatment": "કાપેલા ભાગો પર કોપર પેસ્ટ લગાવો. સૂકી અને રોગિષ્ઠ ડાળીઓ શિયાળામાં કાપીને દૂર કરો."
        },
        "hi": {
            "desc": "पत्तियों पर बाघ की धारियों जैसा सूखापन और अंगूरों पर काले चकत्ते बनाने वाला एस्का रोग।",
            "irrigation": "पौधों को अत्यधिक सूखे या पानी के तनाव से बचाएं।",
            "treatment": "छंटाई के घावों पर कॉपर पेस्ट या कवकनाशी लगाएं। सूखी संक्रमित शाखाओं को काटें।"
        }
    },
    "Grape___Leaf_blight_(Isariopsis_Leaf_Spot)": {
        "en": {
            "desc": "Fungal spots appearing on mature leaves with dark brown centers and yellowish chlorotic borders.",
            "irrigation": "Avoid over-watering and standing moisture. Ensure adequate canopy sunlight penetration.",
            "treatment": "Spray Copper Oxychloride 50% WP (3g/L) or Mancozeb 75% WP (2.5g/L) or Carbendazim 50% WP (1g/L) after monsoon rains."
        },
        "gu": {
            "desc": "દ્રાક્ષના પાન પર કથ્થઈ કેન્દ્ર અને પીળી કિનારી વાળા ટપકાં કરતો સુકારો રોગ.",
            "irrigation": "વધુ પડતું પાણી આપવાનું ટાળો. વેલામાં સૂર્યપ્રકાશ પહોંચે તેવી વ્યવસ્થા રાખો.",
            "treatment": "કોપર ઓક્સીક્લોરાઇડ 50% WP (3 ગ્રામ/લિટર) અથવા કાર્બેન્ડાઝિમ 50% WP (1 ગ્રામ/લિટર) નો છંટકાવ કરવો."
        },
        "hi": {
            "desc": "परिपक्व पत्तियों पर भूरे केंद्र और पीले घेरे वाले धब्बे बनाने वाला पत्ता झुलसा रोग।",
            "irrigation": "अत्यधिक पानी देने से बचें। लताओं में धूप का प्रवेश सुनिश्चित करें।",
            "treatment": "कॉपर ऑक्सीक्लोराइड 50% WP (3g/L) या कार्बेन्डाजिम 50% WP (1g/L) का छिड़काव करें।"
        }
    },

    # Potato Diseases
    "Potato___Early_blight": {
        "en": {
            "desc": "Alternaria solani fungal infection producing dark brown spots with characteristic concentric target-board rings on older leaves.",
            "irrigation": "Water deeply using drip irrigation every 3-4 days. Avoid sprinkler irrigation to prevent spore splash.",
            "treatment": "Spray Chlorothalonil 75% WP (2g/L) or Mancozeb 75% WP (2.5g/L) or Azoxystrobin 23% SC (1ml/L). Remove and destroy lower infected foliage."
        },
        "gu": {
            "desc": "બટાકાના જૂના પાન પર ગોળાકાર વલયો (ટાર્ગેટ બોર્ડ) જેવા કથ્થઈ ડાઘા પાડતો આગોતરો સુકારો.",
            "irrigation": "ટપક પદ્ધતિથી દર 3-4 દિવસે પિયત આપો. ફુવારાથી પાન ભીના ન થાય તેનું ધ્યાન રાખો.",
            "treatment": "ક્લોરોથેલોનિલ 75% WP (2 ગ્રામ/લિટર) અથવા મેન્કોઝેબ (2.5 ગ્રામ/લિટર) અથવા એઝોક્સીસ્ટ્રોબિન (1 મિલી/લિટર) છાંટો. નીચેના ચેપગ્રસ્ત પાન તોડી લો."
        },
        "hi": {
            "desc": "आलू की पुरानी पत्तियों पर छल्लेदार गोल कत्थई धब्बे बनाने वाला अगेती झुलसा रोग।",
            "irrigation": "ड्रिप सिंचाई द्वारा 3-4 दिनों के अंतराल पर पानी दें। पत्तियों पर पानी न छिड़कें।",
            "treatment": "क्लोरोथैलोनिल 75% WP (2g/L) या मैंकोजेब 75% WP (2.5g/L) या एज़ोक्सीस्ट्रोबिन (1ml/L) का छिड़काव करें।"
        }
    },
    "Potato___Late_blight": {
        "en": {
            "desc": "Devastating Phytophthora oomycete causing rapid water-soaked dark lesions with white fungal fuzz underneath in cool, humid conditions.",
            "irrigation": "Immediately halt overhead watering during cloudy/humid weather. Ensure zero water stagnation in furrows.",
            "treatment": "Apply systemic fungicide Metalaxyl-M + Mancozeb (Ridomil Gold 2.5g/L) or Cymoxanil + Mancozeb (Secting 2g/L) immediately. Reapply in 7 days."
        },
        "gu": {
            "desc": "ઠંડા અને ભેજવાળા વાતાવરણમાં ઝડપથી પાન અને કંદ કાળા પાડી સડાવી દેતો વિનાશક પાછોતરો સુકારો.",
            "irrigation": "વાદળછાયા વાતાવરણમાં પિયત બંધ કરો અથવા મર્યાદિત કરો. ખેતરમાં પાણી જરાય ભરાવા ન દો.",
            "treatment": "રિડોમિલ ગોલ્ડ (મેટાલેક્સિલ + મેન્કોઝેબ 2.5 ગ્રામ/લિટર) અથવા સાયમોક્સાનિલ + મેન્કોઝેબ (2 ગ્રામ/લિટર) નો તાત્કાલિક છંટકાવ કરવો."
        },
        "hi": {
            "desc": "ठंडे और नम मौसम में तेजी से पत्तियों और कंदों को सड़ाने वाला विनाशकारी पछेती झुलसा रोग।",
            "irrigation": "नम मौसम में पानी बंद करें और खेत में जलभराव न होने दें।",
            "treatment": "रिडोमिल गोल्ड (मेटालेक्सिल + मैंकोजेब 2.5g/L) या साइमोक्सानिल + मैंकोजेब (2g/L) का तुरंत छिड़काव करें। 7 दिन बाद दोहराएं।"
        }
    },

    # Tomato Diseases
    "Tomato___Bacterial_spot": {
        "en": {
            "desc": "Xanthomonas bacterial spots appearing as small (2-3mm) dark greasy lesions with yellow halos on leaves, stems, and green fruit.",
            "irrigation": "Strictly drip irrigation only. Keep foliage completely dry and never work in fields while dew or rain is present.",
            "treatment": "Spray Copper Hydroxide (2g/L) tank-mixed with Streptocycline or Plantomycin (1g in 10 Liters of water). Practice 3-year crop rotation."
        },
        "gu": {
            "desc": "ટમેટાના પાન અને ફળ પર પીળી કિનારી વાળા નાના કાળા ચીકણા ટપકાં પાડતો જીવાણુ રોગ.",
            "irrigation": "ફક્ત ટપક પિયત પદ્ધતિ જ વાપરો. પાન પર ઝાકળ કે પાણી હોય ત્યારે ખેતમાં કામ ન કરવું.",
            "treatment": "કોપર હાઇડ્રોક્સાઇડ (2 ગ્રામ/લિટર) સાથે સ્ટ્રેપ્ટોસાયક્લીન (1 ગ્રામ પ્રતિ 10 લિટર પાણી) ભેળવીને છંટકાવ કરવો."
        },
        "hi": {
            "desc": "टमाटर की पत्तियों और फलों पर पीले घेरे वाले छोटे तैलीय काले धब्बे बनाने वाला जीवाणु रोग।",
            "irrigation": "सिर्फ ड्रिप सिंचाई करें। पत्तियों को पूरी तरह सूखा रखें।",
            "treatment": "कॉपर हाइड्रॉक्साइड (2g/L) में स्ट्रेप्टोसाइक्लिन (1g प्रति 10 लीटर पानी) मिलाकर छिड़काव करें। फसल चक्र अपनाएं।"
        }
    },
    "Tomato___Early_blight": {
        "en": {
            "desc": "Target-like concentric brown spots on lower leaves with surrounding chlorosis, leading to defoliation and sunscald.",
            "irrigation": "Irrigate at ground level using drip lines. Mulch soil with straw or plastic to prevent soil-borne spore splashing.",
            "treatment": "Apply Mancozeb 75% WP (2.5g/L) or Chlorothalonil (2g/L) or Azoxystrobin (1ml/L). Prune off lower 12 inches of infected foliage."
        },
        "gu": {
            "desc": "નીચેના પાન પર ગોળાકાર વલયો વાળા કથ્થઈ ડાઘા અને પાન પીળા પાડી નાખતો આગોતરો સુકારો.",
            "irrigation": "ટપક પિયત વાપરો. જમીન પર પ્લાસ્ટિક કે સૂકા ઘાસનું મલ્ચિંગ કરો જેથી જમીનમાંથી ફૂગ પાન પર ન ઉડે.",
            "treatment": "મેન્કોઝેબ 75% WP (2.5 ગ્રામ/લિટર) અથવા ક્લોરોથેલોનિલ (2 ગ્રામ/લિટર) છાંટો. નીચેના 1 ફૂટ સુધીના રોગિષ્ઠ પાન કાપી લો."
        },
        "hi": {
            "desc": "निचली पत्तियों पर गोल छल्लेदार भूरे धब्बे और पीलापन पैदा करने वाला अगेती झुलसा।",
            "irrigation": "जमीन पर ड्रिप से पानी दें। मिट्टी पर मल्चिंग करें ताकि मिट्टी के कवक पत्तियों पर न उछलें।",
            "treatment": "मैंकोजेब 75% WP (2.5g/L) या क्लोरोथैलोनिल (2g/L) या एज़ोक्सीस्ट्रोबिन का छिड़काव करें।"
        }
    },
    "Tomato___Late_blight": {
        "en": {
            "desc": "Water-soaked dark oily leaf lesions rapidly expanding into large brown necrotic blotches with white mildew on leaf undersides.",
            "irrigation": "Reduce irrigation frequency immediately. Avoid evening watering and ensure maximum air circulation.",
            "treatment": "Spray Metalaxyl-M + Mancozeb (Ridomil Gold 2.5g/L) or Dimethomorph 50% WP (1.5g/L) immediately. Follow up with Copper Oxychloride (3g/L) in 7 days."
        },
        "gu": {
            "desc": "ભેજવાળા વાતાવરણમાં પાન પર તેલિયા જેવા કાળા ડાઘા અને પાનની નીચે સફેદ ફૂગ બનાવતો વિનાશક પાછોતરો સુકારો.",
            "irrigation": "પિયત તાત્કાલિક ઘટાડી દો. સાંજે પિયત ન આપો અને પાક વચ્ચે હવા ઉજાસ જાળવો.",
            "treatment": "રિડોમિલ ગોલ્ડ (2.5 ગ્રામ/લિટર) અથવા ડાઇમેથોમોર્ફ (1.5 ગ્રામ/લિટર) નો તાત્કાલિક છંટકાવ કરવો. 7 દિવસ બાદ કોપર ઓક્સીક્લોરાઇડ છાંટવું."
        },
        "hi": {
            "desc": "पत्तियों पर तैलीय काले धब्बे और निचली सतह पर सफेद फफूंद बनाने वाला पछेती झुलसा रोग।",
            "irrigation": "सिंचाई तुरंत कम करें। शाम को पानी न दें और खेत में हवा का संचार बनाए रखें।",
            "treatment": "रिडोमिल गोल्ड (2.5g/L) या डाइमेथोमॉर्फ (1.5g/L) का तुरंत छिड़काव करें। 7 दिन बाद कॉपर ऑक्सीक्लोराइड छिड़कें।"
        }
    },
    "Tomato___Leaf_Mold": {
        "en": {
            "desc": "Passalora fulva fungus forming pale green to yellow patches on upper leaf surfaces and velvety olive-brown mold underneath.",
            "irrigation": "Improve greenhouse/field ventilation. Keep relative humidity below 85% and avoid wet leaves.",
            "treatment": "Spray Difenoconazole 25% EC (0.5ml/L) or Chlorothalonil 75% WP (2g/L) or Copper Hydroxide (2g/L). Prune dense canopy."
        },
        "gu": {
            "desc": "પાનની ઉપર આછા પીળા ડાઘા અને પાનની નીચે મખમલી કથ્થઈ-ઓલિવ ફૂગ છવાઈ જતો લીફ મોલ્ડ રોગ.",
            "irrigation": "ગ્રીનહાઉસ કે ખેતરમાં હવાની અવરજવર વધારો. પાન પર ભેજ ન રહે તેની કાળજી રાખવી.",
            "treatment": "ડાયફેનોકોનાઝોલ (0.5 મિલી/લિટર) અથવા ક્લોરોથેલોનિલ (2 ગ્રામ/લિટર) અથવા કોપર હાઇડ્રોક્સાઇડનો છંટકાવ કરવો."
        },
        "hi": {
            "desc": "पत्तियों के ऊपर हल्के पीले धब्बे और नीचे मखमली कत्थई फफूंद बनाने वाला लीफ मोल्ड रोग।",
            "irrigation": "खेत में हवा का प्रवाह बढ़ाएं और पत्तियों को सूखा रखें।",
            "treatment": "डाइफेनोकोनाज़ोल (0.5ml/L) या क्लोरोथैलोनिल (2g/L) या कॉपर हाइड्रॉक्साइड का छिड़काव करें।"
        }
    },
    "Tomato___Septoria_leaf_spot": {
        "en": {
            "desc": "Numerous tiny circular spots (1-3mm) with grayish-white centers and dark brown margins studded with black pycnidia specks.",
            "irrigation": "Water only root zone via drip lines. Do not overhead water; keep foliage dry.",
            "treatment": "Spray Mancozeb 75% WP (2.5g/L) or Chlorothalonil (2g/L) or Copper Oxychloride (3g/L) every 7-10 days until new foliage emerges clean."
        },
        "gu": {
            "desc": "પાન પર સફેદ-રાખોડી કેન્દ્ર અને કાળી કિનારી વાળા અસંખ્ય નાના ગોળ ટપકાં કરતો સેપ્ટોરિયા રોગ.",
            "irrigation": "ફક્ત મૂળ પાસે ટપકથી જ પિયત આપો. પાન પર પાણી છાંટવું નહીં.",
            "treatment": "મેન્કોઝેબ 75% WP (2.5 ગ્રામ/લિટર) અથવા ક્લોરોથેલોનિલ (2 ગ્રામ/લિટર) નો દર 7-10 દિવસે છંટકાવ કરવો."
        },
        "hi": {
            "desc": "पत्तियों पर सफेद-धूसर केंद्र और गहरे भूरे घेरे वाले छोटे-छोटे गोल धब्बे बनाने वाला सेप्टोरिया रोग।",
            "irrigation": "जड़ों में ड्रिप से पानी दें। पत्तियों पर पानी न पड़ने दें।",
            "treatment": "मैंकोजेब 75% WP (2.5g/L) या क्लोरोथैलोनिल (2g/L) का 7-10 दिनों के अंतराल पर छिड़काव करें।"
        }
    },
    "Tomato___Spider_mites Two-spotted_spider_mite": {
        "en": {
            "desc": "Tetranychus urticae mites causing pale yellow stippling on leaves, severe bronzing, and dense webbing across plant tops.",
            "irrigation": "Maintain adequate irrigation to reduce drought stress. Overhead misting during peak heat discourages mite proliferation.",
            "treatment": "Apply Spiromesifen 22.9% SC (1ml/L) or Propargite 57% EC (2ml/L) or Abamectin 1.9% EC (0.5ml/L). Direct spray thoroughly to leaf undersides."
        },
        "gu": {
            "desc": "પાનમાંથી રસ ચૂસી પીળા ઝીણા ટપકાં અને પાતળી જાળી બનાવતી લાલ કથીરી (કરોળિયા) જીવાત.",
            "irrigation": "છોડને પાણીની ખેંચ ન પડવા દો. ગરમીમાં હળવું પિયત આપી ભેજ જાળવવો.",
            "treatment": "સ્પીરોમેસીફેન 22.9% SC (1 મિલી/લિટર) અથવા પ્રોપારગાઇટ (2 મિલી/લિટર) નો પાનની નીચે સારી રીતે પહોંચે તેમ છંટકાવ કરવો."
        },
        "hi": {
            "desc": "पत्तियों का रस चूसकर जाले बनाने वाले और पीलापन पैदा करने वाले लाल मकड़ी कीट।",
            "irrigation": "नियमित सिंचाई करें ताकि पौधा सूखे के तनाव में न आए।",
            "treatment": "स्पाइरोमेसिफेन 22.9% SC (1ml/L) या प्रोपारगाइट (2ml/L) या एबामेक्टिन का पत्तियों के नीचे छिड़काव करें।"
        }
    },
    "Tomato___Target_Spot": {
        "en": {
            "desc": "Corynespora cassiicola fungal lesions with distinct light brown concentric centers surrounded by yellow chlorotic halos.",
            "irrigation": "Avoid wetting foliage; utilize drip irrigation and maintain good plant spacing for airflow.",
            "treatment": "Spray Azoxystrobin + Difenoconazole (1ml/L) or Chlorothalonil 75% WP (2g/L) or Mancozeb (2.5g/L). Stake plants to keep leaves off ground."
        },
        "gu": {
            "desc": "પાન પર ગોળ નિશાન જેવા આછા કથ્થઈ ટપકાં અને પીળી કિનારી પાડતો ટાર્ગેટ સ્પોટ ફૂગનો રોગ.",
            "irrigation": "પાન ભીના ન થાય તે રીતે ટપક પિયત આપો. છોડ વચ્ચે પૂરતી જગ્યા રાખો.",
            "treatment": "એઝોક્સીસ્ટ્રોબિન + ડાયફેનોકોનાઝોલ (1 મિલી/લિટર) અથવા ક્લોરોથેલોનિલ (2 ગ્રામ/લિટર) નો છંટકાવ કરવો. છોડને ટેકો આપી જમીનથી ઊંચા રાખવા."
        },
        "hi": {
            "desc": "पत्तियों पर निशाने जैसे गोल भूरे धब्बे और पीलापन बनाने वाला टारगेट स्पॉट रोग।",
            "irrigation": "पत्तियों को सूखा रखें और ड्रिप से सिंचाई करें।",
            "treatment": "एज़ोक्सीस्ट्रोबिन + डाइफेनोकोनाज़ोल (1ml/L) या क्लोरोथैलोनिल (2g/L) या मैंकोजेब का छिड़काव करें।"
        }
    },
    "Tomato___Tomato_Yellow_Leaf_Curl_Virus": {
        "en": {
            "desc": "Whitefly-transmitted geminivirus causing severe upward curling of leaf margins, severe chlorosis, and stunted bushy growth.",
            "irrigation": "Provide uniform drip irrigation to minimize plant moisture stress; avoid sudden wetting and drying cycles.",
            "treatment": "Control whitefly vectors by spraying Acetamiprid 20% SP (0.5g/L) or Imidacloprid 17.8% SL (0.5ml/L) or Diafenthiuron (1.5g/L). Install yellow sticky traps (20/acre) and remove infected rogue plants."
        },
        "gu": {
            "desc": "સફેદ માખી દ્વારા ફેલાતો કોકડવા વાયરસ, જેથી પાન ચમચીની જેમ ઉપર વળી જાય છે, પીળા પડે છે અને છોડનો વિકાસ અટકી જાય છે.",
            "irrigation": "ટપક પદ્ધતિથી નિયમિત પાણી આપો જેથી છોડ તણાવમાં ન આવે. જમીનમાં પૂરતો ભેજ જાળવવો.",
            "treatment": "સફેદ માખીના નિયંત્રણ માટે એસીટામિપ્રિડ 20% SP (0.5 ગ્રામ/લિટર) અથવા ઇમિડાક્લોપ્રિડ (0.5 મિલી/લિટર) નો છંટકાવ કરવો. એકરે 20 પીળા ચીકણા ટ્રેપ લગાવવા."
        },
        "hi": {
            "desc": "सफेद मक्खी से फैलने वाला विषाणु जिससे पत्तियां ऊपर की ओर मुड़कर पीली हो जाती हैं और पौधे की बढ़वार रुक जाती है।",
            "irrigation": "नियमित ड्रिप सिंचाई करें ताकि पौधे पर सूखे का तनाव न पड़े।",
            "treatment": "सफेद मक्खी नियंत्रण के लिए एसिटामिप्रिड 20% SP (0.5g/L) या इमिडाक्लोप्रिड (0.5ml/L) का छिड़काव करें। पीले चिपचिपे जाल लगाएं।"
        }
    },
    "Tomato___Tomato_mosaic_virus": {
        "en": {
            "desc": "Tobamovirus causing mottled light and dark green mosaic patterns on leaves, leaf distortion, and 'fern-leaf' thinning.",
            "irrigation": "Maintain standard soil moisture. Sanitize hands and tools before touching healthy plants.",
            "treatment": "No chemical cure exists for viruses. Rogue and burn infected plants immediately. Disinfect tools with 10% trisodium phosphate (TSP) or skim milk. Wash hands with soap."
        },
        "gu": {
            "desc": "પાન પર આછા-ઘાટા લીલા ચિત્રવિચિત્ર ધાબા અને પાનને પાતળા દોરા જેવા બનાવી દેતો મોઝેક વાયરસ.",
            "irrigation": "જમીનમાં નિયમિત ભેજ જાળવો. તંદુરસ્ત છોડને અડતા પહેલા હાથ અને ઓજારો સાફ કરવા.",
            "treatment": "વાયરસની કોઈ સીધી દવા નથી. રોગિષ્ઠ છોડને તાત્કાલિક મૂળમાંથી ઉપાડી બાળી નાખો. ખેતીના ઓજારો સાબુ કે સેનિટાઈઝરથી સાફ કરો."
        },
        "hi": {
            "desc": "पत्तियों पर हल्के और गहरे हरे रंग के चितकबरे धब्बे और विकृति पैदा करने वाला मोज़ेक वायरस।",
            "irrigation": "उचित सिंचाई बनाए रखें। स्वस्थ पौधों को छूने से पहले हाथ और औजार साफ करें।",
            "treatment": "वायरस का कोई रासायनिक इलाज नहीं है। संक्रमित पौधों को तुरंत उखाड़कर जला दें। औजारों को साबुन या सैनिटाइजर से साफ करें।"
        }
    },

    # Other Crops
    "Orange___Haunglongbing_(Citrus_greening)": {
        "en": {
            "desc": "Bacterial disease spread by Asian citrus psyllid causing asymmetrical blotchy leaf mottle, yellow veins, and bitter lopsided fruit.",
            "irrigation": "Maintain balanced micro-irrigation to reduce root stress. Provide foliar zinc, iron, and manganese micronutrients.",
            "treatment": "Control Asian citrus psyllid vectors with Thiamethoxam 25% WG (0.5g/L) or Imidacloprid (0.5ml/L). Remove severely infected declining trees."
        },
        "gu": {
            "desc": "સાઈલા જીવાતથી ફેલાતો ગ્રીનિંગ રોગ, જેથી પાન પર અસમાન પીળા ધાબા પડે છે અને ફળ ખાટા-કડવા રહી જાય છે.",
            "irrigation": "મૂળને તણાવ ન પડે તે રીતે નિયમિત પિયત આપો. ઝિંક, આયર્ન અને મેંગેનીઝ જેવા સુક્ષ્મ તત્વોનો છંટકાવ કરવો.",
            "treatment": "સાઈલા જીવાતના નિયંત્રણ માટે થાયામેથોક્ઝામ 25% WG (0.5 ગ્રામ/લિટર) અથવા ઇમિડાક્લોપ્રિડનો છંટકાવ કરવો. વધુ પડતા રોગિષ્ઠ ઝાડ દૂર કરવા."
        },
        "hi": {
            "desc": "सिट्रस साइला कीट द्वारा फैलने वाला रोग जिससे पत्तियों पर पीले धब्बे और फल कड़वे व टेढ़े हो जाते हैं।",
            "irrigation": "संतुलित ड्रिप सिंचाई दें और जिंक, आयरन तथा मैंगनीज सूक्ष्म पोषक तत्वों का छिड़काव करें।",
            "treatment": "साइला कीट नियंत्रण के लिए थायामेथोक्सम 25% WG (0.5g/L) या इमिडाक्लोप्रिड का छिड़काव करें।"
        }
    },
    "Peach___Bacterial_spot": {
        "en": {
            "desc": "Bacterial lesions causing shot-hole perforations on leaves and deeply pitted cracked spots on fruit.",
            "irrigation": "Avoid overhead watering. Maintain orchard floor mulch to reduce dust and bacterial splash.",
            "treatment": "Apply Copper Hydroxide (2g/L) at dormant leaf fall. Spray Oxytetracycline during bloom period."
        },
        "gu": {
            "desc": "પીચના પાનમાં કાણાં પાડી દેતો અને ફળ પર કાળા ચીરા કરતો જીવાણુ રોગ.",
            "irrigation": "ફુવારા પિયત ટાળો. જમીનમાં પૂરતો ભેજ જાળવો.",
            "treatment": "ડોરમન્ટ અવસ્થામાં કોપર હાઇડ્રોક્સાઇડ (2 ગ્રામ/લિટર) નો છંટકાવ કરવો. ફૂલ આવવાના સમયે ઓક્સીટેટ્રાસાયક્લીન છાંટવું."
        },
        "hi": {
            "desc": "पत्तियों में गोल छेद करने वाला और फलों पर दरारें बनाने वाला बैक्टीरियल स्पॉट रोग।",
            "irrigation": "पत्तियों पर पानी का छिड़काव न करें। जड़ों में सिंचाई करें।",
            "treatment": "पत्ते गिरने के समय कॉपर हाइड्रॉक्साइड (2g/L) का छिड़काव करें। फूल आने पर ऑक्सीटेट्रासाइक्लिन का उपयोग करें।"
        }
    },
    "Pepper,_bell___Bacterial_spot": {
        "en": {
            "desc": "Bacterial spots on bell pepper leaves causing small dark blisters with yellow margins, leading to heavy leaf drop.",
            "irrigation": "Employ drip irrigation strictly. Avoid working among wet pepper foliage.",
            "treatment": "Spray Copper Hydroxide (2g/L) mixed with Streptocycline (1g in 10L water). Use certified disease-free seeds."
        },
        "gu": {
            "desc": "મરચીના પાન પર નાના કાળા ફોલ્લા જેવા ટપકાં પાડતો જીવાણુ રોગ, જેથી પાન ઝડપથી ખરી પડે છે.",
            "irrigation": "ફક્ત ટપક પિયત પદ્ધતિ વાપરો. પાન ભીના હોય ત્યારે ખેતમાં ન ફરવું.",
            "treatment": "કોપર હાઇડ્રોક્સાઇડ (2 ગ્રામ/લિટર) સાથે સ્ટ્રેપ્ટોસાયક્લીન (1 ગ્રામ / 10 લિટર પાણી) નો છંટકાવ કરવો."
        },
        "hi": {
            "desc": "शिमला मिर्च की पत्तियों पर पीले घेरे वाले काले छाले जैसे धब्बे बनाने वाला रोग जिससे पत्तियां गिर जाती हैं।",
            "irrigation": "केवल ड्रिप सिंचाई का प्रयोग करें। पत्तियों को सूखा रखें।",
            "treatment": "कॉपर हाइड्रॉक्साइड (2g/L) के साथ स्ट्रेप्टोसाइक्लिन (1g / 10L पानी) मिलाकर छिड़कें।"
        }
    },
    "Squash___Powdery_mildew": {
        "en": {
            "desc": "White powdery fungal growth coating upper and lower leaf surfaces, causing premature senescence.",
            "irrigation": "Water soil at base of plant in the morning. Avoid leaf wetness; high humidity combined with dry soil triggers mildew.",
            "treatment": "Spray Hexaconazole 5% EC (1ml/L) or Wettable Sulfur 80% WP (2.5g/L) or Potassium Bicarbonate (3g/L) early morning."
        },
        "gu": {
            "desc": "કોળાના પાન પર સફેદ પાવડર જેવો છારો છવાઈ જતો ફૂગનો રોગ.",
            "irrigation": "સવારે છોડના મૂળ પાસે પાણી આપો. પાન ભીના ન થાય તેનું ખાસ ધ્યાન રાખવું.",
            "treatment": "હેક્ઝાકોનાઝોલ 5% EC (1 મિલી/લિટર) અથવા સલ્ફર 80% WP (2.5 ગ્રામ/લિટર) નો સવારે છંટકાવ કરવો."
        },
        "hi": {
            "desc": "कद्दू की पत्तियों पर सफेद पाउडर जैसी फफूंद की परत बनाने वाला छाछिया रोग।",
            "irrigation": "सुबह के समय पौधे की जड़ों में पानी दें। पत्तियों को सूखा रखें।",
            "treatment": "हेक्साकोनाज़ोल 5% EC (1ml/L) या घुलनशील सल्फर 80% WP (2.5g/L) का छिड़काव करें।"
        }
    },
    "Strawberry___Leaf_scorch": {
        "en": {
            "desc": "Fungal infection causing irregular purplish-red blotches that turn brown, giving leaves a burnt, scorched appearance.",
            "irrigation": "Use drip tape beneath plastic mulch. Avoid overhead sprinklers that wet strawberry crowns.",
            "treatment": "Spray Captan 50% WP (2.5g/L) or Azoxystrobin (1ml/L) or Copper Oxychloride (2.5g/L). Renovate strawberry beds and remove old scorched foliage after harvest."
        },
        "gu": {
            "desc": "સ્ટ્રોબેરીના પાન પર જાંબલી-લાલ ડાઘા પડી પાન બળી ગયા હોય તેવો સુકારો પેદા કરતો રોગ.",
            "irrigation": "પ્લાસ્ટિક મલ્ચિંગ નીચે ટપક પાઈપથી પિયત આપો. ઉપરથી ફુવારા મારવા નહીં.",
            "treatment": "કેપ્ટાન 50% WP (2.5 ગ્રામ/લિટર) અથવા એઝોક્સીસ્ટ્રોબિન (1 મિલી/લિટર) અથવા કોપર ઓક્સીક્લોરાઇડનો છંટકાવ કરવો. જૂના રોગિષ્ઠ પાન કાપીને દૂર કરવા."
        },
        "hi": {
            "desc": "स्ट्रॉबेरी की पत्तियों पर बैंगनी-लाल धब्बे जो बाद में भूरे होकर जले हुए जैसे दिखाई देते हैं।",
            "irrigation": "ड्रिप सिंचाई का उपयोग करें। पौधों के ऊपरी हिस्से पर पानी न छिड़कें।",
            "treatment": "कैप्टन 50% WP (2.5g/L) या एज़ोक्सीस्ट्रोबिन (1ml/L) या कॉपर ऑक्सीक्लोराइड का छिड़काव करें।"
        }
    },
    "Cherry_(including_sour)___Powdery_mildew": {
        "en": {
            "desc": "White powdery fungal coating on leaves and young shoots, causing leaf curling and fruit blemishes.",
            "irrigation": "Water ground zone directly. Prune inner canopy branches to increase airflow and sunlight penetration.",
            "treatment": "Spray Myclobutanil (1g/L) or Wettable Sulfur 80% WP (2.5g/L) or Tebuconazole (1ml/L) at petal fall."
        },
        "gu": {
            "desc": "ચેરીના પાન અને નવી કૂંપળો પર સફેદ પાવડર જેવી ફૂગ છવાઈ જતો રોગ.",
            "irrigation": "જમીનમાં સીધું પિયત આપો. અંદરની ડાળીઓની છાંટણી કરો જેથી હવા અને તડકો મળી રહે.",
            "treatment": "માયક્લોબ્યુટાનિલ (1 ગ્રામ/લિટર) અથવા સલ્ફર 80% WP (2.5 ગ્રામ/લિટર) નો છંટકાવ કરવો."
        },
        "hi": {
            "desc": "चेरी की पत्तियों और नई टहनियों पर सफेद चूर्ण जैसी फफूंद बनाने वाला रोग।",
            "irrigation": "जड़ों में पानी दें और छंटाई करके हवा का आवागमन बढ़ाएं।",
            "treatment": "माइक्लोब्यूटानिल (1g/L) या घुलनशील सल्फर 80% WP (2.5g/L) का छिड़काव करें।"
        }
    }
}

def get_translated_advice(disease_key, language):
    if "healthy" in disease_key.lower():
        healthy_advice = {
            "en": {
                "desc": "Leaf tissue exhibits robust cellular vitality, optimal chlorophyll balance, and no pathogenic lesions.",
                "irrigation": "Maintain regular scheduled irrigation suited to the crop growth stage. Water deeply 2-3 times per week based on soil moisture checks.",
                "treatment": "No chemical intervention needed. Maintain proactive plant vigor with organic compost and beneficial Trichoderma soil enrichment."
            },
            "gu": {
                "desc": "છોડનું પાન સંપૂર્ણપણે તંદુરસ્ત છે, હરિતદ્રવ્યનું પ્રમાણ ઉત્તમ છે અને કોઈ રોગ કે જીવાતના લક્ષણ નથી.",
                "irrigation": "પાકના વિકાસ અનુસાર નિયમિત પિયત ચાલુ રાખો. જમીનમાં પૂરતો ભેજ ચકાસીને અઠવાડિયે 2-3 વાર ઊંડું પિયત આપવું.",
                "treatment": "કોઈ રાસાયણિક દવાની જરૂર નથી. પાકની તંદુરસ્તી જાળવવા માટે જૈવિક ખાતર અને ટ્રાઇકોડર્માનો ઉપયોગ કરવો."
            },
            "hi": {
                "desc": "पत्ती पूरी तरह स्वस्थ है, क्लोरोफिल का स्तर उत्तम है और किसी भी प्रकार के रोग के लक्षण नहीं हैं।",
                "irrigation": "फसल के विकास के अनुसार नियमित सिंचाई जारी रखें। मिट्टी की नमी के आधार पर सप्ताह में 2-3 बार पर्याप्त पानी दें।",
                "treatment": "किसी रासायनिक दवा की आवश्यकता नहीं है। पौधों की प्राकृतिक मजबूती के लिए जैविक खाद और ट्राइकोडर्मा का उपयोग करें।"
            }
        }
        return healthy_advice.get(language, healthy_advice["en"])

    if disease_key in PRESCRIPTION_DB:
        crop_data = PRESCRIPTION_DB[disease_key]
        return crop_data.get(language, crop_data["en"])

    # Fallback generic structured advice
    label_text = ML_LABELS.get(disease_key, {}).get(language, disease_key)
    fallback = {
        "en": {
            "desc": f"Identified as {label_text}. Fungal or bacterial pathogens detected on foliar tissue.",
            "irrigation": "Maintain drip irrigation and avoid wetting foliage. Allow soil surface to dry slightly between waterings.",
            "treatment": "Apply protective broad-spectrum fungicide (Mancozeb 75% WP @ 2.5g/L or Copper Oxychloride @ 3g/L) and remove infected leaves."
        },
        "gu": {
            "desc": f"ઓળખ: {label_text}. પાન પર ફૂગ કે જીવાણુજન્ય રોગના લક્ષણ જણાય છે.",
            "irrigation": "ટપક પિયત પદ્ધતિ વાપરો અને પાન ભીના ન થાય તેનું ધ્યાન રાખો. જમીનમાં સારો નિકાલ રાખવો.",
            "treatment": "મેન્કોઝેબ 75% WP (2.5 ગ્રામ/લિટર) અથવા કોપર ઓક્સીક્લોરાઇડ (3 ગ્રામ/લિટર) નો છંટકાવ કરવો અને ચેપગ્રસ્ત પાન દૂર કરવા."
        },
        "hi": {
            "desc": f"पहचान: {label_text}। पत्तियों पर कवक या जीवाणु रोग के लक्षण पाए गए हैं।",
            "irrigation": "ड्रिप सिंचाई का उपयोग करें और पत्तियों को सूखा रखें। खेत में जलभराव न होने दें।",
            "treatment": "मैंकोजेब 75% WP (2.5g/L) या कॉपर ऑक्सीक्लोराइड (3g/L) का छिड़काव करें और प्रभावित पत्तों को हटा दें।"
        }
    }
    return fallback.get(language, fallback["en"])

def validate_and_extract_leaf(img_path):
    try:
        img_rgb = Image.open(img_path).convert('RGB')
        rgb = np.array(img_rgb, dtype=np.float32)
        r, g, b = rgb[:,:,0], rgb[:,:,1], rgb[:,:,2]
        
        img_hsv = img_rgb.convert('HSV')
        hsv = np.array(img_hsv)
        h = hsv[:,:,0] # 0-255 in PIL (mapped from 0-360)
        s = hsv[:,:,1] / 255.0
        v = hsv[:,:,2] / 255.0

        # 1. Genuine Chlorophyll Green Foliage:
        # Botanical green: Hue in [20, 115], G dominant over B, and G >= R * 0.82
        green_foliage = (h >= 20) & (h <= 115) & (g > b * 1.08) & (g >= r * 0.82) & (s >= 0.10) & (v >= 0.08)
        green_ratio = float(np.mean(green_foliage))

        # 2. Foliar Lesions (Yellowing chlorosis / necrotic rust / blight spots on leaf tissue):
        foliar_lesions = (h >= 4) & (h <= 28) & (r > b * 1.08) & (g > b * 0.88) & (s >= 0.12) & (v >= 0.08) & (r > 20)

        # 3. Overall Botanical Leaf Surface Area:
        plant_mask = green_foliage | (foliar_lesions & (green_ratio >= 0.02))
        plant_ratio = float(np.mean(plant_mask))

        # Rejection gate: Must contain genuine chlorophyll green foliage (>= 3%)
        if green_ratio < 0.03 or plant_ratio < 0.04:
            return False, None, f"Non-plant image (green: {green_ratio:.2%})"

        # 4. Salient Leaf Bounding Box Localization (crops out blue sky / background)
        rows = np.where(np.any(plant_mask, axis=1))[0]
        cols = np.where(np.any(plant_mask, axis=0))[0]
        if len(rows) > 0 and len(cols) > 0:
            ymin, ymax = max(0, rows[0] - 8), min(img_rgb.height, rows[-1] + 8)
            xmin, xmax = max(0, cols[0] - 8), min(img_rgb.width, cols[-1] + 8)
            if (ymax - ymin) >= 30 and (xmax - xmin) >= 30:
                cropped_leaf = img_rgb.crop((xmin, ymin, xmax, ymax))
                return True, cropped_leaf, "Valid plant leaf"

        return True, img_rgb, "Valid plant leaf"
    except Exception as e:
        print(f"Leaf validation error: {e}")
        return False, None, str(e)

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
        is_plant, cropped_leaf, reason = validate_and_extract_leaf(temp_path)
        if not is_plant or cropped_leaf is None:
            return jsonify({
                "label": UI_STRINGS[lang]["title"],
                "plant": UI_STRINGS[lang]["title"],
                "confidence": 0.0,
                "disease": "unknown",
                "engine": "Keras API",
                "info": {
                    "desc": UI_STRINGS[lang]["desc"],
                    "irrigation": "N/A",
                    "treatment": "N/A"
                },
                "top3": []
            })

        # Dual-Scale Inference (Original image + Salient Leaf Crop for background invariance)
        img_orig = Image.open(temp_path).convert('RGB').resize(IMAGE_SIZE)
        arr_orig = np.expand_dims(np.array(img_orig, dtype=np.float32), axis=0)
        preds_orig = global_model(arr_orig, training=False).numpy()[0]

        img_crop = cropped_leaf.resize(IMAGE_SIZE)
        arr_crop = np.expand_dims(np.array(img_crop, dtype=np.float32), axis=0)
        preds_crop = global_model(arr_crop, training=False).numpy()[0]

        # Prioritize the most focused botanical view
        idx_orig = np.argmax(preds_orig)
        idx_crop = np.argmax(preds_crop)
        if preds_crop[idx_crop] >= preds_orig[idx_orig]:
            predictions = preds_crop
        else:
            predictions = preds_orig
        
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

        # Out-Of-Distribution Safeguards
        top_species = [CLASS_NAMES[idx].split('___')[0] for idx in top_indices]
        unique_species_count = len(set(top_species))

        is_healthy = "healthy" in label.lower()
        min_conf = 80.0 if is_healthy else 50.0

        if confidence < min_conf or (unique_species_count == 3 and confidence < 82.0):
            result['label'] = UI_STRINGS[lang]["title"]
            result['plant'] = UI_STRINGS[lang]["title"]
            result['disease'] = "unknown"
            result['info'] = {
                "desc": UI_STRINGS[lang]["desc"],
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
