// backend/src/models/Crop.js
const mongoose = require('mongoose');

const scheduleSchema = new mongoose.Schema({
    month_index: { type: Number, required: true }, // 0-11 for Jan-Dec
    activity_type: { type: String, trim: true },   // Planting, Irrigation, Harvesting
    activity_icon: { type: String, trim: true },   // FontAwesome icon class name
    activity_color: { type: String, trim: true },  // styling tailwind colors (e.g. orange, blue)
    task_en: { type: String, required: true },
    task_gu: { type: String },
    task_hi: { type: String }
}, {
    _id: false // Disable _id subdocument fields to keep documents small
});

const cropSchema = new mongoose.Schema({
    name: { type: String, required: true, unique: true, trim: true }, // name_en
    name_gu: { type: String, trim: true },
    name_hi: { type: String, trim: true },
    icon: { type: String, trim: true },
    season: { type: String, trim: true }, // season_en
    season_gu: { type: String, trim: true },
    season_hi: { type: String, trim: true },
    category: { type: String, trim: true }, // Cereal, Vegetable, Fruit
    image_url: { type: String },
    districts: { type: [String], default: [] }, // Suitable districts/cities
    schedule: [scheduleSchema]
}, {
    timestamps: true
});

module.exports = mongoose.model('Crop', cropSchema);
