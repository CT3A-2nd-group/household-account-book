<div class="finance-container">
    <h2 class="h2page-title">収支一覧</h2>

    <!-- タブナビゲーション -->
    <div class="tab-navigation">
        <button id="incomeTab" class="tab-button active">収入一覧</button>
        <button id="expenditureTab" class="tab-button">支出一覧</button>
    </div>

    <!-- 編集モード切替 -->
    <div style="text-align: right; margin-bottom: 8px;">
        <button id="toggle-edit-mode">編集モード</button>
    </div>

    <div class="swiper finance-swiper">
        <div class="swiper-wrapper">

            <!-- 収入一覧 -->
            <div class="swiper-slide">
                <form method="POST" action="/List/Delete" class="finance-form" id="income-form">
                    <input type="hidden" name="target_type" value="income">

                    <div class="table-container">
                        <table class="finance-table paginated-table">
                            <thead>
                                <tr>
                                    <th>日付</th>
                                    <th>金額</th>
                                    <th>カテゴリ</th>
                                    <th>メモ</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody id="income-table-body">
                            </tbody>
                        </table>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="delete" value="1" class="delete-button" id="income-delete-button">
                            <svg class="delete-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            選択項目を削除
                        </button>
                        <button type="submit" name="save" value="1" class="save-button" id="income-save-button" style="display:none;">
                        </button>
                    </div>
                </form>
            </div>

            <!-- 支出一覧 -->
            <div class="swiper-slide">
                <form method="POST" action="/List/Delete" class="finance-form" id="expenditure-form">
                    <input type="hidden" name="target_type" value="expenditure">

                    <div class="table-container">
                        <table class="finance-table paginated-table">
                            <thead>
                                <tr>
                                    <th>日付</th>
                                    <th>金額(円)</th>
                                    <th>カテゴリ</th>
                                    <th>満足度</th>
                                    <th>無駄遣い</th>
                                    <th>メモ</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody id="expenditure-table-body">
                                <!-- JSで追加 -->
                            </tbody>
                        </table>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="delete" value="1" class="delete-button" id="expenditure-delete-button">
                            <svg class="delete-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            選択項目を削除
                        </button>
                        <button type="submit" name="save" value="1" class="save-button" id="expenditure-save-button" style="display:none;">
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="/js/Finance/list.js?v=<?= time() ?>"></script>