    <!-- ログイン前のシンプルなレイアウト -->
    <div class="simple-layout">
        <main class="simple-main">
            <div class="auth-container">
                <div class="auth-card signup">
                    <!-- 登録フォームヘッダー -->
                    <div class="auth-header">
                        <div class="auth-icon">✨</div>
                        <h2 class="auth-title">新規登録</h2>
                        <p class="auth-subtitle">アカウントを作成して始めましょう</p>
                    </div>
                    
                    <!-- エラーメッセージ表示 -->
                    <?php if (isset($_GET['error'])): ?>
                        <div class="error-message"><?= htmlspecialchars($_GET['error']) ?></div>
                    <?php endif; ?>
                    
                    <!-- 成功メッセージ表示 -->
                    <?php if (isset($_GET['success'])): ?>
                        <div class="success-message"><?= htmlspecialchars($_GET['success']) ?></div>
                    <?php endif; ?>
                    
                    <!-- 登録フォーム -->
                    <form class="auth-form" action="/register" method="POST">
                        <!-- ユーザー名入力 -->
                        <div class="form-group">
                            <label for="username" class="form-label">ユーザー名</label>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="form-input" 
                                placeholder="ユーザー名を入力してください"
                                required
                                autocomplete="username"
                                value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                            >
                            <div class="input-hint">3文字以上で入力してください</div>
                        </div>

                        <!-- パスワード入力 -->
                        <div class="form-group">
                            <label for="password" class="form-label">パスワード</label>
                            <div class="password-input-wrapper">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-input" 
                                    placeholder="パスワードを入力してください"
                                    required
                                    autocomplete="new-password"
                                >
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <span class="toggle-icon show"></span>
                                </button>
                            </div>
                            <div class="input-hint">4文字以上で英字を含めてください</div>
                        </div>
                        
                        <!-- パスワード確認入力 -->
                        <div class="form-group">
                            <label for="password_confirm" class="form-label">パスワード確認</label>
                            <div class="password-input-wrapper">
                                <input 
                                    type="password" 
                                    id="password_confirm" 
                                    name="password_confirm" 
                                    class="form-input" 
                                    placeholder="パスワードを再入力してください"
                                    required
                                    autocomplete="new-password"
                                >
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirm')">
                                    <span class="toggle-icon show"></span>
                                </button>
                            </div>
                            <div class="input-hint">上記と同じパスワードを入力してください</div>
                        </div>
                        
                        <!-- 利用規約同意 -->
                        <div class="form-group">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="terms" class="checkbox-input" required>
                                <span class="checkbox-custom"></span>
                                <span class="checkbox-label">
                                    <a href="/terms" target="_blank" class="terms-link">利用規約</a>に同意します
                                </span>
                            </label>
                        </div>
                        
                        <!-- 登録ボタン -->
                        <button type="submit" class="auth-button">アカウントを作成</button>
                        
                        <!-- ログインリンク -->
                        <div class="auth-prompt">
                            <p class="auth-text">すでにアカウントをお持ちの方は <a href="/login" class="auth-link">ログイン</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    
    <!-- JavaScript -->
    <script>
        // パスワード表示/非表示の切り替え
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = passwordInput.parentElement.querySelector('.toggle-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'toggle-icon hide';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'toggle-icon show';
            }
        }
        
        // パスワード一致チェック
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            const confirmInput = document.getElementById('password_confirm');
            
            if (passwordConfirm && password !== passwordConfirm) {
                confirmInput.setCustomValidity('パスワードが一致しません');
                confirmInput.classList.add('error');
            } else {
                confirmInput.setCustomValidity('');
                confirmInput.classList.remove('error');
            }
        }
        
        // リアルタイムバリデーション
        document.getElementById('password_confirm').addEventListener('input', checkPasswordMatch);
        document.getElementById('password').addEventListener('input', checkPasswordMatch);
        
        // フォーム送信時のバリデーション
        document.querySelector('.auth-form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            const terms = document.querySelector('input[name="terms"]').checked;
            
            // ユーザー名チェック
            if (username.length < 3) {
                e.preventDefault();
                alert('ユーザー名は3文字以上で入力してください。');
                document.getElementById('username').focus();
                return;
            }
            
            // パスワードチェック
            if (password.length < 8) {
                e.preventDefault();
                alert('パスワードは8文字以上で入力してください。');
                document.getElementById('password').focus();
                return;
            }
            
            // パスワード一致チェック
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('パスワードが一致しません。');
                document.getElementById('password_confirm').focus();
                return;
            }
            
            // 利用規約同意チェック
            if (!terms) {
                e.preventDefault();
                alert('利用規約とプライバシーポリシーに同意してください。');
                return;
            }
        });
    </script>

    <?php if (!empty($noAdmin)): ?>
        <a href="/temp_admin">管理者用</a>
    <?php endif; ?>

</body>
</html>
