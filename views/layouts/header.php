<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? htmlspecialchars($title) : '家計簿アプリ' ?></title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            background-color: #e0e4e4;
        }

        header {
            background-color: #66b7ff;
            color: white;
            padding: 1rem 2rem;
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .navbar {
            background-color: white;
            border: 1px solid #66b7ff;
            display: flex;
            align-items: center;
            padding: 1rem;
        }

        .navbar a {
            margin-right: 2rem;
            text-decoration: none;
            color: black;
            font-weight: bold;
        }

        .navbar .menu-icon {
            font-size: 1.5rem;
            margin-right: 1rem;
        }

        main {
    padding: 2rem;
    min-height: 80vh; /* 高さを確保 */
    background-color:rgb(255, 220, 220);
}

    </style>
</head>
<body>

<header>
    <h1>家計簿アプリ</h1>
</header>

<div class="navbar">
    <span class="menu-icon">☰</span>
    <a href="/account">アカウント</a>
    <a href="/budget">予算の設定</a>
    <a href="/stats">収入・支出の統計</a>
    <a href="/categories">カテゴリごとの支出管理</a>
</div>

<main>
