// backend/src/models/Farmer.js
const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');

const farmerSchema = new mongoose.Schema({
    name: {
        type: String,
        required: true,
        trim: true
    },
    email: {
        type: String,
        trim: true,
        lowercase: true,
        sparse: true // Allows multiple null/empty email values since it's optional
    },
    phone: {
        type: String,
        required: true,
        unique: true,
        trim: true
    },
    district: {
        type: String,
        required: true,
        trim: true
    },
    city: {
        type: String,
        required: true,
        trim: true
    },
    pincode: {
        type: String,
        required: true,
        trim: true
    },
    pin: {
        type: String,
        required: true
    },
    pref_lang: {
        type: String,
        enum: ['en', 'gu', 'hi'],
        default: 'en'
    }
}, {
    timestamps: true
});

// Pre-save hook to hash 6-digit PIN if modified
farmerSchema.pre('save', async function () {
    if (!this.isModified('pin')) return;
    const salt = await bcrypt.genSalt(10);
    this.pin = await bcrypt.hash(this.pin, salt);
});

// Method to verify 6-digit PIN
farmerSchema.methods.comparePin = async function (enteredPin) {
    return await bcrypt.compare(enteredPin, this.pin);
};

module.exports = mongoose.model('Farmer', farmerSchema);
