<?php
$title = '収支一覧';
require_once __DIR__ . '/../controllers/ListController.php';
include __DIR__ . '/layouts/header.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
// エラーがあれば表示
if (isset($_SESSION['error'])) {
    echo '<p style="color: red;">' . htmlspecialchars($_SESSION['error']) . '</p>';
    unset($_SESSION['error']);
}
// 支出一覧データ取得
$controller = new ListController();
$expenditures = $controller->ExpenditureList($_SESSION['user_id']);
?>

<h2>収支一覧</h2>

<h3>支出一覧</h3>
<form method="POST" action="/ListDelete">
    <table>
        <thead>
            <tr>
                <th>日付</th>
                <th>カテゴリ</th>
                <th>金額（円）</th>
                <th>満足度</th>
                <th>無駄遣い</th>
                <th>メモ</th>
                <th>削除</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($expenditures as $exp): ?>
                <tr>
                    <td><?= htmlspecialchars($exp['input_date']) ?></td>
                    <td><?= htmlspecialchars($exp['category_name']) ?></td>
                    <td><?= htmlspecialchars($exp['amount']) ?></td>
                    <td><?= $exp['star_rate'] ? str_repeat('★', (int)$exp['star_rate']) : '評価なし' ?></td>
                    <td><?= $exp['is_waste'] ? 'はい' : 'いいえ' ?></td>
                    <td><?= htmlspecialchars($exp['description']) ?></td>
                    <td><input type="checkbox" name="delete_ids[]" value="<?= htmlspecialchars($exp['id']) ?>"></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit" name="delete" value="1">削除</button>     
</form>

<?php include __DIR__ . '/layouts/footer.php'; ?>
