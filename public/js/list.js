// Swiper初期化
    const swiper = new Swiper('.finance-swiper', {
        loop: false,
        allowTouchMove: false, // タブ切り替えだけに
    });

    // タブボタン取得
    const incomeTab = document.getElementById('incomeTab');
    const expenditureTab = document.getElementById('expenditureTab');

    incomeTab.addEventListener('click', () => {
        swiper.slideTo(0);
        incomeTab.classList.add('active');
        expenditureTab.classList.remove('active');
    });

    expenditureTab.addEventListener('click', () => {
        swiper.slideTo(1);
        expenditureTab.classList.add('active');
        incomeTab.classList.remove('active');
    });

    swiper.on('slideChange', () => {
        if (swiper.activeIndex === 0) {
            incomeTab.classList.add('active');
            expenditureTab.classList.remove('active');
        } else {
            expenditureTab.classList.add('active');
            incomeTab.classList.remove('active');
        }
    });

    // 編集モード切り替え
    let isEditMode = false;
    const toggleBtn = document.getElementById('toggle-edit-mode');

    toggleBtn.addEventListener('click', () => {
        isEditMode = !isEditMode;

        ['income', 'expenditure'].forEach(type => {
            const form = document.getElementById(type + '-form');
            if (!form) return;

            // action属性を編集モードに応じて切り替え
            form.action = isEditMode ? '/List/Update' : '/List/Delete';

            // 表示切替する各要素を取得
            form.querySelectorAll('tr[data-id]').forEach(row => {
                // 収入・支出共通：amount
                const amountText = row.querySelector('.amount-text');
                const amountInput = row.querySelector('.edit-amount');

                // メモ
                const memoText = row.querySelector('.memo-text');
                const memoInput = row.querySelector('.edit-memo');

                if (amountText && amountInput) {
                    amountText.style.display = isEditMode ? 'none' : '';
                    amountInput.style.display = isEditMode ? 'inline-block' : 'none';
                }

                if (memoText && memoInput) {
                    memoText.style.display = isEditMode ? 'none' : '';
                    memoInput.style.display = isEditMode ? 'inline-block' : 'none';
                }

                // 支出のみ: star_rate と is_waste の切替
                if (type === 'expenditure') {
                    const starText = row.querySelector('.star-rating-text');
                    const starSelect = row.querySelector('.edit-star-rate');
                    const wasteText = row.querySelector('.waste-text');
                    const wasteSelect = row.querySelector('.edit-waste');

                    if (starText && starSelect) {
                        starText.style.display = isEditMode ? 'none' : '';
                        starSelect.style.display = isEditMode ? 'inline-block' : 'none';
                    }
                    if (wasteText && wasteSelect) {
                        wasteText.style.display = isEditMode ? 'none' : '';
                        wasteSelect.style.display = isEditMode ? 'inline-block' : 'none';
                    }
                }

                // チェックボックスは常に表示。削除ボタンの表示は後で切り替え
            });

            // ボタン切替
            const deleteBtn = document.getElementById(type + '-delete-button');
            const saveBtn = document.getElementById(type + '-save-button');

            if (deleteBtn && saveBtn) {
                deleteBtn.style.display = isEditMode ? 'none' : '';
                saveBtn.style.display = isEditMode ? '' : 'none';
            }
        });

        toggleBtn.textContent = isEditMode ? '編集モード解除' : '編集モード';
    });