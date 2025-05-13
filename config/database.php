<?php
// 使いやすいよう変数に格納
$host = 'localhost';
$dbname = 'finance_manager';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$user = 'admin';
$pass = 'password';

try {
    $pdo = new PDO($dsn, $user, $pass);
    // エラーが発生した時に例外処理をするよう設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 接続に失敗した時のエラーメッセージを表示
    die('データベース接続失敗: ' . $e->getMessage());
}

