<?php
require_once "db_connect.php";

// 月ごとの支出合計（最新6ヶ月）
$sql = "SELECT to_char(date, 'YYYY-MM') AS month, SUM(cost) AS total
        FROM my_expenses
        GROUP BY month
        ORDER BY month DESC
        LIMIT 6";
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// JSON 用に配列作成（降順を昇順に変換）
$months = array_reverse(array_column($results, 'month'));
$totals = array_reverse(array_column($results, 'total'));
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
		<title>今日の支出メモ</title>
	</head>
	<body>

		<h1><a href="/">今日の支出メモ</a></h1>

		<div class=button_container>
			<p class="register_btn"><a href="register.php">登録する</a></p>
			<p class="change_btn"><a href="change.php">変更する</a></p>
		</div>

		<div class="section_title_container">
			<h2>月ごとの支出グラフ</h2>
		</div>

		<canvas id="expensesChart"></canvas>

		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		<script>
		const months = <?= json_encode($months) ?>;
		const totals = <?= json_encode($totals) ?>;

		const ctx = document.getElementById('expensesChart').getContext('2d');
		new Chart(ctx, {
		    type: 'bar',
		    data: {
		        labels: months,
		        datasets: [{
		            label: '支出合計',
		            data: totals,
		            backgroundColor: 'rgba(75, 192, 192, 0.5)'
		        }]
		    },
		    options: {
		        scales: {
		            y: { beginAtZero: true }
		        }
		    }
		});
		</script>

        <footer>
        © 2025 azoosa uehara | This is a portfolio project.
        </footer>
	</body>
</html>
