<?php /** @var array $categories */ ?>
<?php /** @var array $old */ ?>
<?php /** @var string $error */ ?>

<div class="finance-container">
    <h2 class="finance-title"><span class="finance-title-text">収入を登録</span></h2>

    <?php if ($error): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="/income/create" method="POST" class="finance-form">
        <!-- 日付 -->
        <div class="form-group">
            <div class="nigga"><label class="form-label">日付</label><span class="required">*必須</span></div>
            <div class="date-input-wrapper">
                <input type="text" name="input_date" placeholder="例：yyyy/mm/dd"
                    class="date-input" id="date-input" maxlength="10">
                <button type="button" class="calendar-button" onclick="CalendarAPI.openCalendar('date-input')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </button>
            </div>
        </div>



        <!-- カテゴリ -->
        <div class="form-group">
            <div class="nigga"><label class="form-label">カテゴリ</label><span class="required">*必須</span></div>
            <div class="select-wrapper">
                <select name="category_id"  class="form-select">
                    <option value="" disabled <?= empty($old['category_id']) ? 'selected' : '' ?>>-- カテゴリを選択 --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= htmlspecialchars($c['id']) ?>"
                            <?= (string)$c['id'] === ($old['category_id'] ?? '') ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- 金額 -->
        <div class="form-group">
           <div class="nigga"><label for="amount" class="form-label">金額</label><span class="required">*必須</span></div>
            <div class="input-with-icon">
                <input type="text" name="amount" id="amount" placeholder="金額を入力" 
                       class="form-input amount-input" inputmode="numeric"
                       value="<?= htmlspecialchars($old['amount'] ?? '') ?>">
            </div>
        </div>

        <!-- メモ -->
        <div class="form-group">
            <label for="description" class="form-label">メモ</label>
            <div class="input-with-icon">
               <textarea name="description" id="description" placeholder="詳細"
                        class="form-input" rows="3"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="form-actions">
        <button type="submit" class="submit-button">
            <svg class="button-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            登録する
        </button>
    </div>
    </form>
</div>
