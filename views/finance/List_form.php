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

    <!-- タブナビゲーション -->
    <div class="tab-navigation">
        <button id="incomeTab" class="tab-button">
            収入一覧
        </button>
        <button id="expenditureTab" class="tab-button active">
            支出一覧
        </button>
    </div>

    <div class="swiper finance-swiper">
        <div class="swiper-wrapper">
            <!-- 支出一覧 -->
            <div class="swiper-slide">
                <form method="POST" action="/List/Delete" class="finance-form">
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
                                    <tr>
                                        <td><?= htmlspecialchars($exp['input_date']) ?></td>
                                        <td><?= htmlspecialchars($exp['category_name']) ?></td>
                                        <td class="amount"><?= number_format($exp['amount']) ?></td>
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
                                        <td class="memo"><?= htmlspecialchars($exp['description']) ?></td>
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
                        <button type="submit" name="delete" value="1" class="delete-button">
                            <svg class="delete-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            選択項目を削除
                        </button>
                    </div>
                </form>
            </div>

            <!-- 収入一覧 -->
            <div class="swiper-slide">
                <form method="POST" action="/List/Delete" class="finance-form">
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
                                    <tr>
                                        <td><?= htmlspecialchars($inc['input_date']) ?></td>
                                        <td><?= htmlspecialchars($inc['category_name']) ?></td>
                                        <td class="amount"><?= number_format($inc['amount']) ?></td>
                                        <td class="memo"><?= htmlspecialchars($inc['description']) ?></td>
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

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    // Swiperの初期化
    const swiper = new Swiper('.finance-swiper', {
        loop: false,
        allowTouchMove: false, // タッチでのスワイプを無効化（タブで制御するため）
    });

    // タブ切り替え
    const expenditureTab = document.getElementById('expenditureTab');
    const incomeTab = document.getElementById('incomeTab');

    expenditureTab.addEventListener('click', function() {
        swiper.slideTo(0);
        expenditureTab.classList.add('active');
        incomeTab.classList.remove('active');
    });

    incomeTab.addEventListener('click', function() {
        swiper.slideTo(1);
        incomeTab.classList.add('active');
        expenditureTab.classList.remove('active');
    });

    // スワイプ後にタブの状態を更新
    swiper.on('slideChange', function () {
        if (swiper.activeIndex === 0) {
            expenditureTab.classList.add('active');
            incomeTab.classList.remove('active');
        } else {
            incomeTab.classList.add('active');
            expenditureTab.classList.remove('active');
        }
    });
</script>