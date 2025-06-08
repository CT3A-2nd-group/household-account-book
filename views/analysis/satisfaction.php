<div class="analysis-container">
    <h2 class="h2page-title">支出に対する分析</h2>

    <section class="analysis-section">
        <h3>満足度ランキング（上位5件）</h3>
        <table class="analysis-table">
            <thead>
            <tr><th>日付</th><th>カテゴリ</th><th>金額(円)</th><th>満足度</th><th>メモ</th></tr>
            </thead>
            <tbody>
            <?php foreach ($ranking as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['input_date']) ?></td>
                    <td><?= htmlspecialchars($r['category_name']) ?></td>
                    <td class="amount"><?= number_format($r['amount']) ?></td>
                    <td class="star-rating">
                        <?= str_repeat('★', (int)$r['star_rate']) . str_repeat('☆', 5 - (int)$r['star_rate']) ?>
                    </td>
                    <td class="memo"><?= htmlspecialchars($r['description']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($ranking)): ?>
                <tr><td colspan="5" class="no-data">データがありません</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>

    <section class="analysis-section">
        <h3>無駄な支出</h3>
        <table class="analysis-table">
            <thead>
            <tr><th>日付</th><th>カテゴリ</th><th>金額(円)</th><th>満足度</th><th>メモ</th></tr>
            </thead>
            <tbody>
            <?php foreach ($wastes as $w): ?>
                <tr>
                    <td><?= htmlspecialchars($w['input_date']) ?></td>
                    <td><?= htmlspecialchars($w['category_name']) ?></td>
                    <td class="amount"><?= number_format($w['amount']) ?></td>
                    <td class="star-rating">
                        <?= str_repeat('★', (int)$w['star_rate']) . str_repeat('☆', 5 - (int)$w['star_rate']) ?>
                    </td>
                    <td class="memo"><?= htmlspecialchars($w['description']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($wastes)): ?>
                <tr><td colspan="5" class="no-data">無駄遣いはありません</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>

    <section class="analysis-section">
        <h3>カテゴリ別平均満足度</h3>
        <table class="analysis-table">
            <thead>
            <tr><th>カテゴリ</th><th>平均満足度</th></tr>
            </thead>
            <tbody>
            <?php foreach ($averages as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['category_name']) ?></td>
                    <td><?= number_format($a['avg_rate'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($averages)): ?>
                <tr><td colspan="2" class="no-data">データがありません</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>