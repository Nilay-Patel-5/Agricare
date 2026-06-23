// backend/src/models/Subsidy.js
const mongoose = require('mongoose');

const subsidySchema = new mongoose.Schema({
    name: { type: String, required: true, trim: true },
    name_gu: { type: String, trim: true },
    name_hi: { type: String, trim: true },
    category: { type: String, required: true, trim: true },
    category_gu: { type: String, trim: true },
    category_hi: { type: String, trim: true },
    description: { type: String, required: true },
    description_gu: { type: String },
    description_hi: { type: String },
    benefits: { type: String },
    benefits_gu: { type: String },
    benefits_hi: { type: String },
    eligibility: { type: String },
    eligibility_gu: { type: String },
    eligibility_hi: { type: String },
    apply_link: { type: String },
    status: { type: String, default: 'Live' },
    last_updated: { type: Date, default: Date.now }
}, {
    timestamps: true
});

module.exports = mongoose.model('Subsidy', subsidySchema);
