<h2>目標を登録</h2>
<form action="/finance/goal" method="POST" class="goal-form">
    <label for="target_name">目標名:</label>
    <input type="text" id="target_name" name="target_name" required>

    <label for="target_amount">目標金額 (円):</label>
    <input type="number" id="target_amount" name="target_amount" step="0.01" required>

    <button type="submit">登録</button>
</form>
