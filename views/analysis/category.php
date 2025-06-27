<div class="analysis-container">
    <h2 class="h2page-title">カテゴリ別平均満足度</h2>

    <section class="analysis-section">
        <div class="table-container">
            <table class="analysis-table paginated-table">
                <thead>
                    <tr><th>カテゴリ</th><th>平均満足度</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($averages as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['category_name'] ?? '') ?></td>
                            <td><?= number_format($a['avg_rate'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($averages)): ?>
                        <tr><td colspan="2" class="no-data">データがありません</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>