<?php
    $title = '収入・支出グラフ';
    include __DIR__ . '/layouts/header.php';
?>

<!-- CSS読み込み -->
<link rel="stylesheet" href="/css/graph.css">
<!-- 外部ライブラリの読み込み -->
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Swiper.js -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<h1>収入・支出グラフ</h1>

<!-- 年選択とナビゲーションボタン -->
<div class="graph-controls">
    <div class="left-control">
        <label for="yearSelect">年を選択：</label>
        <select id="yearSelect">
            <option value="all">すべて</option>
        </select>
    </div>

    <div class="right-control">
        <button id="prevButton">前へ</button>
        <button id="nextButton">次へ</button>
    </div>
</div>

<!-- Swiperカルーセル -->
<div class="swiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <h2>収支グラフ</h2>
            <canvas id="combinedChart" width="1000" height="400"></canvas>
        </div>

        <div class="swiper-slide">
            <h2>収入グラフ</h2>
            <canvas id="incomeChart" width="1000" height="400"></canvas>
        </div>

        <div class="swiper-slide">
            <h2>支出グラフ</h2>
            <canvas id="expenditureChart" width="1000" height="400"></canvas>
        </div>
    </div>
</div>



<script>
    //グラフのインスタンス（収入、支出）を保持するオブジェクト
    let chartInstances = {
        income: null,
        expenditure: null,
        combined: null
    };
    //データ（収入、支出）を保持するオブジェクト
    let chartData = {
        income: null,
        expenditure: null,
        combined: null
    };
    // 年ごとの色
    const yearColors = {
        "2025": "rgb(75, 192, 192)",
        "2026": "rgb(255, 99, 132)",
        "2027": "rgb(54, 162, 235)",
        "2028": "rgb(255, 206, 86)"
    };

    // 年のセレクトボックスにデータを反映
    function populateYearSelect(years) {
        const select = document.getElementById('yearSelect');
        select.innerHTML = '<option value="all">すべて</option>';
        years.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            select.appendChild(option);
        });
        // 年が変更されたらグラフを再描画
        select.onchange = () => {
            const selectedYear = select.value;
            drawCharts(selectedYear);
            drawCombinedChart(selectedYear);
        };
    }

    // 収支グラフ（収入・支出）の描画
    function drawCombinedChart(selectedYear) {
        const ctx = document.getElementById('combinedChart').getContext('2d');
        // データがまだロードされていない場合は処理中止
        if (!chartData.income || !chartData.expenditure) return;

        // 「すべて」が選ばれている場合は最初の年で描画
        if (selectedYear === "all") {
            const allYears = Object.keys(chartData.income.years);
            if (allYears.length === 0) return;
            selectedYear = allYears[0];
        }

        // 選択された年のデータを取得
        const income = chartData.income.years[selectedYear];
        const expenditure = chartData.expenditure.years[selectedYear];
        if (!income || !expenditure) return;

        const labels = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        const datasets = [];

        const incomeColor = yearColors[selectedYear] || "rgb(75,192,192)";
        const expenditureColor = "rgb(255,159,64)";

        // 各系列（収入・支出）のデータをセット
        datasets.push({
            label: '収入', //凡例に表示するラベル
            data: income.data,
            borderColor: incomeColor, //折れ線の色
            backgroundColor: incomeColor.replace('rgb', 'rgba').replace(')', ', 0.1)'),
            borderWidth: 2, //線の太さ
            tension: 0.1, //線の滑らかさ
            spanGaps: true //データが無くても線をつなぐ
        });

        datasets.push({
            label: '支出',
            data: expenditure.data,
            borderColor: expenditureColor,
            backgroundColor: expenditureColor.replace('rgb', 'rgba').replace(')', ', 0.1)'),
            borderWidth: 2,
            tension: 0.1,
            spanGaps: true
        });

        // 既存グラフがあれば削除
        if (chartInstances.combined) {
            chartInstances.combined.destroy();
        }

        // Chart.js で描画
        chartInstances.combined = new Chart(ctx, {
            type: 'line', //グラフの種類を設定
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true, //画面サイズに応じて自動調整
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(){
                                return'';
                            },
                            label: function(context) {
                                const month = context.dataIndex + 1;
                                const label = context.dataset.label;
                                const value = context.raw.toLocaleString();
                                return [`${month}月`, `${label}: ${value}円`];
                            }
                        }
                    }
                },
                scales: {
                    //Y軸（縦軸）の設定
                    y: {
                        beginAtZero: true, //０から始める
                        max: Math.max(chartData.income.max, chartData.expenditure.max),
                        ticks: {
                            callback: value => value + '円' //数字に「円」を付けて表示
                        },
                        title: {
                            display: true,
                            text: '金額（円）'
                        }
                    },
                    //X軸（横軸）の設定
                    x: {
                        ticks: {
                            callback: value => (value + 1) + '月'
                        },
                        title: {
                            display: true,
                            text: '月'
                        }
                    }
                }
            }
        });
    }

    // グラフ描画（収入／支出どちらも）
    function drawCharts(selectedYear = "all") {
        ['income', 'expenditure'].forEach(type => {
            const ctx = document.getElementById(type + 'Chart').getContext('2d');
            const dataSet = chartData[type];
            // データがなければスキップ
            if (!dataSet) return; 

            const labels = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            let datasets = [];

            if (selectedYear === "all") {
                // 全年分のデータをデータセットに追加
                datasets = Object.entries(dataSet.years).map(([year, obj]) => {
                    const color = yearColors[year] || "rgb(200,200,200)";
                    return {
                        label: `${year}年`, //凡例に表示するラベル
                        data: obj.data, 
                        borderColor: color, //折れ線の色
                        backgroundColor: color.replace('rgb', 'rgba').replace(')', ', 0.1)'),
                        borderWidth: 2, //線の太さ
                        tension: 0.1, //線の滑らかさ
                        spanGaps: true //データが無くても線をつなぐ
                    };
                });
            } else {
                //選択された年だけを表示
                const obj = dataSet.years[selectedYear];
                if (!obj) return;
                const color = yearColors[selectedYear] || "rgb(200,200,200)";
                datasets = [{
                    label: `${selectedYear}年`,
                    data: obj.data,
                    borderColor: color,
                    backgroundColor: color.replace('rgb', 'rgba').replace(')', ', 0.1)'),
                    borderWidth: 2,
                    tension: 0.1,
                    spanGaps: true
                }];
            }
            // 既存のチャートがあれば破棄
            if (chartInstances[type]) {
                chartInstances[type].destroy();
            }

            // 新しくチャートを作成
            chartInstances[type] = new Chart(ctx, {
                type: 'line', //グラフの種類を設定
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true, //画面サイズに応じて自動調整
                    plugins: {
                        tooltip: {
                            callbacks: {
                                title: function(){
                                    return'';
                                },
                                label: function(context) {
                                    const month = context.dataIndex + 1;
                                    const value = context.raw.toLocaleString();
                                    return [`${month}月`,`${value}円`];
                                }
                            }
                        }
                    },

                    scales: {
                        //Y軸（縦軸）の設定
                        y: {
                            beginAtZero: true, //０から始める
                            max: dataSet.max,
                            ticks: {
                                callback: value => value + '円' //数字に「円」を付けて表示
                            },
                            title: {
                                display: true, //タイトル表示ON
                                text: '金額（円）'
                            }
                        },
                        //X軸（横軸）の設定
                        x: {
                            title: {
                                display: true,
                                text: '月'
                            }
                        }
                    }
                }
            });
        });
    }

    // グラフ用データ（収入・支出）を非同期で取得
    async function fetchChartData() {
        // 収入・支出のデータを同時に取得
        try {
            const [incomeRes, expenditureRes] = await Promise.all([
                fetch('/incomeGraph'),
                fetch('/expendituresGraph')
            ]);
            //JSONに変換
            const incomeData = await incomeRes.json();
            const expenditureData = await expenditureRes.json();

            chartData.income = incomeData;
            chartData.expenditure = expenditureData;

            //すべての年をまとめて習得し、セレクトボックスに反映
            const allYears = Array.from(new Set([
                ...Object.keys(incomeData.years),
                ...Object.keys(expenditureData.years)
            ]));

            populateYearSelect(allYears); //年選択を更新
            drawCharts("all"); //初期表示（すべての年に）
        } catch (error) {
            console.error("データ取得エラー:", error);
        }
    }
    // 初期化
    window.addEventListener('DOMContentLoaded', () => {
        fetchChartData().then(() => {
            const swiper = new Swiper('.swiper', {
                loop: false, //ループするかどうか
                slidesPerView: 1, //1枚ずつ表示
                spaceBetween: 30, //スライド間の余白
                navigation: {
                    nextEl: '#nextButton',
                    prevEl: '#prevButton'
                },
                on: {
                    init: function () {
                        if (this.activeIndex === 0) {
                            const selectedYear = document.getElementById('yearSelect').value;
                            drawCombinedChart(selectedYear);
                        }
                    },
                    slideChange: function () {
                        if (this.activeIndex === 0) {
                            const selectedYear = document.getElementById('yearSelect').value;
                            drawCombinedChart(selectedYear);
                        }
                        if (this.activeIndex === 1) {
                            const selectedYear = document.getElementById('yearSelect').value;
                            drawCharts(selectedYear);
                        }
                        if (this.activeIndex === 2) {
                            const selectedYear = document.getElementById('yearSelect').value;
                            drawCharts(selectedYear);
                        }
                    }
                }
            });

            swiper.init(); // 明示的に初期化
        });
    });
</script>

<?php 
    include __DIR__ . '/layouts/footer.php';
?>
