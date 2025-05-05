<?php
class HomeController {
    public function index() {
        $message = "家計簿アプリへようこそ！";
        include __DIR__ . '/../views/home.php';
    }
}