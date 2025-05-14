<?php
class HomeController
{
    public function index()
    {
        session_start();

        // ログインしていない場合はログイン画面へ
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // 管理者かどうかの確認
        $isAdmin = $_SESSION['is_admin'] ?? 0;
        $username = $this->getUsername($_SESSION['user_id']);

        // ホーム画面を表示
        require_once __DIR__ . '/../views/home.php';
    }

    private function getUsername($userId)
    {
        require __DIR__ . '/../config/database.php';

        $stmt = $pdo->prepare('SELECT username FROM users WHERE id = :id');
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['username'] : 'ゲスト';
    }
}