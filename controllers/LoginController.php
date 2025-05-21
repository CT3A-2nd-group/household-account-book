<?php
class LoginController
{
    public function login()
    {
        session_start();
        require_once __DIR__ . '/../config/database.php';

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // 入力バリデーション
        if ($username === '' || $password === '') {
            $this->redirectWithError('ユーザー名とパスワードを入力してください。');
        }

        // ユーザー取得
        $stmt = $pdo->prepare('SELECT id, password_hash, is_admin FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        //　パスワードと一致するか
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->redirectWithError('ユーザー名またはパスワードが正しくありません。');
        }

        // ログイン成功 → セッションに保存
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];

        if ((int)$user['is_admin'] === 1) {
            header('Location: /admin/category/create');  // 管理者専用ページへ
        } else {
            header('Location: /home'); // 一般ユーザーはホームへ
        }
        exit;
    }

    private function redirectWithError($message)
    {
        header('Location: /login?error=' . urlencode($message));
        exit;
    }
}
