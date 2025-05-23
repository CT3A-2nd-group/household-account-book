<?php
class GraphController extends BaseController
{
    /** 収入データ (JSON) */
    public function income(): void
    {
        $data = $this->aggregateByMonth('incomes');
        $this->json($data);
    }

    /** 支出データ (JSON) */
    public function expenditure(): void
    {
        $data = $this->aggregateByMonth('expenditures');
        $this->json($data);
    }

    /** 折れ線・円グラフ HTML（共通ビュー） */
    public function view(): void
    {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        $this->render('graph/line');          // 旧 graph_line.php を移動
    }

    /* ---------- private ---------- */

    /** incomes / expenditures を年-月集計して整形 */
    private function aggregateByMonth(string $table): array
    {
        $stmt = $this->pdo->query("
            SELECT DATE_FORMAT(input_date,'%Y') y,
                   DATE_FORMAT(input_date,'%m') m,
                   SUM(amount) total
            FROM {$table}
            GROUP BY y, m
            ORDER BY y, m
        ");

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

    private function json(array $payload): never
    {
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
