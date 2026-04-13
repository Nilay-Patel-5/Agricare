import tensorflow as tf
import os
import sys

# Monkeypatch InputLayer to handle 'batch_shape' from Keras 3 models
from tensorflow.keras.layers import InputLayer
original_init = InputLayer.__init__

def patched_init(self, *args, **kwargs):
    if 'batch_shape' in kwargs and 'input_shape' not in kwargs:
        # Keras 3 uses batch_shape, Keras 2 uses input_shape
        batch_shape = kwargs.pop('batch_shape')
        if batch_shape and len(batch_shape) > 1:
            kwargs['input_shape'] = batch_shape[1:]
    original_init(self, *args, **kwargs)

InputLayer.__init__ = patched_init

model_path = r'd:\SGP\Agricare\ai\plant_disease_model.keras'
try:
    print("Attempting to load model...")
    model = tf.keras.models.load_model(model_path)
    print("Model loaded successfully!")
except Exception as e:
    print(f"Failed to load model: {e}")
    import traceback
    traceback.print_exc()
