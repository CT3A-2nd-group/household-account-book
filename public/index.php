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

    case '/admin/category/create':
        require_once __DIR__ . '/../controllers/AdminCategoryController.php';
        $controller = new AdminCategoryController();
        $controller->create();
        break;

    case '/admin/category/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../controllers/AdminCategoryController.php';
            $controller = new AdminCategoryController();
            $controller->store();
        }
        break;

    case '/admin/category/delete':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../controllers/AdminCategoryController.php';
        $controller = new AdminCategoryController();
        $controller->delete();
    }
    break;

    case '/income/create':
        require_once __DIR__ . '/../controllers/IncomeController.php';
        $controller = new IncomeController();
        // 関数名は変更予定
        //　POSTはデータを変更する　
        //　GETはデータを変更しない
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->Income_Registration();  // 収入登録処理
        } else {
            $controller->Income_Input_Indication(); // 入力画面表示
        }
        break;

    case '/expenditure/create':
        require_once __DIR__ . '/../controllers/ExpenditureController.php';
        $controller = new ExpenditureController();
        // 関数名は変更予定
        //　POSTはデータを変更する　
        //　GETはデータを変更しない
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->bbb();  // 支出登録処理
        } else {
            $controller->aaa(); // 入力画面表示
        }
        break;

    case '/incomeGraph':
        require_once __DIR__.'/../controllers/GraphController.php';
        $controller = new GraphController();
        $controller->incomeGraph();
        break;
    
    case '/expendituresGraph':
        require_once __DIR__.'/../controllers/GraphController.php';
        $controller = new GraphController();
        $controller->expendituresGraph();
        break;

      case '/categoriesGraph':
        require_once __DIR__.'/../controllers/GraphController.php';
        $controller = new GraphController();
        $controller->categoriesGraph();
        break;

    case '/graphLine-view':
        require_once __DIR__ . '/../views/graph_line.php';
        break;
    
    case '/graphCircle-view':
        require_once __DIR__ . '/../views/graph_circle.php';
        break;

    //あとで消す
    case '/create-admin':
        require_once __DIR__ . '/../views/create_admin.php';
        break;

    case '/store-admin':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        $controller->store();
    }        
    break;

    default:
        http_response_code(404);
        echo 'ページが見つかりません。';
        break;
}