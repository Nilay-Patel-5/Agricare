// backend/src/config/db.js
const mongoose = require('mongoose');
const config = require('./env');

const connectDB = async () => {
    try {
        const conn = await mongoose.connect(config.mongoUri, {
            // MongoDB driver options (Mongoose handles pooling by default)
            maxPoolSize: 10,
            serverSelectionTimeoutMS: 5000,
            socketTimeoutMS: 45000,
        });
        console.log(`MongoDB Connected: ${conn.connection.host}`);
        return conn.connection.db;
    } catch (err) {
        console.error(`Database Connection Error: ${err.message}`);
        process.exit(1);
    }
};

module.exports = connectDB;
