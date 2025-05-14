<?php
class RegisterController
{
    public function register()
    {
        session_start();
        require_once __DIR__ . '/../config/database.php';

        // 入力チェック
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // バリデーション
        if ($username === '' || $password === '') {
            $this->redirectWithError('全ての項目を入力してください。');
        }

        if (strlen($username) > 255) {
            $this->redirectWithError('ユーザー名は255文字以内にしてください。');
        }

        if (strlen($password) < 4) {
            $this->redirectWithError('パスワードは4文字以上で入力してください。');
        }

        // ユーザー名の重複チェック
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if ($stmt->fetch()) {
            $this->redirectWithError('このユーザー名は既に使われています。');
        }

        // パスワードのハッシュ化
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // 登録処理
        $stmt = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password_hash', $passwordHash);

        if ($stmt->execute()) {
            // 登録成功すると自動ログイン
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['is_admin'] = 0;
            header('Location: /home');
            exit;
        } else {
            $this->redirectWithError('登録中にエラーが発生しました。');
        }
    }

    //　エラーメッセージのメソッド化
    private function redirectWithError($message)
    {
        header('Location: /register?error=' . urlencode($message));
        exit;
    }
}
