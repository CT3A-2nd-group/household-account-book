<?php
    //DBのデータをJSONに変換
    $pdo = new PD0('mysql:host=localhost;dbname=testdb','user','pass');
    //SQLを記述する
    $stmt = $pdo->query("");

    //あとで変数名をカエル
    $months = [];
    $sales = [];

    while($row = $stmt->fetch(PD0::FETCH_ASSOC)){
        $months[] = $row['month'];
        $sales[] = $row['total_sales'];
    }

    $data = ['labels' => $months,'data' => $sales];

    header('Content-Type: application/json');
    echo json_encode($data);
?>