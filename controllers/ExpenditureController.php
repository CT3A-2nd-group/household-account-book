<?php
class ExpenditureController
{
    //　入力画面表示
    public function aaa() {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    require_once __DIR__ . '/../config/database.php';
    
    // カテゴリ一覧取得
    $stmt = $pdo->prepare('SELECT id, name FROM categories');
    $stmt->execute();
    $categories = $stmt->fetchAll();

    require_once __DIR__ . '/../views/expenditure_form.php';
}
    
    // 支出登録処理      
    public function bbb() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        require_once __DIR__ . '/../config/database.php';

        //　記述はここから
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //データを受け取って変数に代入する処理
        $user_id =$_SESSION['user_id'];
        $input_date = $_POST['input_date'] ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $description = $_POST['description'] ?? null;
        $is_waste = isset($_POST['is_waste']) ? 1 : 0;  // チェックボックス
        $star_rate = $_POST['star_rate'] ?? null;

        //正しく入力されてるかどうか
       if (!isset($amount) || $amount === '' || !is_numeric($amount) || $amount <= 0) {
            header('Location: /expenditure/form.php?error=' . urlencode('金額は1円以上で入力してください'));
            exit;
        }

        // 他の必須入力のチェック
        if (empty($input_date) || empty($category_id)) {
            header('Location: /expenditure/form.php?error=' . urlencode('日付とカテゴリは必須です'));
            exit;
        }

        // star_rateのチェック
        if ($star_rate === '' || !in_array((int)$star_rate, [1, 2, 3, 4, 5], true)) {
            header('Location: /expenditure/form.php?error=' . urlencode('評価は1〜5の数値で選んでください'));
            exit;
        }



        //$user_id = ; // ログインユーザーIDなどでセット

        $stmt = $pdo->prepare("
            INSERT INTO expenditures
            (user_id, category_id, input_date, amount, description, is_waste, star_rate)
            VALUES (:user_id, :category_id, :input_date, :amount, :description, :is_waste, :star_rate)
        ");

        $stmt->execute([
            ':user_id' => $user_id,
            ':category_id' => $category_id,
            ':input_date' => $input_date,
            ':amount' => $amount,
            ':description' => $description,
            ':is_waste' => $is_waste,
            ':star_rate' => $star_rate
        ]);

        header('Location: /graph-view');
        exit;
    }
    }

    //　支出一覧表示（入出金一覧にしてその２つを同じファイルに作るならここに記述しなくていい）
    // public function ccc() {}
}