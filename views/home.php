<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ホーム</title>
</head>
<body>
    <h1>ようこそ、<?= htmlspecialchars($username) ?> さん！</h1>

    <?php if (!empty($isAdmin)): ?>
        <p>あなたは管理者です。</p>
    <?php else: ?>
        <p>あなたは一般ユーザーです。</p>
    <?php endif; ?>

    <p><a href="/logout">ログアウト</a></p>
</body>
</html>