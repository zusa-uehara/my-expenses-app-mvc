<div class="section_title_container">
    <h2>支出編集</h2>
</div>

<?php if (!empty($errors)): ?>
<div style="color:red;">
  <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
</div>
<?php endif; ?>

<?php if (!empty($success_message)): ?>
<div style="color:green;">
  <p><?= htmlspecialchars($success_message) ?></p>
</div>
<?php endif; ?>

<form method="post" action="/edit/update">
  <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">

  <label>日付：<input type="date" name="date" value="<?= htmlspecialchars($row['date']) ?>"></label><br>
  <label>金額：<input type="number" name="cost" min="0" value="<?= htmlspecialchars($row['cost']) ?>"></label><br>
  <label>カテゴリ：
    <select name="category">
      <?php foreach ($valid_categories as $key=>$label): ?>
      <option value="<?= $key ?>" <?= ($row['category']==$key)?'selected':'' ?>><?= $label ?></option>
      <?php endforeach; ?>
    </select>
  </label><br>
  <label>メモ：<input type="text" name="memo" maxlength="200" value="<?= htmlspecialchars($row['memo']) ?>"></label><br>

  <button type="submit" name="action" value="update">更新する</button>
  <button type="submit" name="action" value="delete" onclick="return confirm('削除しますか？');">削除する</button>
</form>


<div class="section_title_container">
    <h2>支出一覧</h2>
</div>

<table cellpadding="8">
<tr>
  <th>ID</th><th>日付</th><th>金額</th><th>カテゴリ</th><th>メモ</th>
</tr>
<?php foreach ($rows as $row): ?>
<tr>
  <td><?= htmlspecialchars($row['id']) ?></td>
  <td><?= htmlspecialchars($row['date']) ?></td>
  <td><?= htmlspecialchars($row['cost']) ?> 円</td>
  <td><?= htmlspecialchars($valid_categories[$row['category']] ?? $row['category']) ?></td>
  <td><?= htmlspecialchars($row['memo']) ?></td>
</tr>
<?php endforeach; ?>
</table>
