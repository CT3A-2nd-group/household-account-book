<?php
$title = '収支一覧';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
// エラーがあれば表示
if (isset($_SESSION['error'])) {
    echo '<div class="error-message">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}
?>

<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<link rel="stylesheet" href="/css/finance.css">

<div class="finance-container">
    <h2 class="page-title">収支一覧</h2>

    <!-- タブナビ -->
    <div class="tab-navigation">
        <button id="incomeTab" class="tab-button active">収入一覧</button>
        <button id="expenditureTab" class="tab-button">支出一覧</button>
    </div>

    <div style="text-align: right; margin-bottom: 8px;">
        <button id="toggle-edit-mode">編集モード</button>
    </div>

    <div class="swiper finance-swiper">
        <div class="swiper-wrapper">

            <!-- 収入一覧 -->
            <div class="swiper-slide">
                <form method="POST" action="/List/Delete" class="finance-form" id="income-form">
                    <input type="hidden" name="target_type" value="income">

                    <div class="table-container">
                        <table class="finance-table">
                            <thead>
                                <tr>
                                    <th>日付</th>
                                    <th>カテゴリ</th>
                                    <th>金額(円)</th>
                                    <th>メモ</th>
                                    <th>削除</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($incomes as $inc): ?>
                                <tr data-id="<?= htmlspecialchars($inc['id']) ?>">
                                    <td><?= htmlspecialchars($inc['input_date']) ?></td>
                                    <td><?= htmlspecialchars($inc['category_name']) ?></td>

                                    <td>
                                        <span class="amount-text"><?= number_format($inc['amount']) ?></span>
                                        <input type="number" step="0.01" name="items[<?= $inc['id'] ?>][amount]" class="edit-amount" value="<?= htmlspecialchars($inc['amount']) ?>" style="display:none; width: 100px;">
                                    </td>

                                    <td>
                                        <span class="memo-text"><?= htmlspecialchars($inc['description']) ?></span>
                                        <input type="text" name="items[<?= $inc['id'] ?>][description]" class="edit-memo" value="<?= htmlspecialchars($inc['description']) ?>" style="display:none; width: 200px;">
                                    </td>

                                    <td class="checkbox-cell">
                                        <label class="checkbox-label">
                                            <input type="checkbox" name="delete_ids[]" value="<?= htmlspecialchars($inc['id']) ?>" class="delete-checkbox">
                                        </label>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($incomes)): ?>
                                <tr>
                                    <td colspan="5" class="no-data">データがありません</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-actions">
                        <!-- 削除ボタン -->
                        <button type="submit" name="delete" value="1" class="delete-button" id="income-delete-button">
                            <svg class="delete-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:16px; height:16px; vertical-align:middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            選択項目を削除
                        </button>

                        <!-- 保存ボタン -->
                        <button type="submit" name="save" value="1" class="save-button" id="income-save-button" style="display:none;">
                            💾 一括保存
                        </button>
                    </div>
                </form>
            </div>

            <!-- 支出一覧 -->
            <div class="swiper-slide">
                <form method="POST" action="/List/Delete" class="finance-form" id="expenditure-form">
                    <input type="hidden" name="target_type" value="expenditure">

                    <div class="table-container">
                        <table class="finance-table">
                            <thead>
                                <tr>
                                    <th>日付</th>
                                    <th>カテゴリ</th>
                                    <th>金額(円)</th>
                                    <th>満足度</th>
                                    <th>無駄遣い</th>
                                    <th>メモ</th>
                                    <th>削除</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($expenditures as $exp): ?>
                                <tr data-id="<?= htmlspecialchars($exp['id']) ?>">
                                    <td><?= htmlspecialchars($exp['input_date']) ?></td>
                                    <td><?= htmlspecialchars($exp['category_name']) ?></td>

                                    <td>
                                        <span class="amount-text"><?= number_format($exp['amount']) ?></span>
                                        <input type="number" step="0.01" name="items[<?= $exp['id'] ?>][amount]" class="edit-amount" value="<?= htmlspecialchars($exp['amount']) ?>" style="display:none; width: 100px;">
                                    </td>

                                    <td>
                                        <span class="star-rating-text">
                                            <?= $exp['star_rate'] ? str_repeat('★', (int)$exp['star_rate']) . str_repeat('☆', 5 - (int)$exp['star_rate']) : '評価なし' ?>
                                        </span>
                                        <select name="items[<?= $exp['id'] ?>][star_rate]" class="edit-star-rate" style="display:none;">
                                            <option value="">評価なし</option>
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <option value="<?= $i ?>" <?= ((int)$exp['star_rate'] === $i) ? 'selected' : '' ?>><?= $i ?>★</option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>

                                    <td>
                                        <span class="waste-text">
                                            <?= $exp['is_waste'] ? '<span class="badge badge-waste">はい</span>' : '<span class="badge badge-no-waste">いいえ</span>' ?>
                                        </span>
                                        <select name="items[<?= $exp['id'] ?>][is_waste]" class="edit-waste" style="display:none;">
                                            <option value="0" <?= $exp['is_waste'] ? '' : 'selected' ?>>いいえ</option>
                                            <option value="1" <?= $exp['is_waste'] ? 'selected' : '' ?>>はい</option>
                                        </select>
                                    </td>

                                    <td>
                                        <span class="memo-text"><?= htmlspecialchars($exp['description']) ?></span>
                                        <input type="text" name="items[<?= $exp['id'] ?>][description]" class="edit-memo" value="<?= htmlspecialchars($exp['description']) ?>" style="display:none; width: 200px;">
                                    </td>

                                    <td class="checkbox-cell">
                                        <label class="checkbox-label">
                                            <input type="checkbox" name="delete_ids[]" value="<?= htmlspecialchars($exp['id']) ?>" class="delete-checkbox">
                                        </label>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($expenditures)): ?>
                                <tr>
                                    <td colspan="7" class="no-data">データがありません</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-actions">
                        <!-- 削除ボタン -->
                        <button type="submit" name="delete" value="1" class="delete-button" id="expenditure-delete-button">
                            <svg class="delete-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:16px; height:16px; vertical-align:middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            選択項目を削除
                        </button>

                        <!-- 保存ボタン -->
                        <button type="submit" name="save" value="1" class="save-button" id="expenditure-save-button" style="display:none;">
                            💾 一括保存
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    // Swiper初期化
    const swiper = new Swiper('.finance-swiper', {
        loop: false,
        allowTouchMove: false, // タブ切り替えだけに
    });

    // タブボタン取得
    const incomeTab = document.getElementById('incomeTab');
    const expenditureTab = document.getElementById('expenditureTab');

    incomeTab.addEventListener('click', () => {
        swiper.slideTo(0);
        incomeTab.classList.add('active');
        expenditureTab.classList.remove('active');
    });

    expenditureTab.addEventListener('click', () => {
        swiper.slideTo(1);
        expenditureTab.classList.add('active');
        incomeTab.classList.remove('active');
    });

    swiper.on('slideChange', () => {
        if (swiper.activeIndex === 0) {
            incomeTab.classList.add('active');
            expenditureTab.classList.remove('active');
        } else {
            expenditureTab.classList.add('active');
            incomeTab.classList.remove('active');
        }
    });

    // 編集モード切り替え
    let isEditMode = false;
    const toggleBtn = document.getElementById('toggle-edit-mode');

    toggleBtn.addEventListener('click', () => {
        isEditMode = !isEditMode;

        ['income', 'expenditure'].forEach(type => {
            const form = document.getElementById(type + '-form');
            if (!form) return;

            // action属性を編集モードに応じて切り替え
            form.action = isEditMode ? '/List/Update' : '/List/Delete';

            // 表示切替する各要素を取得
            form.querySelectorAll('tr[data-id]').forEach(row => {
                // 収入・支出共通：amount
                const amountText = row.querySelector('.amount-text');
                const amountInput = row.querySelector('.edit-amount');

                // メモ
                const memoText = row.querySelector('.memo-text');
                const memoInput = row.querySelector('.edit-memo');

                if (amountText && amountInput) {
                    amountText.style.display = isEditMode ? 'none' : '';
                    amountInput.style.display = isEditMode ? 'inline-block' : 'none';
                }

                if (memoText && memoInput) {
                    memoText.style.display = isEditMode ? 'none' : '';
                    memoInput.style.display = isEditMode ? 'inline-block' : 'none';
                }

                // 支出のみ: star_rate と is_waste の切替
                if (type === 'expenditure') {
                    const starText = row.querySelector('.star-rating-text');
                    const starSelect = row.querySelector('.edit-star-rate');
                    const wasteText = row.querySelector('.waste-text');
                    const wasteSelect = row.querySelector('.edit-waste');

                    if (starText && starSelect) {
                        starText.style.display = isEditMode ? 'none' : '';
                        starSelect.style.display = isEditMode ? 'inline-block' : 'none';
                    }
                    if (wasteText && wasteSelect) {
                        wasteText.style.display = isEditMode ? 'none' : '';
                        wasteSelect.style.display = isEditMode ? 'inline-block' : 'none';
                    }
                }

                // チェックボックスは常に表示。削除ボタンの表示は後で切り替え
            });

            // ボタン切替
            const deleteBtn = document.getElementById(type + '-delete-button');
            const saveBtn = document.getElementById(type + '-save-button');

            if (deleteBtn && saveBtn) {
                deleteBtn.style.display = isEditMode ? 'none' : '';
                saveBtn.style.display = isEditMode ? '' : 'none';
            }
        });

        toggleBtn.textContent = isEditMode ? '編集モード解除' : '編集モード';
    });
</script>
