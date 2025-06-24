<?php
class SettingController extends BaseController
{
    public function index(): void
    {
        $this->requireLogin();
        $this->forbidAdmin();

        $user = $this->getUserData($_SESSION['user_id']);

        $extraCss = '<link rel="stylesheet" href="/css/Auth/settings.css">';

        $this->render('auth/setting', [
            'title' => 'アカウント設定',
            'extraCss' => $extraCss,
            'user' => $user,
        ]);
    }

    public function updateUsername(): void
    {
        $this->requireLogin();
        $this->forbidAdmin();

        $newUsername = trim($_POST['username'] ?? '');
        if ($newUsername === '') {
            $this->indexWithMessage(null, 'ユーザー名を入力してください');
            return;
        }

        // 重複チェック
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username AND id != :id');

        $stmt->execute([
            ':username' => $newUsername,
            ':id' => $_SESSION['user_id']
        ]);
        
        if ($stmt->fetchColumn() > 0) {
            $this->indexWithMessage(null, 'このユーザー名は既に使われています');
            return;
        }

        $stmt = $this->pdo->prepare('UPDATE users SET username = :username WHERE id = :id');
        $stmt->execute([
            ':username' => $newUsername,
            ':id' => $_SESSION['user_id']
        ]);

        $this->indexWithMessage('ユーザー名を更新しました');
    }

    public function changePassword(): void
    {
        $this->requireLogin();
        $this->forbidAdmin();

        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($new !== $confirm) {
            $this->indexWithMessage(null, '新しいパスワードが一致しません');
            return;
        }

        $stmt = $this->pdo->prepare('SELECT password_hash FROM users WHERE id = :id');
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($current, $user['password_hash'])) {
            $this->indexWithMessage(null, '現在のパスワードが正しくありません');
            return;
        }

        $newHash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare('UPDATE users SET password_hash = :hash WHERE id = :id');
        $stmt->execute([':hash' => $newHash, ':id' => $_SESSION['user_id']]);

        $this->indexWithMessage('パスワードを変更しました');
    }



    private function getUserData(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT username FROM users WHERE id = :id');
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    private function indexWithMessage(?string $success = null, ?string $error = null): void
    {
        $user = $this->getUserData($_SESSION['user_id']);
        $data = [
            'title' => 'アカウント設定',
            'extraCss' => '<link rel="stylesheet" href="/css/Auth/settings.css">',
            'user' => $user
        ];
        if ($success) $data['success'] = $success;
        if ($error)   $data['error'] = $error;

        $this->render('auth/setting', $data);
    }

    /**
     * 管理者ユーザーが一般向け設定にアクセスした場合は拒否する
     */
    private function forbidAdmin(): void
    {
        if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
            http_response_code(403);
            exit('管理者アカウントでは実行できません');
        }
    }

    public function deleteAccount(): void
    {
        $this->requireLogin(); // ログイン確認（実装済み前提）
        $this->forbidAdmin();
        // セッションからユーザーIDを取得
        $userId = $_SESSION['user_id']; 
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);

        // セッション削除（ログアウト処理）
        session_unset();
        session_destroy();

        // ログインページへリダイレクト
        header('Location: /login');
        exit;
    }

}
