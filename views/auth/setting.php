<div class="settings-container">
    <div class="settings-header">
        <h1 class="settings-title">アカウント設定</h1>
        <p class="settings-subtitle">プロフィール情報やセキュリティ設定を管理できます</p>
    </div>

    <div class="settings-content">
        <!-- 成功/エラー メッセージ -->
        <?php if (!empty($success)): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- プロフィール -->
        <div class="settings-section">
            <div class="section-header">
                <h2 class="section-title"><span class="section-icon">👤</span>プロフィール情報</h2>
                <p class="section-description">基本的なアカウント情報を編集できます</p>
            </div>

            <div class="settings-card">
                <form class="settings-form" method="POST" action="/auth/update-username">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username" class="form-label">ユーザー名</label>
                            <input type="text" id="username" name="username" class="form-input"
                                value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <button type="submit" class="btn btn-primary">ユーザー名を更新</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- パスワード変更 -->
        <div class="settings-section">
            <div class="section-header">
                <h2 class="section-title"><span class="section-icon">🔒</span>パスワード変更</h2>
                <p class="section-description">セキュリティのため定期的な変更をおすすめします</p>
            </div>

            <div class="settings-card">
                <form class="settings-form" method="POST" action="/auth/change-password">
                    <div class="form-group">
                        <label for="current_password" class="form-label">現在のパスワード</label>
                        <input type="password" id="current_password" name="current_password" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="new_password" class="form-label">新しいパスワード</label>
                        <input type="password" id="new_password" name="new_password" class="form-input" minlength="8" required>
                        <div class="form-help">8文字以上で入力してください</div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password" class="form-label">新しいパスワード（確認）</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <span class="btn-icon">🔒</span>パスワードを変更
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 通知設定 -->
        <div class="settings-section">
            <div class="section-header">
                <h2 class="section-title"><span class="section-icon">🔔</span>通知設定</h2>
                <p class="section-description">必要な通知をON/OFFできます</p>
            </div>

            <div class="settings-card">
                <form class="settings-form" method="POST" action="/auth/update-notifications">
                    <div class="notification-settings">
                        <?php
                        $options = [
                            'budget_alert'      => '月の支出が予算を超えた時に通知します',
                            'income_reminder'   => '給与日などの収入登録を忘れないよう通知します',
                            'goal_achievement'  => '貯金目標などを達成した時に通知します',
                            'weekly_report'     => '毎週の収支サマリーをメールで送信します',
                        ];
                        foreach ($options as $key => $description):
                        ?>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h3 class="notification-title"><?= htmlspecialchars($key) ?></h3>
                                    <p class="notification-description"><?= $description ?></p>
                                </div>
                                <div class="notification-toggle">
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="<?= $key ?>" <?= !empty($notifications[$key]) ? 'checked' : '' ?>>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <span class="btn-icon">🔔</span>通知設定を保存
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ホームに戻る -->
        <div class="settings-footer">
            <a href="/home" class="btn btn-secondary">
                <span class="btn-icon">🏠</span>ホームに戻る
            </a>
        </div>
    </div>
</div>

<!-- パスワード一致チェック -->
<script>
document.getElementById('confirm_password')?.addEventListener('input', function () {
    const newPassword = document.getElementById('new_password').value;
    if (this.value !== newPassword) {
        this.setCustomValidity('パスワードが一致しません');
    } else {
        this.setCustomValidity('');
    }
});
</script>
