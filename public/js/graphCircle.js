let swiper; // Swiper用グローバル変数

const pieCharts = {
  income: null,
  expenditure: null,
};

window.addEventListener("DOMContentLoaded", async () => {
  const incomeRes = await fetch("/graph/inCircle-data");
  const expenditureRes = await fetch("/graph/exCircle-data");

  const incomeData = await incomeRes.json();
  const expenditureData = await expenditureRes.json();

  const { years, months } = extractAvailableYearsAndMonths([incomeData, expenditureData]);

  setupYearMonthSelectors(years, months);

  document.getElementById("yearSelect").addEventListener("change", () => {
    updateMonthOptions([incomeData, expenditureData]);
    drawCharts(incomeData, expenditureData);
  });

  document.getElementById("monthSelect").addEventListener("change", () => {
    drawCharts(incomeData, expenditureData);
  });

  drawCharts(incomeData, expenditureData);

  // Swiper 初期化
  swiper = new Swiper(".mySwiper", {
    pagination: {
      el: ".swiper-pagination",
    },
    loop: false,
  });
  

  // ボタンによるスライド切り替え
  document.getElementById("prevButton").addEventListener("click", () => {
    swiper.slideTo(0); // 収入
    drawCharts(incomeData, expenditureData);
  });
  document.getElementById("nextButton").addEventListener("click", () => {
    swiper.slideTo(1); // 支出
    drawCharts(incomeData, expenditureData);
  });
});

// 年月の一覧を抽出
function extractAvailableYearsAndMonths(dataArray) {
  const yearSet = new Set();
  const monthSet = new Set();

  dataArray.forEach((data) => {
    for (const category in data) {
      for (const year in data[category]) {
        yearSet.add(year);
        data[category][year].labels.forEach(month => monthSet.add(month));
      }
    }
  });

  return {
    years: Array.from(yearSet).sort(),
    months: Array.from(monthSet).sort(),
  };
}

// セレクトボックス初期化
function setupYearMonthSelectors(years, months) {
  const yearSelect = document.getElementById("yearSelect");
  const monthSelect = document.getElementById("monthSelect");

  yearSelect.innerHTML = "";
  monthSelect.innerHTML = "";

  years.forEach(y => {
    const option = document.createElement("option");
    option.value = y;
    option.textContent = y;
    yearSelect.appendChild(option);
  });

  months.forEach(m => {
    const option = document.createElement("option");
    option.value = m;
    option.textContent = `${parseInt(m)}月`;
    monthSelect.appendChild(option);
  });

  yearSelect.value = years[years.length - 1];
  monthSelect.value = months[0];
}

// 年に応じた月に絞って更新
function updateMonthOptions(dataArray) {
  const year = document.getElementById("yearSelect").value;
  const monthSelect = document.getElementById("monthSelect");

  const monthSet = new Set();

  dataArray.forEach((data) => {
    for (const category in data) {
      if (data[category][year]) {
        data[category][year].labels.forEach(month => monthSet.add(month));
      }
    }
  });

  const months = Array.from(monthSet).sort();

  monthSelect.innerHTML = "";
  months.forEach(m => {
    const option = document.createElement("option");
    option.value = m;
    option.textContent = `${parseInt(m)}月`;
    monthSelect.appendChild(option);
  });

  if (!months.includes(monthSelect.value)) {
    monthSelect.value = months[0];
  }
}

// グラフ描画処理
function drawCharts(incomeData, expenditureData) {
  const year = document.getElementById("yearSelect").value;
  const month = document.getElementById("monthSelect").value;
  drawPieChart("income", incomeData, year, month);
  drawPieChart("expenditure", expenditureData, year, month);
}

// 円グラフを描画
function drawPieChart(type, data, year, month) {
  const ctx = document.getElementById(
    type === "income" ? "incomePieChart" : "expenditurePieChart"
  );
  if (!ctx) return;

  const labels = [];
  const dataset = [];

  for (const category in data) {
    if (data[category][year]) {
      const index = data[category][year]["labels"].indexOf(month);
      if (index !== -1) {
        labels.push(category);
        dataset.push(data[category][year]["data"][index]);
      }
    }
  }

  if (pieCharts[type]) pieCharts[type].destroy();

  pieCharts[type] = new Chart(ctx, {
    type: "pie",
    data: {
      labels: labels,
      datasets: [{
        data: dataset,
        backgroundColor: generateColors(labels.length),
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: type === "income" ? "収入カテゴリ別" : "支出カテゴリ別",
        }
      }
    }
  });
}

// 色を生成
function generateColors(count) {
  const colors = ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"];
  const result = [];
  for (let i = 0; i < count; i++) {
    result.push(colors[i % colors.length]);
  }
  return result;
}

console.log(swiper);