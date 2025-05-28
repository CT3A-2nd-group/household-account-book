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
</div>

<script src="/js/progressbar.js" defer></script>
<!-- ProgressBar.js を CDN で読み込み -->
<script src="https://cdn.jsdelivr.net/npm/progressbar.js"></script>

<!-- 円を描画する場所 -->
<div id="circle-container" style="width:200px; height:200px;"></div>