<?php
class IncomeController
{
    // 収入入力フォームの表示
    public function a1()
    {
        session_start(); // セッション開始（ログインユーザー判定に必要）
        if (!isset($_SESSION['user_id'])) { // ログインしていなければログインページへリダイレクト
            header('Location: /login');
            exit; // 処理終了
        }
        require_once __DIR__ . '/../config/database.php'; // DB接続設定ファイルを読み込み（ここはa1ではなくb1でだけで良いかも）
        require_once __DIR__ . '/../views/income_form.php'; // 収入入力フォームのHTMLを読み込み表示
    }

    // 収入登録処理
    public function b1()
    {
        session_start(); // セッション開始（ログイン状態の確認・ユーザーID取得に必要）
        ob_start(); // 出力バッファリング開始（ヘッダー操作の安全確保のため）

        if (!isset($_SESSION['user_id'])) { // ログインしていなければログインページへリダイレクト
            header('Location: /login');
            exit;
        }

        global $pdo; // database.phpで定義したグローバル変数$pdoをメソッド内で使用する宣言
        require_once __DIR__ . '/../config/database.php'; // DB接続用設定を読み込み$pdoを初期化

        $id = $_SESSION['user_id']; // ログインユーザーIDを取得
        $category = $_POST['category'] ?? null; // フォームから送信された収入カテゴリー（存在しなければnull）
        $amount = $_POST['amount'] ?? null; // 収入金額
        $description = $_POST['description'] ?? null; // メモなど説明
        $time = date('Y-m-d'); // 登録日を今日の日付でセット

        // 入力チェック：カテゴリーが空、金額が数字でないか0以下ならエラー
        if (
            empty($category) ||
            !is_numeric($amount) || $amount <= 0
        ) {
            $this->redirectWithError('すべての項目を正しく入力してください。'); // エラー用メソッドでリダイレクト
        }

        try {
            // SQL文を準備。プレースホルダを使いSQLインジェクション対策
            $sql = "INSERT INTO incomes (user_id, category_id, input_date, amount, description)
                    VALUES (:id, :category, :time, :amount, :description)";
            $stmt = $pdo->prepare($sql); // SQL文の準備

            $category = (int)$category; // 型変換：カテゴリーは整数
            $amount = number_format((float)$amount, 2, '.', ''); // 金額は小数点2桁の文字列でフォーマット

            // プレースホルダに値をバインド（型も指定）
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':category', $category, PDO::PARAM_INT);
            $stmt->bindParam(':time', $time, PDO::PARAM_STR);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);

            if ($stmt->execute()) { // SQL実行成功したら
                if (ob_get_level()) { // バッファに何かあればクリア
                    ob_clean();
                }
                header('Location: /income/create'); // 成功ページへリダイレクト
                exit;
            } else {
                $this->redirectWithError('登録に失敗しました。'); // 実行失敗時はエラー処理
            }
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
