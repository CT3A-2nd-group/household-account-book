<?php
// グラフ表示用のデータを提供するコントローラークラス
class GraphCircleController extends BaseController {

    // 収入データを月別に集計し、JSONとして返す
    public function incomeCircle(): void {
        $data = $this->aggregateByMonth('incomes');
        $this->json($data);
    }

    // 支出データを月別に集計し、JSONとして返す
    public function expenditureCircle(): void {
        $data = $this->aggregateByMonth('expenditures');
        $this->json($data);
    }

    // グラフページの表示処理（ユーザーがログインしていなければログインページへリダイレクト）
    public function view(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $extraCss = implode("\n", [
            '<link rel="stylesheet" href="/css/Graph/graph.css">',
            '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />'
        ]);

        $extraJs = implode("\n", [
            '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>',
            '<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>',
            '<script src="/js/Graph/graphCircle.js" defer></script>'
        ]);

        $this->render('graph/circle', [
            'title'    => '収入・支出グラフ',
            'extraCss' => $extraCss,
            'extraJs'  => $extraJs
        ]);
    }

    // 指定されたテーブル（収入または支出）から月別集計データを作成
    private function aggregateByMonth(string $table): array {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            exit('ログインしていません');
        }

        $userId = $_SESSION['user_id'];

        // SQLでカテゴリと年と月ごとに金額を合計（ユーザーごとに別データ、カテゴリと年月で昇順で並べる）
        $stmt = $this->pdo->prepare("
            SELECT DATE_FORMAT(input_date,'%Y') as y,
                   DATE_FORMAT(input_date,'%m') as m,
                   SUM(amount) as total,
                   categories.name as categoryName
            FROM {$table}
            INNER JOIN categories ON {$table}.category_id = categories.id
            WHERE {$table}.user_id = :user_id
            GROUP BY category_id, y, m
            ORDER BY category_id, y, m
        ");
        $stmt->execute([':user_id' => $userId]);

        $result = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $year = $row['y'];
            $month = $row['m'];
            $amount = (float)$row['total'];
            $category = $row['categoryName'];

            // ラベルとデータを順番に格納
            $result[$category][$year]['labels'][] = $month;
            $result[$category][$year]['data'][] = $amount;
        }

        return $result;
    }

    // 配列をJSONとして出力し、スクリプトを終了する
    private function json(array $payload): never {
        header('Content-Type: application/json'); // ヘッダーをJSONに設定
        echo json_encode($payload, JSON_UNESCAPED_UNICODE); // JSON形式で出力（日本語も対応）
        exit; // 処理を終了（これ以降のコードは実行されない）
    }
}
?>
