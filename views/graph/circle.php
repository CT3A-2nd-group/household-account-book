<!-- グラフページのメインコンテンツ -->
<div class="graph-page">
    <!-- 年選択とナビゲーションボタン -->
    <div class="graph-controls">
        <div class="left-control">
            <select id="yearSelect"></select>
	    <select id="monthSelect"></select>
        </div>

        <div class="right-control">
            <button id="prevButton">収入カテゴリ</button>
            <button id="nextButton">支出カテゴリ</button>
        </div>
    </div>

    <!-- Swiperカルーセル -->
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
      <h2 class="h2Pie">収入カテゴリ</h2>
      <canvas id="incomePieChart" class = "pieCanvas"></canvas>
    </div>
    <div class="swiper-slide">
      <h2 class="h2Pie">支出カテゴリ</h2>
      <canvas id="expenditurePieChart" class="pieCanvas"></canvas>
    </div>
        </div>
    </div>
</div>
