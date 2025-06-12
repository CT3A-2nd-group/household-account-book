document.addEventListener("DOMContentLoaded", function () {
  const barElement = document.getElementById('circle-goal-bar');

  if (barElement) {
    const progressValue = parseFloat(barElement.dataset.progress);
    const rate = progressValue / 100;

    let barColor = '#bbb';
    let useRainbow = false;

    if (!isNaN(progressValue)) {
      if (progressValue < 50) barColor = '#f44336';
      else if (progressValue < 75) barColor = '#ff9800';
      else if (progressValue < 100) barColor = '#4CAF50';
      else useRainbow = true;
    }

    const progressBar = new ProgressBar.Circle(barElement, {
      strokeWidth: 6,
      easing: 'easeInOut',
      duration: 1400,
      color: barColor,
      trailColor: '#eee',
      trailWidth: 4,
      svgStyle: {
        width: '200px',
        height: '200px',
      },
      text: {
        value: '0%',
        style: {
          color: '#333',
          position: 'absolute',
          left: '50%',
          top: '50%',
          transform: 'translate(-50%, -50%)',
          fontSize: '16px',
          fontWeight: 'bold'
        }
      },
      step: function (state, circle) {
        const value = Math.round(circle.value() * 100);
        circle.setText(value + '%');

        if (useRainbow) {
          circle.path.setAttribute('stroke', 'url(#rainbow-gradient)');
        } else {
          circle.path.setAttribute('stroke', barColor);
        }
      }
    });

    progressBar.animate(rate);

    // 100%時だけ虹色グラデーションと回転を付加
    if (useRainbow) {
      const svg = barElement.querySelector('svg');
      injectRainbowGradient(svg);
      svg.classList.add('rainbow-rotate'); // SVGを回転させる
    }
  }

  function injectRainbowGradient(svg) {
    const defs = document.createElementNS("http://www.w3.org/2000/svg", "defs");
    const gradient = document.createElementNS("http://www.w3.org/2000/svg", "linearGradient");

    gradient.setAttribute("id", "rainbow-gradient");
    gradient.setAttribute("x1", "0%");
    gradient.setAttribute("y1", "0%");
    gradient.setAttribute("x2", "100%");
    gradient.setAttribute("y2", "0%");
    gradient.setAttribute("gradientUnits", "userSpaceOnUse");

    const colors = ['#ff5353', '#ffcf53', '#e8ff53', '#53ff5d', '#53ffbc', '#5393ff', '#ca53ff', '#ff53bd'];
    colors.forEach((color, index) => {
      const stop = document.createElementNS("http://www.w3.org/2000/svg", "stop");
      stop.setAttribute("offset", `${(index / (colors.length - 1)) * 100}%`);
      stop.setAttribute("stop-color", color);
      gradient.appendChild(stop);
    });

    defs.appendChild(gradient);
    svg.insertBefore(defs, svg.firstChild);
  }
});
