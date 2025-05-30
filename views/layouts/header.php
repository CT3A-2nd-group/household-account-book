<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'ページ' ?></title>

    <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <!-- 管理者専用 CSS -->
        <link rel="stylesheet" href="/css/admin-layout.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <?php else: ?>
        <!-- 通常ユーザー用 CSS -->
        <link rel="stylesheet" href="/css/common.css">
        <link rel="stylesheet" href="/css/layout.css">
    <?php endif; ?>

    <?= $extraCss ?? '' ?>
    <?= $extraJs ?? ''?>

</head>
<body>
<?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
    <!-- 管理者専用レイアウト -->
    <div class="admin-layout">
        <header class="admin-header">
            <div class="header-container">
                <div class="brand-section">
                    <h1 class="admin-title">
                        <span class="brand-icon">🛠️</span>
                        <span class="brand-text">管理者画面</span>
                        <span class="admin-badge">Admin</span>
                    </h1>
                    <p class="admin-subtitle">システム管理・設定</p>
                </div>
                <nav class="admin-navigation">
                    <a href="/admin/category" class="admin-nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/category') === 0) ? 'active' : '' ?>">
                        <i class="fas fa-tags"></i>
                        カテゴリ管理
                    </a>
                    <a href="/logout" class="admin-nav-link logout">
                        <i class="fas fa-sign-out-alt"></i>
                        ログアウト
                    </a>
                </nav>
            </div>
        </header>
        <main class="admin-main">

<?php elseif (isset($_SESSION['user_id'])): ?>
    <?php
    // ランダムキャッチフレーズ
    $catchphrases = [
        '財務管理をスマートに',
        '毎日の支出をもっと見える化',
        '貯金の習慣、ここから始めよう',
        'お金の流れ、ひと目でわかる',
        '今日の節約が、明日の余裕',
        '家計の味方、あなた専用のアシスタント',
        '未来のために、今からできること',
    ];
    $randomSubtitle = $catchphrases[array_rand($catchphrases)];
    ?>
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

            <!-- ホームボタン固定 -->
            <div class="sidebar-fixed-home">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="/home" class="home-link <?= (isset($currentPage) && $currentPage === 'home') ? 'active' : '' ?>">
                            <span class="nav-icon home-icon"></span>
                            <span class="nav-text">ホーム</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- スクロール可能なナビゲーションエリア -->
            <div class="scrollable-navigation">
                <nav class="main-navigation">
                    <ul class="nav-menu">
                        <!-- グラフセクション -->
                        <li class="nav-section">
                            <button class="section-toggle" onclick="toggleSection('graph')" aria-expanded="true">
                                <span class="section-title">グラフ</span>
                                <span class="toggle-icon">▼</span>
                            </button>
                            <ul class="section-items" id="section-graph">
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
                            </ul>
                        </li>

                        <!-- 登録セクション -->
                        <li class="nav-section">
                            <button class="section-toggle" onclick="toggleSection('register')" aria-expanded="true">
                                <span class="section-title">登録</span>
                                <span class="toggle-icon">▼</span>
                            </button>
                            <ul class="section-items" id="section-register">
                                <li class="nav-item">
                                    <a href="/income/create" class="nav-link">
                                        <span class="nav-icon income-icon"></span>
                                        <span class="nav-text">収入登録</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/expenditure/create" class="nav-link">
                                        <span class="nav-icon expense-icon"></span>
                                        <span class="nav-text">支出登録</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- 一覧セクション -->
                        <li class="nav-section">
                            <button class="section-toggle" onclick="toggleSection('list')" aria-expanded="true">
                                <span class="section-title">一覧</span>
                                <span class="toggle-icon">▼</span>
                            </button>
                            <ul class="section-items" id="section-list">
                                <li class="nav-item">
                                    <a href="/List/view" class="nav-link">
                                        <span class="nav-icon income-icon"></span>
                                        <span class="nav-text">収支一覧</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>

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
                        <p class="page-subtitle"><?= $randomSubtitle ?></p>
                    </div>
                    <div class="header-actions">
                        <button class="header-btn notification-btn">
                            <span class="btn-icon">🔔</span>
                            <span class="notification-dot"></span>
                        </button>
                        <a href="/auth/setting" class="header-btn profile-btn" title="アカウント設定">
                            <span class="btn-icon">👤</span>
                        </a>
                    </div>
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