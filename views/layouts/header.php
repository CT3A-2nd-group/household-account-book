<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'ページ' ?></title>

    <!-- 共通 CSS -->
    <link rel="stylesheet" href="/css/common.css">

    <!-- ページ固有 CSS を挿し込みたい場合はコントローラ側で
         $extraCss = '<link rel="stylesheet" href="/css/graph.css">'; のように渡す -->
    <?= $extraCss ?? '' ?>
</head>
<body>
<header>
    <h1><?= $title ?? 'ページ' ?></h1>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/home">ホーム</a>
            <a href="/graph/line">折れ線グラフ</a>
            <a href="/graph/circle">円グラフ</a>
            <a href="/expenditure/create">支出登録</a>
            <a href="/income/create">収入登録</a>
            <a href="/List/view">収支一覧</a>
            <a href="/logout">ログアウト</a>
        <?php else: ?>
            <a href="/login">ログイン</a>
            <a href="/register">新規登録</a>
        <?php endif; ?>
    </nav>
</header>
<main>
