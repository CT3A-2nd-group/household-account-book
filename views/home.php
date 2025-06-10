<!-- ホームページのメインコンテンツ -->
<div class="dashboard-container">
    <!-- ユーザー情報セクション -->
    <section class="welcome-section">
        <div class="user-greeting">
            <h2 class="greeting-text">ようこそ、<?= htmlspecialchars($username) ?> さん！</h2>
        </div>
    </section>
            <!-- 財務サマリー -->
    <section class="finance-summary">
        <div class="summary-cards">

            <!-- 今月の自由資金 -->
            <div class="summary-card income-card">
            <div class="card-header">
                <h3 class="card-title">今月の自由資金</h3>
                <span class="card-icon thismonth-icon"></span>
            </div>
            <p class="card-amount" style="color: #10b981;">¥34,000</p>
            <p class="card-change">前月比：+¥11,000</p>
            </div>

            <!-- 総自由資金 -->
            <div class="summary-card balance-card">
            <div class="card-header">
                <h3 class="card-title">総自由資金</h3>
                <span class="card-icon balance-icon"></span>
            </div>
            <p class="card-amount" style="color: #3b82f6;">¥<?= number_format($totalFreeMoney) ?></p>
            <p class="card-change">累計貯蓄額</p>
            </div>

            <!-- 目標達成率 -->
            <div class="summary-card" style="border-left-color: #a855f7;">
            <div class="card-header">
                <h3 class="card-title">目標達成率</h3>
                <span class="card-icon" style="background-color: #f3e8ff;"></span>
            </div>
            <p class="card-amount" style="color: #a855f7;">50.0%</p>
            <p class="card-change">現在の進捗</p>
            </div>

        </div>
    </section>

    <a href="/finance/save-form">貯金額登録</a>
    <!-- 仮置きの自由資金表示 -->
    <h2>自由資金(表示)</h2>
    <!-- 月：金額の表示 -->
    <ul>
        <?php foreach ($freeMoney as $month => $amount): ?>
            <li><?= htmlspecialchars($month) ?> : ¥<?= number_format($amount, 0) ?></li>
        <?php endforeach; ?>
    </ul>
