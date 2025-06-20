<?php
class AdminCategoryController extends BaseController
{
    /** カテゴリ管理画面 */
    public function create(): void
    {
        $extraCss = '<link rel="stylesheet" href="/css/Admin/admin.css">';
        $extraJs = '<script src="/js/admin.js" defer></script>';

        if (!$this->isAdmin()) $this->redirect('/login');

        // 支出・収入カテゴリを一気に取得
        $expenditureCategories = $this->fetchCategories('expenditure');
        $incomeCategories      = $this->fetchCategories('income');

        // 管理者フラグをセッションに設定（デバッグ用）
        $_SESSION['is_admin'] = 1;

        // ビューに渡す（compactに配列追加）
        $this->render('admin/category_form', [
            'expenditureCategories' => $expenditureCategories,
            'incomeCategories'      => $incomeCategories,
            'title'                 => 'カテゴリ管理',
            'extraCss'              => $extraCss,
            'extraJs'              => $extraJs
        ]);
    }

    /** カテゴリ追加 */
    public function store(): void
    {
        if (!$this->isAdmin()) $this->redirect('/login');

        $name = trim($_POST['name'] ?? '');
        $type = $_POST['type'] ?? '';

        if ($name === '' || !in_array($type, ['income', 'expenditure'], true)) {
            $this->redirect('/admin/category/create?error=' . urlencode('入力が不正です'));
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO categories (name, type) VALUES (:n, :t)"
        );
        $stmt->execute([':n' => $name, ':t' => $type]);

        $this->redirect('/admin/category/create?success=' . urlencode('カテゴリを追加しました'));
    }

    /** カテゴリ削除 */
    public function delete(): void
    {
        if (!$this->isAdmin()) $this->redirect('/login');

        $id = $_POST['id'] ?? null;
        if (!$id) $this->redirect('/admin/category/create?error=ID未指定');

        // 関連データがあるかチェック
        if ($this->hasRelatedRecords($id)) {
            $this->redirect('/admin/category/create?error=' . urlencode('使用中のカテゴリは削除できません'));
        }

        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $this->redirect('/admin/category/create?success=' . urlencode('カテゴリを削除しました'));
    }

    /* ---------- ヘルパ --------- */

    private function isAdmin(): bool
    {
        return isset($_SESSION['user_id'], $_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
    }

    private function fetchCategories(string $type): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, name FROM categories WHERE type = :t " .
            "ORDER BY (name = 'その他'), id"
        );
        $stmt->execute([':t' => $type]);
        return $stmt->fetchAll();
    }

    private function hasRelatedRecords(int $catId): bool
    {
        $tables = ['incomes', 'expenditures'];
        foreach ($tables as $tbl) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$tbl} WHERE category_id = :id");
            $stmt->execute([':id' => $catId]);
            if ($stmt->fetchColumn() > 0) return true;
        }
        return false;
    }
}
