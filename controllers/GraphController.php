<?php
    class GraphController {
        public function incomeGraph() {
            session_start();
            include __DIR__ . '/../config/database.php';

            $stmt = $pdo->query(
                "SELECT DATE_FORMAT(input_date, '%Y-%m') AS month, SUM(amount) AS total
                FROM incomes
                GROUP BY month
                ORDER BY month;"
            );

            $months = [];
            $sales = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $months[] = $row['month'];
                $sales[] = (float)$row['total']; // 数値に変換
            }

            // 最大月合計 + 20000
            $maxStmt = $pdo->query(
                "SELECT MAX(month_total) AS max_total
                FROM (
                    SELECT SUM(amount) AS month_total
                    FROM incomes
                    GROUP BY DATE_FORMAT(input_date, '%Y-%m')
                ) AS monthly_totals"
            );
            $maxRow = $maxStmt->fetch(PDO::FETCH_ASSOC);
            $maxValue = isset($maxRow['max_total']) ? (float)$maxRow['max_total'] + 30000 : 0;

            $data = [
                'labels' => $months,
                'data' => $sales,
                'max' => $maxValue
            ];

            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }
?>
