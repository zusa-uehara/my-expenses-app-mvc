<?php
class DatabaseManager {

    protected $pdo;
    protected $models = [];

    /**
     * 接続
     * $params = [
     *    'hostname' => 'localhost',
     *    'port'     => 5432,
     *    'username' => 'postgres',
     *    'password' => 'password',
     *    'database' => 'mydb'
     * ];
     */
    public function connect(array $params) {
        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            $params['hostname'],
            $params['port'] ?? 5432,
            $params['database']
        );

        try {
            $this->pdo = new PDO($dsn, $params['username'], $params['password']);
            // エラー時に例外を投げる設定
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new RuntimeException('PostgreSQL接続エラー：' . $e->getMessage());
        }
    }

    /**
     * モデル取得
     */
    public function get($modelName) {
        if (!isset($this->models[$modelName])) {
            $model = new $modelName($this->pdo);
            $this->models[$modelName] = $model;
        }
        return $this->models[$modelName];
    }

    public function __destruct() {
        // PDOは明示的に閉じる必要はない
        $this->pdo = null;
    }
}
