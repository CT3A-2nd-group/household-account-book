<div class="admin-container">
    <div class="admin-card">
        <!-- カードヘッダー -->
        <div class="card-header">
            <div class="card-icon">🏷️</div>
            <h2 class="card-title">カテゴリ管理</h2>
            <p class="card-subtitle">収入・支出カテゴリの追加と管理</p>
        </div>

        <!-- アラートメッセージ -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>

        <!-- カテゴリ追加フォーム -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-plus"></i>
                新しいカテゴリを追加
            </h3>
            
            <form action="/admin/category/store" method="POST" class="category-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">カテゴリ名</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-input" 
                            placeholder="例: 食費、給与など"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="type" class="form-label">種別</label>
                        <select id="type" name="type" class="form-input" required>
                            <option value=""disabled selected>種別を選択</option>
                            <option value="income">収入</option>
                            <option value="expenditure">支出</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="add-button">
                            <i class="fas fa-plus"></i>
                            追加
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- カテゴリ一覧 -->
        <div class="categories-section">
            <div class="categories-grid">
                <!-- 収入カテゴリ -->
                <div class="category-section">
                    <div class="section-header">
                        <h4 class="section-title">
                            <i class="fas fa-arrow-up text-green"></i>
                            収入カテゴリ
                            <span class="badge"><?= count($incomeCategories) ?></span>
                        </h4>
                    </div>
                    
                    <div class="category-list">
                        <?php if (empty($incomeCategories)): ?>
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>収入カテゴリがありません</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($incomeCategories as $cat): ?>
                                <div class="category-item">
                                    <div class="category-info">
                                        <i class="fas fa-tag text-green"></i>
                                        <span class="category-name"><?= htmlspecialchars($cat['name']) ?></span>
                                    </div>
                                    <form action="/admin/category/delete" method="POST" 
                                          onsubmit="return confirm('「<?= htmlspecialchars($cat['name']) ?>」を削除しますか？この操作は取り消せません。');"
                                          class="delete-form">
                                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                        <button type="submit" class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            
                <!-- 支出カテゴリ -->
                <div class="category-section">
                    <div class="section-header">
                        <h4 class="section-title">
                            <i class="fas fa-arrow-down text-red"></i>
                            支出カテゴリ
                            <span class="badge"><?= count($expenditureCategories) ?></span>
                        </h4>
                    </div>
                    
                    <div class="category-list">
                        <?php if (empty($expenditureCategories)): ?>
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>支出カテゴリがありません</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($expenditureCategories as $cat): ?>
                                <div class="category-item">
                                    <div class="category-info">
                                        <i class="fas fa-tag text-red"></i>
                                        <span class="category-name"><?= htmlspecialchars($cat['name']) ?></span>
                                    </div>
                                    <form action="/admin/category/delete" method="POST" 
                                          onsubmit="return confirm('「<?= htmlspecialchars($cat['name']) ?>」を削除しますか？この操作は取り消せません。');"
                                          class="delete-form">
                                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                        <button type="submit" class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                
        </div>
    </div>
</div>