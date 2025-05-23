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
            if (!isset($_SESSION['user_id'])) $this->redirect('/login'); // ログインしていなければリダイレクト
            $this->render('graph/line', array_merge(
                ['title' => '収入・支出グラフ'] // タイトル付きでビューを表示
            ));
        }

        // 指定されたテーブル（収入または支出）から月別集計データを作成
        private function aggregateByMonth(string $table): array {
            // SQLで年と月ごとに金額を合計（昇順で並べる）
            $stmt = $this->pdo->query("
                SELECT DATE_FORMAT(input_date,'%Y') y,      -- 年
                       DATE_FORMAT(input_date,'%m') m,      -- 月
                       SUM(amount) total                    -- 月ごとの合計
                FROM {$table}
                GROUP BY y, m
                ORDER BY y, m
            ");

            // 1月〜12月までの全ての月を表す配列
            $allMonths = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            $raw   = [];   // 生データを格納
            $max   = 0;    // 最大金額を記録（グラフのスケールに使用）

            // SQLの結果を1件ずつ取り出し、配列に格納
            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $raw[$r['y']][$r['m']] = (float)$r['total']; // 年と月をキーに格納
                $max = max($max, (float)$r['total']);        // 最大金額を更新
            }

            $years = []; // 年ごとのラベルとデータをまとめる配列
            foreach ($raw as $y => $months) {
                $years[$y] = [
                    'labels' => $allMonths, // ラベルは常に1月〜12月
                    'data'   => array_map(fn($m) => $months[$m] ?? null, $allMonths) // 月がない場合はnullを挿入
                ];
            }

            // グラフ描画時に使用する最大金額を調整して返却
            return [
                'years' => $years,
                'max'   => ($max > 200_000) ? $max + 30_000 : 200_000 // 最大値が一定以上なら余白を加える
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
