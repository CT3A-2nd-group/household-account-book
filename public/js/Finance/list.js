document.addEventListener('DOMContentLoaded', () => {
    const swiper = new Swiper('.finance-swiper', {
        loop: false,
        allowTouchMove: false
    });

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

    let isEditMode = false;
    const toggleBtn = document.getElementById('toggle-edit-mode');
    toggleBtn?.addEventListener('click', toggleEditMode);

    function toggleEditMode() {
        isEditMode = !isEditMode;
        toggleBtn.textContent = isEditMode ? '編集モード解除' : '編集モード';

        // 削除ボタンと保存ボタンの表示切り替え
        ['income', 'expenditure'].forEach(type => {
            const deleteBtn = document.getElementById(`${type}-delete-button`);
            const saveBtn = document.getElementById(`${type}-save-button`);
            if (deleteBtn && saveBtn) {
                if (isEditMode) {
                    deleteBtn.style.display = 'none';
                    saveBtn.style.display = 'inline-block';
                } else {
                    deleteBtn.style.display = 'inline-block';
                    saveBtn.style.display = 'none';
                }
            }
        });

        renderTables();
    }
    const incomeFilter = { category: '', month: '' };
    const expenditureFilter = { category: '', month: '' };

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

    let incomeCategories = [];
    let expenditureCategories = [];

    async function loadCategories() {
        try {
            const [incomeRes, expRes] = await Promise.all([
                fetch('/categories/income/json'),
                fetch('/categories/expenditure/json')
            ]);

            if (!incomeRes.ok || !expRes.ok) {
                throw new Error('カテゴリの取得に失敗しました');
            }

            incomeCategories = await incomeRes.json();
            expenditureCategories = await expRes.json();
        } catch (error) {
            console.error('カテゴリ読み込みエラー:', error);
            incomeCategories = [];
            expenditureCategories = [];
        }
    }

    async function loadIncomeList() {
        const res = await fetch('/List/incomes/json');
        const data = await res.json();
        window.incomeData = data;
        renderIncomeTable();
    }

    async function loadExpenditureList() {
        const res = await fetch('/List/expenditures/json');
        const data = await res.json();
        window.expenditureData = data;
        renderExpenditureTable();
    }

    function renderTables() {
        renderIncomeTable();
        renderExpenditureTable();
    }

    function renderIncomeTable() {
        const tbody = document.getElementById('income-table-body');
        if (!tbody) return;
        tbody.innerHTML = '';

        const data = window.incomeData || [];
        data.forEach(item => {
            const tr = document.createElement('tr');
            if (isEditMode) {
                const categoryOptions = (incomeCategories || []).map(cat =>
                    `<option value="${cat.id}" ${cat.id === item.category_id ? 'selected' : ''}>${cat.name}</option>`
                ).join('');
                tr.innerHTML = `
<td><input type="date" value="${item.input_date}" data-id="${item.id}" data-field="input_date"></td>
                    <td><input type="number" value="${item.amount}" data-id="${item.id}" data-field="amount"></td>
                    <td><select data-id="${item.id}" data-field="category_id">${categoryOptions}</select></td>
                    <td><input type="text" value="${item.description ?? ''}" data-id="${item.id}" data-field="description"></td>
                    <td><button class="save-btn" data-id="${item.id}" data-type="income">保存</button></td>
                `;
            } else {
                tr.innerHTML = `
                    <td>${item.input_date}</td>
                    <td>${item.amount}</td>
                    <td>${item.category_name}</td>
                    <td>${item.description ?? ''}</td>
                    <td><input type="checkbox" name="delete_ids[]" value="${item.id}" /></td>
                `;
            }
            tbody.appendChild(tr);
        });
    }

    function renderExpenditureTable() {
        const tbody = document.getElementById('expenditure-table-body');
        if (!tbody) return;
        tbody.innerHTML = '';

        const data = window.expenditureData || [];
        data.forEach(item => {
            const tr = document.createElement('tr');
            if (isEditMode) {
                const categoryOptions = (expenditureCategories || []).map(cat =>
                    `<option value="${cat.id}" ${cat.id === item.category_id ? 'selected' : ''}>${cat.name}</option>`
                ).join('');
                tr.innerHTML = `
                    <td><input type="date" value="${item.input_date}" data-id="${item.id}" data-field="input_date"></td>
                    <td><input type="number" value="${item.amount}" data-id="${item.id}" data-field="amount"></td>
                    <td><select data-id="${item.id}" data-field="category_id">${categoryOptions}</select></td>
                    <td><select data-id="${item.id}" data-field="star_rate">
                        ${[1,2,3,4,5].map(n => `<option value="${n}" ${n === item.star_rate ? 'selected' : ''}>${n}</option>`).join('')}
                    </select></td>
                    <td><input type="checkbox" ${item.is_waste ? 'checked' : ''} data-id="${item.id}" data-field="is_waste"></td>
                    <td><input type="text" value="${item.description ?? ''}" data-id="${item.id}" data-field="description"></td>
                    <td><button class="save-btn" data-id="${item.id}" data-type="expenditure">保存</button></td>
                `;
            } else {
                tr.innerHTML = `
                    <td>${item.input_date}</td>
                    <td>${item.amount}</td>
                    <td>${item.category_name}</td>
                    <td>${item.star_rate}</td>
                    <td>${item.is_waste ? '〇' : ''}</td>
                    <td>${item.description ?? ''}</td>
                    <td><input type="checkbox" name="delete_ids[]" value="${item.id}" /></td>
                `;
            }
            tbody.appendChild(tr);
        });
    }

    document.body.addEventListener('click', async e => {
        if (e.target.matches('.save-btn')) {
            const id = e.target.dataset.id;
            const type = e.target.dataset.type;
            const row = document.querySelectorAll(`[data-id='${id}']`);
            const payload = {
                id: Number(id),
                target_type: type
            };

            row.forEach(input => {
                const field = input.dataset.field;
                if (!field) return;

                if (input.type === 'checkbox') {
                    payload[field] = input.checked ? 1 : 0;
                } else {
                    payload[field] = input.value;
                }
            });

            const res = await fetch('/List/update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const result = await res.json();
            if (result.success) {
                alert('保存しました');
                await Promise.all([loadIncomeList(), loadExpenditureList()]);
            } else {
                alert(result.error || '保存に失敗しました');
            }
        }
    });

    // 初期ロード
    (async () => {
        await loadCategories();
        await Promise.all([loadIncomeList(), loadExpenditureList()]);
    })();
});
