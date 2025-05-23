/* --------- グローバル変数 --------- */
let chartInstances = { income: null, expenditure: null, combined: null };
let chartData = { income: null, expenditure: null };

const yearColors = {
  2025: "rgb(75,192,192)",
  2026: "rgb(255,99,132)",
  2027: "rgb(54,162,235)",
  2028: "rgb(255,206,86)",
};

/* --------- 年セレクト --------- */
function populateYearSelect(years) {
  const select = document.getElementById("yearSelect");
  select.innerHTML = '<option value="all">すべて</option>';
  years.forEach((y) => {
    select.insertAdjacentHTML(
      "beforeend",
      `<option value="${y}">${y}</option>`
    );
  });
  select.onchange = () => {
    const y = select.value;
    drawCharts(y);
    drawCombinedChart(y);
  };
}

/* --------- グラフ共通 --------- */
const monthLabels = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];

function destroyIfExists(name) {
  if (chartInstances[name]) chartInstances[name].destroy();
}

/* --------- 収支（2 系列）グラフ --------- */
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
  chartInstances.combined = new Chart(
    document.getElementById("combinedChart"),
    {
      type: "line",
      data: {
        labels: monthLabels,
        datasets: [
          makeDataset(
            "収入",
            inc.data,
            yearColors[selectedYear] || "rgb(75,192,192)"
          ),
          makeDataset("支出", exp.data, "rgb(255,159,64)"),
        ],
      },
      options: defaultOptions(
        Math.max(chartData.income.max, chartData.expenditure.max)
      ),
    }
  );
}

/* --------- 単独グラフ --------- */
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

/* --------- Dataset & Options ヘルパ --------- */
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

/* --------- 初期化 --------- */
window.addEventListener("DOMContentLoaded", () => {
  fetchChartData().then(() => {
    const swiper = new Swiper(".swiper", {
      loop: false,
      slidesPerView: 1,
      spaceBetween: 30,
      navigation: { nextEl: "#nextButton", prevEl: "#prevButton" },
      on: {
        init() {
          drawCombinedChart(document.getElementById("yearSelect").value);
        },
        slideChange() {
          const y = document.getElementById("yearSelect").value;
          this.activeIndex === 0 ? drawCombinedChart(y) : drawCharts(y);
        },
      },
    });
  });
});
