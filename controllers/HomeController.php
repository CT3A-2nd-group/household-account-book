<?php
class HomeController extends BaseController
{
    public function index(): void
    {
        $this->requireLogin();

        $userId   = $_SESSION['user_id'];
        $isAdmin  = $_SESSION['is_admin'] ?? 0;
        $username = $this->getUsername($userId);
        $extraCss  = '<link rel="stylesheet" href="/css/home.css">';

        //ここで自由に使えるお金を計算
        $freeMoney = $this->calcFreeMoney($this->pdo, $userId);

        // header / footer を自動付与して home ビューへ
        $this->render('home', array_merge(
            compact('username', 'isAdmin', 'freeMoney'),
            [
                'title'     => 'ホーム',
                'extraCss'  => $extraCss,
            ]
        ));
    }

    private function getUsername(int $userId): string
    {
        $stmt = $this->pdo->prepare(
            'SELECT username FROM users WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $userId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['username'] ?? 'ゲスト';
    }

    //自由資金算出のための計算（それぞれYY-MM : amount　の形でデータベースから情報を回収）
    function calcFreeMoney(PDO $pdo, int $user_id): array {
        //収入データ
        $sql = "SELECT DATE_FORMAT(input_date, '%Y-%m') AS ym, 
                ROUND(SUM(amount)) AS total_income
                FROM incomes 
                WHERE user_id = :user_id 
                GROUP BY ym";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $incomes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $incomes[$row['ym']] = (float)$row['total_income'];
        }

        //支出データ
        $sql = "SELECT DATE_FORMAT(input_date, '%Y-%m') AS ym, 
                ROUND(SUM(amount)) AS total_expenditure
                FROM expenditures 
                WHERE user_id = :user_id 
                GROUP BY ym";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $expenditures = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $expenditures[$row['ym']] = (float)$row['total_expenditure'];
        }

        //貯金データ
        $sql = "SELECT CONCAT(LPAD(year, 4, '0'), '-', LPAD(month, 2, '0')) AS ym,
                ROUND(SUM(saved_this_month)) AS total_saving
                FROM monthly_finances
                WHERE user_id = :user_id
                GROUP BY ym";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $savings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $savings[$row['ym']] = (float)$row['total_saving'];
        }

        //収入、支出、貯金のうち一つでもあれば$monthに入れる（情報がある月だけ）
        $months = array_unique(array_merge(
            array_keys($incomes),
            array_keys($expenditures),
            array_keys($savings)
        ));
        sort($months);

        //自由資金の算出＋$freeMoneyにその金額を
        $freeMoney = [];

        foreach ($months as $ym) {
            $income = $incomes[$ym] ?? 0;
            $expenditure = $expenditures[$ym] ?? 0;
            $saving = $savings[$ym] ?? 0;
            $amount = $income - $expenditure - $saving;
            $freeMoney[$ym] = $amount;

            $year = substr($ym, 0, 4);
            $month = substr($ym, 5, 2);

            
        $saveFreeMoney = 
        "INSERT INTO monthly_finances (user_id, year, month, free_money)
            VALUES($user_id , :year , :month ,:free_money)
            ON DUPLICATE KEY UPDATE free_money = :free_money
        ";
    
        $stmt = $pdo->prepare($saveFreeMoney);
        $stmt->execute([
            ':user_id' => $user_id,
            ':year'       => $year,
            ':month'      => $month,
            ':free_money' => $amount,
        ]);
        }
        return $freeMoney;
    }   
}