/* --------- グローバル変数 --------- */
// 各グラフインスタンスの参照を保持（破棄や更新のため）
let chartInstances = { income: null, expenditure: null, combined: null };

// サーバーから取得した収入・支出のデータを保持
let chartData = { income: null, expenditure: null };

// 年ごとの色を定義（グラフの色分けに使用）
const yearColors = {
  2025: "rgb(75,192,192)",
  2026: "rgb(255,99,132)",
  2027: "rgb(54,162,235)",
  2028: "rgb(255,206,86)",
};

/* --------- 年セレクト --------- */
// 年度選択のプルダウンに選択肢を追加し、変更時の挙動を設定
function populateYearSelect(years) {
  const select = document.getElementById("yearSelect");
  select.innerHTML = '<option value="all">すべて</option>'; // 初期オプション
  years.forEach((y) => {
    select.insertAdjacentHTML(
      "beforeend",
      `<option value="${y}">${y}</option>`
    );
  });
  // 年が変更されたら、グラフを描画しなおす
  select.onchange = () => {
    const y = select.value;
    drawCharts(y);
    drawCombinedChart(y);
  };
}

/* --------- グラフ共通 --------- */
// 月のラベル（X軸に使用）
const monthLabels = ["01","02","03","04","05","06","07","08","09","10","11","12"];

// すでにグラフインスタンスが存在する場合は破棄
function destroyIfExists(name) {
  if (chartInstances[name]) chartInstances[name].destroy();
}

/* --------- 収支（2 系列）グラフ --------- */
// 指定された年の収入・支出を1つのグラフに表示
function drawCombinedChart(selectedYear) {
  if (!chartData.income || !chartData.expenditure) return;

  // "すべて"が選ばれた場合は最初の年を選択
  if (selectedYear === "all") {
    const first = Object.keys(chartData.income.years)[0];
    if (!first) return;
    selectedYear = first;
  }

  const inc = chartData.income.years[selectedYear];
  const exp = chartData.expenditure.years[selectedYear];
  if (!inc || !exp) return;

  // 既存グラフを破棄して再描画
  destroyIfExists("combined");
  chartInstances.combined = new Chart(
    document.getElementById("combinedChart"),
    {
      type: "line",
      data: {
        labels: monthLabels,
        datasets: [
          makeDataset("収入", inc.data, yearColors[selectedYear] || "rgb(75,192,192)"),
          makeDataset("支出", exp.data, "rgb(255,159,64)"),
        ],
      },
      options: defaultOptions(Math.max(chartData.income.max, chartData.expenditure.max)),
    }
  );
}

/* --------- 単独グラフ（収入 or 支出） --------- */
// 指定年 or 全年のデータを個別グラフで表示
function drawCharts(selectedYear = "all") {
  ["income", "expenditure"].forEach((type) => {
    const d = chartData[type];
    if (!d) return;

    // "すべて"の場合は各年のデータを系列として表示
    const datasets =
      selectedYear === "all"
        ? Object.entries(d.years).map(([y, obj]) =>
            makeDataset(`${y}年`, obj.data, yearColors[y] || "rgb(200,200,200)")
          )
        : [
            makeDataset(
              `${selectedYear}年`,
              d.years[selectedYear]?.data || [],
              yearColors[selectedYear] || "rgb(200,200,200)"
            ),
          ];

    destroyIfExists(type); // 既存グラフを破棄
    chartInstances[type] = new Chart(document.getElementById(type + "Chart"), {
      type: "line",
      data: { labels: monthLabels, datasets },
      options: defaultOptions(d.max),
    });
  });
}

/* --------- Dataset & Options ヘルパ --------- */
// グラフ用データセットオブジェクトを作成
function makeDataset(label, data, color) {
  return {
    label,
    data,
    borderColor: color,
    backgroundColor: color.replace("rgb", "rgba").replace(")", ",0.1)"),
    borderWidth: 2,
    tension: 0.1,
    spanGaps: true, // データがない月も線をつなぐ
  };
}

// グラフの共通オプションを返す
function defaultOptions(max) {
  return {
    responsive: true,
    plugins: {
      tooltip: {
        callbacks: {
          title: () => "", // ツールチップのタイトルを空に
          label: (c) => {
            const m = c.dataIndex + 1;
            return [`${m}月`, `${(c.raw ?? 0).toLocaleString()}円`];
          },
        },
      },
    },
    scales: {
      y: {
        beginAtZero: true,
        max,
        ticks: { callback: (v) => v + "円" },
        title: { display: true, text: "金額（円）" },
      },
      x: { title: { display: true, text: "月" } },
    },
  };
}

/* --------- データ取得 --------- */
// サーバーから収入・支出のデータを取得し、グラフを初期表示
async function fetchChartData() {
  try {
    const [incRes, expRes] = await Promise.all([
      fetch("/graph/income-data"),
      fetch("/graph/expend-data"),
    ]);
    chartData.income = await incRes.json();
    chartData.expenditure = await expRes.json();

    // データに含まれる全ての年を取得しセレクトボックスに反映
    const years = Array.from(
      new Set([
        ...Object.keys(chartData.income.years),
        ...Object.keys(chartData.expenditure.years),
      ])
    );
    populateYearSelect(years);
    drawCharts();
  } catch (e) {
    console.error("データ取得エラー", e);
  }
}

/* --------- 初期化処理 --------- */
// ページ読み込み後、データを取得してSwiperとグラフを初期化
window.addEventListener("DOMContentLoaded", () => {
  fetchChartData().then(() => {
    const swiper = new Swiper(".swiper", {
      loop: false,
      slidesPerView: 1,
      spaceBetween: 30,
      navigation: { nextEl: "#nextButton", prevEl: "#prevButton" },
      on: {
        init() {
          // 初期スライドで収支グラフを描画
          drawCombinedChart(document.getElementById("yearSelect").value);
        },
        slideChange() {
          const y = document.getElementById("yearSelect").value;
          // スライドに応じて収支グラフ or 単独グラフを再描画
          this.activeIndex === 0 ? drawCombinedChart(y) : drawCharts(y);
        },
      },
    });
  });
});
