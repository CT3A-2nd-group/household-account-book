<?php
$title = 'ログイン';
include __DIR__ . '/layouts/header.php';
?>

<?php if (isset($_GET['error'])): ?>
    <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<form action="/login" method="POST">
    <label for="username">ユーザー名</label>
    <input type="text" name="username" id="username" required>

    <label for="password">パスワード</label>
    <input type="password" name="password" id="password" required>

    <button type="submit">ログイン</button>
</form>

<div class="link">
    アカウントをお持ちでない方は <a href="/register">新規登録</a>
</div>

<!--あとで消す-->
<p><a href="/create-admin">管理者作成ページへ</a></p>

<?php include __DIR__ . '/layouts/footer.php'; ?>
