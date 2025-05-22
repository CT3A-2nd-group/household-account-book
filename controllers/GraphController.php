<?php
    class GraphController {
        //収入データをJSONに変換
        public function incomeGraph() {
            session_start();
            include __DIR__ . '/../config/database.php';

            $stmt = $pdo->query(
                "SELECT DATE_FORMAT(input_date, '%Y') AS year, DATE_FORMAT(input_date, '%m') AS month, SUM(amount) AS total
                FROM incomes
                GROUP BY year, month
                ORDER BY year, month;"
            );

            $allMonths = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            $rawData = [];
            $maxTotal = 0;

            // 一旦すべてのデータを配列に格納
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $year = $row['year'];
                $month = $row['month'];
                $amount = (float)$row['total'];

                $rawData[$year][$month] = $amount;

                if ($amount > $maxTotal) {
                    $maxTotal = $amount;
                }
            }

            // 欠けている月を null で補完
            $dataByYear = [];
            foreach ($rawData as $year => $months) {
                $dataByYear[$year] = [
                    'labels' => $allMonths,
                    'data' => []
                ];

                foreach ($allMonths as $month) {
                    $dataByYear[$year]['data'][] = $months[$month] ?? null;
                }
            }

            // グラフの最大値（200000 以上なら +30000）
            $maxValue = ($maxTotal > 200000) ? $maxTotal + 30000 : 200000;

            $response = [
                'years' => $dataByYear,
                'max' => $maxValue
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        }

        public function expendituresGraph() {
            session_start();
            include __DIR__ . '/../config/database.php';

            $stmt = $pdo->query(
                "SELECT DATE_FORMAT(input_date, '%Y') AS year, DATE_FORMAT(input_date, '%m') AS month, SUM(amount) AS total
                FROM expenditures
                GROUP BY year, month
                ORDER BY year, month;"
            );

            $allMonths = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            $rawData = [];
            $maxTotal = 0;

            // 一旦すべてのデータを配列に格納
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $year = $row['year'];
                $month = $row['month'];
                $amount = (float)$row['total'];

                $rawData[$year][$month] = $amount;

                if ($amount > $maxTotal) {
                    $maxTotal = $amount;
                }
            }

            // 欠けている月を null で補完
            $dataByYear = [];
            foreach ($rawData as $year => $months) {
                $dataByYear[$year] = [
                    'labels' => $allMonths,
                    'data' => []
                ];

                foreach ($allMonths as $month) {
                    $dataByYear[$year]['data'][] = $months[$month] ?? null;
                }
            }

            // グラフの最大値（200000 以上なら +30000）
            $maxValue = ($maxTotal > 200000) ? $maxTotal + 30000 : 200000;

            $response = [
                'years' => $dataByYear,
                'max' => $maxValue
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        }

        public function categoriesGraph(){
            session_start();
            include __DIR__ . '/../config/database.php';

            $stmt = $pdo->query(
                "SELECT DATE_FORMAT(input_date, '%Y') AS year, DATE_FORMAT(input_date, '%m') AS month,
                    SUM(amount) AS total,categories.name as categoryName
                    FROM incomes 
                    INNER JOIN categories
                    ON incomes.category_id = categories.id
                    group by category_id,year,month
                    order by category_id,year,month;"
            );

            $allMonths = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            $rawData = [];
            // 一旦すべてのデータを配列に格納
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $year = $row['year'];
                $month = $row['month'];
                $amount = (float)$row['total'];
                $name = $row['categoryName'];

                $rawData[$name][$year][$month] = $amount;
            }

            // 欠けている月を null で補完
            $dataByCategory = [];
            foreach ($rawData as $categoryName => $years) {
                $dataByCategory[$categoryName] = [];

                foreach ($years as $year => $months) {
                    $dataByCategory[$categoryName][$year] = [
                        'labels' => $allMonths,
                        'data' => []
                    ];

                    foreach ($allMonths as $month) {
                        $dataByCategory[$categoryName][$year]['data'][] = $months[$month] ?? null;
                    }
                }
            }

            header('Content-Type: application/json');
            echo json_encode($dataByCategory);
        }
    }
?>
