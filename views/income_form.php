<?php
$title = '収入入力';
include __DIR__ . '/layouts/header.php';
?>

<h2>収入を登録</h2>

<?php if (isset($_SESSION['error'])): ?>
    <p style="color: red;"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form action="/income/create" method="POST">
    <label>日付設定</label>
    <input type="date" id="input_date" name="input_date" required>
    <label>カテゴリ</label>
        <select name="category_id" required>
            <option value="" disabled selected>カテゴリを選択してね</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?=htmlspecialchars($category['id']) ?>">
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="amount">金額</label>
        <input type="number" name="amount" id="amount" required>

        <label for="description">補足(任意)</label>
        <input type="text" name="description" id="description">

    <button type="submit">登録</button>
</form>

<?php include __DIR__ . '/layouts/footer.php'; ?>