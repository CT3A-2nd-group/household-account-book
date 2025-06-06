<?php
class BaseController
{
    protected PDO $pdo;

    public function __construct()
    {
        // index.php で database.php を読み込んで $pdo を生成している前提
        global $pdo;
        $this->pdo = $pdo;

        // 全コントローラ共通でセッションを開始
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * ログイン必須ページのためのチェック
     * @param bool $api true の場合は 403 を返して終了する
     */
    protected function requireLogin(bool $api = false): void
    {
        if (!isset($_SESSION['user_id'])) {
            if ($api) {
                http_response_code(403);
                exit('ログインしていません');
            }
            $this->redirect('/login');
        }
    }

    /** ビューの読み込み（layouts/header と footer を自動で付ける） */
    protected function render(string $viewPath, array $data = []): void
    {
        extract($data, EXTR_SKIP);                   // $categories などを変数化
        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/' . $viewPath . '.php';
        include __DIR__ . '/../views/layouts/footer.php';
    }

    /** シンプルリダイレクト */
    protected function redirect(string $url): never
    {
        header("Location: {$url}");
        exit;
    }
}
