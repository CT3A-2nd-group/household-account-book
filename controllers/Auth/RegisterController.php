<?php
class RegisterController extends BaseController
{
    /* GET /register ─ 登録フォームを表示 */
    public function showForm(): void
    {
        $this->render('auth/register', ['title' => 'ユーザー登録']);
    }

    /* POST /register ─ 実際の登録処理 */
    public function register(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        /* ── バリデーション ───────────────────────── */
        if ($username === '' || $password === '') {
            $this->redirect('/register?error=' . urlencode('全ての項目を入力してください。'));
        }
        if (strlen($username) > 255) {
            $this->redirect('/register?error=' . urlencode('ユーザー名は255文字以内にしてください。'));
        }
        if (strlen($password) < 4) {
            $this->redirect('/register?error=' . urlencode('パスワードは4文字以上で入力してください。'));
        }

        /* ── 重複チェック ─────────────────────────── */
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        if ($stmt->fetch()) {
            $this->redirect('/register?error=' . urlencode('このユーザー名は既に使われています。'));
        }

        /* ── 登録 ────────────────────────────────── */
        $ok = $this->pdo->prepare(
            'INSERT INTO users (username, password_hash) VALUES (:u, :h)'
        )->execute([
            ':u' => $username,
            ':h' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        if (!$ok) {
            $this->redirect('/register?error=' . urlencode('登録中にエラーが発生しました。'));
        }

        /* ── 成功：セッションに保存してホームへ ──── */
        $_SESSION['user_id']  = $this->pdo->lastInsertId();
        $_SESSION['is_admin'] = 0;
        $this->redirect('/home');
    }
}
