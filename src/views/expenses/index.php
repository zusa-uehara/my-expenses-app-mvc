<div class="section_title_container">
    <h2>支出登録</h2>
</div>

<?php if (!empty($errors)): ?>
<div style="color:red;">
  <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
</div>
<?php endif; ?>

<form method="post" action="/expenses/create">
  <label>日付：<input type="date" name="date" required></label><br>
  <label>金額：<input type="number" name="cost" min="0" required></label><br>
  <label>カテゴリ：
    <select name="category">
      <?php foreach (['rent'=>'家賃','utilities'=>'光熱費','living'=>'生活費・雑費','entertainment'=>'交際費','medical'=>'医療費'] as $key=>$label): ?>
      <option value="<?= $key ?>"><?= $label ?></option>
      <?php endforeach; ?>
    </select>
  </label><br>
  <label>メモ：<input type="text" name="memo" maxlength="200"></label><br>
  <button type="submit">登録する</button>
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
  <td><?= htmlspecialchars($row['category']) ?></td>
  <td><?= htmlspecialchars($row['memo']) ?></td>
</tr>
<?php endforeach; ?>
</table>
