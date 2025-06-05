<div class="savings-form-container">
  <div class="savings-form">
    <div class="form-header">
      <div class="coin-icon">
        <img src="/images/coin.png" alt="貯金アイコン">
      </div>
      <h2>今月の貯金額を登録</h2>
    </div>
    
    <form method="POST" action="/finance/save">
      <div class="date-inputs">
        <div class="form-group">
          <label for="year">
             年:
          </label>
          <input type="number" id="year" name="year" class="form-control" value="<?= date('Y') ?>" required>
        </div>
        
        <div class="form-group">
          <label for="month">
             月:
          </label>
          <input type="number" id="month" name="month" class="form-control" value="<?= date('n') ?>" min="1" max="12" required>
        </div>
      </div>
      
      <div class="form-group">
        <label for="saved">
           今月の貯金額（円）:
        </label>
        <input type="number" id="saved" name="saved" class="form-control" step="0.01" required>
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