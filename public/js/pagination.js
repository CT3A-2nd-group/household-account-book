document.addEventListener("DOMContentLoaded", () => {
  const DEFAULT_SIZE = 10;
  document.querySelectorAll(".paginated-table").forEach((table) => {
    const pageSize = parseInt(table.dataset.pageSize || DEFAULT_SIZE, 10);
    const tbody = table.querySelector("tbody");
    if (!tbody) return;
    const rows = Array.from(tbody.querySelectorAll("tr"));
    if (rows.length <= pageSize) return;

    let currentPage = 1;
    const pageCount = Math.ceil(rows.length / pageSize);

    const controls = document.createElement("div");
    controls.className = "pagination-controls";

    const prevBtn = document.createElement("button");
    prevBtn.type = "button";
    prevBtn.textContent = "前へ";
    const nextBtn = document.createElement("button");
    nextBtn.type = "button";
    nextBtn.textContent = "次へ";
    controls.appendChild(prevBtn);
    controls.appendChild(nextBtn);

    table.parentNode.appendChild(controls);

    function renderPage(page) {
      currentPage = page;
      rows.forEach((row, index) => {
        row.style.display =
          index >= (page - 1) * pageSize && index < page * pageSize
            ? ""
            : "none";
      });
      prevBtn.disabled = page <= 1;
      nextBtn.disabled = page >= pageCount;
    }

    prevBtn.addEventListener("click", () => {
      if (currentPage > 1) renderPage(currentPage - 1);
    });
    nextBtn.addEventListener("click", () => {
      if (currentPage < pageCount) renderPage(currentPage + 1);
    });

    renderPage(1);
  });
});
