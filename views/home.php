<?php /* views/home.php : header/footer いらない */ ?>

<p>ようこそ、<?= htmlspecialchars($username) ?> さん！</p>

<?php if (!empty($isAdmin)): ?>
    <p>あなたは<strong>管理者</strong>です。</p>
<?php else: ?>
    <p>あなたは一般ユーザーです。</p>
<?php endif; ?>

<p><a href="/logout">ログアウト</a></p>
