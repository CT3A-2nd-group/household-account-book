<?php
$title = '支出入力';
include __DIR__ . '/layouts/header.php';
?>

<?php if (isset($_GET['error'])): ?>
    <p style="color: red;"><?= htmlspecialchars($_GET['error']) ?></p>
<?php endif; ?>

<form action="/expenditure/create" method="POST">
    <label>日付
        <input type="date" name="input_date" required>
    </label>

   <label>カテゴリ</label>
    <select name="category_id" required>
        <option value="" disabled selected>-- 選択してください --</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= htmlspecialchars($category['id']) ?>">
                <?= htmlspecialchars($category['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <label>金額
        <input type="number" name="amount" step="1" min="1" required placeholder="金額を入力">
    </label>

    <label>メモ（任意）
        <textarea name="description" placeholder="メモ（任意）"></textarea>
    </label>

    <label>
        無駄遣い
        <input type="checkbox" name="is_waste" value="1">
    </label>

    <label>満足度
        <select name="star_rate">
            <option value="">評価なし</option>
            <option value="1">★</option>
            <option value="2">★★</option>
            <option value="3">★★★</option>
            <option value="4">★★★★</option>
            <option value="5">★★★★★</option>
        </select>
    </label>

    <button type="submit">登録</button>
</form>

<?php include __DIR__ . '/layouts/footer.php'; ?>