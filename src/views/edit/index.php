<h2>支出編集</h2>

<?php if (!empty($errors)): ?>
<div style="color:red;">
  <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
</div>
<?php endif; ?>


<form method="post">
  <label>日付：<input type="date" name="date" value="<?= htmlspecialchars($row['date']) ?>" required></label><br>
  <label>金額：<input type="number" name="cost" min="0" value="<?= htmlspecialchars($row['cost']) ?>" required></label><br>
  <label>カテゴリ：
    <select name="category">
      <?php foreach (['rent'=>'家賃','utilities'=>'光熱費','living'=>'生活費・雑費','entertainment'=>'交際費','medical'=>'医療費'] as $key=>$label): ?>
      <option value="<?= $key ?>" <?= $row['category']==$key?'selected':'' ?>><?= $label ?></option>
      <?php endforeach; ?>
    </select>
  </label><br>
  <label>メモ：<input type="text" name="memo" maxlength="200" value="<?= htmlspecialchars($row['memo']) ?>"></label><br>

  <button type="submit" name="action" value="update">更新する</button>
  <button type="submit" name="action" value="delete" onclick="return confirm('削除しますか？');">削除する</button>
</form>
