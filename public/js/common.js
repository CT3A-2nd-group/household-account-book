// モバイルメニュー制御
function toggleMobileMenu() {
  const sidebar = document.getElementById("leftSidebar");
  const overlay = document.getElementById("mobileOverlay");

  sidebar.classList.toggle("mobile-open");
  overlay.classList.toggle("active");
}

function closeMobileMenu() {
  const sidebar = document.getElementById("leftSidebar");
  const overlay = document.getElementById("mobileOverlay");

  sidebar.classList.remove("mobile-open");
  overlay.classList.remove("active");
}

// アコーディオンセクションの開閉制御
function toggleSection(sectionId) {
  const sectionElement = document.getElementById(`section-${sectionId}`);
  const toggleButton = document.querySelector(
    `[onclick="toggleSection('${sectionId}')"]`
  );

  if (!sectionElement || !toggleButton) {
    console.error(`Section ${sectionId} not found`);
    return;
  }

  const isExpanded = toggleButton.getAttribute("aria-expanded") === "true";

  if (isExpanded) {
    // セクションを閉じる
    collapseSection(sectionElement, toggleButton);
  } else {
    // セクションを開く
    expandSection(sectionElement, toggleButton);
  }

  // 状態を保存（オプション）
  saveSectionState(sectionId, !isExpanded);
}

// セクションを展開
function expandSection(sectionElement, toggleButton) {
  sectionElement.classList.remove("collapsed", "collapsing");
  sectionElement.classList.add("expanding");

  toggleButton.setAttribute("aria-expanded", "true");

  // アニメーション完了後にクラスを削除
  setTimeout(() => {
    sectionElement.classList.remove("expanding");
  }, 300);
}

// セクションを折りたたみ
function collapseSection(sectionElement, toggleButton) {
  sectionElement.classList.remove("expanding");
  sectionElement.classList.add("collapsing");

  toggleButton.setAttribute("aria-expanded", "false");

  // アニメーション完了後にcollapsedクラスを追加
  setTimeout(() => {
    sectionElement.classList.remove("collapsing");
    sectionElement.classList.add("collapsed");
  }, 300);
}

// セクション状態をローカルストレージに保存
function saveSectionState(sectionId, isExpanded) {
  try {
    const sectionStates = JSON.parse(
      localStorage.getItem("sectionStates") || "{}"
    );
    sectionStates[sectionId] = isExpanded;
    localStorage.setItem("sectionStates", JSON.stringify(sectionStates));
  } catch (error) {
    console.warn("Failed to save section state:", error);
  }
}

// ページ読み込み時にセクション状態を復元
function restoreSectionStates() {
  try {
    const sectionStates = JSON.parse(
      localStorage.getItem("sectionStates") || "{}"
    );

    Object.entries(sectionStates).forEach(([sectionId, isExpanded]) => {
      const sectionElement = document.getElementById(`section-${sectionId}`);
      const toggleButton = document.querySelector(
        `[onclick="toggleSection('${sectionId}')"]`
      );

      if (sectionElement && toggleButton) {
        if (!isExpanded) {
          // 即座に閉じた状態にする（アニメーションなし）
          sectionElement.classList.add("collapsed");
          toggleButton.setAttribute("aria-expanded", "false");
        }
      }
    });
  } catch (error) {
    console.warn("Failed to restore section states:", error);
  }
}

// 全セクションを開く/閉じる（管理用関数）
function toggleAllSections(expand = true) {
  const sections = ["graph", "register"];

  sections.forEach((sectionId) => {
    const sectionElement = document.getElementById(`section-${sectionId}`);
    const toggleButton = document.querySelector(
      `[onclick="toggleSection('${sectionId}')"]`
    );

    if (sectionElement && toggleButton) {
      const isCurrentlyExpanded =
        toggleButton.getAttribute("aria-expanded") === "true";

      if (expand && !isCurrentlyExpanded) {
        expandSection(sectionElement, toggleButton);
        saveSectionState(sectionId, true);
      } else if (!expand && isCurrentlyExpanded) {
        collapseSection(sectionElement, toggleButton);
        saveSectionState(sectionId, false);
      }
    }
  });
}

// キーボードアクセシビリティ対応
document.addEventListener("keydown", (e) => {
  if (e.target.classList.contains("section-toggle")) {
    if (e.key === "Enter" || e.key === " ") {
      e.preventDefault();
      e.target.click();
    }
  }
});

// ページ読み込み時に状態を復元
document.addEventListener("DOMContentLoaded", () => {
  restoreSectionStates();
});
