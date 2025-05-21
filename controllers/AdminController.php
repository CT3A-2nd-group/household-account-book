<?php
//あとでファイルごと消す
class AdminController {
    public function store() {
        require __DIR__ . '/../config/database.php';

        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, is_admin) VALUES (?, ?, 1)');
        $stmt->execute([$username, $password]);

        echo '管理者ユーザーを作成しました。<a href="/login">ログイン</a>';
    }
}