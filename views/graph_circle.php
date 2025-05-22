<?php
    $title = 'カテゴリー別グラフ';
    include __DIR__ . '/layouts/header.php';
?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<h1>円グラフ</h1>
<canvas id = categoryPieChart></canvas>

<script>
    fetch('/categoriesGraph')
        .then(response => response.json())
        .then(data => {
            const labels = [];
            const totals = [];

            for (const category in data) {
                let sum = 0;
                for (const year in data[category]) {
                        const monthlyData = data[category][year].data;
                        monthlyData.forEach(value => {
                            if (value !== null) sum += value;
                        });
                    }
                labels.push(category);
                totals.push(sum);
            }

            const ctx = document.getElementById('categoryPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'カテゴリ別合計',
                        data: totals,
                        backgroundColor: [
                            '#FF0000', '#0000FF', '#00FF00', '#4BC0C0',
                            '#9966FF', '#FF9F40', '#C9CBCF', '#A1DE93'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'カテゴリ別 合算支出円グラフ'
                        }
                    }
                }
            });
        });
</script>

<?php 
    include __DIR__ . '/layouts/footer.php';
?>
