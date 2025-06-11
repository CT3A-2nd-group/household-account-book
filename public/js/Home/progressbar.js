// htmlのページの読み込みが完了したら実行
document.addEventListener("DOMContentLoaded", function () {
    // プログレスバーを表示する要素（div#circle-goal-bar）を取得
    const barElement = document.getElementById('circle-goal-bar');

    // 要素が存在する場合のみ処理を続行
    if (barElement) {
        // data-progress 属性から達成率（0〜100の数値）を取得
        const progressValue = parseFloat(barElement.dataset.progress);

        // ProgressBar.js は 0〜1 の小数で受け取るので変換
        const rate = progressValue / 100;

        // 達成率に応じてバーの色を決定
        let barColor;
        if (isNaN(progressValue) || progressValue <= 0) {
            // 目標が未設定 or 0%のときはグレー
            barColor = '#bbb';
        } else if (progressValue < 50) {
            // 50%未満：赤
            barColor = '#f44336';
        } else if (progressValue < 75) {
            // 50〜74%：オレンジ
            barColor = '#ff9800';
        } else {
            // 75%以上：緑
            barColor = '#4CAF50';
        }

        // プログレスバーを作成（円形）
        const progressBar = new ProgressBar.Circle(barElement, {
            strokeWidth: 6,       // 線の太さ
            easing: 'easeInOut',  // アニメーションの動き
            duration: 1400,       // アニメーション時間（ミリ秒）
            color: barColor,      // 線の色（後でstepでも再指定）
            trailColor: '#eee',   // 背景の線（未達成部分）の色
            trailWidth: 4,        // 背景の線の太さ
            svgStyle: {
                width: '200px',
                height: '200px'
            },
            text: {
                value: '0%', // 初期表示（アニメーション開始前）
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
            // アニメーション中に毎フレーム呼ばれる関数
            step: function (state, circle) {
                const value = Math.round(circle.value() * 100); // 小数→整数%
                circle.setText(value + '%');                    // 中央に%表示

                // バーに色を適用（これがないと色が反映されない）
                circle.path.setAttribute('stroke', barColor);
            }
        });

        // rate の値までアニメーションでバーを伸ばす
        progressBar.animate(rate);
    }
});
