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
<a href="/finance/save-form">貯金額登録</a>