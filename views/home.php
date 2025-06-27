<!-- ホームページのメインコンテンツ -->
<div class="dashboard-container">
  <!-- ユーザー情報セクション -->
  <section class="welcome-section">
    <div class="user-greeting-row">
        <h2 class="greeting-text">ようこそ、<?= htmlspecialchars($username) ?> さん！</h2>

        <div class="conditional-buttons">
            <?php if (!$hasGoal): ?>
                <a href="/finance/goal-form" class="btn btn-goal">🎯 目標登録</a>
            <?php endif; ?>
            <a href="/finance/save-form" class="btn btn-saving">💰 貯金額登録</a>
            <a href="/finance/save-goalsaving" class="btn btn-saving">💰目標貯金額登録</a>
        </div>
    </div>
  </section>


  <!-- 財務サマリー -->
  <section class="finance-summary">
    <div class="summary-cards">

      <!-- 今月の自由資金 -->
      <div class="summary-card income-card">
        <div class="card-header">
          <h3 class="card-title">今月の自由資金</h3>
          <span class="card-icon thismonth-icon"></span>
        </div>
        <p class="card-amount" style="color: #10b981;">¥<?= number_format($freeMoney[$latestMonth] ?? 0) ?></p>
        <?php
          $current = $freeMoney[$latestMonth] ?? 0;
          $previous = $freeMoney[$prevMonth] ?? 0;
          $diff = $current - $previous;

          // クラスを決定（値によって）
          $diffClass = $diff > 0 ? 'plus' : ($diff < 0 ? 'minus' : 'zero');

          // 表示する文字列（+記号付きにして数値フォーマット）
          $formattedDiff = ($diff > 0 ? '+' : '') . number_format($diff);
        ?>
        <p class="card-change">
          前月比：
          <span class="diff-number <?= $diffClass ?>">
            <?= $formattedDiff ?>
          </span>
        </p>

      </div>

      <!-- 総自由資金 -->
      <div class="summary-card balance-card">
        <div class="card-header">
          <h3 class="card-title">現在の自由資金</h3>
          <span class="card-icon balance-icon"></span>
        </div>
        <p class="card-amount" style="color: #3b82f6;">¥<?= number_format($totalFreeMoney) ?></p>
        <p class="card-change">累計貯蓄額</p>
      </div>

      <!-- 目標達成率 -->
      <div class="summary-card" style="border-left-color: #a855f7;">
        <div class="card-header">
          <h3 class="card-title">目標達成率</h3>
          <span class="card-icon" style="background-color: #f3e8ff;"></span>
        </div>
        <p class="card-amount" style="color: #a855f7;"> <?= number_format($goalProgress, 1) ?>%</p>
        <p class="card-change">現在の進捗</p>
      </div>

    </div>
  </section>



  <div class="goal-progress-card">
    <!-- 左：タイトル＋バー -->
    <div class="goal-left">
      <div class="goal-progress-label">
        目標：<?= htmlspecialchars($goalTitle ?? '未設定') ?>
      </div>
      <div id="circle-goal-bar" class="circle-progress-bar" data-progress="<?= $goalProgress ?>"></div>
    </div>

    <!-- 右：詳細 -->
    <div class="goal-right">
      <div class="goal-amount-grid">
        <div class="goal-box">
          <div class="label">現在の自由資金額</div>
          <div class="value">¥<?= number_format($totalFreeMoney) ?></div>
        </div>
        <div class="goal-box">
          <div class="label">目標金額</div>
          <div class="value">¥<?= number_format($goalMoney) ?></div>
        </div>
      </div>

      <div class="goal-remaining-box">
        <div class="label">あと必要な金額</div>
        <div class="value">¥<?= number_format(max(0, $goalMoney - $totalFreeMoney)) ?></div>
      </div>
      <div class="clear-delete-buttons">
        <!-- 達成率100%になったら現れるタイプのボタン -->
        <?php if ($goalProgress >= 100): ?>
        <form action="/goal/finish" method="POST">
          <button type="submit" class="goal-clear-button">目標達成！</button>
        </form>
        <?php endif; ?>
        <!-- 目標を設定時に現れるタイプの削除ボタン -->
        <?php if ($hasGoal && ($goalProgress) < 100): ?>
          <form action="/goal/delete" method="POST">
            <button type="submit" class="goal-delete-button">目標削除</button>
          </form>
        <?php endif ?>
      </div>
    </div>
  </div>


<div class="finance-container">
    <h2 class="h2page-title">自由資金一覧</h2>

    <div class="table-container">
        <table class="finance-table paginated-table">
            <thead>
                <tr>
                    <th>月</th>
                    <th>金額（円）</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($freeMoney)): ?>
                    <?php foreach ($freeMoney as $month => $amount): ?>
                        <tr>
                            <td><?= htmlspecialchars($month) ?></td>
                            <td class="amount">¥<?= number_format($amount, 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="no-data">自由資金のデータがありません</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


</div>
