<?php
    // グラフ表示用のデータを提供するコントローラークラス
    class GraphLineController extends BaseController {

        // 収入データを月別に集計し、JSONとして返す
        public function incomeLine(): void {
            $data = $this->aggregateByMonth('incomes'); // 'incomes' テーブルから月別集計
            $this->json($data); // 結果をJSONとして出力
        }

        // 支出データを月別に集計し、JSONとして返す
        public function expenditureLine(): void {
            $data = $this->aggregateByMonth('expenditures'); // 'expenditures' テーブルから月別集計
            $this->json($data); // 結果をJSONとして出力
        }

        // グラフページの表示処理（ユーザーがログインしていなければログインページへリダイレクト）
        public function view(): void {
        $this->requireLogin();

        $extraCss = implode("\n", [
            '<link rel="stylesheet" href="/css/Graph/graph.css">',
            '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />'
        ]);

        $extraJs = implode("\n", [
            '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>',
            '<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>',
            '<script src="/js/Graph/graphLine.js" defer></script>'
        ]);


        $this->render('graph/line', [
            'title'    => '折れ線グラフ',
            'extraCss' => $extraCss,
            'extraJs'  => $extraJs
        ]);
    }

        // 指定されたテーブル（収入または支出）から月別集計データを作成
        private function aggregateByMonth(string $table): array {
            $this->requireLogin(true);

            $userId = $_SESSION['user_id'];

            // SQLで年と月ごとに金額を合計（ユーザーごと、昇順で並べる）
            $stmt = $this->pdo->prepare("
                SELECT DATE_FORMAT(input_date,'%Y') y,
                    DATE_FORMAT(input_date,'%m') m,
                    SUM(amount) total
                FROM {$table}
                WHERE user_id = :user_id
                GROUP BY y, m
                ORDER BY y, m
            ");
            $stmt->execute(['user_id' => $userId]);

            $allMonths = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            $raw   = [];
            $max   = 0;

            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $raw[$r['y']][$r['m']] = (float)$r['total'];
                $max = max($max, (float)$r['total']);
            }

            $years = [];
            foreach ($raw as $y => $months) {
                $years[$y] = [
                    'labels' => $allMonths,
                    'data'   => array_map(fn($m) => $months[$m] ?? null, $allMonths)
                ];
            }

            return [
                'years' => $years,
                'max'   => ($max > 200_000) ? $max + 30_000 : 200_000
            ];
        }


        // 配列をJSONとして出力し、スクリプトを終了する
        private function json(array $payload): never {
            header('Content-Type: application/json'); // ヘッダーをJSONに設定
            echo json_encode($payload, JSON_UNESCAPED_UNICODE); // JSON形式で出力（日本語対応）
            exit; // 処理を終了
        }
        
    }
?>
