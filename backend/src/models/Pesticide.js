// backend/src/models/Pesticide.js
const mongoose = require('mongoose');

const pesticideSchema = new mongoose.Schema({
    name: { type: String, required: true, trim: true }, // title_en
    name_gu: { type: String, trim: true },
    name_hi: { type: String, trim: true },
    brand: { type: String, trim: true },
    category: { type: String, trim: true },
    price_range: { type: String, trim: true },
    usage: { type: String }, // usage_en
    usage_gu: { type: String },
    usage_hi: { type: String },
    target_pests: { type: String }, // target_pests_en
    target_pests_gu: { type: String },
    target_pests_hi: { type: String },
    image_url: { type: String },
    // Replaces pest_pesticide_mapping table
    pests: [{
        pest_name: { type: String, required: true, trim: true },
        effectiveness: { type: String, default: 'High' }
    }]
}, {
    timestamps: true
});

// Index for search performance
pesticideSchema.index({ name: 'text', brand: 'text', 'pests.pest_name': 'text' });

module.exports = mongoose.model('Pesticide', pesticideSchema);
