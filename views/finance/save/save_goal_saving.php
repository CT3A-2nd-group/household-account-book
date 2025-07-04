<div class="savings-form-container">
  <div class="savings-form">
    <div class="form-header">
      <div class="coin-icon">
        <img src="/images/save.png" alt="貯金アイコン">
      </div>
      <h2>目標貯金額を登録</h2>
    </div>
    
    <form method="POST" action="/finance/goalsave">
      <div class="form-group">
        <label for="goalsaved">
           貯金額目標（円）:
        </label>
        <input type="number" id="goalsaved" name="goalsaved" class="form-control" step="0.01" required>
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