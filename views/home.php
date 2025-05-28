<?php /* views/home.php : header/footer いらない */ ?>

<p>ようこそ、<?= htmlspecialchars($username) ?> さん！</p>

<?php if (!empty($isAdmin)): ?>
    <p>あなたは<strong>管理者</strong>です。</p>
<?php else: ?>
    <p>あなたは一般ユーザーです。</p>
<?php endif; ?>

<link rel="stylesheet" href="/css/home.css">
<script src="/js/progressbar.js" defer></script>
<!-- ProgressBar.js を CDN で読み込み -->
<script src="https://cdn.jsdelivr.net/npm/progressbar.js"></script>

<div class="container">
    <div class="circle-wrapper">
        <h2 class="circle-title">目標：東京旅行</h2>
        <div id="circle-container" class="circle-container"></div>
        <div class="circle-labels">
            <div class="goal-amount">目標金額：50000円</div>
        </div>
    </div>
    <p>
        <?= htmlspecialchars($username) ?>さんの現在の自由資金は<br>
        <span class="centered-text">○○円です</span>
    </p>
</div>
