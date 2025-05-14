<?php
$title = 'ユーザー登録';
include __DIR__ . '/layouts/header.php';
?>

<?php if (isset($_GET['error'])): ?>
    <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<form action="/register" method="POST">
    <label for="username">ユーザー名</label>
    <input type="text" name="username" id="username" required maxlength="255">

    <label for="password">パスワード</label>
    <input type="password" name="password" id="password" required minlength="4">

    <button type="submit">登録</button>
</form>

<div class="link">
    すでにアカウントをお持ちの方は <a href="/login">ログイン</a>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>
