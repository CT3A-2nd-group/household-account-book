<?php
// タイムゾーンの設定
date_default_timezone_set('Asia/Tokyo');

// URLのパス部分を取得
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 各ルートごとの処理
switch ($path) {
    case '/':
    case '/home':
        require_once __DIR__ . '/../controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    case '/register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../controllers/RegisterController.php';
            $controller = new RegisterController();
            $controller->register();
        } else {
            require_once __DIR__ . '/../views/register.php';
        }
        break;

    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../controllers/LoginController.php';
            $controller = new LoginController();
            $controller->login();
        } else {
            require_once __DIR__ . '/../views/login.php';
        }
        break;

    case '/logout':
        require_once __DIR__ . '/../controllers/LogoutController.php';
        $controller = new LogoutController();
        $controller->logout();
        break;

    case '/incomeGraph':
        require_once __DIR__.'/../controllers/GraphController.php';
        $controller = new GraphController();
        $controller->incomeGraph();
        break;
       
    case '/graph-view':
        require_once __DIR__ . '/../views/graph.php';
        break;

    default:
        http_response_code(404);
        echo 'ページが見つかりません。';
        break;
}
