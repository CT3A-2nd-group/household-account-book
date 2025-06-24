<?php
class adminController extends BaseController
{
    public function registerAdmin(): void
    {
        // 既存の管理者であればこの画面を利用できないようにする
        $this->forbidAdmin();
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->pdo->prepare("INSERT INTO users (username, password_hash, is_admin) VALUES (?, ?, 1)");
            try {
                $stmt->execute([$username, $hash]);
                $message = '✅ 管理者ユーザーを登録しました。';
            } catch (PDOException $e) {
                $message = '❌ エラー: ' . htmlspecialchars($e->getMessage());
            }
        }

        $this->render('admin/temp_admin_register', [
            'title' => '管理者登録',
            'message' => $message,
        ]);
    }
}
