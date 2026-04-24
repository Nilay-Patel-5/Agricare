# Plant Disease Detection System - Project Overview

## Project Summary
An AI-powered plant disease detection system that identifies crop diseases from leaf images and provides treatment recommendations. Uses a deep learning CNN model trained on 38 disease classes across 9 different crops.

---

## How It Works

### 1. User Flow
```
User uploads leaf image
        ↓
Image preprocessing (resize to 128×128, normalize)
        ↓
CNN model analyzes image
        ↓
Model outputs 38 disease probabilities
        ↓
Top 3 predictions extracted
        ↓
Disease info & recommendations retrieved
        ↓
JSON response returned to dashboard
```

### 2. Core Components

**Model Architecture:**
- Input: 128×128 RGB image
- 6 Convolutional blocks (32→64 filters) with pooling
- Flatten layer
- Dense layer (1500 neurons + 40% dropout)
- Output layer (38 classes with softmax)

**Supported Crops & Diseases:**
- Apple (4 classes): Scab, Black Rot, Cedar Rust, Healthy
- Tomato (10 classes): Early Blight, Late Blight, Leaf Mold, Septoria, Spider Mites, Target Spot, Viruses, Healthy
- Potato, Corn, Grape, Peach, Pepper, Squash, Strawberry, etc.

**Disease Information Database:**
Each disease includes:
- Description (symptoms)
- Irrigation recommendations
- Treatment options
- Fertilizer suggestions

---

## Technical Stack

| Component | Technology |
|-----------|-----------|
| **Model** | TensorFlow/Keras CNN |
| **Model Format** | .keras (with .h5 fallback) |
| **Image Processing** | PIL/Pillow, NumPy |
| **Backend** | Python CLI script |
| **Frontend** | PHP Dashboard |
| **Dataset** | New Plant Diseases Dataset (Augmented) |

---

## Key Features

✅ **38 Disease Classes** - Comprehensive disease coverage  
✅ **High Accuracy** - Trained on augmented dataset (2000+ images per disease)  
✅ **Top-3 Predictions** - Shows alternatives with confidence scores  
✅ **Actionable Advice** - Irrigation, treatment, fertilizer recommendations  
✅ **Fallback System** - Works even if model file is corrupted  
✅ **Fast Inference** - Real-time predictions  
✅ **JSON Output** - Easy integration with dashboard  

---

## File Structure

```
ai/
├── plant_disease_model.keras    # Trained model (primary)
├── predict_cli.py               # Prediction script
└── dataset/
    └── New Plant Diseases Dataset(Augmented)/
        └── train/
            ├── Apple___Apple_scab/      (2016 images)
            ├── Apple___Black_rot/       (1987 images)
            ├── Tomato___Early_blight/   (...)
            └── ... (38 disease folders)

dashboard/
├── admin.php                    # Main dashboard
├── admin_subsidies.php          # Subsidies page
└── _sidebar.php                 # Navigation
```

---

## Usage

### Command Line
```bash
# Predict disease from image
python ai/predict_cli.py --image /path/to/leaf.jpg

# Check model availability
python ai/predict_cli.py --health
```

### Output Example
```json
{
  "disease": "Tomato___Early_blight",
  "label": "Tomato Early Blight",
  "plant": "Tomato",
  "confidence": 94.5,
  "info": {
    "desc": "Rapid browning and death of tissue.",
    "irrigation": "Keep leaves dry.",
    "treatment": "Copper fungicides.",
    "fertilizer": "Avoid excess Nitrogen."
  },
  "top3": [
    {"class_name": "Tomato___Early_blight", "label": "Tomato Early Blight", "confidence": 94.5},
    {"class_name": "Tomato___Late_blight", "label": "Tomato Late Blight", "confidence": 3.2},
    {"class_name": "Tomato___healthy", "label": "Tomato Healthy", "confidence": 2.3}
  ]
}
```

---

## Model Details

### Architecture Layers
1. **Conv2D(32) + MaxPool** → Detects basic features (edges, colors)
2. **Conv2D(64) + MaxPool** → Detects textures and patterns
3. **Conv2D(64) + MaxPool** → Detects disease-specific shapes
4. **Conv2D(64) + MaxPool** → Combines features
5. **Conv2D(64) + MaxPool** → Refines predictions
6. **Conv2D(64) + MaxPool** → Final feature extraction
7. **Flatten** → Converts to 1D vector
8. **Dense(1500) + Dropout(0.4)** → Learns complex patterns
9. **Dense(38) + Softmax** → Outputs probabilities for 38 classes

### Why This Works
- **Convolutional layers** extract visual features (spots, discoloration, lesions)
- **Pooling layers** reduce noise and computation
- **Dropout** prevents overfitting
- **Multiple blocks** learn hierarchical features
- **Softmax** provides confidence scores

---

## Data Augmentation

Training data includes variations:
- Original images
- 90° rotations
- 270° rotations
- Horizontal flips
- 30° angle rotations

This increases dataset size and improves model generalization.

---

## Integration with Dashboard

### Current State
- Dashboard files exist (admin.php, admin_subsidies.php, _sidebar.php)
- AI model is ready for integration

### Next Steps
1. Create API endpoint in PHP to call Python script
2. Add image upload form to dashboard
3. Display predictions and recommendations
4. Store prediction history in database

---

## Performance Metrics

- **Input Size**: 128×128 pixels (optimized for speed)
- **Classes**: 38 disease categories
- **Inference Time**: ~1-2 seconds per image
- **Model Size**: ~50-100 MB (depending on format)
- **Accuracy**: Trained on 50,000+ augmented images

---

## Error Handling

✅ Model file not found → Fallback to weight extraction  
✅ Corrupted .keras file → Rebuild from .h5 weights  
✅ Invalid image format → PIL handles conversion  
✅ Missing arguments → Clear error messages  

---

## Future Enhancements

- Real-time camera feed analysis
- Batch image processing
- Model retraining with new data
- Mobile app integration
- Disease severity scoring
- Pest identification
- Crop recommendation system
