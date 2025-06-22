<?php
/** 名前空間無しバージョン */
class ExpenditureController extends BaseController
{
    /* 支出入力フォーム表示 */
    public function showForm(): void
    {
        $this->requireLogin();

        $stmt = $this->pdo->query(
            "SELECT id, name FROM categories WHERE type = 'expenditure' " .
            "ORDER BY (name = 'その他'), id"
        );
        $categories = $stmt->fetchAll();

        // 入力保持用のflash取得
        $old   = $_SESSION['flash_old']   ?? [];
        $error = $_SESSION['flash_error'] ?? '';

        // 1回だけ使って削除
        unset($_SESSION['flash_old'], $_SESSION['flash_error']);

        $extraCss = implode("\n", [
            '<link rel="stylesheet" href="/css/Finance/finance.css">',
            '<link rel="stylesheet" href="/css/Finance/expenditure.css">'
        ]);
        $extraJs = implode("\n", [
            '<script src="/js/calendar-api.js"></script>',
            '<script src="/js/Finance/expenditure.js"></script>'
        ]);
        $this->render('finance/expenditure_form', [
            'categories' => $categories,
            'title'      => '支出登録',
            'extraCss'   => $extraCss,
            'extraJs'    => $extraJs,
            'old'        => $old,
            'error'      => $error
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();

        $user_id     = $_SESSION['user_id'];
        $input_date  = $_POST['input_date']  ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $amount      = $_POST['amount']      ?? '';
        $description = $_POST['description'] ?? null;
        $is_waste    = isset($_POST['is_waste']) ? 1 : 0;
        $star_rate   = $_POST['star_rate']   ?? '';

        // バリデーション
        if ($input_date === '' || $category_id === '') {
            $_SESSION['flash_error'] = '日付とカテゴリは必須です';
            $_SESSION['flash_old'] = $_POST;
            $this->redirect('/expenditure/create');
            return;
        }

        if ($amount === '' || !is_numeric($amount) || $amount <= 0) {
            $_SESSION['flash_error'] = '金額は1円以上で入力してください';
            $_SESSION['flash_old'] = $_POST;
            $this->redirect('/expenditure/create');
            return;
        }

        if ($star_rate === '' || !in_array((int)$star_rate, [1, 2, 3, 4, 5], true)) {
            $_SESSION['flash_error'] = '満足度は1から5の間で選択してください';
            $_SESSION['flash_old'] = $_POST;
            $this->redirect('/expenditure/create');
            return;
        }

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO expenditures
                (user_id, category_id, input_date, amount, description, is_waste, star_rate)
                VALUES (:u, :c, :d, :a, :desc, :w, :s)
            ");
            $stmt->execute([
                ':u'    => $user_id,
                ':c'    => $category_id,
                ':d'    => $input_date,
                ':a'    => $amount,
                ':desc' => $description,
                ':w'    => $is_waste,
                ':s'    => $star_rate,
            ]);
        } catch (PDOException $e) {
            error_log('DB接続エラー: ' . $e->getMessage());
            $_SESSION['flash_error'] = '支出の登録に失敗しました。';
            $_SESSION['flash_old'] = $_POST;
            $this->redirect('/expenditure/create');
            return;
        }

        $this->redirect('/List/view');
    }
       
    /** 支出編集フォーム表示 */
    public function editForm(): void
    {
        $this->requireLogin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/List/view');
            return;
        }

        $stmt = $this->pdo->prepare(
            'SELECT * FROM expenditures WHERE id = :id AND user_id = :uid'
        );
        $stmt->execute([':id' => $id, ':uid' => $_SESSION['user_id']]);
        $expenditure = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$expenditure) {
            $this->redirect('/List/view');
            return;
        }

        $categories = $this->pdo
            ->query(
                "SELECT id, name FROM categories WHERE type = 'expenditure' " .
                "ORDER BY (name = 'その他'), id"
            )
            ->fetchAll();

        $extraCss = implode("\n", [
            '<link rel="stylesheet" href="/css/Finance/finance.css">',
            '<link rel="stylesheet" href="/css/Finance/expenditure.css">'
        ]);

        $this->render('finance/expenditure_edit_form', [
            'categories'  => $categories,
            'title'       => '支出編集',
            'extraCss'    => $extraCss,
            'extraJs'     => implode("\n", [
                '<script src="/js/calendar-api.js"></script>',
                '<script src="/js/Finance/expenditure.js"></script>'
            ]),
            'expenditure' => $expenditure
        ]);
    }

    /** 支出更新処理 */
    public function update(): void
    {
        $this->requireLogin();

        $id          = $_POST['id'] ?? null;
        $input_date  = $_POST['input_date']  ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $amount      = $_POST['amount']      ?? '';
        $description = $_POST['description'] ?? null;
        $is_waste    = isset($_POST['is_waste']) ? 1 : 0;
        $star_rate   = $_POST['star_rate']   ?? '';

        if (!$id || $input_date === '' || $category_id === '') {
            $this->redirect('/List/view');
            return;
        }

        if ($amount === '' || !is_numeric($amount) || $amount <= 0) {
            $this->redirect('/expenditure/edit?id=' . urlencode((string)$id));
            return;
        }

        if ($star_rate === '' || !in_array((int)$star_rate, [1,2,3,4,5], true)) {
            $this->redirect('/expenditure/edit?id=' . urlencode((string)$id));
            return;
        }

        $stmt = $this->pdo->prepare(
            'UPDATE expenditures SET category_id = :c, input_date = :d, amount = :a, description = :desc, is_waste = :w, star_rate = :s WHERE id = :id AND user_id = :u'
        );
        $stmt->execute([
            ':c'   => $category_id,
            ':d'   => $input_date,
            ':a'   => $amount,
            ':desc'=> $description,
            ':w'   => $is_waste,
            ':s'   => $star_rate,
            ':id'  => $id,
            ':u'   => $_SESSION['user_id']
        ]);

        $this->redirect('/List/view');
    }
}
