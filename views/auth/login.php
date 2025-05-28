<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - 家計簿アプリ</title>
    
    <!-- 共通CSS -->
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/css/layout.css">
    <!-- ログイン専用CSS -->
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>
    <!-- ログイン前のシンプルなレイアウト -->
    <div class="simple-layout">
        <main class="simple-main">
            <div class="login-container">
                <div class="login-card">
                    <!-- ログインフォームヘッダー -->
                    <div class="login-header">
                        <div class="login-icon">🔐</div>
                        <h2 class="login-title">ログイン</h2>
                        <p class="login-subtitle">アカウントにサインインしてください</p>
                    </div>
                    
                    <!-- エラーメッセージ表示 -->
                    <?php if (isset($_GET['error'])): ?>
                        <div class="error-message"><?= htmlspecialchars($_GET['error']) ?></div>
                    <?php endif; ?>
                    
                    <!-- ログインフォーム -->
                    <form class="login-form" action="/login" method="POST">
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
                            <a href="/forgot-password" class="forgot-password-link">パスワードを忘れた方</a>
                        </div>
                        
                        <!-- ログインボタン -->
                        <button type="submit" class="login-button">ログイン</button>
                        
                        <!-- 新規登録リンク -->
                        <div class="register-prompt">
                            <p class="register-text">アカウントをお持ちでない方は <a href="/register" class="register-link">新規登録</a></p>
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
