<?php
class ListController extends BaseController{
    public function __construct() {
        parent::__construct();
    }

    public function Listview() {
        $this->requireLogin();

        $incomes = $this->IncomeList($_SESSION['user_id']);
        $expenditures = $this->ExpenditureList($_SESSION['user_id']);
        $extraCss = implode("\n", [
            '<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />',
            '<link rel="stylesheet" href="/css/Finance/finance.css">'
        ]);
        $extraJs = implode("\n", [
            '<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>'
        ]);

        $this->render('finance/List_form', [
            'title' => '収支一覧',
            'incomes' => $incomes,
            'expenditures' => $expenditures,
            'extraCss' => $extraCss,
            'extraJs'  => $extraJs
        ]);
    }

    public function IncomeList($user_id){
        $stmt = $this->pdo->prepare("
            SELECT i.id, i.input_date, c.id AS category_id, c.name AS category_name, i.amount, i.description
            FROM incomes i
            JOIN categories c ON i.category_id = c.id
            WHERE i.user_id = :user_id
            ORDER BY i.input_date DESC
        ");
        $stmt->execute([':user_id' => $user_id]);

        $incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $incomes;
    }

    public function ExpenditureList($user_id){
        $stmt = $this->pdo->prepare("
            SELECT e.id, e.input_date, c.id AS category_id, c.name AS category_name, e.amount, e.star_rate, e.is_waste, e.description
            FROM expenditures e
            JOIN categories c ON e.category_id = c.id
            WHERE e.user_id = :user_id
            ORDER BY e.input_date DESC
        ");
        $stmt->execute([':user_id' => $user_id]);

        $expenditures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $expenditures;
    }

    public function getIncomeListAsJson(): void
    {
        $userId = $_SESSION['user_id'];
        $stmt = $this->pdo->prepare("
            SELECT i.id, i.input_date, c.id AS category_id, c.name AS category_name, i.amount, i.description
            FROM incomes i
            JOIN categories c ON i.category_id = c.id
            WHERE i.user_id = :user_id
            ORDER BY i.input_date DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        $incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($incomes);
        exit;
    }

    public function getExpenditureListAsJson(): void
    {
        $userId = $_SESSION['user_id'];
        $stmt = $this->pdo->prepare("
            SELECT e.id, e.input_date, c.id AS category_id, c.name AS category_name, e.amount, e.star_rate, e.is_waste, e.description
            FROM expenditures e
            JOIN categories c ON e.category_id = c.id
            WHERE e.user_id = :user_id
            ORDER BY e.input_date DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        $expenditures = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($expenditures);
        exit;
    }

    public function getIncomeCategoriesAsJson(): void
    {
        // カテゴリはユーザー共通の想定なので user_id 条件なし
        $stmt = $this->pdo->prepare("SELECT id, name FROM categories WHERE type = 'income'");
        $stmt->execute();

        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    public function getExpenditureCategoriesAsJson(): void
    {
        $stmt = $this->pdo->prepare("SELECT id, name FROM categories WHERE type = 'expenditure'");
        $stmt->execute();

        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    public function UpdateList(): void
    {
        $this->requireLogin();

        $data = json_decode(file_get_contents('php://input'), true);

        if (
            !isset($data['id'], $data['input_date'], $data['category_id'], $data['amount'], $data['description'], $data['target_type']) ||
            !in_array($data['target_type'], ['income', 'expenditure'], true)
        ) {
            http_response_code(400);
            echo json_encode(['error' => '不正なデータ形式です。']);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $id = $data['id'];
        $inputDate = $data['input_date'];
        $categoryId = $data['category_id'];
        $amount = $data['amount'];
        $description = $data['description'];
        $targetType = $data['target_type'];

        try {
            if ($targetType === 'income') {
                $sql = "
                    UPDATE incomes
                    SET input_date = :input_date, category_id = :category_id, amount = :amount, description = :description
                    WHERE id = :id AND user_id = :user_id
                ";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':input_date' => $inputDate,
                    ':category_id' => $categoryId,
                    ':amount' => $amount,
                    ':description' => $description,
                    ':id' => $id,
                    ':user_id' => $userId,
                ]);
            } else {
                // ✅ 支出用の追加データ処理（星評価・無駄遣い）
                $starRate = $data['star_rate'] ?? 0;
                $isWaste = $data['is_waste'] ?? 0;

                $sql = "
                    UPDATE expenditures
                    SET input_date = :input_date, category_id = :category_id, amount = :amount, description = :description,
                        star_rate = :star_rate, is_waste = :is_waste
                    WHERE id = :id AND user_id = :user_id
                ";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':input_date' => $inputDate,
                    ':category_id' => $categoryId,
                    ':amount' => $amount,
                    ':description' => $description,
                    ':star_rate' => $starRate,
                    ':is_waste' => $isWaste,
                    ':id' => $id,
                    ':user_id' => $userId,
                ]);
            }

            echo json_encode(['success' => true]);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => '更新に失敗しました。']);
            exit;
        }
    }

    public function DeleteList() {
        $this->requireLogin();
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
}
