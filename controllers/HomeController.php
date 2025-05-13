<?php
class HomeController {
    public function index() {
        global $pdo;

        $message1 = "家計簿アプリ（仮）へようこそ！";
        // タイムゾーンを日本の標準時間（JST）に設定
        date_default_timezone_set('Asia/Tokyo');

        // PHPの現在時刻を取得
        $message2 = "時（JST）：" . date('Y-m-d H:i:s');
        //　home.phpの読み込み（ファイルがない場合警告メッセージ）
        
        try {
            $stmt = $pdo->query("SELECT NOW()");
            $row = $stmt->fetch();
            $message3 = "DB接続成功：現在の日時は " . $row[0];
            $message4 = "MariaDBはデフォルトでUTCを使うので、DBの時間だと日本時間とずれるので注意を";
        } catch (PDOException $e) {
            $message3 = "DB：" . $e->getMessage();
        }

        include __DIR__ . '/../views/home.php';
    }
}
