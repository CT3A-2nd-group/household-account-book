<?php
class HomeController extends BaseController
{
    public function index(): void
    {
        $this->requireLogin();

        $userId   = $_SESSION['user_id'];
        $isAdmin  = $_SESSION['is_admin'] ?? 0;
        $username = $this->getUsername($userId);
        $extraCss = '<link rel="stylesheet" href="/css/home.css">';

        // 自由資金関連データ取得（freeMoney, latestMonth, prevMonth を一括取得）
        $calcResult = $this->calcFreeMoney($this->pdo, $userId);

        // 自由資金の合計を取得
        $totalFreeMoney = $this->allFreeMoney($this->pdo, $userId);

        // ビューに渡す
        $this->render('home', array_merge(
            compact('username', 'isAdmin', 'totalFreeMoney'),
            $calcResult,
            [
                'title'    => 'ホーム',
                'extraCss' => $extraCss,
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

    function calcFreeMoney(PDO $pdo, int $user_id): array
    {
        // 収入データ
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

        // 支出データ
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

        // 貯金データ
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

        // 各月をまとめる（収入、支出、貯金のいずれかにデータがある月のみ）
        $months = array_unique(array_merge(
            array_keys($incomes),
            array_keys($expenditures),
            array_keys($savings)
        ));
        sort($months);

        $freeMoney = [];
        $latestMonth = null;
        $prevMonth = null;

        $lastIndex = count($months) - 1;
        if ($lastIndex >= 0) {
            $latestMonth = $months[$lastIndex];
            $prevMonth = $months[$lastIndex - 1] ?? null;
        }

        foreach ($months as $ym) {
            $income     = $incomes[$ym] ?? 0;
            $expenditure= $expenditures[$ym] ?? 0;
            $saving     = $savings[$ym] ?? 0;
            $amount     = $income - $expenditure - $saving;

            $freeMoney[$ym] = $amount;

            // DBに保存（ON DUPLICATE KEY）
            $year  = substr($ym, 0, 4);
            $month = substr($ym, 5, 2);

            $sql = "INSERT INTO monthly_finances (user_id, year, month, free_money)
                    VALUES (:user_id, :year, :month, :free_money)
                    ON DUPLICATE KEY UPDATE free_money = :free_money";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':user_id'    => $user_id,
                ':year'       => $year,
                ':month'      => $month,
                ':free_money' => $amount,
            ]);
        }

        return [
            'freeMoney'   => $freeMoney,
            'latestMonth' => $latestMonth,
            'prevMonth'   => $prevMonth,
        ];
    }

    function allFreeMoney(PDO $pdo, int $user_id): float
    {
        $sql = "SELECT SUM(free_money) as total_free_money
                  FROM monthly_finances 
                 WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)($row['total_free_money'] ?? 0);
    }
}
