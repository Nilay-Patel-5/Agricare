"""
Plant Disease Detection - CNN Model Training Script
====================================================
Based on: SPOTLESS TECH YouTube Playlist
URL: https://youtube.com/playlist?list=PLvz5lCwTgdXDNcXEVwwHsb9DwjNXZGsoy

DATASET: New Plant Diseases Dataset
Source:  https://www.kaggle.com/datasets/vipoooool/new-plant-diseases-dataset
Author:  vipoooool on Kaggle
Classes: 38 (healthy & diseased categories across 14 crop species)

SETUP INSTRUCTIONS:
1. pip install tensorflow==2.15.0 scikit-learn numpy matplotlib seaborn pandas pillow
2. Download dataset from Kaggle (see URL above)
3. Extract into: ai/dataset/  (folder structure: ai/dataset/train/, ai/dataset/valid/)
4. Run: python train_model.py
5. Model saved as: ai/plant_disease_model.keras
"""

import os
import numpy as np
import tensorflow as tf
from tensorflow.keras.preprocessing.image import ImageDataGenerator
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import (
    Conv2D, MaxPool2D, Flatten, Dense, Dropout, BatchNormalization
)
from sklearn.metrics import classification_report, confusion_matrix
import matplotlib
matplotlib.use('Agg')  # Non-interactive backend for saving plots
import matplotlib.pyplot as plt
import seaborn as sns

# ============================================================
# 1. CONFIGURATION  (matches the YouTube tutorial exactly)
# ============================================================
DATASET_TRAIN_PATH  = 'dataset/train'   # training images directory
DATASET_VALID_PATH  = 'dataset/valid'   # validation images directory
IMAGE_SIZE          = (128, 128)         # 128x128 as used in the video
BATCH_SIZE          = 32
NUM_CLASSES         = 38                 # 38 disease/healthy categories
EPOCHS              = 10
MODEL_SAVE_PATH     = 'plant_disease_model.keras'

# ============================================================
# 2. CLASS LABELS  (38 classes – PlantVillage / New Plant Diseases Dataset)
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

# ============================================================
# 3. DATA PREPROCESSING  (rescale only – no augmentation on valid)
# ============================================================
print("[1/5] Preparing data generators...")

train_datagen = ImageDataGenerator(rescale=1.0 / 255)
valid_datagen = ImageDataGenerator(rescale=1.0 / 255)

train_generator = train_datagen.flow_from_directory(
    DATASET_TRAIN_PATH,
    target_size=IMAGE_SIZE,
    batch_size=BATCH_SIZE,
    class_mode='categorical',
    shuffle=True
)

valid_generator = valid_datagen.flow_from_directory(
    DATASET_VALID_PATH,
    target_size=IMAGE_SIZE,
    batch_size=BATCH_SIZE,
    class_mode='categorical',
    shuffle=False
)

print(f"  Training samples  : {train_generator.samples}")
print(f"  Validation samples: {valid_generator.samples}")
print(f"  Classes found     : {len(train_generator.class_indices)}")

# ============================================================
# 4. MODEL ARCHITECTURE  (Custom CNN – exactly as shown in the video)
#    Input  → 128x128x3
#    Hidden → Conv2D → MaxPool → Conv2D → MaxPool → Conv2D → MaxPool
#    Dense  → 1500 neurons (ReLU) → Dropout(0.4)
#    Output → 38 neurons (Softmax)
# ============================================================
print("\n[2/5] Building CNN model architecture...")

model = Sequential([
    # Block 1
    Conv2D(32, (3, 3), padding='same', activation='relu', input_shape=(128, 128, 3)),
    MaxPool2D(2, 2),

    # Block 2
    Conv2D(64, (3, 3), padding='same', activation='relu'),
    MaxPool2D(2, 2),

    # Block 3
    Conv2D(64, (3, 3), padding='same', activation='relu'),
    MaxPool2D(2, 2),

    # Block 4
    Conv2D(64, (3, 3), padding='same', activation='relu'),
    MaxPool2D(2, 2),

    # Block 5
    Conv2D(64, (3, 3), padding='same', activation='relu'),
    MaxPool2D(2, 2),

    # Block 6
    Conv2D(64, (3, 3), padding='same', activation='relu'),
    MaxPool2D(2, 2),

    # Fully-connected head (1500 neurons as in the tutorial)
    Flatten(),
    Dense(1500, activation='relu'),
    Dropout(0.4),

    # Output layer – 38 disease classes
    Dense(NUM_CLASSES, activation='softmax')
])

model.summary()

# ============================================================
# 5. COMPILE
# ============================================================
model.compile(
    optimizer='adam',
    loss='categorical_crossentropy',
    metrics=['accuracy']
)

# ============================================================
# 6. TRAIN
# ============================================================
print("\n[3/5] Training the model...")

history = model.fit(
    train_generator,
    steps_per_epoch=train_generator.samples // BATCH_SIZE,
    validation_data=valid_generator,
    validation_steps=valid_generator.samples // BATCH_SIZE,
    epochs=EPOCHS,
    verbose=1
)

# ============================================================
# 7. EVALUATE & SAVE
# ============================================================
print("\n[4/5] Evaluating model...")

val_loss, val_acc = model.evaluate(valid_generator)
print(f"  Validation Accuracy : {val_acc * 100:.2f}%")
print(f"  Validation Loss     : {val_loss:.4f}")

# Save model
model.save(MODEL_SAVE_PATH)
print(f"\n[5/5] Model saved → {MODEL_SAVE_PATH}")

# ============================================================
# 8. GENERATE TRAINING PLOTS
# ============================================================
fig, axes = plt.subplots(1, 2, figsize=(14, 5))
fig.suptitle('Plant Disease CNN – Training Results', fontsize=14, fontweight='bold')

# Accuracy
axes[0].plot(history.history['accuracy'],     label='Train Accuracy',      color='#22c55e', linewidth=2)
axes[0].plot(history.history['val_accuracy'], label='Validation Accuracy',  color='#f97316', linewidth=2)
axes[0].set_title('Model Accuracy')
axes[0].set_xlabel('Epoch')
axes[0].set_ylabel('Accuracy')
axes[0].legend()
axes[0].grid(True, alpha=0.3)

# Loss
axes[1].plot(history.history['loss'],     label='Train Loss',      color='#22c55e', linewidth=2)
axes[1].plot(history.history['val_loss'], label='Validation Loss',  color='#f97316', linewidth=2)
axes[1].set_title('Model Loss')
axes[1].set_xlabel('Epoch')
axes[1].set_ylabel('Loss')
axes[1].legend()
axes[1].grid(True, alpha=0.3)

plt.tight_layout()
plt.savefig('training_plot.png', dpi=150)
print("Training plot saved → training_plot.png")

print("\nDone! The trained model can now be used by the local CLI predictor in ai/predict_cli.py.")
