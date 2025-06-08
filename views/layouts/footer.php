<?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        </main>
    </div>
    <script>
    // 管理者用メニュー制御
    document.addEventListener('DOMContentLoaded', function() {
        // アクティブなナビゲーションアイテムをハイライト
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.admin-nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (currentPath === href || (href !== '/admin' && currentPath.startsWith(href))) {
                link.classList.add('active');
            }
        });
    });
    </script>

<?php elseif (isset($_SESSION['user_id'])): ?>
            </div>
        </main>
        
        <!-- 右サイドバー（補足情報） -->
        <aside class="right-sidebar">
            <!-- 通知セクション -->
            <div class="sidebar-section">
                <div class="section-header">
                    <h3 class="section-title">通知</h3>
                    <span class="notification-count">3</span>
                </div>
                <div class="notification-list">
                    <div class="notification-item unread">
                        <div class="notification-icon warning-icon"></div>
                        <div class="notification-content">
                            <div class="notification-text">今月の支出が予算を超過しています</div>
                            <div class="notification-time">2時間前</div>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-icon info-icon"></div>
                        <div class="notification-content">
                            <div class="notification-text">給与が振り込まれました</div>
                            <div class="notification-time">1日前</div>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-icon success-icon"></div>
                        <div class="notification-content">
                            <div class="notification-text">貯金目標を達成しました</div>
                            <div class="notification-time">3日前</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 目標進捗セクション -->
            <div class="sidebar-section">
                <div class="section-header">
                    <h3 class="section-title">今月の目標</h3>
                </div>
                <div class="goal-list">
                    <div class="goal-item">
                        <div class="goal-info">
                            <div class="goal-name">貯金目標</div>
                            <div class="goal-amount">¥50,000 / ¥100,000</div>
                        </div>
                        <div class="goal-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 50%"></div>
                            </div>
                            <div class="progress-text">50%</div>
                        </div>
                    </div>
                    
                    <div class="goal-item">
                        <div class="goal-info">
                            <div class="goal-name">食費予算</div>
                            <div class="goal-amount">¥45,000 / ¥40,000</div>
                        </div>
                        <div class="goal-progress">
                            <div class="progress-bar">
                                <div class="progress-fill over-budget" style="width: 100%"></div>
                            </div>
                            <div class="progress-text over">112%</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 今月の収支サマリー -->
            <div class="sidebar-section">
                <div class="section-header">
                    <h3 class="section-title">今月の収支</h3>
                </div>
                <div class="summary-chart">
                    <div class="chart-placeholder">
                        <!-- 簡易チャートまたはグラフ表示エリア -->
                        <div class="mini-chart">
                            <div class="chart-bar income-bar" style="height: 60%"></div>
                            <div class="chart-bar expense-bar" style="height: 45%"></div>
                            <div class="chart-bar balance-bar" style="height: 15%"></div>
                        </div>
                        <div class="chart-labels">
                            <span class="chart-label">収入</span>
                            <span class="chart-label">支出</span>
                            <span class="chart-label">残高</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- クイックアクション -->
            <div class="sidebar-section">
                <div class="section-header">
                    <h3 class="section-title">クイックアクション</h3>
                </div>
                <div class="quick-actions">
                    <a href="/income/create" class="quick-action-btn income-btn">
                        <span class="quick-action-icon quick-action-income-icon"></span>
                        <span class="action-text">収入登録</span>
                    </a>
                    <a href="/expenditure/create" class="quick-action-btn expense-btn">
                        <span class="quick-action-icon quick-action-expense-icon"></span>
                        <span class="action-text">支出登録</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- モバイル用オーバーレイ -->
        <div class="mobile-overlay" id="mobileOverlay" onclick="closeMobileMenu()"></div>
    </div>
<?php else: ?>
        </main>
    </div>
<?php endif; ?>
</body>
</html>
