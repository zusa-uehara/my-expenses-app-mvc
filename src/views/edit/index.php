<div class="section_title_container">
    <h2>支出編集　ID選択</h2>
</div>

<form action="/edit/edit" method="post">
    <label for="edit_id">IDを入力してください：
        <input type="number" name="id" id="edit_id" min="1" required>
    </label>
    <button type="submit">編集する</button>
</form>

<div class="section_title_container">
    <h2>支出一覧</h2>
</div>

<table border="1" cellpadding="8">
<tr>
  <th>ID</th><th>日付</th><th>金額</th><th>カテゴリ</th><th>メモ</th>
</tr>
<?php foreach ($rows as $row): ?>
<tr>
  <td><?= htmlspecialchars($row['id']) ?></td>
  <td><?= htmlspecialchars($row['date']) ?></td>
  <td><?= htmlspecialchars($row['cost']) ?> 円</td>
  <td><?= htmlspecialchars($category_labels[$row['category']] ?? $row['category']) ?></td>
  <td><?= htmlspecialchars($row['memo']) ?></td>
</tr>
<?php endforeach; ?>
</table>
