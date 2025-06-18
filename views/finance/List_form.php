<div class="finance-container">
    <h2 class="h2page-title">収支一覧</h2>

    <!-- タブナビゲーション -->
    <div class="tab-navigation">
        <button id="incomeTab" class="tab-button active">
            収入一覧
        </button>
        <button id="expenditureTab" class="tab-button">
            支出一覧
        </button>
    </div>

    <div class="swiper finance-swiper">
        <div class="swiper-wrapper">
            <!-- 収入一覧 -->
            <div class="swiper-slide">
                <form method="POST" action="/List/Delete" class="finance-form">
                    <input type="hidden" name="target_type" value="income">
                    
                    <div class="table-container">
                        <table class="finance-table paginated-table">
                            <thead>
                                <tr>
                                    <th>日付</th>
                                    <th>カテゴリ</th>
                                    <th>金額</th>
                                    <th>メモ</th>
                                    <th>編集</th>
                                    <th>削除</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($incomes as $inc): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($inc['input_date'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($inc['category_name'] ?? '') ?></td>
                                        <td class="amount"><?= number_format($inc['amount']) ?> 円</td>
                                        <td class="memo"><?= htmlspecialchars($inc['description'] ?? '') ?></td>
                                        <td><a href="/income/edit?id=<?= htmlspecialchars($inc['id']) ?>">編集</a></td>
                                        <td class="checkbox-cell">
                                            <label class="checkbox-label">
                                                <input type="checkbox" name="delete_ids[]" value="<?= htmlspecialchars($inc['id'] ?? '') ?>" class="delete-checkbox">
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($incomes)): ?>
                                    <tr>
                                        <td colspan="6" class="no-data">データがありません</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="delete" value="1" class="delete-button">
                            <svg class="delete-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            選択項目を削除
                        </button>
                    </div>
                </form>
            </div>
            <!-- 支出一覧 -->
            <div class="swiper-slide">
                <form method="POST" action="/List/Delete" class="finance-form">
                    <input type="hidden" name="target_type" value="expenditure">
                    
                    <div class="table-container">
                        <table class="finance-table paginated-table">
                            <thead>
                                <tr>
                                    <th>日付</th>
                                    <th>カテゴリ</th>
                                    <th>金額</th>
                                    <th>満足度</th>
                                    <th>無駄遣い</th>
                                    <th>メモ</th>
                                    <th>編集</th>
                                    <th>削除</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($expenditures as $exp): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($exp['input_date'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($exp['category_name'] ?? '') ?></td>
                                        <td class="amount"><?= number_format($exp['amount']) ?> 円</td>
                                        <td class="star-rating">
                                            <?= $exp['star_rate'] ? str_repeat('★', (int)$exp['star_rate']) . str_repeat('☆', 5 - (int)$exp['star_rate']) : '評価なし' ?>
                                        </td>
                                        <td>
                                            <?php if($exp['is_waste']): ?>
                                                <span class="badge badge-waste">はい</span>
                                            <?php else: ?>
                                                <span class="badge badge-no-waste">いいえ</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="memo"><?= htmlspecialchars($exp['description'] ?? '') ?></td>
                                        <td><a href="/expenditure/edit?id=<?= htmlspecialchars($exp['id']) ?>">編集</a></td>
                                        <td class="checkbox-cell">
                                            <label class="checkbox-label">
                                                <input type="checkbox" name="delete_ids[]" value="<?= htmlspecialchars($exp['id'] ?? '') ?>" class="delete-checkbox">
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($expenditures)): ?>
                                    <tr>
                                        <td colspan="8" class="no-data">データがありません</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="delete" value="1" class="delete-button">
                            <svg class="delete-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            選択項目を削除
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const savedTab = localStorage.getItem('selectedTab');
    const initialIndex = savedTab === 'expenditure' ? 1 : 0;

    const wrapper = document.querySelector('.finance-swiper .swiper-wrapper');
    if (wrapper) wrapper.style.visibility = 'hidden'; // 一時的に非表示

    const swiper = new Swiper('.finance-swiper', {
        loop: false,
        allowTouchMove: false,
        autoHeight: false,
        initialSlide: initialIndex,
        on: {
            init: function () {
                // 初期化完了後に表示
                if (wrapper) wrapper.style.visibility = 'visible';
            }
        }
    });

    const expenditureTab = document.getElementById('expenditureTab');
    const incomeTab = document.getElementById('incomeTab');

    function updateTabActiveState(index) {
        if (index === 0) {
            incomeTab.classList.add('active');
            expenditureTab.classList.remove('active');
            localStorage.setItem('selectedTab', 'income');
        } else {
            incomeTab.classList.remove('active');
            expenditureTab.classList.add('active');
            localStorage.setItem('selectedTab', 'expenditure');
        }
    }

    updateTabActiveState(initialIndex);

    incomeTab.addEventListener('click', () => {
        swiper.slideTo(0);
        updateTabActiveState(0);
    });

    expenditureTab.addEventListener('click', () => {
        swiper.slideTo(1);
        updateTabActiveState(1);
    });

    swiper.on('slideChange', () => {
        updateTabActiveState(swiper.activeIndex);
    });
});

</script>


