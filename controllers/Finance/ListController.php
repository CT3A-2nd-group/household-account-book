<?php
    class ListController extends BaseController{
        public function __construct() {
            parent::__construct();
        }
        public function Listview() {
            $expenditures = $this->ExpenditureList($_SESSION['user_id']);
            $incomes = $this->IncomeList($_SESSION['user_id']);

            $extraCss = implode("\n", [
                '<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />',
                '<link rel="stylesheet" href="/css/finance.css">'
            ]);

            $extraJs = implode("\n", [
                '<script src="/js/list.js" defer></script>'//まだ中身はない
            ]);

            $this->render('finance/List_form', [
                'expenditures' => $expenditures,
                'incomes' => $incomes,
                'title' => '収支一覧表',
                'extraCss' => $extraCss,
                'extraJs'  => $extraJs
            ]);
        }
        public function IncomeList($user_id){

            $stmt = $this->pdo->prepare("
                SELECT i.id, i.input_date, c.name AS category_name, i.amount, i.description
                FROM incomes i
                JOIN categories c ON i.category_id = c.id
                WHERE i.user_id = :user_id
                ORDER BY i.input_date DESC
            ");

            $stmt->execute([':user_id' => $user_id]);


            $incomes = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $incomes[] = $row;
            }
            return $incomes;
        }
        public function ExpenditureList($user_id){

            $stmt = $this->pdo->prepare("
                SELECT e.id, e.input_date, c.name AS category_name, e.amount, e.star_rate, e.is_waste, e.description
                FROM expenditures e
                JOIN categories c ON e.category_id = c.id
                WHERE e.user_id = :user_id
                ORDER BY e.input_date DESC
            ");
            $stmt->execute([':user_id' => $user_id]);

            $expenditures = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $expenditures[] = $row;
            }
            return $expenditures;
        }

        public function DeleteList() {
            if (
                isset($_POST['delete_ids'], $_POST['target_type']) &&
                is_array($_POST['delete_ids']) &&
                in_array($_POST['target_type'], ['income', 'expenditure'], true)
            ) {
                $delete_ids = $_POST['delete_ids'];
                $target_type = $_POST['target_type'];
                $table = $target_type === 'income' ? 'incomes' : 'expenditures';

                $placeholders = rtrim(str_repeat('?,', count($delete_ids)), ',');
                $sql = "DELETE FROM {$table} WHERE id IN ($placeholders) AND user_id = ?";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([...$delete_ids, $_SESSION['user_id']]);

                $_SESSION['success'] = '選択した' . ($target_type === 'income' ? '収入' : '支出') . 'を削除しました。';
            } else {
                $_SESSION['error'] = '削除対象が選択されていません。';
            }

            $this->redirect('/List/view');
            exit;
        }
        public function UpdateList(): void
        {
            $userId = $_SESSION['user_id'] ?? null;
            if (!$userId) {
                $this->redirect('/login');
            }

            $items = $_POST['items'] ?? [];

            foreach ($items as $id => $data) {
                $amount = $data['amount'] ?? null;
                $desc = $data['description'] ?? null;

                // 判定: 収入 or 支出
                $table = $this->isIncome($id, $userId) ? 'incomes' : 'expenditures';

                $stmt = $this->pdo->prepare("UPDATE {$table} SET amount = :amount, description = :desc WHERE id = :id AND user_id = :user_id");
                $stmt->execute([
                    ':amount' => $amount,
                    ':desc' => $desc,
                    ':id' => $id,
                    ':user_id' => $userId,
                ]);
            }

            $this->redirect('/List/view');
        }

        private function isIncome(int $id, int $userId): bool
        {
            // 簡易的に incomes テーブルに存在すれば収入と判断
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM incomes WHERE id = :id AND user_id = :user_id");
            $stmt->execute([':id' => $id, ':user_id' => $userId]);
            return (bool)$stmt->fetchColumn();
        }


    }

    
?>
