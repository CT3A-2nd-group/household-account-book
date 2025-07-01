<?php
class IncomeController extends BaseController
{
    /* 収入入力フォーム表示 */
    public function showForm(): void
    {
        $this->requireLogin();
        $this->forbidAdmin();

        // カテゴリ取得
        $categories = $this->pdo
             ->query(
                "SELECT id, name FROM categories WHERE type = 'income' " .
                "ORDER BY (name = 'その他'), id"
            )
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
                '<link rel="stylesheet" href="/css/Finance/income.css">',
                '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">'
            ]),
            'extraJs'    => implode("\n", [
                '<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>',
                '<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>',
                '<script src="/js/Finance/income.js"></script>'
            ]),
            'old'        => $old,
            'error'      => $error
        ]);
    }

    /* 収入登録処理 */
    public function store(): void
    {
        $this->requireLogin();
        $this->forbidAdmin();

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

        if (!is_numeric($income) || (float)$income < 0 || (float)$income > 99999999.99)  {
            $_SESSION['flash_error'] = '有効な金額を入力してください';
            $_SESSION['flash_old']   = $_POST;
            $this->redirect('/income/create');
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

    /** 収入編集フォーム表示 */
    public function editForm(): void
    {
        $this->requireLogin();
        $this->forbidAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/List/view');
            return;
        }

        $stmt = $this->pdo->prepare(
            'SELECT * FROM incomes WHERE id = :id AND user_id = :uid'
        );
        $stmt->execute([':id' => $id, ':uid' => $_SESSION['user_id']]);
        $income = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$income) {
            $this->redirect('/List/view');
            return;
        }

        $categories = $this->pdo
            ->query(
                "SELECT id, name FROM categories WHERE type = 'income' " .
                "ORDER BY (name = 'その他'), id"
            )
            ->fetchAll();

        $extraCss = implode("\n", [
            '<link rel="stylesheet" href="/css/Finance/finance.css">',
            '<link rel="stylesheet" href="/css/Finance/income.css">',
            '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">'
        ]);

        $extraJs = implode("\n", [
            '<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>',
            '<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>',
            '<script src="/js/Finance/income.js"></script>'
        ]);

        $this->render('finance/income_edit_form', [
            'categories' => $categories,
            'title'      => '収入編集',
            'extraCss'   => $extraCss,
            'extraJs'    => $extraJs,
            'income'     => $income
        ]);
    }

    /** 収入更新処理 */
    public function update(): void
    {
        $this->requireLogin();
        $this->forbidAdmin();

        $id          = $_POST['id'] ?? null;
        $input_date  = $_POST['input_date']  ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $amount      = $_POST['amount']      ?? '';
        $description = $_POST['description'] ?? null;

        if (!$id || $input_date === '' || $category_id === '') {
            $this->redirect('/List/view');
            return;
        }

        if ($amount === '' || !is_numeric($amount) || $amount <= 0 || (float)$amount > 99999999.99) {
            $this->redirect('/income/edit?id=' . urlencode((string)$id));
            return;
        }

        $stmt = $this->pdo->prepare(
            'UPDATE incomes SET category_id = :c, input_date = :d, amount = :a, description = :desc WHERE id = :id AND user_id = :u'
        );
        $stmt->execute([
            ':c'   => $category_id,
            ':d'   => $input_date,
            ':a'   => $amount,
            ':desc'=> $description,
            ':id'  => $id,
            ':u'   => $_SESSION['user_id']
        ]);

        $this->redirect('/List/view');
    }
}