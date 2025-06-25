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

// 月のラベル（X軸に使用）
const monthLabels = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];

// Swiperインスタンスをグローバルで保持
let swiper;

/* --------- 年セレクト --------- */
function populateYearSelect(years) {
  const select = document.getElementById("yearSelect");
  select.innerHTML = '<option value="all">すべて</option>';
  years.forEach((y) => {
    select.insertAdjacentHTML("beforeend", `<option value="${y}">${y}</option>`);
  });

  select.onchange = () => {
    const y = select.value;
    updateGraphsForSlide(swiper?.activeIndex || 0, y);
  };
}

/* --------- グラフ破棄 --------- */
function destroyIfExists(name) {
  if (chartInstances[name]) {
    chartInstances[name].destroy();
    chartInstances[name] = null;
  }
}

/* --------- グラフ描画 --------- */
function drawCombinedChart(selectedYear) {
  if (!chartData.income || !chartData.expenditure) return;

  if (selectedYear === "all") {
    const first = Object.keys(chartData.income.years)[0];
    if (!first) return;
    selectedYear = first;
  }

  const inc = chartData.income.years[selectedYear];
  const exp = chartData.expenditure.years[selectedYear];
  if (!inc || !exp) return;

  destroyIfExists("combined");
  chartInstances.combined = new Chart(document.getElementById("combinedChart"), {
    type: "line",
    data: {
      labels: monthLabels,
      datasets: [
        makeDataset("収入", inc.data, yearColors[selectedYear] || "rgb(75,192,192)"),
        makeDataset("支出", exp.data, "rgb(255,159,64)"),
      ],
    },
    options: defaultOptions(Math.max(chartData.income.max, chartData.expenditure.max)),
  });
}

function drawCharts(selectedYear = "all") {
  ["income", "expenditure"].forEach((type) => {
    const d = chartData[type];
    if (!d) return;

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

    destroyIfExists(type);
    chartInstances[type] = new Chart(document.getElementById(type + "Chart"), {
      type: "line",
      data: { labels: monthLabels, datasets },
      options: defaultOptions(d.max),
    });
  });
}

/* --------- ヘルパー --------- */
function makeDataset(label, data, color) {
  return {
    label,
    data,
    borderColor: color,
    backgroundColor: color.replace("rgb", "rgba").replace(")", ",0.1)"),
    borderWidth: 2,
    tension: 0.1,
    spanGaps: true,
  };
}

function defaultOptions(max) {
  return {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      tooltip: {
        callbacks: {
          title: () => "",
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

/* --------- スライドごとのグラフ更新 --------- */
function updateGraphsForSlide(index, year) {
  switch (index) {
    case 0:
      drawCharts(year);
      break;
    case 1:
      drawCharts(year);
      break;
    case 2:
      drawCombinedChart(year);
      break;
  }
}

/* --------- データ取得 --------- */
async function fetchChartData() {
  try {
    const [incRes, expRes] = await Promise.all([
      fetch("/graph/inLine-data"),
      fetch("/graph/exLine-data"),
    ]);
    chartData.income = await incRes.json();
    chartData.expenditure = await expRes.json();

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
window.addEventListener("DOMContentLoaded", () => {
  fetchChartData().then(() => {
    swiper = new Swiper(".swiper", {
      loop: false,
      slidesPerView: 1,
      spaceBetween: 30,
      on: {
        init() {
          const y = document.getElementById("yearSelect").value;
          updateGraphsForSlide(0, y);
        },
        slideChange() {
          const y = document.getElementById("yearSelect").value;
          updateGraphsForSlide(swiper.activeIndex, y);
        },
      },
    });

    // スライドに直接移動するボタン
    document.getElementById("prevButton").addEventListener("click", () => {
      swiper.slideTo(0);
    });
    document.getElementById("nextButton").addEventListener("click", () => {
      swiper.slideTo(1);
    });
    document.getElementById("thirdButton").addEventListener("click", () => {
      swiper.slideTo(2);
    });
  });
});
