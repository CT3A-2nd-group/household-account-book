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
<?php if (isset($_GET['error'])): ?>
    <p style="color:red;"><?= htmlspecialchars($_GET['error']) ?></p>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
    <p style="color:green;"><?= htmlspecialchars($_GET['success']) ?></p>
<?php endif; ?>

<h3>支出カテゴリ一覧</h3>
<ul>
<?php foreach ($expenditureCategories as $cat): ?>
    <li>
        <?= htmlspecialchars($cat['name']) ?>
        <form action="/admin/category/delete" method="POST" style="display:inline" onsubmit="return confirm('本当に削除しますか？');">
            <input type="hidden" name="id" value="<?= $cat['id'] ?>">
            <button type="submit">削除</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>

<h3>収入カテゴリ一覧</h3>
<ul>
<?php foreach ($incomeCategories as $cat): ?>
    <li>
        <?= htmlspecialchars($cat['name']) ?>
        <form action="/admin/category/delete" method="POST" style="display:inline" onsubmit="return confirm('本当に削除しますか？');">
            <input type="hidden" name="id" value="<?= $cat['id'] ?>">
            <button type="submit">削除</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>


<p><a href="/logout">ログアウトしてログインページへ</a></p>