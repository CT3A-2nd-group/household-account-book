<?php
class IncomeController
{
    // 収入入力フォームの表示
    public function Income_Input_Indication()
    {
        session_start(); // セッション開始（ログインユーザー判定に必要）
        if (!isset($_SESSION['user_id'])) { // ログインしていなければログインページへリダイレクト
            header('Location: /login');
            exit; // 処理終了
        }
        require_once __DIR__ . '/../config/database.php';
        $stmt = $pdo->prepare("SELECT id, name FROM categories WHERE type = 'income'"); // 収入カテゴリを選択
        $stmt->execute(); // SQLを実行
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/income_form.php'; // 収入入力フォームのHTMLを読み込み表示
    }

    // 収入登録処理
    public function Income_Registration()
    {
        session_start(); // セッション開始（ログイン状態の確認・ユーザーID取得に必要）

        if (!isset($_SESSION['user_id'])) { // ログインしていなければログインページへリダイレクト
            header('Location: /login');
            exit;
        }

        require_once __DIR__ . '/../config/database.php'; // DB接続用設定を読み込み$pdoを初期化

        $id = $_SESSION['user_id']; // ログインユーザーIDを取得
        $category = $_POST['category_id']; // フォームから送信された収入カテゴリー（存在しなければnull）
        $amount = $_POST['amount']; // 収入金額
        $description = $_POST['description'] ?? null; // メモなど説明
        $input_date = $_POST['input_date']; // 日付設定

        // 入力チェック：金額が0以下ならエラー
        if (!is_numeric($amount) || $amount <= 0) {
        $this->redirectWithError('金額は正しく入力してください。');
    }

        try {
            $stmt = $pdo->prepare("INSERT INTO incomes (user_id, category_id, input_date, amount, description)
                    VALUES (:id, :category, :input_date, :amount, :description)"); // SQL文
        
            $stmt->execute([':id' => $id,
                ':category' => (int)$category,
                ':input_date' => $input_date,
                ':amount' => number_format((float)$amount, 2, '.', ''),
                ':description' => $description
            ]);
            header('Location: /income/create'); // 成功ページへリダイレクト

        } catch (PDOException $e) { // DBエラー発生時はここに飛ぶ
            die('データベースエラー: ' . htmlspecialchars($e->getMessage())); // エラーメッセージを安全に表示して終了
        }
    }

    // エラーメッセージ付きでリダイレクトするヘルパー関数（仮実装）
    private function redirectWithError($msg)
    {
        $_SESSION['error'] = $msg; // セッションにエラーメッセージを保存
        header('Location: /income/create'); // 収入作成ページに戻す（エラー表示はview側で実装想定）
        exit; // 処理終了
    }
}
