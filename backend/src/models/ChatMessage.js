// backend/src/models/ChatMessage.js
const mongoose = require('mongoose');

const chatMessageSchema = new mongoose.Schema({
    user_id: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Farmer',
        index: true
    },
    session_key: {
        type: String,
        required: true,
        index: true,
        trim: true
    },
    role: {
        type: String,
        required: true,
        enum: ['user', 'assistant']
    },
    message: {
        type: String,
        required: true
    },
    model: {
        type: String,
        trim: true
    }
}, {
    timestamps: { createdAt: true, updatedAt: false }
});

chatMessageSchema.index({ session_key: 1, createdAt: 1 });

module.exports = mongoose.model('ChatMessage', chatMessageSchema);
