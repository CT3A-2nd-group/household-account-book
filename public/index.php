<?php
declare(strict_types=1);

require_once __DIR__ . '/../core/Autoload.php'; // ← 自家製オートローダ
require_once __DIR__ . '/../core/Router.php';   // ← 自家製ルータ
require_once __DIR__ . '/../config/database.php';

date_default_timezone_set('Asia/Tokyo');

$router = new Router();

/* ---------- ルート定義 ---------- */
/* Home */
$router->add('GET', '/',     fn() => (new HomeController)->index());
$router->add('GET', '/home', fn() => (new HomeController)->index());

/* Auth */
$router->add('GET',  '/login',    fn() => (new LoginController)->showForm());
$router->add('POST', '/login',    fn() => (new LoginController)->login());
$router->add('GET',  '/register', fn() => (new RegisterController)->showForm());
$router->add('POST', '/register', fn() => (new RegisterController)->register());
$router->add('GET',  '/logout',   fn() => (new LogoutController)->logout());

/* Finance */
$router->add('GET',  '/income/create',      fn() => (new IncomeController)->showForm());
$router->add('POST', '/income/create',      fn() => (new IncomeController)->store());
$router->add('GET',  '/expenditure/create', fn() => (new ExpenditureController)->showForm());
$router->add('POST', '/expenditure/create', fn() => (new ExpenditureController)->store());

/* Admin */
$router->add('GET',  '/admin/category/create', fn() => (new AdminCategoryController)->create());
$router->add('POST', '/admin/category/store',  fn() => (new AdminCategoryController)->store());
$router->add('POST', '/admin/category/delete', fn() => (new AdminCategoryController)->delete());

/* GraphLine */
$router->add('GET', '/graph/inLine-data', fn() => (new GraphLineController)->incomeLine());
$router->add('GET', '/graph/exLine-data', fn() => (new GraphLineController)->expenditureLine());
$router->add('GET', '/graph/line',        fn() => (new GraphLineController)->view());;
/* GraphCircle */
$router->add('GET', '/graph/incomea-data', fn() => (new GraphCircleController)->incomeCircle());
$router->add('GET', '/graph/expenda-data', fn() => (new GraphCircleController)->expenditureCircle());
$router->add('GET', '/graph/circle',        fn() => (new GraphCircleController)->view());;


/* ---------- 発射 ---------- */
$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
