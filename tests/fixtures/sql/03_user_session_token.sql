DROP TABLE IF EXISTS users_token;

CREATE TABLE IF NOT EXISTS users_token
(
    id         SERIAL PRIMARY KEY,
    user_id    BIGINT UNSIGNED,
    token      VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

