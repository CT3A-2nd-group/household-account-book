<?php /* views/home.php : header/footer いらない */ ?>

<p>ようこそ、<?= htmlspecialchars($username) ?> さん！</p>

<?php if (!empty($isAdmin)): ?>
    <p>あなたは<strong>管理者</strong>です。</p>
<?php else: ?>
    <p>あなたは一般ユーザーです。</p>
<?php endif; ?>

<script src="/js/progressbar.js" defer></script>
<!-- ProgressBar.js を CDN で読み込み -->
<script src="https://cdn.jsdelivr.net/npm/progressbar.js"></script>

<!-- 円を描画する場所 -->
<div id="circle-container" style="width:200px; height:200px;"></div>

