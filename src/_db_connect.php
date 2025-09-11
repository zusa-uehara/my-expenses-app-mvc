<?php
// -------------------------
// DB接続
// -------------------------
$host = $_ENV['DB_HOST'];
$port = "5432";       // PostgreSQL デフォルトポート
$dbname = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASSWORD'];

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // テーブル作成（初回のみ）
    $sql = "CREATE TABLE IF NOT EXISTS my_expenses (
        id SERIAL PRIMARY KEY,
        date DATE NOT NULL,
        cost INT NOT NULL,
        category TEXT NOT NULL,
        memo VARCHAR(200),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";
    $pdo->exec($sql);

} catch (PDOException $e) {
    die("DB接続失敗: " . $e->getMessage());
}
