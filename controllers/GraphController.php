<?php
class GraphController {
    public function graph() {
        session_start();
        include __DIR__ . '/../config/database.php';

        $stmt = $pdo->query("SELECT input_date, amount FROM incomes");

        $months = [];
        $sales = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $months[] = $row['input_date'];
            $sales[] = (float)$row['amount']; // ← 数値化も忘れず
        }

        $data = [
            'labels' => $months,
            'data' => $sales
        ];

        // ヘッダーでJSONであることを明示
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
?>
