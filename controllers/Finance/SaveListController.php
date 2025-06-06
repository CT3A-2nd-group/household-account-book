<?php
    class SaveListController extends BaseController{
        public function SavingsListview() {
            $this->requireLogin(); // ログインチェックOK

            $extraCss = '<link rel="stylesheet" href="/css/Finance/finance.css">';
            $extraJs  = ''; // 必要に応じて

            $stmt = $this->pdo->prepare("
                SELECT year, month, saved_this_month
                FROM monthly_finances
                WHERE user_id = :user_id
                ORDER BY year DESC, month DESC
            ");
            $stmt->execute([':user_id' => $_SESSION['user_id']]);
            $savings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->render('finance/save_list', [
                'savings'   => $savings,
                'title'     => '貯金額一覧',
                'extraCss'  => $extraCss,
                'extraJs'   => $extraJs,
            ]);
        }


    }

    
?>
