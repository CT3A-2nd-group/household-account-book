<?php
    class GraphController {
        //収入データをJSONに変換
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

            // 200000 or 最大月合計 + 20000
            $maxStmt = $pdo->query(
                "SELECT MAX(month_total) AS max_total
                 FROM (
                     SELECT SUM(amount) AS month_total
                     FROM incomes
                     GROUP BY DATE_FORMAT(input_date, '%Y-%m')
                 ) AS monthly_totals"
            );
            $maxRow = $maxStmt->fetch(PDO::FETCH_ASSOC);

            if (isset($maxRow['max_total'])) {
                $maxTotal = (float)$maxRow['max_total'];
                if ($maxTotal > 200000) {
                    $maxValue = $maxTotal + 30000;
                } else {
                    $maxValue = 200000;
                }
            } else {
                $maxValue = 200000; // データがない場合のデフォルト
            }

            $data = [
                'labels' => $months,
                'data' => $sales,
                'max' => $maxValue
            ];

            header('Content-Type: application/json');
            echo json_encode($data);
        }

        //支出データをJSONに変換
        public function expendituresGraph(){
            session_start();
            include __DIR__ . '/../config/database.php';

            $stmt = $pdo->query(
                "SELECT DATE_FORMAT(input_date, '%Y-%m') AS month, SUM(amount) AS total
                FROM expenditures
                GROUP BY month
                ORDER BY month;"
            );

            $months = [];
            $sales = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $months[] = $row['month'];
                $sales[] = (float)$row['total']; // 数値に変換
            }

            // 200000 or 最大月合計＋30000
            $maxStmt = $pdo->query(
                "SELECT MAX(month_total) AS max_total
                 FROM (
                     SELECT SUM(amount) AS month_total
                     FROM expenditures
                     GROUP BY DATE_FORMAT(input_date, '%Y-%m')
                 ) AS monthly_totals"
            );
            $maxRow = $maxStmt->fetch(PDO::FETCH_ASSOC);

            if (isset($maxRow['max_total'])) {
                $maxTotal = (float)$maxRow['max_total'];
                if ($maxTotal > 200000) {
                    $maxValue = $maxTotal + 30000;
                } else {
                    $maxValue = 200000;
                }
            } else {
                $maxValue = 200000; // データがない場合のデフォルト
            }

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
