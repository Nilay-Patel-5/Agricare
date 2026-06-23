// backend/src/models/MarketPrice.js
const mongoose = require('mongoose');

const marketPriceSchema = new mongoose.Schema({
    state: {
        type: String,
        default: 'Gujarat',
        trim: true
    },
    district: {
        type: String,
        required: true,
        trim: true
    },
    market: {
        type: String,
        required: true,
        trim: true
    },
    commodity: {
        type: String,
        required: true,
        trim: true
    },
    variety: {
        type: String,
        trim: true
    },
    grade: {
        type: String,
        trim: true
    },
    arrival_date: {
        type: String,
        required: true
    },
    min_price: {
        type: Number,
        default: 0
    },
    max_price: {
        type: Number,
        default: 0
    },
    modal_price: {
        type: Number,
        default: 0
    }
}, {
    timestamps: { createdAt: true, updatedAt: false }
});

// Compound unique index to mirror PostgreSQL schema constraint
marketPriceSchema.index({
    state: 1,
    district: 1,
    market: 1,
    commodity: 1,
    variety: 1,
    arrival_date: 1
}, { unique: true });

// Individual search indexes for query performance
marketPriceSchema.index({ district: 1 });
marketPriceSchema.index({ commodity: 1 });

module.exports = mongoose.model('MarketPrice', marketPriceSchema);
