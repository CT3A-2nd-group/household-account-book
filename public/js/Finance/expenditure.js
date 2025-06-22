// カレンダーAPIを利用して日付を選択する関数
// This function is invoked from the calendar button in the form
// and forwards the request to the shared CalendarAPI.
function openCalendar() {
  if (window.CalendarAPI && typeof window.CalendarAPI.openCalendar === "function") {
    window.CalendarAPI.openCalendar("date-input");
  }
}

// 全角文字を半角に変換する関数
function toHalfWidth(str) {
  return str
    .replace(/[０-９]/g, (s) => String.fromCharCode(s.charCodeAt(0) - 0xfee0))
    .replace(/[，]/g, ",")
    .replace(/[．]/g, ".");
}

// 日付の妥当性をチェックする関数
function isValidDate(year, month, day) {
  const date = new Date(year, month - 1, day);
  return (
    date.getFullYear() == year &&
    date.getMonth() == month - 1 &&
    date.getDate() == day
  );
}

// 日付を自動フォーマットする関数（スラッシュ自動挿入）
function formatDateInput(value) {
  // 数字のみを抽出
  const numbers = value.replace(/[^0-9]/g, "");

  if (numbers.length === 0) return "";

  let formatted = "";

  if (numbers.length <= 4) {
    // 年の入力中（4桁まで）
    formatted = numbers;
  } else if (numbers.length <= 6) {
    // 月の入力中（5-6桁）
    const year = numbers.substring(0, 4);
    const month = numbers.substring(4);
    formatted = `${year}/${month}`;
  } else if (numbers.length <= 8) {
    // 日の入力中（7-8桁）
    const year = numbers.substring(0, 4);
    const month = numbers.substring(4, 6);
    const day = numbers.substring(6, 8);
    formatted = `${year}/${month}/${day}`;
  } else {
    // 8桁を超える場合は8桁まで
    const year = numbers.substring(0, 4);
    const month = numbers.substring(4, 6);
    const day = numbers.substring(6, 8);
    formatted = `${year}/${month}/${day}`;
  }

  return formatted;
}

// 日付の妥当性をチェックして警告する関数
function validateDateInput(value) {
  const parts = value.split("/");

  if (parts.length === 3) {
    const year = Number.parseInt(parts[0]);
    const month = Number.parseInt(parts[1]);
    const day = Number.parseInt(parts[2]);

    // 基本的な範囲チェック
    if (year < 1900 || year > 2100) {
      return { valid: false, message: "年は1900-2100の範囲で入力してください" };
    }

    if (month < 1 || month > 12) {
      return { valid: false, message: "月は01-12の範囲で入力してください" };
    }

    if (day < 1 || day > 31) {
      return { valid: false, message: "日は01-31の範囲で入力してください" };
    }

    // 実際の日付として有効かチェック
    if (!isValidDate(year, month, day)) {
      return { valid: false, message: "存在しない日付です" };
    }

    return { valid: true, message: "" };
  }

  return { valid: false, message: "yyyy/mm/dd形式で入力してください" };
}

// 金額にカンマを追加する関数
function addCommaToAmount(value) {
  // 数字のみを抽出
  const numbers = value.replace(/[^0-9]/g, "");

  if (numbers === "") return "";

  // 3桁区切りでカンマを追加
  return Number(numbers).toLocaleString("ja-JP");
}

// 金額フィールドの初期化
function initializeAmountField() {
  const amountField = document.getElementById("amount");

  if (!amountField) return;

  // 金額フィールドのフォーカス時にIMEを無効化
  amountField.addEventListener("focus", function () {
    this.style.imeMode = "disabled";
    this.style.webkitImeMode = "disabled";
  });

  // 金額フィールドの入力時の処理
  amountField.addEventListener("input", (e) => {
    const cursorPosition = e.target.selectionStart;
    let value = e.target.value;

    // 全角文字を半角に変換
    value = toHalfWidth(value);

    // カンマを追加
    const formattedValue = addCommaToAmount(value);

    // 値を設定
    e.target.value = formattedValue;

    // カーソル位置を調整
    const newLength = formattedValue.length;
    const oldLength = value.length;
    const diff = newLength - oldLength;

    setTimeout(() => {
      const newCursorPosition = Math.min(cursorPosition + diff, newLength);
      e.target.setSelectionRange(newCursorPosition, newCursorPosition);
    }, 0);
  });

  // 金額フィールドのキー入力時の処理
  amountField.addEventListener("keydown", (e) => {
    // 許可するキー: 数字、テンキー数字、Backspace、Delete、Tab、Enter、矢印キー、Ctrl+A、Ctrl+C、Ctrl+V、Ctrl+X
    const allowedKeys = [
      "Backspace",
      "Delete",
      "Tab",
      "Enter",
      "ArrowLeft",
      "ArrowRight",
      "ArrowUp",
      "ArrowDown",
      "Home",
      "End",
    ];

    // テンキーの数字キー（Numpad0-Numpad9）を許可
    const numpadKeys = [
      "Numpad0",
      "Numpad1",
      "Numpad2",
      "Numpad3",
      "Numpad4",
      "Numpad5",
      "Numpad6",
      "Numpad7",
      "Numpad8",
      "Numpad9",
    ];

    // Ctrl+キーの組み合わせを許可
    if (e.ctrlKey && ["a", "c", "v", "x"].includes(e.key.toLowerCase())) {
      return;
    }

    // 許可されたキー、数字キー、またはテンキーの場合は通す
    if (
      allowedKeys.includes(e.key) ||
      (e.key >= "0" && e.key <= "9") ||
      numpadKeys.includes(e.code)
    ) {
      return;
    }

    // その他のキーは無効化
    e.preventDefault();
  });

  // 金額フィールドのペースト時の処理
  amountField.addEventListener("paste", function (e) {
    e.preventDefault();

    // クリップボードからテキストを取得
    const paste = (e.clipboardData || window.clipboardData).getData("text");

    // カンマを追加
    const formattedValue = addCommaToAmount(paste);

    // 値を設定
    this.value = formattedValue;
  });

  // 金額フィールドのコンポジション（IME入力）を制御
  amountField.addEventListener("compositionstart", (e) => {
    e.preventDefault();
  });

  amountField.addEventListener("compositionupdate", (e) => {
    e.preventDefault();
  });

  amountField.addEventListener("compositionend", function (e) {
    const value = toHalfWidth(e.data || "");
    const formattedValue = addCommaToAmount(value);
    this.value = formattedValue;
    e.preventDefault();
  });
}

// 日付入力フィールドの初期化
function initializeDateField() {
  const dateInput = document.getElementById("date-input");

  if (!dateInput) return;

  // 今日の日付をデフォルト値として設定(入力がある場合はそっちを優先)
  if (!dateInput.value.trim()) {
    const today = new Date();
    const defaultDate = `${today.getFullYear()}/${String(
      today.getMonth() + 1
    ).padStart(2, "0")}/${String(today.getDate()).padStart(2, "0")}`;
    dateInput.value = defaultDate;
  }

  // 日付入力フィールドの入力時の処理
  dateInput.addEventListener("input", (e) => {
    const cursorPosition = e.target.selectionStart;
    let value = e.target.value;

    // 全角数字を半角に変換
    value = toHalfWidth(value);

    // 自動フォーマット適用（スラッシュ自動挿入）
    const formatted = formatDateInput(value);

    // 値を設定
    e.target.value = formatted;

    // カーソル位置を調整
    let newCursorPosition = cursorPosition;
    if (formatted.length > value.length) {
      // スラッシュが追加された場合、カーソル位置を調整
      newCursorPosition = cursorPosition + (formatted.length - value.length);
    }

    // カーソル位置を設定（次のフレームで実行）
    setTimeout(() => {
      e.target.setSelectionRange(newCursorPosition, newCursorPosition);
    }, 0);
  });

  // 日付入力フィールドのキー入力制御
  dateInput.addEventListener("keydown", (e) => {
    // 許可するキー: 数字、テンキー数字、Backspace、Delete、Tab、Enter、矢印キー
    const allowedKeys = [
      "Backspace",
      "Delete",
      "Tab",
      "Enter",
      "ArrowLeft",
      "ArrowRight",
      "ArrowUp",
      "ArrowDown",
      "Home",
      "End",
    ];

    // テンキーの数字キー（Numpad0-Numpad9）を許可
    const numpadKeys = [
      "Numpad0",
      "Numpad1",
      "Numpad2",
      "Numpad3",
      "Numpad4",
      "Numpad5",
      "Numpad6",
      "Numpad7",
      "Numpad8",
      "Numpad9",
    ];

    // Ctrl+キーの組み合わせを許可
    if (e.ctrlKey && ["a", "c", "v", "x"].includes(e.key.toLowerCase())) {
      return;
    }

    // 許可されたキー、数字キー、またはテンキーの場合は通す
    if (
      allowedKeys.includes(e.key) ||
      (e.key >= "0" && e.key <= "9") ||
      numpadKeys.includes(e.code)
    ) {
      return;
    }

    // その他のキーは無効化
    e.preventDefault();
  });

  // 日付入力フィールドのフォーカスアウト時の妥当性チェック
  dateInput.addEventListener("blur", (e) => {
    const value = e.target.value.trim();

    if (value) {
      const validation = validateDateInput(value);
      if (validation.valid) {
        // 有効な日付の場合、エラー表示をクリア
        e.target.setCustomValidity("");
        e.target.style.borderColor = "";
      } else {
        // 無効な日付の場合、エラーメッセージを設定
        e.target.setCustomValidity(validation.message);
        e.target.style.borderColor = "#ef4444";
        console.warn("日付入力エラー:", validation.message);
      }
    }
  });

  // 日付入力フィールドのペースト時の処理
  dateInput.addEventListener("paste", function (e) {
    e.preventDefault();

    // クリップボードからテキストを取得
    const paste = (e.clipboardData || window.clipboardData).getData("text");

    // 自動フォーマットを適用
    const formatted = formatDateInput(paste);
    this.value = formatted;

    // 妥当性チェック
    if (formatted.split("/").length === 3) {
      const validation = validateDateInput(formatted);
      if (!validation.valid) {
        this.setCustomValidity(validation.message);
        this.style.borderColor = "#ef4444";
      }
    }
  });

  // 日付入力フィールドのフォーカス時の処理
  dateInput.addEventListener("focus", function () {
    // エラー状態をクリア
    this.setCustomValidity("");
    this.style.borderColor = "";
  });

  // カレンダーボタンの初期化
  const calendarButton = document.querySelector(".calendar-button");
  if (calendarButton) {
    console.log("カレンダーボタンが見つかりました");
    calendarButton.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      console.log("カレンダーボタンクリックイベントが発火しました");
      openCalendar();
    });
  } else {
    console.log("カレンダーボタンが見つかりませんでした");
  }
}

// テキストエリアの自動拡張機能
document.addEventListener("DOMContentLoaded", () => {
  const textareas = document.querySelectorAll("textarea.auto-expand");

  textareas.forEach((textarea) => {
    textarea.style.overflow = "hidden";
    textarea.style.resize = "none";

    const resize = () => {
      textarea.style.height = "auto";
      textarea.style.height = textarea.scrollHeight + "px";
    };

    textarea.addEventListener("input", resize);
    resize(); // 初期表示でも実行
  });
});

// チェックボックスの初期化
function initializeCheckbox() {
  const checkbox = document.querySelector('input[name="is_waste"]');

  if (!checkbox) return;

  // チェックボックスのクリック時のアニメーション
  checkbox.addEventListener("change", function () {
    const customCheckbox = this.nextElementSibling;
    if (customCheckbox) {
      if (this.checked) {
        customCheckbox.style.transform = "scale(1.1)";
        setTimeout(() => {
          customCheckbox.style.transform = "scale(1)";
        }, 150);
      }
    }
  });
}

// 送信ボタンの初期化
function initializeSubmitButton() {
  const submitButton = document.querySelector(".submit-button");

  if (!submitButton) return;

  // 送信ボタンのリップル効果
  submitButton.addEventListener("click", (e) => {
    const button = e.currentTarget;
    const rect = button.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    button.style.setProperty("--click-x", x + "px");
    button.style.setProperty("--click-y", y + "px");
  });
}

// フォーム送信の初期化
function initializeFormSubmit() {
  const form = document.querySelector(".finance-form");

  if (!form) return;

  // フォーム送信時の処理
  form.addEventListener("submit", (e) => {
    const amountField = document.getElementById("amount");
    const dateField = document.getElementById("date-input");

    // 金額フィールドのカンマを除去（サーバー送信用）
    if (amountField) {
      const value = amountField.value.replace(/,/g, "");
      amountField.value = value;
    }

    // 日付フィールドの最終チェック
    if (dateField && dateField.value) {
      const validation = validateDateInput(dateField.value);
      if (validation.valid) {
        // yyyy/mm/dd形式をyyyy-mm-dd形式に変換（サーバー送信用）
        const parts = dateField.value.split("/");
        if (parts.length === 3) {
          dateField.value = `${parts[0]}-${parts[1].padStart(
            2,
            "0"
          )}-${parts[2].padStart(2, "0")}`;
        }
      } else {
        e.preventDefault();
        alert(validation.message);
        dateField.focus();
        return false;
      }
    }
  });
}

// DOMが読み込まれた後に初期化を実行
document.addEventListener("DOMContentLoaded", () => {
  console.log("DOMが読み込まれました");

  // 各機能の初期化
  initializeAmountField();
  initializeDateField();
  initializeCheckbox();
  initializeSubmitButton();
  initializeFormSubmit();

  console.log(
    "支出登録フォームが初期化されました（自動スラッシュ + 自動カンマ + カレンダー対応）"
  );

  // ブラウザ情報をログ出力
  console.log("ブラウザ:", navigator.userAgent);
  console.log(
    "showPicker対応:",
    typeof document.createElement("input").showPicker === "function"
  );
});

// エラーハンドリング
window.addEventListener("error", (e) => {
  console.error("JavaScript エラーが発生しました:", e.error);
});

// 未処理のPromise拒否をキャッチ
window.addEventListener("unhandledrejection", (e) => {
  console.error("未処理のPromise拒否:", e.reason);
});
