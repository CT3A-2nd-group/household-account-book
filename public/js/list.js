// --- Swiper 初期化 ---
const swiper = new Swiper('.finance-swiper', {
    loop: false,
    allowTouchMove: false,
});

// --- タブ切替 ---
const incomeTab = document.getElementById('incomeTab');
const expenditureTab = document.getElementById('expenditureTab');

incomeTab.addEventListener('click', () => {
    swiper.slideTo(0);
    setActiveTab(true);
});

expenditureTab.addEventListener('click', () => {
    swiper.slideTo(1);
    setActiveTab(false);
});

swiper.on('slideChange', () => {
    setActiveTab(swiper.activeIndex === 0);
});

function setActiveTab(isIncome) {
    incomeTab.classList.toggle('active', isIncome);
    expenditureTab.classList.toggle('active', !isIncome);
}

// --- 編集モード ---
let isEditMode = false;
const toggleBtn = document.getElementById('toggle-edit-mode');
toggleBtn.addEventListener('click', toggleEditMode);

function toggleEditMode() {
    isEditMode = !isEditMode;
    toggleBtn.textContent = isEditMode ? '編集モード解除' : '編集モード';
    renderTables(); // 編集モードの切替でも再描画
}

// --- 🔽 ここから追加 ---
// PHPから受け取ったデータ
const incomeData = window.incomeData || [];
const expenditureData = window.expenditureData || [];

const incomeFilter = {
    category: '',
    month: ''
};
const expenditureFilter = {
    category: '',
    month: ''
};

// --- フィルターUIのイベント登録 ---
document.getElementById('filter-income-category')?.addEventListener('change', e => {
    incomeFilter.category = e.target.value;
    renderIncomeTable();
});
document.getElementById('filter-income-month')?.addEventListener('change', e => {
    incomeFilter.month = e.target.value;
    renderIncomeTable();
});
document.getElementById('filter-expenditure-category')?.addEventListener('change', e => {
    expenditureFilter.category = e.target.value;
    renderExpenditureTable();
});
document.getElementById('filter-expenditure-month')?.addEventListener('change', e => {
    expenditureFilter.month = e.target.value;
    renderExpenditureTable();
});

// --- 表描画 ---
function renderTables() {
    renderIncomeTable();
    renderExpenditureTable();
}

function renderIncomeTable() {
    const tbody = document.querySelector('#income-table tbody');
    tbody.innerHTML = ''; // クリア

    const filtered = incomeData.filter(item => {
        const matchCategory = !incomeFilter.category || item.category_id == incomeFilter.category;
        const matchMonth = !incomeFilter.month || item.input_date.startsWith(incomeFilter.month);
        return matchCategory && matchMonth;
    });

    for (const item of filtered) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="checkbox" name="delete_ids[]" value="${item.id}"></td>
            <td>${item.input_date}</td>
            <td>${item.category_name}</td>
            <td>
                <span class="amount-text">${item.amount}</span>
                <input type="number" class="edit-amount" name="items[${item.id}][amount]" value="${item.amount}" style="display: ${isEditMode ? 'inline-block' : 'none'}">
            </td>
            <td>
                <span class="memo-text">${item.description}</span>
                <input type="text" class="edit-memo" name="items[${item.id}][description]" value="${item.description}" style="display: ${isEditMode ? 'inline-block' : 'none'}">
            </td>
        `;
        tbody.appendChild(tr);
    }
}

function renderExpenditureTable() {
    const tbody = document.querySelector('#expenditure-table tbody');
    tbody.innerHTML = ''; // クリア

    const filtered = expenditureData.filter(item => {
        const matchCategory = !expenditureFilter.category || item.category_id == expenditureFilter.category;
        const matchMonth = !expenditureFilter.month || item.input_date.startsWith(expenditureFilter.month);
        return matchCategory && matchMonth;
    });

    for (const item of filtered) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="checkbox" name="delete_ids[]" value="${item.id}"></td>
            <td>${item.input_date}</td>
            <td>${item.category_name}</td>
            <td>
                <span class="amount-text">${item.amount}</span>
                <input type="number" class="edit-amount" name="items[${item.id}][amount]" value="${item.amount}" style="display: ${isEditMode ? 'inline-block' : 'none'}">
            </td>
            <td>
                <span class="memo-text">${item.description}</span>
                <input type="text" class="edit-memo" name="items[${item.id}][description]" value="${item.description}" style="display: ${isEditMode ? 'inline-block' : 'none'}">
            </td>
            <td>
                <span class="star-rating-text">${item.star_rate}</span>
                <select class="edit-star-rate" name="items[${item.id}][star_rate]" style="display: ${isEditMode ? 'inline-block' : 'none'}">
                    ${[1, 2, 3, 4, 5].map(val => `<option value="${val}" ${val == item.star_rate ? 'selected' : ''}>${val}</option>`).join('')}
                </select>
            </td>
            <td>
                <span class="waste-text">${item.is_waste ? '無駄' : '必要'}</span>
                <select class="edit-waste" name="items[${item.id}][is_waste]" style="display: ${isEditMode ? 'inline-block' : 'none'}">
                    <option value="0" ${item.is_waste ? '' : 'selected'}>必要</option>
                    <option value="1" ${item.is_waste ? 'selected' : ''}>無駄</option>
                </select>
            </td>
        `;
        tbody.appendChild(tr);
    }
}

// --- 初期描画 ---
renderTables();
