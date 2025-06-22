<?php
class AnalysisController extends BaseController
{
    public function satisfaction(): void
    {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];

        // 満足度ランキング（上位5件）
        $rankingStmt = $this->pdo->prepare(
            "SELECT e.input_date, c.name AS category_name, e.amount, e.star_rate, e.description
             FROM expenditures e
             JOIN categories c ON e.category_id = c.id
             WHERE e.user_id = :u
             ORDER BY e.star_rate DESC, e.input_date DESC
             LIMIT 5"
        );
        $rankingStmt->execute([':u' => $userId]);
        $ranking = $rankingStmt->fetchAll(PDO::FETCH_ASSOC);

        // 無駄遣い一覧
        $wasteStmt = $this->pdo->prepare(
            "SELECT e.input_date, c.name AS category_name, e.amount, e.star_rate, e.description
             FROM expenditures e
             JOIN categories c ON e.category_id = c.id
             WHERE e.user_id = :u AND e.is_waste = 1
             ORDER BY e.input_date DESC"
        );
        $wasteStmt->execute([':u' => $userId]);
        $wastes = $wasteStmt->fetchAll(PDO::FETCH_ASSOC);

        $extraCss = '<link rel="stylesheet" href="/css/Analysis/analysis.css">';
        $extraJs = '<script src="/js/pagination.js"></script>';
        $this->render('analysis/satisfaction', [
            'title'    => '満足度ランキング',
            'ranking'  => $ranking,
            'wastes'   => $wastes,
            'extraCss' => $extraCss,
            'extraJs'  => $extraJs
        ]);
    }

    public function category(): void
    {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];

        $avgStmt = $this->pdo->prepare(
            "SELECT c.name AS category_name, AVG(e.star_rate) AS avg_rate
             FROM expenditures e
             JOIN categories c ON e.category_id = c.id
             WHERE e.user_id = :u
             GROUP BY e.category_id, c.name
             HAVING AVG(e.star_rate) IS NOT NULL
             ORDER BY avg_rate DESC"
        );
        $avgStmt->execute([':u' => $userId]);
        $averages = $avgStmt->fetchAll(PDO::FETCH_ASSOC);

        $extraCss = '<link rel="stylesheet" href="/css/Analysis/analysis.css">';
        $extraJs = '<script src="/js/pagination.js"></script>';
        $this->render('analysis/category', [
            'title'    => 'カテゴリ平均',
            'averages' => $averages,
            'extraCss' => $extraCss,
            'extraJs'  => $extraJs
        ]);
    }
}
?>