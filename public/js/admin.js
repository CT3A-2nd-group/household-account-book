// 管理者画面専用JavaScript

document.addEventListener("DOMContentLoaded", () => {
  // フォームバリデーション
  const categoryForm = document.querySelector(".category-form")
  if (categoryForm) {
    categoryForm.addEventListener("submit", (e) => {
      const nameInput = document.getElementById("name")
      const typeSelect = document.getElementById("type")

      // 入力チェック
      if (!nameInput.value.trim()) {
        e.preventDefault()
        showAlert("カテゴリ名を入力してください", "error")
        nameInput.focus()
        return
      }

      if (!typeSelect.value) {
        e.preventDefault()
        showAlert("種別を選択してください", "error")
        typeSelect.focus()
        return
      }

      // 成功時のアニメーション
      showLoadingButton()
    })
  }

  // アラートの自動非表示
  const alerts = document.querySelectorAll(".alert")
  alerts.forEach((alert) => {
    setTimeout(() => {
      alert.style.opacity = "0"
      alert.style.transform = "translateY(-10px)"
      setTimeout(() => {
        alert.remove()
      }, 300)
    }, 5000)
  })

  // フォーム入力時のリアルタイムバリデーション
  const nameInput = document.getElementById("name")
  const typeSelect = document.getElementById("type")

  if (nameInput) {
    nameInput.addEventListener("input", () => {
      if (nameInput.value.trim()) {
        nameInput.style.borderColor = "#22c55e"
        nameInput.style.boxShadow = "0 0 0 3px rgba(34, 197, 94, 0.1)"
      } else {
        nameInput.style.borderColor = "#d1d5db"
        nameInput.style.boxShadow = "none"
      }
    })
  }

  if (typeSelect) {
    typeSelect.addEventListener("change", () => {
      if (typeSelect.value) {
        typeSelect.style.borderColor = "#22c55e"
        typeSelect.style.boxShadow = "0 0 0 3px rgba(34, 197, 94, 0.1)"
      } else {
        typeSelect.style.borderColor = "#d1d5db"
        typeSelect.style.boxShadow = "none"
      }
    })
  }

  // ナビゲーションのアクティブ状態管理
  const currentPath = window.location.pathname
  const navLinks = document.querySelectorAll(".admin-nav-link")

  navLinks.forEach((link) => {
    if (link.getAttribute("href") === currentPath) {
      link.classList.add("active")
    }
  })
})

// アラート表示関数
function showAlert(message, type = "info") {
  const adminCard = document.querySelector(".admin-card")
  const existingAlert = document.querySelector(".alert")

  // 既存のアラートを削除
  if (existingAlert) {
    existingAlert.remove()
  }

  const alert = document.createElement("div")
  alert.className = `alert alert-${type}`

  const icon = type === "error" ? "fas fa-exclamation-circle" : "fas fa-check-circle"
  alert.innerHTML = `
    <i class="${icon}"></i>
    ${message}
  `

  // カードヘッダーの後に挿入
  const cardHeader = document.querySelector(".card-header")
  cardHeader.insertAdjacentElement("afterend", alert)

  // 自動非表示
  setTimeout(() => {
    alert.style.opacity = "0"
    alert.style.transform = "translateY(-10px)"
    setTimeout(() => {
      alert.remove()
    }, 300)
  }, 5000)
}

// ボタンローディング状態
function showLoadingButton() {
  const addButton = document.querySelector(".add-button")
  if (addButton) {
    const originalText = addButton.innerHTML
    addButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 追加中...'
    addButton.disabled = true

    // 実際の処理では、フォーム送信完了後に元に戻す
    setTimeout(() => {
      addButton.innerHTML = originalText
      addButton.disabled = false
    }, 2000)
  }
}

// キーボードショートカット
document.addEventListener("keydown", (e) => {
  // Ctrl + N で新しいカテゴリフォームにフォーカス
  if (e.ctrlKey && e.key === "n") {
    e.preventDefault()
    const nameInput = document.getElementById("name")
    if (nameInput) {
      nameInput.focus()
      nameInput.scrollIntoView({ behavior: "smooth", block: "center" })
    }
  }

  // Esc でフォーカスを外す
  if (e.key === "Escape") {
    document.activeElement.blur()
  }
})

// スムーズスクロール機能
function scrollToSection(selector) {
  const element = document.querySelector(selector)
  if (element) {
    element.scrollIntoView({
      behavior: "smooth",
      block: "start",
    })
  }
}

// 管理者ナビゲーションのハイライト
function highlightCurrentPage() {
  const currentPath = window.location.pathname
  const navLinks = document.querySelectorAll(".admin-nav-link")

  navLinks.forEach((link) => {
    link.classList.remove("active")
    if (link.getAttribute("href") === currentPath) {
      link.classList.add("active")
    }
  })
}

// ページ読み込み時にナビゲーションをハイライト
window.addEventListener("load", highlightCurrentPage)
