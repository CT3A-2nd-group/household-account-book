(function(window) {
  window.CalendarAPI = {
    openCalendar: function(inputId) {
      const textInput = document.getElementById(inputId);
      if (!textInput) return;

      const existing = document.getElementById('calendar-api-date');
      if (existing) existing.remove();

      const dateInput = document.createElement('input');
      dateInput.type = 'date';
      dateInput.id = 'calendar-api-date';
      dateInput.style.position = 'absolute';
      dateInput.style.left = '-9999px';
      dateInput.style.opacity = '0';
      dateInput.style.width = '1px';
      dateInput.style.height = '1px';

      const currentValue = textInput.value;
      if (currentValue && currentValue.includes('/')) {
        const parts = currentValue.split('/');
        if (parts.length === 3) {
          const y = parts[0];
          const m = parts[1].padStart(2,'0');
          const d = parts[2].padStart(2,'0');
          dateInput.value = `${y}-${m}-${d}`;
        }
      } else {
        const today = new Date();
        const y = today.getFullYear();
        const m = String(today.getMonth()+1).padStart(2,'0');
        const d = String(today.getDate()).padStart(2,'0');
        dateInput.value = `${y}-${m}-${d}`;
      }

      document.body.appendChild(dateInput);

      dateInput.addEventListener('change', function() {
        if (this.value) {
          const dt = new Date(this.value);
          const y = dt.getFullYear();
          const m = String(dt.getMonth()+1).padStart(2,'0');
          const d = String(dt.getDate()).padStart(2,'0');
          textInput.value = `${y}/${m}/${d}`;
          textInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
        setTimeout(() => this.remove(), 100);
      });

      dateInput.addEventListener('blur', () => setTimeout(() => dateInput.remove(), 200));

      setTimeout(() => {
        dateInput.focus();
        if (typeof dateInput.showPicker === 'function') {
          try { dateInput.showPicker(); } catch(e) { dateInput.click(); }
        } else {
          dateInput.click();
        }
      }, 10);

      setTimeout(() => { if (dateInput.parentNode) dateInput.remove(); }, 10000);
    }
  };
})(window);
