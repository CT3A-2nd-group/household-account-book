<?php
class LogoutController
{
    public function logout()
    {
        session_start();

        // セッションの変数を全て削除
        $_SESSION = [];

        // セッションクッキーも削除
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // セッションの破棄
        session_destroy();

        // ログイン画面へリダイレクト
        header('Location: /login');
        exit;
    }
}