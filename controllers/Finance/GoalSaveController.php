<?php
class GoalSaveController extends BaseController
{
    public function showForm(): void
    {
        $extraCss = '<link rel="stylesheet" href="/css/Finance/save.css">';
        $this->requireLogin();
        $this->render('finance/save_goal_saving', [
            'title' => '貯金目標登録',
            'extraCss' => $extraCss
        ]);
    }

    public function handleSaveGoal(): void
    {
        $this->requireLogin(); // ログインチェック
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $goal_amount = $_POST['goalsaved'] ?? '';
            if (!is_numeric($goal_amount) || (float)$goal_amount < 0) {
                $_SESSION['error'] = '有効な金額を入力してください';
                $this->redirect('/finance/save-goalsaving');
            }
            

            $this->saveGoalAmount($this->pdo, $_SESSION['user_id'], (float)$goal_amount);
            $this->redirect('/../home'); // 任意のリダイレクト先に変更可能
        }
    }

    public function saveGoalAmount(PDO $pdo, int $user_id, float $goal_amount): void
    {
        $sql = '
            INSERT INTO saving_goal (user_id, goal_amount)
            VALUES (:user_id, :goal_amount)
            ON DUPLICATE KEY UPDATE goal_amount = :goal_amount
        ';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':goal_amount' => $goal_amount,
        ]);
    }

}
