"""
AgriCare - Advanced AI Disease Detection Training
=================================================
Optimized Version: MobileNetV2 + TFLite Quantization + Checkpoint/Resume
Target: High Accuracy + Ultra Fast Output
"""

import os
import json
import numpy as np
import tensorflow as tf
from tensorflow.keras.preprocessing.image import ImageDataGenerator
from tensorflow.keras.applications import MobileNetV2
from tensorflow.keras.models import Model, load_model
from tensorflow.keras.layers import Dense, GlobalAveragePooling2D, Dropout
from tensorflow.keras.optimizers import Adam
import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt

# ============================================================
# 1. CONFIGURATION
# ============================================================
DATASET_TRAIN_PATH  = 'ai/data/train'
DATASET_VALID_PATH  = 'ai/data/valid'
IMAGE_SIZE          = (224, 224) 
BATCH_SIZE          = 32
NUM_CLASSES         = 38
EPOCHS              = 15         
LEARNING_RATE       = 0.0001     
MODEL_SAVE_PATH     = 'ai/plant_disease_model.keras'
CHECKPOINT_PATH     = 'ai/checkpoint_best.keras'
TFLITE_SAVE_PATH    = 'ai/plant_disease_model.tflite'

# ============================================================
# 2. DATA PREPARATION
# ============================================================
print("[1/7] Preparing data generators...")

train_datagen = ImageDataGenerator(
    rescale=1.0 / 255,
    rotation_range=20,
    width_shift_range=0.1,
    height_shift_range=0.1,
    shear_range=0.1,
    zoom_range=0.1,
    horizontal_flip=True,
    fill_mode='nearest'
)
valid_datagen = ImageDataGenerator(rescale=1.0 / 255)

train_generator = train_datagen.flow_from_directory(
    DATASET_TRAIN_PATH, target_size=IMAGE_SIZE, batch_size=BATCH_SIZE, class_mode='categorical', shuffle=True
)
valid_generator = valid_datagen.flow_from_directory(
    DATASET_VALID_PATH, target_size=IMAGE_SIZE, batch_size=BATCH_SIZE, class_mode='categorical', shuffle=False
)

# ============================================================
# 3. BUILD OR RESUME MODEL
# ============================================================
if os.path.exists(CHECKPOINT_PATH):
    print(f"\n[2/7] Resuming training from existing checkpoint: {CHECKPOINT_PATH}")
    model = load_model(CHECKPOINT_PATH)
else:
    print("\n[2/7] Building a new MobileNetV2 architecture...")
    base_model = MobileNetV2(weights='imagenet', include_top=False, input_shape=(224, 224, 3))
    base_model.trainable = False
    x = base_model.output
    x = GlobalAveragePooling2D()(x)
    x = Dense(512, activation='relu')(x)
    x = Dropout(0.5)(x)
    output = Dense(NUM_CLASSES, activation='softmax')(x)
    model = Model(inputs=base_model.input, outputs=output)
    model.compile(optimizer=Adam(learning_rate=LEARNING_RATE), loss='categorical_crossentropy', metrics=['accuracy'])

# ============================================================
# 4. TRAINING WITH CALLBACKS
# ============================================================
print("\n[3/7] Training in progress...")

callbacks = [
    # Save weights every 500 steps (so we don't lose progress mid-epoch)
    tf.keras.callbacks.ModelCheckpoint(
        filepath=CHECKPOINT_PATH,
        monitor='accuracy',
        save_best_only=False,
        save_weights_only=False,
        save_freq=500,
        verbose=1
    ),
    # Also save the BEST weights per epoch
    tf.keras.callbacks.ModelCheckpoint(
        filepath='ai/best_model_accuracy.keras',
        monitor='val_accuracy',
        save_best_only=True,
        verbose=1
    ),
    # Stop early if accuracy stops improving
    tf.keras.callbacks.EarlyStopping(
        monitor='val_accuracy', 
        patience=4, 
        restore_best_weights=True,
        verbose=1
    ),
    # Reduce heating by reducing learning rate if stuck
    tf.keras.callbacks.ReduceLROnPlateau(
        monitor='val_loss', 
        factor=0.2, 
        patience=2, 
        min_lr=1e-7
    )
]

history = model.fit(
    train_generator,
    steps_per_epoch=train_generator.samples // BATCH_SIZE,
    validation_data=valid_generator,
    validation_steps=valid_generator.samples // BATCH_SIZE,
    epochs=EPOCHS,
    callbacks=callbacks,
    verbose=1
)

# ============================================================
# 5. SAVE FINAL MODELS
# ============================================================
print("\n[4/7] Saving final model formats...")
model.save(MODEL_SAVE_PATH)
print(f"  Saved Keras model → {MODEL_SAVE_PATH}")

# ============================================================
# 6. TFLITE CONVERSION
# ============================================================
print("\n[5/7] Converting to Ultra-Fast TFLite Format...")
try:
    converter = tf.lite.TFLiteConverter.from_keras_model(model)
    converter.optimizations = [tf.lite.Optimize.DEFAULT]
    tflite_model = converter.convert()
    with open(TFLITE_SAVE_PATH, 'wb') as f:
        f.write(tflite_model)
    print(f"  Saved TFLite model → {TFLITE_SAVE_PATH}")
except Exception as e:
    print(f"  TFLITE ERROR: {str(e)}")

# ============================================================
# 7. EVALUATE & PLOT
# ============================================================
print("\n[6/7] Evaluating...")
val_loss, val_acc = model.evaluate(valid_generator)
print(f"  Final Validation Accuracy: {val_acc * 100:.2f}%")

print("\n[7/7] Generating training results plot...")
plt.figure(figsize=(12, 5))
plt.subplot(1, 2, 1)
plt.plot(history.history['accuracy'], label='Train Acc')
plt.plot(history.history['val_accuracy'], label='Val Acc')
plt.title('Accuracy')
plt.legend()
plt.subplot(1, 2, 2)
plt.plot(history.history['loss'], label='Train Loss')
plt.plot(history.history['val_loss'], label='Val Loss')
plt.title('Loss')
plt.legend()
plt.tight_layout()
plt.savefig('ai/ai_training_results.png')
print("  Results saved → ai/ai_training_results.png")

print("\nSUCCESS: Training complete. System ready for inference.")
