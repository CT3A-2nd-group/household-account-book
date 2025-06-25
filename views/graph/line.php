<!-- グラフページのメインコンテンツ -->
<div class="graph-page">
    <!-- 年選択とナビゲーションボタン -->
    <div class="graph-controls">
        <div class="left-control">
            <label for="yearSelect">年を選択：</label>
            <select id="yearSelect">
                <option value="all">すべて</option>
            </select>
        </div>

        <div class="right-control">
            <button id="prevButton">収入</button>
            <button id="nextButton">支出</button>
            <button id="thirdButton">収支</button>
        </div>
    </div>

    <!-- Swiperカルーセル -->
    <div class="swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <h2 class="h2Line">収入グラフ</h2>
                <div class="chart-container">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>

            <div class="swiper-slide">
                <h2 class="h2Line">支出グラフ</h2>
                <div class="chart-container">
                    <canvas id="expenditureChart"></canvas>
                </div>
            </div>

            <div class="swiper-slide">
                <h2 class="h2Line">収支グラフ</h2>
                <div class="chart-container">
                    <canvas id="combinedChart"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>
