<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../core/Autoload.php'; // ← 自家製オートローダ
require_once __DIR__ . '/../core/Router.php';   // ← 自家製ルータ
require_once __DIR__ . '/../config/database.php';

date_default_timezone_set('Asia/Tokyo');

$router = new Router();

/* ---------- ルート定義 ---------- */
/* Home */
$router->add('GET', '/',     fn() => (new HomeController)->index());
$router->add('GET', '/home', fn() => (new HomeController)->index());
$router->add('POST', '/goal/delete', fn() => (new HomeController)->deleteGoalAndRecord());

/* Auth */
$router->add('GET',  '/login',    fn() => (new LoginController)->showForm());
$router->add('POST', '/login',    fn() => (new LoginController)->login());
$router->add('GET',  '/register', fn() => (new RegisterController)->showForm());
$router->add('POST', '/register', fn() => (new RegisterController)->register());
$router->add('GET',  '/logout',   fn() => (new LogoutController)->logout());
/* 利用規約 */
$router->add('GET',  '/terms',   fn() => (new TermsController)->show());


/* Setting */
$router->add('GET',  '/auth/setting',           fn() => (new SettingController)->index());
$router->add('POST', '/auth/update-username',   fn() => (new SettingController)->updateUsername());
$router->add('POST', '/auth/change-password',   fn() => (new SettingController)->changePassword());
$router->add('POST', '/account/delete',    fn() => (new SettingController)->deleteAccount());

/* Finance */
/* 収入登録 */
$router->add('GET',  '/income/create',      fn() => (new IncomeController)->showForm());
$router->add('POST', '/income/create',      fn() => (new IncomeController)->store());
$router->add('GET',  '/income/edit',        fn() => (new IncomeController)->editForm());
$router->add('POST', '/income/edit',       fn() => (new IncomeController)->update());
/* 支出登録 */
$router->add('GET',  '/expenditure/create', fn() => (new ExpenditureController)->showForm());
$router->add('POST', '/expenditure/create', fn() => (new ExpenditureController)->store());
$router->add('GET',  '/expenditure/edit',   fn() => (new ExpenditureController)->editForm());
$router->add('POST', '/expenditure/edit',   fn() => (new ExpenditureController)->update());
/* 収支一覧 */
$router->add('GET', '/List/view', fn() => (new ListController)->Listview());
$router->add('POST', '/List/Delete', fn() => (new ListController)->DeleteList());
/* 貯金額登録 */
$router->add('POST', '/finance/save', fn() => (new SaveController)->save());
$router->add('GET', '/finance/save-form', fn() => (new SaveController)->showForm());
/* 貯金額一覧 */
$router->add('GET', '/SaveList/view', fn() => (new SaveListController)->SavingsListview());
/* 目標登録 */
$router->add('POST', '/finance/goal', fn() => (new GoalController)->createGoal());
$router->add('GET', '/finance/goal-form', fn() => (new GoalController)->showForm());

/* Admin */
$router->add('GET',  '/admin/category/create', fn() => (new AdminCategoryController)->create());
$router->add('POST', '/admin/category/store',  fn() => (new AdminCategoryController)->store());
$router->add('POST', '/admin/category/delete', fn() => (new AdminCategoryController)->delete());

/* GraphLine */
$router->add('GET', '/graph/inLine-data', fn() => (new GraphLineController)->incomeLine());
$router->add('GET', '/graph/exLine-data', fn() => (new GraphLineController)->expenditureLine());
$router->add('GET', '/graph/line',        fn() => (new GraphLineController)->view());
/* GraphCircle */
$router->add('GET', '/graph/inCircle-data', fn() => (new GraphCircleController)->incomeCircle());
$router->add('GET', '/graph/exCircle-data', fn() => (new GraphCircleController)->expenditureCircle());
$router->add('GET', '/graph/circle',        fn() => (new GraphCircleController)->view());


$router->add('GET',  '/temp_admin', fn() => (new adminController)->registerAdmin());
$router->add('POST', '/temp_admin', fn() => (new adminController)->registerAdmin());
/* Analysis */
$router->add('GET', '/analysis', fn() => (new AnalysisController)->view());

/* ---------- 発射 ---------- */
$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
