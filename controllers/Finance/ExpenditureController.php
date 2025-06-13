<?php
/** 名前空間無しバージョン */
class ExpenditureController extends BaseController
{
    /* 支出入力フォーム表示 */
    public function showForm(): void
    {
        $this->requireLogin();

        $stmt = $this->pdo->query(
            "SELECT id, name FROM categories WHERE type = 'expenditure'"
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
        $extraJs = '<script src="/js/Finance/expenditure.js"></script>';
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
}
