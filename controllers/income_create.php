<?php
session_start();
require_once __DIR__ . '/../config/database.php';
//SQLlogin

$category = $_POST["category"];
//カテゴリ
$amount = $_POST["amount"];
//金額
$description = $_POST["description"];
//補足
$Time = time();
//時刻取得

$stmt = "INSERT INTO incomes (user_id,category_id,input_date,amount,description)
        VALUES($id,$category,$Time,$amount,$description)";
//SQL文

$stmt->execute();
//命令実行
?>