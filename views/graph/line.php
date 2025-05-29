<!-- グラフページのメインコンテンツ -->
<div class="graph-page">
    <!-- CSS読み込み -->
<link rel="stylesheet" href="/css/graph.css">
<!-- 外部ライブラリの読み込み -->
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Swiper.js -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="/js/graphLine.js" defer></script>

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
                <h2 class="h2Line">収支グラフ</h2>
                <div class="chart-container">
                    <canvas id="combinedChart" width="1000" height="400"></canvas>
                </div>
            </div>

            <div class="swiper-slide">
                <h2 class="h2Line">収入グラフ</h2>
                <div class="chart-container">
                    <canvas id="incomeChart" width="1000" height="400"></canvas>
                </div>
            </div>

            <div class="swiper-slide">
                <h2>支出グラフ</h2>
                <div class="chart-container">
                    <canvas id="expenditureChart" width="1000" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
