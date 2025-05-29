<!-- CSS読み込み -->
<link rel="stylesheet" href="/css/graph.css">
<!-- 外部ライブラリの読み込み -->
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Swiper.js -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script src="/js/graphCircle.js" defer></script>

<select id="yearSelect"></select>
<select id="monthSelect"></select>

<button id="prevButton">収入</button>
<button id="nextButton">支出</button>

<!-- Swiper コンテナ -->
<div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <div class="swiper-slide">
      <canvas id="incomePieChart" width="1000" height="600"></canvas>
    </div>
    <div class="swiper-slide">
      <canvas id="expenditurePieChart" width="1000" height="600"></canvas>
    </div>
  </div>
  <div class="swiper-pagination"></div>
</div>




