<?php
    $title = '収入グラフ';
    include __DIR__ . '/layouts/header.php'; // 必要に応じて変更
?>

<h1>収入の折れ線グラフ</h1>
<canvas id="myLineChart" width="1000" height="400"></canvas>

<h1>支出の折れ線グラフ</h1>
<canvas id="myChart" width="1000" height="400"></canvas>

<!-- Chart.js 読み込み -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    //収入グラフの表示処理
    fetch('/incomeGraph')
    .then(response => response.json()) //JSONデータをJavaScriptオブジェクトに変換 
    .then(json => {
        const ctx = document.getElementById('myLineChart').getContext('2d');
        new Chart(ctx, {
        type: 'line', //グラフの種類を設定
        data: {
            labels: json.labels, //X軸のラベル【例：2025-05】
            datasets: [{
                label: '収入額', //X軸の凡例に表示されるラベル
                data: json.data, //収入データ
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2, //線の太さ
                fill: true, //線の下を塗りつぶすかどうか
                tension: 0.1 //線の滑らかさ
            }]
        },
        options: {
            responsive: true, // 画面サイズに応じて自動調整
            scales: {
                //Y軸(縦軸)の設定
                y: {
                    beginAtZero: true, // 0から始める
                    max: json.max,
                    min:0,
                    ticks:{
                        callback: value => value + '円' // 数字に「円」を付けて表示
                    },
                    title: {
                        display: true, // タイトル表示ON
                        text: '金額（円）'
                    }
                },
                //X軸(横軸)の設定
                x: {
                    title: {
                    display: true, // タイトル表示ON
                    text: '日付'
                    }
                }
            }
        }
        });
    })
    
    //支出グラフの表示処理
    fetch('/expendituresGraph')
    .then(response => response.json())
    .then(json => {
        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
        type: 'line',
        data: {
            labels: json.labels,
            datasets: [{
            label: '支出額',
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
                max: json.max,
                min:0,
                ticks:{
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
