    <!-- ログイン前のシンプルなレイアウト -->
    <div class="simple-layout">
        <main class="simple-main">
            <div class="auth-container">
                <div class="auth-card">
                    <!-- ログインフォームヘッダー -->
                    <div class="auth-header">
                        <div class="auth-icon">🔐</div>
                        <h2 class="auth-title">ログイン</h2>
                        <p class="auth-subtitle">アカウントにサインインしてください</p>
                    </div>
                    
                    <!-- エラーメッセージ表示 -->
                    <?php if (isset($_GET['error'])): ?>
                        <div class="error-message"><?= htmlspecialchars($_GET['error']) ?></div>
                    <?php endif; ?>
                    
                    <!-- ログインフォーム -->
                    <form class="auth-form" action="/login" method="POST">
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
                            >
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
                                    autocomplete="current-password"
                                >
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <span class="toggle-icon show"></span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- オプション -->
                        <div class="form-options">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="remember" class="checkbox-input">
                                <span class="checkbox-custom"></span>
                                <span class="checkbox-label">ログイン状態を保持する</span>
                            </label>
                            <a class="forgot-password-link">パスワードを忘れた方</a>
                        </div>
                        
                        <!-- ログインボタン -->
                        <button type="submit" class="auth-button">ログイン</button>
                        
                        <!-- 新規登録リンク -->
                        <div class="auth-prompt">
                            <p class="auth-text">アカウントをお持ちでない方は <a href="/register" class="auth-link">新規登録</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    
    <!-- JavaScript -->
    <script>
        // パスワード表示/非表示の切り替え
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'toggle-icon hide';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'toggle-icon show';
            }
        }
    </script>
</body>
</html>
