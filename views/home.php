<!-- ホームページのメインコンテンツ -->
<div class="dashboard-container">
    <!-- ユーザー情報セクション -->
    <section class="welcome-section">
        <div class="user-greeting">
            <h2 class="greeting-text">ようこそ、<?= htmlspecialchars($username) ?> さん！</h2>
            <?php if (!empty($isAdmin)): ?>
                <p class="user-role admin-role">あなたは<strong>管理者</strong>です。</p>
            <?php else: ?>
                <p class="user-role user-role-standard">あなたは一般ユーザーです。</p>
            <?php endif; ?>
        </div>
    </section>
<<<<<<< HEAD
</div>

<button id="">目標登録</button>
<a href="/finance/save-form" class="btn-savings">貯金額登録</a>


<script src="/js/progressbar.js" defer></script>
<!-- ProgressBar.js を CDN で読み込み -->
<script src="https://cdn.jsdelivr.net/npm/progressbar.js"></script>

<!-- 円を描画する場所 -->
<div id="circle-container" style="width:200px; height:200px;"></div>
=======
    <!-- 仮置きの自由資金表示 -->
    <h2>自由資金(表示)</h2>
    <!-- 月：金額の表示 -->
    <ul>
        <?php foreach ($freeMoney as $month => $amount): ?>
            <li><?= htmlspecialchars($month) ?> : ¥<?= number_format($amount, 0) ?></li>
        <?php endforeach; ?>
    </ul>
>>>>>>> 5d99400 (自由資金算出＋表示をホームでやってみた)
