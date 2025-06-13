<div class="settings-container">
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
            <div class="section-header1">
                <h2 class="section-title1"><span class="section-icon1">👤</span>プロフィール情報</h2>
                <p class="section-description1">基本的なアカウント情報を編集できます</p>
            </div>

            <div class="settings-card">
                <form class="settings-form" method="POST" action="/auth/update-username">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username" class="form-label">ユーザー名</label>
                            <input type="text" id="username" name="username" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="form-row">
                            <button type="submit" class="btn btn-primary">ユーザー名を更新</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- パスワード変更 -->
        <div class="settings-section">
            <div class="section-header1">
                <h2 class="section-title1"><span class="section-icon1">🔒</span>パスワード変更</h2>
                <p class="section-description1">セキュリティのため定期的な変更をおすすめします</p>
            </div>

            <div class="settings-card">
                <form class="settings-form" method="POST" action="/auth/change-password">
                    <div class="form-group">
                        <label for="current_password" class="form-label">現在のパスワード</label>
                                                <div class="password-input-wrapper">
                            <input type="password" id="current_password" name="current_password" class="form-input" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                <span class="toggle-icon show"></span>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="new_password" class="form-label">新しいパスワード</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="new_password" name="new_password" class="form-input" minlength="4" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                <span class="toggle-icon show"></span>
                            </button>
                        </div>
                        <div class="form-help">4文字以上で入力してください</div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password" class="form-label">新しいパスワード（確認）</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                <span class="toggle-icon show"></span>
                            </button>
                        </div>
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
            <div class="section-header1">
                <h2 class="section-title1"><span class="section-icon1">🔔</span>通知設定</h2>
                <p class="section-description1">必要な通知をON/OFFできます</p>
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
                        <button type="button" class="btn btn-primary">
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
function togglePassword(id) {
    const input = document.getElementById(id);
    const icon = input.parentElement.querySelector('.toggle-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'toggle-icon hide';
    } else {
        input.type = 'password';
        icon.className = 'toggle-icon show';
    }
}


document.getElementById('confirm_password')?.addEventListener('input', function () {
    const newPassword = document.getElementById('new_password').value;
    if (this.value !== newPassword) {
        this.setCustomValidity('パスワードが一致しません');
    } else {
        this.setCustomValidity('');
    }
});
</script>
