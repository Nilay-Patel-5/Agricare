// backend/src/models/Shop.js
const mongoose = require('mongoose');

const shopSchema = new mongoose.Schema({
    name: { type: String, required: true, trim: true },
    district: { type: String, required: true, trim: true },
    city: { type: String, trim: true },
    address: { type: String, trim: true },
    phone: { type: String, trim: true },
    latitude: { type: Number },
    longitude: { type: Number },
    verified: { type: Boolean, default: false }
}, {
    timestamps: true
});

shopSchema.index({ district: 1 });

module.exports = mongoose.model('Shop', shopSchema);
