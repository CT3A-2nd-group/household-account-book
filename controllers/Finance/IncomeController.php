<?php
class IncomeController extends BaseController
{
    /* 収入入力フォーム表示 */
    public function showForm(): void
    {
        $this->requireLogin();

        // カテゴリ取得
        $categories = $this->pdo
            ->query("SELECT id, name FROM categories WHERE type = 'income'")
            ->fetchAll();

        // ── flash 取り出し ──
        $old   = $_SESSION['flash_old']   ?? [];
        $error = $_SESSION['flash_error'] ?? '';
        unset($_SESSION['flash_old'], $_SESSION['flash_error']);

        $this->render('finance/income_form', [
            'categories' => $categories,
            'title'      => '収入登録',
            'extraCss'   => implode("\n", [
                '<link rel="stylesheet" href="/css/Finance/finance.css">',
                '<link rel="stylesheet" href="/css/Finance/income.css">'
            ]),
            'extraJs'    => '<script src="/js/Finance/income.js"></script>',
            'old'        => $old,
            'error'      => $error
        ]);
    }

    /* 収入登録処理 */
    public function store(): void
    {
        $this->requireLogin();

        $user_id     = $_SESSION['user_id'];
        $input_date  = $_POST['input_date']  ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $amount      = $_POST['amount']      ?? '';
        $description = $_POST['description'] ?? null;

        // ── バリデーション ──
        if ($input_date === '' || $category_id === '') {
            $_SESSION['flash_error'] = '日付とカテゴリは必須です';
            $_SESSION['flash_old']   = $_POST;
            $this->redirect('/income/create');
            return;
        }

        if ($amount === '' || !is_numeric($amount) || $amount <= 0) {
            $_SESSION['flash_error'] = '金額は1円以上で入力してください';
            $_SESSION['flash_old']   = $_POST;
            $this->redirect('/income/create');
            return;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO incomes
            (user_id, category_id, input_date, amount, description)
            VALUES (:u, :c, :d, :a, :desc)
        ");
        $stmt->execute([
            ':u'    => $user_id,
            ':c'    => $category_id,
            ':d'    => $input_date,
            ':a'    => $amount,
            ':desc' => $description
        ]);

        $this->redirect('/List/view');
    }
}