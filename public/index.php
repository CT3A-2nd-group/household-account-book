<?php
//　まず初めに読み込まれるファイルがこのファイル
date_default_timezone_set('Asia/Tokyo');

// 別ファイルに書かれた処理の実行　同じファイルを一度だけ読み込む（ファイルがない場合、実行が停止）
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/HomeController.php';

//　インスタンスの生成
$controller = new HomeController();
//　生成したインスタンスのindexメソッドの呼び出し
$controller->index();