<?php
    $title = 'グラフ表示';
    include __DIR__ . '/layouts/header.php';
?>

<h1>折れ線グラフ</h1>
<canvas id="myLineChart" width="600" height="400"></canvas>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>

<script>
    // グラフデータを取得して描画
    fetch('/../controllers/GraphController.php')
        .then(response => response.json())
        .then(json => {
            const ctx = document.getElementById('myLineChart').getContext('2d');
            new Chart(ctx, {
                type: 'line', // ← 折れ線グラフ
                data: {
                    labels: json.labels,
                    datasets: [{
                        label: '収入（円）',
                        data: json.data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        })
        .catch(error => {
            console.error('エラー:', error);
        });
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>