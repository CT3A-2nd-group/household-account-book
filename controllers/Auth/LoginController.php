<?php
class LoginController extends BaseController
{
    /* ① GET /login で呼ぶ */
    public function showForm(): void
    {
        $extraCss = '<link rel="stylesheet" href="/css/login.css">';

        // $title, $extraCss をビューに渡す
        $this->render('auth/login', [
            'title' => 'ログイン',
            'extraCss' => $extraCss
        ]);
    }

    /* ② POST /login で呼ぶ（認証） */
    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $this->redirect('/login?error=' . urlencode('ユーザー名とパスワードを入力してください。'));
        }

        $stmt = $this->pdo->prepare(
            'SELECT id, password_hash, is_admin FROM users WHERE username = :username'
        );
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->redirect('/login?error=' . urlencode('ユーザー名またはパスワードが正しくありません。'));
        }

        $_SESSION['user_id']  = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];

        $this->redirect($user['is_admin'] ? '/admin/category/create' : '/home');
    }
}
