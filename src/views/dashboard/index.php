<div class="button_container">
    <p class="register_btn">
      <a href="/expenses">登録する</a>
    </p>
    <p class="change_btn">
      <a href="/edit">変更する</a>
    </p>
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
