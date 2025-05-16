<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? htmlspecialchars($title) : 'ページ' ?></title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 2rem;
            background-color: #f9f9f9;
        }
        header {
            background: #007bff;
            color: white;
            padding: 1rem;
            margin-bottom: 2rem;
        }
        header h1 {
            margin: 0;
        }
        .footer {
            background-color: #f0f0f0; 
            padding: 1rem;
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #333;
            border-top: 1px solid #ccc;
        }
        nav {
            margin-top: 0.5rem;
        }
        nav a {
            color: white;
            margin-right: 1rem;
            text-decoration: underline;
        }
        nav a:hover {
            text-decoration: none;
        }
        main {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .error {
            color: red;
            margin-bottom: 1rem;
        }
        .link {
            margin-top: 1rem;
            text-align: center;
        }
        form {
            width: 50%;
            margin: 2rem auto;
            padding: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        form label,
        form input,
        form button {
            display: block;
            width: 50%;
            margin-bottom: 1rem;
            
        }
    </style>
</head>
<body>
    <header>
        <h1><?= isset($title) ? htmlspecialchars($title) : 'ページ' ?></h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/home">ホーム</a>
                <a href="/logout">ログアウト</a>
                <a href="/logout">ログアウト</a>
                <a href="/logout">ログアウト</a>
                <a href="/logout">ログアウト</a>
                <a href="/logout">ログアウト</a>
                <a href="/expenditure/create">ログアウト</a>
            <?php else: ?>
                <a href="/login">ログイン</a>
                <a href="/register">新規登録</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
