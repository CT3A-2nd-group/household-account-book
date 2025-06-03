<link rel="stylesheet" href="/css/income.css">

<div class="finance-container">
    <h2 class="page-title"><span class="page-title-text">収入を登録</span></h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form action="/income/create" method="POST" class="finance-form">
        <div class="form-group">
            <label class="form-label">日付</label>
            <div class="date-input-wrapper">
                <input type="date" name="input_date" value="<?= date('Y-m-d') ?>" required class="date-input" id="date-input">
                <button type="button" class="calendar-button" onclick="openCalendar()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">カテゴリ</label>
            <div class="select-wrapper">
                <select name="category_id" required class="form-select">
                    <option value="" disabled selected>-- カテゴリを選択 --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['id']) ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="amount" class="form-label">金額</label>
            <div class="input-with-icon">
                <input type="text" name="amount" id="amount" placeholder="金額を入力" required class="form-input amount-input" inputmode="numeric">
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">メモ</label>
            <div class="input-with-icon">
                <input type="text" name="description" id="description" placeholder="詳細" class="form-input">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-button">
                <svg class="button-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                登録する
            </button>
        </div>
    </form>
</div>

<script>
// カレンダーを開く関数
function openCalendar() {
    const dateInput = document.getElementById('date-input');
    
    // モダンブラウザでshowPicker()が利用可能な場合
    if (dateInput.showPicker) {
        try {
            dateInput.showPicker();
        } catch (error) {
            // フォールバック: フォーカスを当てる
            dateInput.focus();
            dateInput.click();
        }
    } else {
        // 古いブラウザ用のフォールバック
        dateInput.focus();
        dateInput.click();
    }
}

// 日付入力フィールドのクリック時にカレンダーを開く
document.getElementById('date-input').addEventListener('click', function() {
    openCalendar();
});

// 日付入力フィールドのフォーカス時にカレンダーを開く
document.getElementById('date-input').addEventListener('focus', function() {
    // 少し遅延させてからカレンダーを開く
    setTimeout(() => {
        if (this.showPicker) {
            try {
                this.showPicker();
            } catch (error) {
                console.log('カレンダーピッカーを開けませんでした');
            }
        }
    }, 100);
});

// 全角文字を半角に変換する関数
function toHalfWidth(str) {
    return str.replace(/[０-９]/g, function(s) {
        return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
    }).replace(/[，]/g, ',').replace(/[．]/g, '.');
}

// 金額フィールドの処理
const amountField = document.getElementById('amount');

// 金額フィールドのフォーカス時にIMEを無効化
amountField.addEventListener('focus', function() {
    this.style.imeMode = 'disabled';
    this.style.webkitImeMode = 'disabled';
});

// 金額フィールドの入力時の処理
amountField.addEventListener('input', function(e) {
    let value = e.target.value;
    
    // 全角文字を半角に変換
    value = toHalfWidth(value);
    
    // 数字とカンマ以外の文字を除去
    value = value.replace(/[^0-9,]/g, '');
    
    // 既存のカンマを除去
    const numericValue = value.replace(/,/g, '');
    
    // 数値として有効な場合のみ3桁区切りを適用
    if (numericValue && !isNaN(numericValue)) {
        e.target.value = Number(numericValue).toLocaleString('ja-JP');
    } else {
        e.target.value = numericValue;
    }
});

// 金額フィールドのキー入力時の処理
amountField.addEventListener('keydown', function(e) {
    // 許可するキー: 数字、Backspace、Delete、Tab、Enter、矢印キー、Ctrl+A、Ctrl+C、Ctrl+V、Ctrl+X
    const allowedKeys = [
        'Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight', 
        'ArrowUp', 'ArrowDown', 'Home', 'End'
    ];
    
    // Ctrl+キーの組み合わせを許可
    if (e.ctrlKey && ['a', 'c', 'v', 'x'].includes(e.key.toLowerCase())) {
        return;
    }
    
    // 許可されたキーまたは数字キーの場合は通す
    if (allowedKeys.includes(e.key) || (e.key >= '0' && e.key <= '9')) {
        return;
    }
    
    // その他のキーは無効化
    e.preventDefault();
});

// 金額フィールドのペースト時の処理
amountField.addEventListener('paste', function(e) {
    e.preventDefault();
    
    // クリップボードからテキストを取得
    const paste = (e.clipboardData || window.clipboardData).getData('text');
    
    // 全角を半角に変換し、数字以外を除去
    let cleanedPaste = toHalfWidth(paste).replace(/[^0-9]/g, '');
    
    // 数値として有効な場合のみ設定
    if (cleanedPaste && !isNaN(cleanedPaste)) {
        this.value = Number(cleanedPaste).toLocaleString('ja-JP');
    }
});

// 金額フィールドのコンポジション（IME入力）を制御
amountField.addEventListener('compositionstart', function(e) {
    // IME入力開始時に入力を無効化
    e.preventDefault();
});

amountField.addEventListener('compositionupdate', function(e) {
    // IME入力中の更新を無効化
    e.preventDefault();
});

amountField.addEventListener('compositionend', function(e) {
    // IME入力終了時に全角を半角に変換
    let value = toHalfWidth(e.data || '');
    value = value.replace(/[^0-9]/g, '');
    
    if (value && !isNaN(value)) {
        this.value = Number(value).toLocaleString('ja-JP');
    }
    
    e.preventDefault();
});

// 送信ボタンのリップル効果
document.querySelector('.submit-button').addEventListener('click', function(e) {
    const button = e.currentTarget;
    const rect = button.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    button.style.setProperty('--click-x', x + 'px');
    button.style.setProperty('--click-y', y + 'px');
});

// フォーム送信時の処理（金額フィールドのカンマを除去）
document.querySelector('.finance-form').addEventListener('submit', function(e) {
    const amountField = document.getElementById('amount');
    const value = amountField.value.replace(/,/g, '');
    amountField.value = value;
});

// 日付の妥当性チェック
document.getElementById('date-input').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const today = new Date();
    
    // 未来の日付が選択された場合の警告（必要に応じて）
    if (selectedDate > today) {
        console.log('未来の日付が選択されました');
        // 必要に応じて警告メッセージを表示
    }
});

// ブラウザ互換性の確認
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date-input');
    
    // ブラウザがdate inputをサポートしているかチェック
    const testInput = document.createElement('input');
    testInput.type = 'date';
    
    if (testInput.type !== 'date') {
        // date inputがサポートされていない場合のフォールバック
        console.log('このブラウザはdate inputをサポートしていません');
        // 必要に応じてカスタムカレンダーライブラリを読み込む
    }
});
</script>