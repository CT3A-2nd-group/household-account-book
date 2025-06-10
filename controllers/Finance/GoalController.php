<?php
class GoalController extends BaseController{
    
    public function showForm(): void{
        $this->requireLogin();

        $extraCss = '<link rel="stylesheet" href="/css/Finance/goal.css">';
        $extraJs = '';

        $this->render('finance/goal_form',  [
                'title' => '目標登録',
                'extraCss' => $extraCss,
                'extraJs'  => $extraJs
        ]);
    }

    public function createGoal(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $targetName = trim($_POST['target_name'] ?? '');
        $targetAmount = floatval($_POST['target_amount'] ?? 0);

        if ($targetName === '' || $targetAmount <= 0) {
            $this->redirect('/goals?error=不正な入力です');
            return;
        }

        $stmt = $this->pdo->prepare("INSERT INTO goals (user_id, target_name, target_amount) VALUES (:user_id, :name, :amount)");
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':name' => $targetName,
            ':amount' => $targetAmount,
        ]);

        header('Location: /home');
    }



}