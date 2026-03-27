CREATE TABLE IF NOT EXISTS ai_scans (
    id SERIAL PRIMARY KEY,
    user_id INTEGER,
    pest_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX IF NOT EXISTS idx_ai_scans_user_id ON ai_scans(user_id);
CREATE INDEX IF NOT EXISTS idx_ai_scans_created_at ON ai_scans(created_at);
