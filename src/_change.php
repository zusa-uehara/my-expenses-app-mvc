<?php

require_once "db_connect.php";

// -------------------------
// 変数の初期化
// -------------------------
$errors = [];
$success_message = '';
$expense = null;
$fetch_id_input = '';

// -------------------------
// フォーム処理（POST）
// -------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // データ取得処理
    if ($action === 'fetch') {
        $fetch_id = $_POST['fetch_id'] ?? null;
        if ($fetch_id) {
            $fetch_id_input = $fetch_id; // 入力内容を保持
            try {
                $stmt = $pdo->prepare("SELECT * FROM my_expenses WHERE id = ?");
                $stmt->execute([$fetch_id]);
                $expense = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$expense) {
                    $errors[] = "指定されたIDの支出は見つかりませんでした。";
                }
            } catch (PDOException $e) {
                $errors[] = "データベースエラー: " . $e->getMessage();
            }
        } else {
            $errors[] = "IDを入力してください。";
        }
    }
    // 更新・削除処理
    else {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $errors[] = "IDが指定されていません。";
        } else {
            try {
                if ($action === 'update') {
                    // 更新処理
                    $date = $_POST['date'] ?? '';
                    $cost = $_POST['cost'] ?? '';
                    $category = $_POST['category'] ?? '';
                    $memo = $_POST['memo'] ?? '';

                    // バリデーション
                    if (!$date) {
                        $errors[] = "日付を入力してください。";
                    }
                    if (!is_numeric($cost) || $cost < 0) {
                        $errors[] = "金額は0以上の数字で入力してください。";
                    }
                    if (mb_strlen($memo) > 200) {
                        $errors[] = "メモは200文字以内で入力してください。";
                    }

                    if (empty($errors)) {
                        $sql = "UPDATE my_expenses SET date = ?, cost = ?, category = ?, memo = ? WHERE id = ?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$date, $cost, $category, $memo, $id]);
                        $success_message = "支出を更新しました。";
                    }

                } elseif ($action === 'delete') {
                    // 削除処理
                    $sql = "DELETE FROM my_expenses WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$id]);
                    $success_message = "支出を削除しました。";
                }
            } catch (PDOException $e) {
                $errors[] = "データベースエラー: " . $e->getMessage();
            }
        }

}
}
// -------------------------
// 支出一覧取得
// -------------------------
$stmt = $pdo->query("SELECT * FROM my_expenses ORDER BY date DESC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
// -------------------------
// IDがGETで渡された場合の処理
// -------------------------
if (isset($_GET['id'])) {
    $fetch_id_input = $_GET['id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM my_expenses WHERE id = ?");
        $stmt->execute([$fetch_id_input]);
        $expense = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$expense) {
            $errors[] = "指定されたIDの支出は見つかりませんでした。";
            $fetch_id_input = '';
        }
    } catch (PDOException $e) {
        $errors[] = "データベースエラー: " . $e->getMessage();
        $fetch_id_input = '';
    }
}

// -------------------------
// カテゴリの対応表
// -------------------------
$category_labels = [
    'rent' => '家賃',
    'utilities' => '光熱費',
    'living' => '生活費・雑費',
    'entertainment' => '交際費',
    'medical' => '医療費'
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支出の編集・削除</title>
</head>
<body>
    <h1><a href="/">今日の支出メモ</a></h1>

    <!-- 成功メッセージ表示 -->
    <?php if (!empty($success_message)): ?>
        <div class="message success-message">
            <p><?= htmlspecialchars($success_message) ?></p>
        </div>
    <?php endif; ?>

    <!-- エラーメッセージ表示 -->
    <?php if (!empty($errors)): ?>
        <div class="message error-message">
            <?php foreach ($errors as $err): ?>
                <p><?= htmlspecialchars($err) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="section_title_container">
        <h2>支出を編集・削除</h2>
    </div>

    <!-- ID入力・編集・削除フォーム -->
    <form action="change.php" method="post">
        <label for="fetch_id">IDを入力してください：
            <input type="number" id="fetch_id" name="fetch_id" value="<?= htmlspecialchars($fetch_id_input) ?>">
        </label>
        <br>
        <button type="submit" name="action" value="fetch">データ取得</button>

        <?php if ($expense): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($expense['id']) ?>">
            <br><br>
            <label for="date">日付：
                <input type="date" id="date" name="date" value="<?= htmlspecialchars($expense['date']) ?>">
            </label>
            <br>
            <label for="cost">金額：
                <input type="number" id="cost" name="cost" min="0" value="<?= htmlspecialchars($expense['cost']) ?>">
            </label>
            <br>
            <label for="category">カテゴリ：
                <select id="category" name="category">
                    <?php foreach ($category_labels as $key => $label): ?>
                        <option value="<?= htmlspecialchars($key) ?>" <?= ($expense['category'] === $key) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <br>
            <label for="memo">メモ：
                <input type="text" id="memo" name="memo" value="<?= htmlspecialchars($expense['memo']) ?>">
            </label>
            <br>
            <button type="submit" name="action" value="update">更新する</button>
            <button type="submit" name="action" value="delete" onclick="return confirm('本当に削除しますか？');">削除する</button>
        <?php endif; ?>
    </form>
	<div class="section_title_container">
        <h2>支出の一覧</h2>
    </div>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>日付</th>
            <th>金額</th>
            <th>カテゴリ</th>
            <th>メモ</th>
        </tr>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row["id"]) ?></td>
                <td><?= htmlspecialchars($row["date"]) ?></td>
                <td><?= htmlspecialchars($row["cost"]) ?> 円</td>
                <td>
                    <?= htmlspecialchars($category_labels[$row["category"]] ?? $row["category"]) ?>
                </td>
                <td><?= htmlspecialchars($row["memo"]) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <footer>
        © 2025 azoosa uehara | This is a portfolio project.
    </footer>
</body>
</html>
