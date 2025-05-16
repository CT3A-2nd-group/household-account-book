<?php
$title = '収入グラフ';
include __DIR__ . '/layouts/header.php'; // 必要に応じて変更
?>

<h1>収入の折れ線グラフ</h1>

<canvas id="myLineChart" width="1000" height="400"></canvas>

<!-- Chart.js 読み込み -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
fetch('/graph')
  .then(response => response.json())
  .then(json => {
    const ctx = document.getElementById('myLineChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: json.labels,
        datasets: [{
          label: '収入額',
          data: json.data,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 2,
          fill: true,
          tension: 0.1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            min:0,
            max:200000,
            ticks:{
                stepSize: 20000,
                callback: value => value + '円'
            },
            title: {
              display: true,
              text: '金額（円）'
            }
          },
          x: {
            title: {
              display: true,
              text: '日付'
            }
          }
        }
      }
    });
  })
  .catch(error => {
    console.error('データの取得に失敗しました:', error);
  });
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
