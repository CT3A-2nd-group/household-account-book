<?php
class HomeController extends BaseController
{
    public function index(): void
    {
        $this->requireLogin();

        $userId   = $_SESSION['user_id'];
        $isAdmin  = $_SESSION['is_admin'] ?? 0;
        $username = $this->getUsername($userId);
        $extraCss  = '<link rel="stylesheet" href="/css/home.css">';

        // header / footer を自動付与して home ビューへ
        $this->render('home', array_merge(
            compact('username', 'isAdmin'),
            [
                'title'     => 'ホーム',
                'extraCss'  => $extraCss,
            ]
        ));
    }

    private function getUsername(int $userId): string
    {
        $stmt = $this->pdo->prepare(
            'SELECT username FROM users WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $userId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['username'] ?? 'ゲスト';
    }
}