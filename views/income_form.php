<?php
$title = '収入入力';
include __DIR__ . '/layouts/header.php';
?>

<h2>収入を登録</h2>

<?php if (isset($_GET['error'])): ?>
    <p style="color: red;"><?= htmlspecialchars($_GET['error']) ?></p>
<?php endif; ?>

<form action="/income/create" method="POST">
    <label>カテゴリ</label>
        <select name="category" id="category">
            <option value="" disable selected>カテゴリを選択してね</option>
            <option value="1">給与</option>
            <option value="2">DBのidが決まったら</option>
            <option value="3">変更します</option>
        </select>
        <label for="amount">金額</label>
        <input type="number" name="amount" id="amount" required>

        <label for="description">補足</label>
        <input type="text" name="description" id="description">

    <button type="submit">登録</button>
</form>

<?php include __DIR__ . '/layouts/footer.php'; ?>