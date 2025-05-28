<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'ページ' ?></title>

    <!-- 共通 CSS -->
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/css/layout.css">

    <!-- ページ固有 CSS を挿し込みたい場合はコントローラ側で
         $extraCss = '<link rel="stylesheet" href="/css/graph.css">'; のように渡す -->
    <?= $extraCss ?? '' ?>
</head>
<body>

<?php if (isset($_SESSION['user_id'])): ?>
    <!-- ログイン後の3カラムレイアウト -->
    <div class="app-layout">
        <!-- モバイルメニューボタン -->
        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <!-- 左サイドバー（ナビゲーション） -->
        <aside class="left-sidebar" id="leftSidebar">
            <!-- ロゴ・タイトル部分 -->
            <div class="sidebar-header">
                <h1 class="app-title">家計簿アプリ</h1>
                <p class="app-subtitle">Finance Manager</p>
                <div class="header-divider"></div>
            </div>
            
            <!-- ナビゲーションメニュー -->
            <nav class="main-navigation">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="/home" class="nav-link active">
                            <span class="nav-icon home-icon"></span>
                            <span class="nav-text">ホーム</span>
                        </a>
                    </li>
                    
                    <!-- グラフセクション -->
                    <li class="nav-section">
                        <span class="section-title">グラフ</span>
                    </li>
                    <li class="nav-item">
                        <a href="/graph/line" class="nav-link">
                            <span class="nav-icon chart-line-icon"></span>
                            <span class="nav-text">折れ線グラフ</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/graph/circle" class="nav-link">
                            <span class="nav-icon chart-circle-icon"></span>
                            <span class="nav-text">円グラフ</span>
                        </a>
                    </li>
                    
                    <!-- 登録セクション -->
                    <li class="nav-section">
                        <span class="section-title">登録</span>
                    </li>
                    <li class="nav-item">
                        <li class="nav-item">
                        <a href="/income/create" class="nav-link">
                            <span class="nav-icon income-icon"></span>
                            <span class="nav-text">収入登録</span>
                        </a>
                    </li>
                        <a href="/expenditure/create" class="nav-link">
                            <span class="nav-icon expense-icon"></span>
                            <span class="nav-text">支出登録</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- ログアウト部分 -->
            <div class="sidebar-footer">
                <a href="/logout" class="logout-link">
                    <span class="nav-icon logout-icon"></span>
                    <span class="nav-text">ログアウト</span>
                </a>
            </div>
        </aside>
        
        <!-- メインコンテンツエリア -->
        <main class="main-content">
            <div class="content-header">
                <div class="page-header-content">
                    <div class="page-title-wrapper">
                        <h1 class="page-title">
                            <span class="title-icon">📊</span>
                            <span class="title-text"><?= $title ?? 'ページ' ?></span>
                            <span class="title-badge">Pro</span>
                        </h1>
                        <p class="page-subtitle">財務管理をスマートに</p>
                    </div>
                    <div class="header-actions">
                        <button class="header-btn notification-btn">
                            <span class="btn-icon">🔔</span>
                            <span class="notification-dot"></span>
                        </button>
                        <button class="header-btn profile-btn">
                            <span class="btn-icon">👤</span>
                        </button>
                    </div>
                </div>
                <div class="breadcrumb">
                    <!-- パンくずリストがある場合はここに -->
                </div>
            </div>
            <div class="content-body">
                <!-- ここに各ページのコンテンツが差し込まれます -->

<?php else: ?>
    <!-- ログイン前のシンプルなレイアウト -->
    <div class="simple-layout">
        <header class="simple-header">
            <div class="header-container">
                <div class="brand-section">
                    <h1 class="site-title">
                        <span class="brand-icon">💰</span>
                        <span class="brand-text"><?= $title ?? 'ページ' ?></span>
                    </h1>
                    <p class="brand-tagline">あなたの財務パートナー</p>
                </div>
                <nav class="auth-navigation">
                    <a href="/login" class="auth-link login-btn">ログイン</a>
                    <a href="/register" class="auth-link register-btn">新規登録</a>
                </nav>
            </div>
        </header>
        <main class="simple-main">
            <!-- ここに各ページのコンテンツが差し込まれます -->

<?php endif; ?>
