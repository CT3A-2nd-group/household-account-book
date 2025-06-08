<div class="finance-container">
    <h2 class="h2page-title">貯金額一覧</h2>

    <div class="table-container">
        <table class="finance-table">
            <thead>
                <tr>
                    <th>年</th>
                    <th>月</th>
                    <th>貯金額（円）</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($savings)): ?>
                    <?php foreach ($savings as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['year']) ?></td>
                            <td><?= htmlspecialchars($row['month']) ?></td>
                            <td class="amount"><?= number_format($row['saved_this_month']) ?> 円</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="no-data">貯金額のデータがありません</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
