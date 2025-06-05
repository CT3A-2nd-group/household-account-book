<?php
class SaveController extends BaseController{
    public function showForm(): void
    {
        $this->requireLogin();
        require_once __DIR__ . '/../../views/finance/save_savings.php';
    }
    
    public function save(): void
    {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];

        $year = intval($_POST['year']);
        $month = intval($_POST['month']);
        $saved = floatval($_POST['saved']);

        $pdo = require __DIR__ . '/../../config/database.php';

        // ① 今月の収入合計を取得
        $stmt = $pdo->prepare("
            SELECT SUM(amount) AS total_income
            FROM incomes
            WHERE user_id = ? AND YEAR(input_date) = ? AND MONTH(input_date) = ?
        ");
        $stmt->execute([$userId, $year, $month]);
        $result = $stmt->fetch();
        $totalIncome = floatval($result['total_income'] ?? 0);

        // ② 貯金額が収入を超えていたらエラー表示
        if ($saved > $totalIncome) {
            $_SESSION['error'] = "貯金額が今月の収入を超えています（収入: {$totalIncome}円）";
            header("Location: /finance/save-form");
            exit;
        }

        // ③ 登録・更新処理
        $stmt = $pdo->prepare("SELECT id FROM monthly_finances WHERE user_id = ? AND year = ? AND month = ?");
        $stmt->execute([$userId, $year, $month]);
        $existing = $stmt->fetch();

        if ($existing) {
            $update = $pdo->prepare("UPDATE monthly_finances SET saved_this_month = ? WHERE user_id = ? AND year = ? AND month = ?");
            $update->execute([$saved, $userId, $year, $month]);
        } else {
            $insert = $pdo->prepare("INSERT INTO monthly_finances (user_id, year, month, saved_this_month) VALUES (?, ?, ?, ?)");
            $insert->execute([$userId, $year, $month, $saved]);
        }

        header('Location: /home');
    }


}