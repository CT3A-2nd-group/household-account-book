<h2>支出を登録</h2>

<?php if (isset($_SESSION['error'])): ?>
    <p style="color: red;"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form action="/expenditure/create" method="POST">
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

    <label>
        無駄遣い
        <input type="checkbox" name="is_waste" value="1">
    </label>

    <label>満足度
        <select name="star_rate">
            <option value="" disabled selected>-- 満足度評価 --</option>
            <option value="1">★☆☆☆☆</option>
            <option value="2">★★☆☆☆</option>
            <option value="3">★★★☆☆</option>
            <option value="4">★★★★☆</option>
            <option value="5">★★★★★</option>
        </select>
    </label>

    <button type="submit">登録</button>
</form>