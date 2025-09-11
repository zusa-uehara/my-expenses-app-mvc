<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/style.css">
  <title><?= isset($title)? '今日の支出メモ - ' . htmlspecialchars($title): '' ?></title>
</head>
<body>
  <h1><a href="/">今日の支出メモ</a></h1>
  <div>
    <?= $content ?>
  </div>
  <footer>
    © 2025 azoosa uehara | This is a portfolio project.
  </footer>
</body>
</html>
