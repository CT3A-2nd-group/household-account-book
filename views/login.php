<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <style>
        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        label, input {
            display: block;
            margin-bottom: 0.5rem;
            width: 100%;
        }
        button {
            padding: 0.5rem;
            width: 100%;
        }
        .error {
            color: red;
            margin-bottom: 1rem;
        }
        .link {
            max-width: 400px;
            margin: 1rem auto;
            text-align: center;
        }
        .link a:link,
        .link a:visited,
        .link a:hover,
        .link a:active {
            color: blue;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">ログイン</h2>

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
    
</body>
</html>
