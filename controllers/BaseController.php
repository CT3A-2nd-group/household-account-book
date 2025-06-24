<?php
class BaseController
{
    protected PDO $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function requireLogin(bool $api = false): void
    {
        if (!isset($_SESSION['user_id'])) {
            if ($api) {
                http_response_code(403);
                exit('ログインしていません');
            }
            $this->redirect('/login');
        }
    }

    /**
     * 一般ユーザー向け画面に管理者がアクセスした場合は403を返す
     */
    protected function forbidAdmin(): void
    {
        if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
            http_response_code(403);
            exit('管理者アカウントでは実行できません');
        }
    }

    protected function render(string $viewPath, array $data = []): void
    {
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            $data['goalSaving'] = $this->goalSaving($this->pdo, $user_id);
            $data['allSaving'] = $this->allSaving($this->pdo, $user_id);
            $data['getIncome'] = $this->getIncome($this->pdo, $user_id);
            $data['getExpenditures'] = $this->getExpenditures($this->pdo, $user_id);

            // 収支の比率を計算（ゼロ除算防止）
            $incomeForChart = $data['getIncome'];
            $expenditureForChart = $data['getExpenditures'];

            if ($incomeForChart > 0) {
                $data['expenseRate'] = $expenditureForChart / $incomeForChart * 100;
                $data['balanceRate'] = 100 - $data['expenseRate'];
            } else {
                $data['expenseRate'] = 0;
                $data['balanceRate'] = 0;
            }
        }

        extract($data, EXTR_SKIP);
        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/' . $viewPath . '.php';
        include __DIR__ . '/../views/layouts/footer.php';
    }

    protected function redirect(string $url): never
    {
        header("Location: {$url}");
        exit;
    }

    protected function allSaving(PDO $pdo, int $user_id): float
    {
        $sql = 'SELECT SUM(saved_this_month) as allsave
                FROM monthly_finances
                WHERE user_id = :user_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (float)($row['allsave'] ?? 0);
    }

    protected function goalSaving(PDO $pdo, int $user_id): float
    {
        $sql = 'SELECT goal_amount
                FROM saving_goal
                WHERE user_id = :user_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (float)($row['goal_amount'] ?? 0);
    }

    protected function getIncome(PDO $pdo, int $user_id): float
    {
        $sql = "
            SELECT SUM(amount) as monthlyIncome
            FROM incomes
            WHERE DATE_FORMAT(input_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
            AND user_id = :user_id;
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (float)($row['monthlyIncome'] ?? 0);
    }

    protected function getExpenditures(PDO $pdo, int $user_id): float
    {
        $sql = "
            SELECT SUM(amount) as monthlyExpenditures
            FROM expenditures
            WHERE DATE_FORMAT(input_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
            AND user_id = :user_id;
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (float)($row['monthlyExpenditures'] ?? 0);
    }
}
