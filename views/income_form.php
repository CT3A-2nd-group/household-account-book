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
    <label>日付</label>
    <input type="date" name="input_date" value="<?= date('Y-m-d') ?>" required><br>

    <label>カテゴリ</label>
    <select name="category_id" required>
        <option value="" disabled selected>-- カテゴリを選択 --</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= htmlspecialchars($category['id']) ?>">
                <?= htmlspecialchars($category['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="amount">
        金額
        <input type="number" name="amount" id="amount" placeholder="金額を入力" required>
    </label>

    <label for="description">
        メモ
        <input type="text" name="description" id="description" placeholder="詳細">
    </label>

    <button type="submit">登録</button>
</form>


<?php include __DIR__ . '/layouts/footer.php'; ?>