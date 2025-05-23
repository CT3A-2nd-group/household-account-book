<?php
class IncomeController extends BaseController
{
    /* 収入入力フォーム表示 */
    public function showForm(): void
    {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');

        $stmt = $this->pdo->query(
            "SELECT id, name FROM categories WHERE type = 'income'"
        );
        $categories = $stmt->fetchAll();

        $this->render('finance/income_form', array_merge(
            compact('categories'),
            ['title' => '収入登録']
));
    }

    /* 収入登録処理 */
    public function store(): void
    {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');

        $user_id    = $_SESSION['user_id'];
        $input_date = $_POST['input_date']  ?? '';
        $category   = $_POST['category_id'] ?? '';
        $amount     = $_POST['amount']      ?? '';
        $description= $_POST['description'] ?? null;

        if ($amount === '' || !is_numeric($amount) || $amount <= 0) {
            $this->redirect('/income/create?error='
                . urlencode('金額は正しく入力してください'));
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO incomes
            (user_id, category_id, input_date, amount, description)
            VALUES (:u, :c, :d, :a, :desc)
        ");
        $stmt->execute([
            ':u'    => $user_id,
            ':c'    => $category ?: null,
            ':d'    => $input_date,
            ':a'    => $amount,
            ':desc' => $description
        ]);

        $this->redirect('/graph/line');
    }
}
