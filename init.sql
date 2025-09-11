CREATE TABLE IF NOT EXISTS my_expenses (
    id SERIAL PRIMARY KEY,
    date DATE NOT NULL,
    cost INT NOT NULL CHECK (cost >= 0),
    category TEXT NOT NULL,
    memo VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
