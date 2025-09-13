<?php
class DatabaseManager {

    protected $pdo;
    protected $models = [];

    public function connect(array $params) {
        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            $params['hostname'],
            $params['port'] ?? 5432,
            $params['database']
        );

        try {
            $this->pdo = new PDO($dsn, $params['username'], $params['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new RuntimeException('PostgreSQL接続エラー：' . $e->getMessage());
        }
    }

    public function get($modelName) {
        if (!isset($this->models[$modelName])) {
            $model = new $modelName($this->pdo);
            $this->models[$modelName] = $model;
        }
        return $this->models[$modelName];
    }

    /**
     * データベース初期化（テーブルがなければ作成）
     */
    public function initDatabase() {
        $sql = "
        CREATE TABLE IF NOT EXISTS my_expenses (
            id SERIAL PRIMARY KEY,
            date DATE NOT NULL,
            cost INT NOT NULL CHECK (cost >= 0),
            category TEXT NOT NULL,
            memo VARCHAR(200),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        ";

        try {
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            throw new RuntimeException("データベース初期化エラー: " . $e->getMessage());
        }
    }

    public function __destruct() {
        $this->pdo = null;
    }
}
