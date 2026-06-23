# AgriCare 🌾
Smart Agriculture Platform for Real-Time Market Insights, Crop Management, and AI-Powered Guidance.

## Project Description
AgriCare is an advanced, full-stack web platform designed to assist farmers by providing real-time mandi prices, weather updates, and actionable agricultural insights. Recently overhauled into a modern Node.js/Express ecosystem, the platform integrates government APIs and leverages AI for crop disease detection. Its responsive, multilingual, and intuitive glassmorphism UI ensures critical information is highly accessible to farmers across any device.

## Problem Statement
Farmers often lack access to real-time market prices, reliable agricultural data, and immediate crop health diagnostics, leading to poor decision-making and financial loss.

## Solution
AgriCare provides a centralized, robust platform that delivers:
- **Live Mandi Prices** through real-time API integrations and cached aggregations.
- **Multilingual Support** ensuring seamless localization (English, Gujarati, Hindi).
- **AI-Powered Diagnostics** for crop disease detection directly within the platform.
- **Responsive & Accessible UI** custom-built to work flawlessly on both low-end mobile devices and large desktop monitors.

## Key Features
- 📈 **Live Mandi Price Tracking** (State, District, Commodity filtering)
- 🤖 **AI Crop Disease Detection**
- 💬 **Interactive Farmer Chatbot**
- 🌦️ **Real-Time Weather Updates**
- 🌍 **Multi-language Support** (Local translations dynamically loaded)
- 📅 **Crop Calendar** for seasonal planning
- 🧑‍🌾 **Admin & Farmer Dashboards** with analytics and role-based management
- 📱 **Highly Responsive UI** with glassmorphism aesthetics and custom scroll-lock behaviors

## Tech Stack (Fully Modernized)
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla), Tailwind CSS, EJS Templates
- **Backend**: Node.js, Express.js
- **Database**: MongoDB (via Mongoose ODM)
- **Security & Auth**: JWT (JSON Web Tokens), bcryptjs, express-session
- **APIs & Data**: Agmarknet API, OpenWeather API, Axios for external fetching
- **AI/ML**: Keras/H5 Models (for disease detection)

## System Architecture
`User` → `Tailwind & EJS Frontend` → `Express.js Routes` → `MongoDB Aggregations / External APIs` → `Response` → `Dynamic UI`

## Database Design (Collections)
- `users` (Farmers, Admins)
- `markets`
- `districts`
- `commodities`
- `market_prices` (with historical aggregation pipelines)

## Installation & Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/Nilay-Patel-5/Agricare.git
   cd Agricare
   ```

2. **Install Dependencies**
   ```bash
   npm install
   ```

3. **Configure Environment Variables**
   Create a `.env` file in the root directory and configure:
   ```env
   PORT=3000
   MONGODB_URI=your_mongodb_connection_string
   JWT_SECRET=your_jwt_secret
   WEATHER_API_KEY=your_openweather_api_key
   ```

4. **Build Tailwind CSS (Optional/Dev)**
   ```bash
   npm run watch:css
   ```

5. **Start the Development Server**
   ```bash
   npm run dev
   ```
   *The application will now be running on `http://localhost:3000`*

## API Integration
- **Agmarknet API**: Fetches real-time crop prices in JSON format, cached locally to prevent rate-limiting and improve speed.
- **OpenWeather API**: Dynamically updates localized weather conditions based on the user's selected district.

## Challenges Overcome
- **Architecture Migration**: Successfully migrated from legacy PHP/PostgreSQL to a modern, scalable Node.js/MongoDB architecture.
- **Responsive Layout Control**: Mastered viewport overflow handling and custom scrollbars to ensure complex forms (like the Register and Dashboard pages) fit perfectly on small laptops and mobile devices without visual clipping.
- **Complex Aggregations**: Built efficient MongoDB aggregation pipelines (`test_agg.js`) to handle thousands of market price data points instantly.

## Future Scope
- 📱 **Dedicated Native Mobile App** (React Native / Flutter)
- 🔐 **OTP-Based Login System** for rural accessibility
- 🚜 **Predictive Yield Analysis** using advanced Machine Learning

## Team
- **Nilay Patel** – Full Stack Developer
- **Garv** – Backend Developer
- **Mihir** – Frontend Developer

## License
Academic Project (SGP CEUP201)