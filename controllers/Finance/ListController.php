<?php
    class ListController extends BaseController{
        public function IncomeList($user_id){
            include __DIR__ . '/../config/database.php';

            $stmt = $pdo->prepare("
                SELECT i.id, i.input_date, c.name AS category_name, i.amount, i.description
                FROM incomes i
                JOIN categories c ON i.category_id = c.id
                WHERE i.user_id = :user_id
                ORDER BY i.input_date DESC
            ");

            $stmt->execute([':user_id' => $user_id]);


            $incomes = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $incomes[] = $row;
            }
            return $incomes;
        }
        public function ExpenditureList($user_id){
            session_start();
            include __DIR__ . '/../config/database.php';

            $stmt = $pdo->prepare("
                SELECT e.id, e.input_date, c.name AS category_name, e.amount, e.star_rate, e.is_waste, e.description
                FROM expenditures e
                JOIN categories c ON e.category_id = c.id
                WHERE e.user_id = :user_id
                ORDER BY e.input_date DESC
            ");
            $stmt->execute([':user_id' => $user_id]);

            $expenditures = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $expenditures[] = $row;
            }
            return $expenditures;
        }

        public function DeleteList(){

            session_start();
            include __DIR__ . '/../config/database.php';


            if(isset($_POST['delete_ids']) && is_array($_POST['delete_ids'])){
                $delete_ids = $_POST['delete_ids'];

                $placeholders = rtrim(str_repeat('?,', count($delete_ids)),',');
                $sql = "DELETE FROM expenditures WHERE id IN ($placeholders) AND user_id = ?";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([...$delete_ids, $_SESSION['user_id']]);

                $_SESSION['success'] = '選択した支出を削除しました。';
            } 
            else{
                $_SESSION['error'] = '削除対象が選択されていません。';
            }

            header('Location: /List-view');
            exit;
        }
    }

    
?>
