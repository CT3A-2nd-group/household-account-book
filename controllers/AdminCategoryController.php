<?php
class AdminCategoryController
{
    public function create()
    {
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
            header('Location: /login');
            exit;
        }

        require __DIR__ . '/../config/database.php';

        // 支出カテゴリ取得
        $stmt1 = $pdo->prepare("SELECT id, name FROM categories WHERE type = 'expenditure'");
        $stmt1->execute();
        $expenditureCategories = $stmt1->fetchAll();

        // 収入カテゴリ取得
        $stmt2 = $pdo->prepare("SELECT id, name FROM categories WHERE type = 'income'");
        $stmt2->execute();
        $incomeCategories = $stmt2->fetchAll();

        require __DIR__ . '/../views/admin_category_form.php';
    }

    public function store()
    {
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
            header('Location: /login');
            exit;
        }

        require __DIR__ . '/../config/database.php';

        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? '';

        if ($name === '' || !in_array($type, ['income', 'expenditure'])) {
            header('Location: /admin/category/create?error=入力が不正です');
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO categories (name, type) VALUES (?, ?)");
        $stmt->execute([$name, $type]);

        header('Location: /admin/category/create');
        exit;
    }

    public function delete()
    {
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
            header('Location: /login');
            exit;
        }

        require __DIR__ . '/../config/database.php';

        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: /admin/category/create?error=カテゴリIDが指定されていません');
            exit;
        }

        // ここから関連レコードのチェック
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM incomes WHERE category_id = ?");
        $stmt->execute([$id]);
        $countIncome = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM expenditures WHERE category_id = ?");
        $stmt->execute([$id]);
        $countExpend = $stmt->fetchColumn();

        if ($countIncome > 0 || $countExpend > 0) {
            // 関連する収入・支出が存在するため削除不可
            header('Location: /admin/category/create?error=このカテゴリは使用されているため削除できません');
            exit;
        }
        // ここまでチェック

        // 問題なければ削除実行
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);

        header('Location: /admin/category/create?success=カテゴリを削除しました');
        exit;
    }

}
