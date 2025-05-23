<?php
/** 名前空間無しバージョン */
class ExpenditureController extends BaseController
{
    /* 支出入力フォーム表示 */
    public function showForm(): void
    {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');

        $stmt = $this->pdo->query(
            "SELECT id, name FROM categories WHERE type = 'expenditure'"
        );
        $categories = $stmt->fetchAll();

        $this->render('finance/expenditure_form', array_merge(
            compact('categories'),
            ['title' => '支出登録']
));
    }

    /* 支出登録処理 */
    public function store(): void
    {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');

        $user_id     = $_SESSION['user_id'];
        $input_date  = $_POST['input_date']  ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $amount      = $_POST['amount']      ?? '';
        $description = $_POST['description'] ?? null;
        $is_waste    = isset($_POST['is_waste']) ? 1 : 0;
        $star_rate   = $_POST['star_rate']   ?? '';

        // ── バリデーション ─────────────────────────
        if ($amount === '' || !is_numeric($amount) || $amount <= 0) {
            $this->redirect('/expenditure/create?error='
                . urlencode('金額は1円以上で入力してください'));
        }
        if ($input_date === '' || $category_id === '') {
            $this->redirect('/expenditure/create?error='
                . urlencode('日付とカテゴリは必須です'));
        }
        if ($star_rate === '' || !in_array((int)$star_rate, [1,2,3,4,5], true)) {
            $this->redirect('/expenditure/create?error='
                . urlencode('評価は1〜5の数値で選んでください'));
        }
        // ──────────────────────────────────────

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

        $this->redirect('/graph/line');
    }
}
