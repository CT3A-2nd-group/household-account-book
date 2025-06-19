<?php /** @var array $categories */ ?>
<?php /** @var array $expenditure */ ?>

<div class="finance-container">
    <h2 class="finance-title"><span class="finance-title-text">支出を編集</span></h2>

    <form action="/expenditure/edit" method="POST" class="finance-form">
        <input type="hidden" name="id" value="<?= htmlspecialchars($expenditure['id']) ?>">
        <div class="form-group">
            <div class="nigga"><label class="form-label">日付</label><span class="required">*必須</span></div>
            <div class="date-input-wrapper">
                <input type="text" name="input_date" placeholder="例：yyyy/mm/dd" required
                       class="date-input" id="date-input" maxlength="10"
                       value="<?= htmlspecialchars($expenditure['input_date'] ?? '') ?>">
                <button type="button" class="calendar-button" onclick="openCalendar()">
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

        <div class="form-group">
           <div class="nigga"><label class="form-label">カテゴリ</label><span class="required">*必須</span></div>
            <div class="select-wrapper">
                <select name="category_id" class="form-select">
                    <option value="" disabled <?= empty($expenditure['category_id']) ? 'selected' : '' ?>>-- カテゴリを選択 --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['id']) ?>"
                            <?= (string)$category['id'] === ($expenditure['category_id'] ?? '') ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="nigga"><label for="amount" class="form-label">金額</label><span class="required">*必須</span></div>
            <div class="input-with-icon amount-wrapper">
                <input type="text" name="amount" id="amount" placeholder="金額を入力"
                    class="form-input amount-input" inputmode="numeric"
                    value="<?= rtrim(rtrim(number_format($expenditure['amount'], 2, '.', ''), '0'), '.') ?>">
            </div>
        </div>

        <div class="form-group">
            <div class="label-row"><label for="description" class="form-label">メモ</label></div>
            <div class="input-with-icon">
               <textarea name="description" id="description" placeholder="詳細"
                        class="form-input" rows="3"><?= htmlspecialchars($expenditure['description'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">無駄遣い</label>
            <div class="checkbox-wrapper">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_waste" value="1" class="checkbox-input"
                        <?= isset($expenditure['is_waste']) && $expenditure['is_waste'] ? 'checked' : '' ?>>
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-text">無駄遣いだった</span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="nigga"><label class="form-label">満足度</label><span class="required">*必須</span></div>
            <div class="select-wrapper">
                <select name="star_rate" class="form-select">
                    <option value="" disabled <?= empty($expenditure['star_rate']) ? 'selected' : '' ?>>-- 満足度評価 --</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>"
                            <?= (string)$i === ($expenditure['star_rate'] ?? '') ? 'selected' : '' ?>>
                            <?= str_repeat('★', $i) . str_repeat('☆', 5 - $i) ?> (<?= $i ?>点)
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-button">
                <svg class="button-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                更新する
            </button>
        </div>
    </form>
</div>