<div class="goal-form-container">
  <div class="goal-form-box">
    <div class="form-header">
      <div class="goal-icon">
        <img src="/images/goal.png" alt="目標アイコン">
      </div>
      <h2>目標を登録</h2>
    </div>

    <form method="POST" action="/finance/goal">
      <div class="form-group">
        <label for="target_name">目標名:</label>
        <input type="text" id="target_name" name="target_name" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="target_amount">目標金額（円）:</label>
        <input type="number" id="target_amount" name="target_amount" class="form-control" step="0.01" required>
      </div>

      <?php if (!empty($_SESSION['error'])): ?>
        <div class="error-message">
          <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <button type="submit" class="submit-button">
        <span class="check-icon">✓</span> 登録
      </button>
    </form>
  </div>
</div>
