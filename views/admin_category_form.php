<h2>カテゴリ追加</h2>

<form action="/admin/category/store" method="POST">
    <label>カテゴリ名: <input type="text" name="name" required></label><br>
    <label>種別:
        <select name="type" required>
            <option value="expenditure">支出</option>
            <option value="income">収入</option>
        </select>
    </label><br>
    <button type="submit">追加</button>
</form>

<hr>

<h3>支出カテゴリ一覧</h3>
<ul>
<?php foreach ($expenditureCategories as $cat): ?>
    <li><?= htmlspecialchars($cat['name']) ?></li>
<?php endforeach; ?>
</ul>

<h3>収入カテゴリ一覧</h3>
<ul>
<?php foreach ($incomeCategories as $cat): ?>
    <li><?= htmlspecialchars($cat['name']) ?></li>
<?php endforeach; ?>
</ul>

<p><a href="/login">ログインページへ</a></p>