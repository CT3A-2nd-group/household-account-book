const current = 35000;
const goal = 50000;
const percent = Math.min(current / goal, 1); // 1.0 を上限に

const circle = new ProgressBar.Circle('#circle-container', {
  color: '#4caf50',        // 円の色
  trailColor: '#eee',      // 残り部分の色
  strokeWidth: 10,
  trailWidth: 10,
  easing: 'easeInOut',
  duration: 1400,
  text: {
    autoStyleContainer: false
  },
  from: { color: '#f44336' }, // 達成度が低いと赤
  to: { color: '#4caf50' },   // 達成度が高いと緑
  step: function(state, circle) {
    circle.path.setAttribute('stroke', state.color);
    const value = Math.round(circle.value() * 100);
    circle.setText(`${value}%`);
  }
});

circle.text.className = 'progressbar-text';


circle.animate(percent); // 0.0 ～ 1.0 の範囲