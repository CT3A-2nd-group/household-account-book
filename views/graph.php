<?php
$title = '収入・支出グラフ';
include __DIR__ . '/layouts/header.php';
?>

<h1>収入・支出グラフ</h1>

<!-- 年を選択 -->
<label for="yearSelect">年を選択：</label>
<select id="yearSelect">
  <option value="all">すべて</option>
</select>

<!-- グラフ種類切り替え -->
<button onclick="loadChart('/incomeGraph')">収入グラフ</button>
<button onclick="loadChart('/expendituresGraph')">支出グラフ</button>

<!-- グラフキャンバス -->
<canvas id="myLineChart" width="1000" height="400"></canvas>

<!-- Chart.js 読み込み -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let chartInstance = null;
let allData = null;

// 年ごとの色（共通）
const yearColors = {
    "2025": "rgb(75, 192, 192)",
    "2026": "rgb(255, 99, 132)",
    "2027": "rgb(54, 162, 235)",
    "2028": "rgb(255, 206, 86)"
};

// データ読み込みとグラフ初期化
function loadChart(endpoint) {
    fetch(endpoint)
        .then(response => response.json())
        .then(json => {
            allData = json;
            populateYearSelect(Object.keys(json.years));
            drawChart("all");
        })
        .catch(error => {
            console.error('データの取得に失敗しました:', error);
        });
}

// セレクトボックスを更新
function populateYearSelect(years) {
    const select = document.getElementById('yearSelect');

    // 一度クリア（重複防止）
    select.innerHTML = '<option value="all">すべて</option>';

    years.forEach(year => {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        select.appendChild(option);
    });

    // セレクト変更イベント（すでにあれば削除して再登録）
    select.onchange = () => {
        const selected = select.value;
        drawChart(selected);
    };
}

// グラフを描画する関数
function drawChart(selectedYear) {
    const ctx = document.getElementById('myLineChart').getContext('2d');
    const labels = ['01','02','03','04','05','06','07','08','09','10','11','12'];
    let datasets = [];

    if (selectedYear === "all") {
        datasets = Object.entries(allData.years).map(([year, obj]) => {
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
        const obj = allData.years[selectedYear];
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

    // 旧チャート破棄
    if (chartInstance) {
        chartInstance.destroy();
    }

    // 新チャート描画
    chartInstance = new Chart(ctx, {
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
                    max: allData.max,
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

// 初期ロード：収入グラフ
window.addEventListener('DOMContentLoaded', () => {
    loadChart('/incomeGraph');
});
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
