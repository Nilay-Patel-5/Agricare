// backend/src/models/AIScan.js
const mongoose = require('mongoose');

const aiScanSchema = new mongoose.Schema({
    user_id: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Farmer',
        required: true,
        index: true
    },
    pest_name: {
        type: String,
        required: true,
        trim: true
    }
}, {
    timestamps: { createdAt: true, updatedAt: false }
});

module.exports = mongoose.model('AIScan', aiScanSchema);
