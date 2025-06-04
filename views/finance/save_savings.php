<h2>今月の貯金額を登録</h2>
<form method="POST" action="/finance/save">
    <label for="year">年:</label>
    <input type="number" name="year" value="<?= date('Y') ?>" required><br>

    <label for="month">月:</label>
    <input type="number" name="month" value="<?= date('n') ?>" min="1" max="12" required><br>

    <label for="saved">今月の貯金額（円）:</label>
    <input type="number" name="saved" step="0.01" required><br>

    <button type="submit">登録</button>
</form>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="error-message" style="color: red;">
        <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
