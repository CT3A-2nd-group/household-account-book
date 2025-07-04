let swiper; // Swiper用グローバル変数

//円グラフインスタンス
const pieCharts = {
  income: null,
  expenditure: null,
};

window.addEventListener("DOMContentLoaded", async () => {
  //income,expendituresのデータ取得
  const incomeRes = await fetch("/graph/inCircle-data");
  const expenditureRes = await fetch("/graph/exCircle-data");

  const incomeData = await incomeRes.json();
  const expenditureData = await expenditureRes.json();
  //セレクトで扱う年月の取得
  const { years, months } = extractAvailableYearsAndMonths([
    incomeData,
    expenditureData,
  ]);

  setupYearMonthSelectors(years, months);
  //select内の年月が変更されたときに再描画
  document.getElementById("yearSelect").addEventListener("change", () => {
    updateMonthOptions([incomeData, expenditureData]);
    drawCharts(incomeData, expenditureData);
  });

  document.getElementById("monthSelect").addEventListener("change", () => {
    drawCharts(incomeData, expenditureData);
  });
  //初期描画
  drawCharts(incomeData, expenditureData);

  // Swiper 初期化
  swiper = new Swiper(".mySwiper", {
    pagination: {
      el: ".swiper-pagination",
    },
    on: {
      slideChange: updateButtonState, // スライドが変わったらボタン状態を更新
    },
    loop: false,
  });

  // ボタンによるスライド切り替え
  document.getElementById("prevButton").addEventListener("click", () => {
    swiper.slideTo(0); // 収入カテゴリ
    drawCharts(incomeData, expenditureData);
  });
  document.getElementById("nextButton").addEventListener("click", () => {
    swiper.slideTo(1); // 支出カテゴリ
    drawCharts(incomeData, expenditureData);
  });
  // ボタンの状態を更新する関数
  function updateButtonState() {
    const currentIndex = swiper.activeIndex;
    //現在のスライドによってどちらかのボタンを無効化
    prevButton.disabled = currentIndex === 0;
    nextButton.disabled = currentIndex === swiper.slides.length - 1;
  }
  // 初期状態でもボタン状態を更新
  updateButtonState();
});

// 年月の一覧を抽出
function extractAvailableYearsAndMonths(dataArray) {
  const yearSet = new Set();
  const monthSet = new Set();

  dataArray.forEach((data) => {
    for (const category in data) {
      for (const year in data[category]) {
        yearSet.add(year);
        data[category][year].labels.forEach((month) => monthSet.add(month));
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
  //まずクリア
  yearSelect.innerHTML = "";
  monthSelect.innerHTML = "";
  //年の追加
  years.forEach((y) => {
    const option = document.createElement("option");
    option.value = y;
    option.textContent = `${parseInt(y)}年`;
    yearSelect.appendChild(option);
  });
  //月の追加
  months.forEach((m) => {
    const option = document.createElement("option");
    option.value = m;
    option.textContent = `${parseInt(m)}月`;
    monthSelect.appendChild(option);
  });
  //初期値セット(0だったら一番遅い年月　year.length-1にすると最新の年はもってこれる　DBから現在の年月持ってきたほうがよさげだが)
  yearSelect.value = years[0];
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
        data[category][year].labels.forEach((month) => monthSet.add(month));
      }
    }
  });

  const months = Array.from(monthSet).sort();
  //月セレクトの更新
  monthSelect.innerHTML = "";
  months.forEach((m) => {
    const option = document.createElement("option");
    option.value = m;
    option.textContent = `${parseInt(m)}月`;
    monthSelect.appendChild(option);
  });
  //変更後に現在の月がなかったら初期値に
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

  const tempData = [];

  //データを一時配列に格納
  for (const category in data) {
    if (data[category][year]) {
      const index = data[category][year]["labels"].indexOf(month);
      if (index !== -1) {
        tempData.push({
          category,
          value: data[category][year]["data"][index],
        });
      }
    }
  }

  //ソート処理の追加：データの値が多い順に並び替え（降順）
  tempData.sort((a,b) => b.value - a.value);

  // グラフに渡すラベルとデータを配列に分離
  const labels = tempData.map((item) => item.category);
  const dataset = tempData.map((item) => item.value);

  //再描画のためにグラフを削除
  if (pieCharts[type]) pieCharts[type].destroy();

  pieCharts[type] = new Chart(ctx, {
    type: "pie",
    data: {
      labels: labels,
      datasets: [
        {
          data: dataset,
          backgroundColor: generateColors(labels.length),
          borderWidth: 0,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
        },
      },
    },
  });
}

// 色を生成
function generateColors(count) {
  const colors = [
    "#FF6384",
    "#36A2EB",
    "#FFCE56",
    "#4BC0C0",
    "#9966FF",
    "#FF9F40",
  ];
  const result = [];
  for (let i = 0; i < count; i++) {
    result.push(colors[i % colors.length]);
  }
  return result;
}

console.log(swiper);
