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
    let chartInstances = {
        income: null,
        expenditure: null,
        combined: null
    };

    let chartData = {
        income: null,
        expenditure: null,
        combined: null
    };

    const yearColors = {
        "2025": "rgb(75, 192, 192)",
        "2026": "rgb(255, 99, 132)",
        "2027": "rgb(54, 162, 235)",
        "2028": "rgb(255, 206, 86)"
    };

    function populateYearSelect(years) {
        const select = document.getElementById('yearSelect');
        select.innerHTML = '<option value="all">すべて</option>';
        years.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            select.appendChild(option);
        });
        select.onchange = () => {
            const selectedYear = select.value;
            drawCharts(selectedYear);
            drawCombinedChart(selectedYear);
        };
    }

    function drawCombinedChart(selectedYear) {
        const ctx = document.getElementById('combinedChart').getContext('2d');
        if (!chartData.income || !chartData.expenditure) return;

        if (selectedYear === "all") {
            const allYears = Object.keys(chartData.income.years);
            if (allYears.length === 0) return;
            selectedYear = allYears[0];
        }

        const income = chartData.income.years[selectedYear];
        const expenditure = chartData.expenditure.years[selectedYear];
        if (!income || !expenditure) return;

        const labels = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        const datasets = [];

        const incomeColor = yearColors[selectedYear] || "rgb(75,192,192)";
        const expenditureColor = "rgb(255,159,64)";

        datasets.push({
            label: '収入',
            data: income.data,
            borderColor: incomeColor,
            backgroundColor: incomeColor.replace('rgb', 'rgba').replace(')', ', 0.1)'),
            borderWidth: 2,
            tension: 0.1,
            spanGaps: true
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

        if (chartInstances.combined) {
            chartInstances.combined.destroy();
        }

        chartInstances.combined = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: Math.max(chartData.income.max, chartData.expenditure.max),
                        ticks: {
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
                            text: '月'
                        }
                    }
                }
            }
        });
    }

    function drawCharts(selectedYear = "all") {
        ['income', 'expenditure'].forEach(type => {
            const ctx = document.getElementById(type + 'Chart').getContext('2d');
            const dataSet = chartData[type];
            if (!dataSet) return; 

            const labels = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            let datasets = [];

            if (selectedYear === "all") {
                datasets = Object.entries(dataSet.years).map(([year, obj]) => {
                    const color = yearColors[year] || "rgb(200,200,200)";
                    return {
                        label: `${year}年`,
                        data: obj.data, 
                        borderColor: color,
                        backgroundColor: color.replace('rgb', 'rgba').replace(')', ', 0.1)'),
                        borderWidth: 2,
                        tension: 0.1,
                        spanGaps: true
                    };
                });
            } else {
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

            if (chartInstances[type]) {
                chartInstances[type].destroy();
            }

            chartInstances[type] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: dataSet.max,
                            ticks: {
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
                                text: '月'
                            }
                        }
                    }
                }
            });
        });
    }

    async function fetchChartData() {
        try {
            const [incomeRes, expenditureRes] = await Promise.all([
                fetch('/incomeGraph'),
                fetch('/expendituresGraph')
            ]);
            const incomeData = await incomeRes.json();
            const expenditureData = await expenditureRes.json();

            chartData.income = incomeData;
            chartData.expenditure = expenditureData;

            const allYears = Array.from(new Set([
                ...Object.keys(incomeData.years),
                ...Object.keys(expenditureData.years)
            ]));

            populateYearSelect(allYears);
            drawCharts("all");
        } catch (error) {
            console.error("データ取得エラー:", error);
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        fetchChartData().then(() => {
            const swiper = new Swiper('.swiper', {
                loop: false,
                slidesPerView: 1,
                spaceBetween: 30,
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
