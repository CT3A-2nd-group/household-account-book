<?php
    $title = '収入・支出グラフ';
    include __DIR__ . '/layouts/header.php';
?>
<link rel="stylesheet" href="/css/graph.css">
<h1>収入・支出グラフ</h1>

<!-- 年を選択 -->
<!-- セレクトボックスと切り替えボタンをまとめるラッパー -->
<div class="graph-controls">
  <div class="left-control">
    <label for="yearSelect">年を選択：</label>
    <select id="yearSelect">
      <option value="all">すべて</option>
    </select>
  </div>

  <div class="right-control">
    <button id="prevButton">収入グラフ</button>
    <button id="nextButton">支出グラフ</button>
  </div>
</div>

<!-- Swiperカルーセル -->
<div class="swiper">
  <div class="swiper-wrapper">
    <!-- スライド1：収入グラフ -->
    <div class="swiper-slide">
      <h2>収入グラフ</h2>
      <canvas id="incomeChart" width="1000" height="400"></canvas>
    </div>
    <!-- スライド2：支出グラフ -->
    <div class="swiper-slide">
      <h2>支出グラフ</h2>
      <canvas id="expenditureChart" width="1000" height="400"></canvas>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Swiper.js -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    //グラフのインスタンス（収入、支出）を保持するオブジェクト
    let chartInstances = {
        income: null,
        expenditure: null
    };
    //データ（収入、支出）を保持するオブジェクト
    let chartData = {
        income: null,
        expenditure: null
    };

    // 年ごとの色
    const yearColors = {
        "2025": "rgb(75, 192, 192)",
        "2026": "rgb(255, 99, 132)",
        "2027": "rgb(54, 162, 235)",
        "2028": "rgb(255, 206, 86)"
    };

    // セレクトボックスに年を追加し、選択時のイベントを設定
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
            drawCharts(select.value);
        };
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
                    scales: {
                        //Y軸（縦軸）の設定
                        y: {
                            beginAtZero: true, //０から始める
                            max: dataSet.max,
                            ticks: {
                                callback: value => value + '円'//数字に「円」を付けて表示
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
            drawCharts("all");//初期表示（すべての年に）

        } catch (error) {
            console.error("データ取得エラー:", error);
        }
    }

    // 初期化
    window.addEventListener('DOMContentLoaded', () => {
        fetchChartData();
        // Swiper初期化
        const swiper = new Swiper('.swiper', {
            loop: false, //ループするかどうか
            slidesPerView: 1, //1枚ずつ表示
            spaceBetween: 30, //スライド間の余白
            navigation: {
                nextEl: '#nextButton',
                prevEl: '#prevButton'
            },
            pagination: {
                el: '.swiper-pagination', //ページネーション表示
            },
        });

    });
</script>

<?php 
    include __DIR__ . '/layouts/footer.php';
?>
