<?php
$now = date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>家計簿</title>
</head>
<body>
    <pre>
<?php
    // コードをテキストとして表示させる
    echo htmlspecialchars('<?"Hello, World!"?>');
?>
    </pre>
    <h1><?= htmlspecialchars($message1) ?></h1>
    <h1><?= htmlspecialchars($message2) ?></h1>
    <h1><?= htmlspecialchars($message3) ?></h1>
    <h1><?= htmlspecialchars($message4) ?></h1>
    <h1><h1 id="clock"></h1>

  <script>
    // PHPから初期時刻を受け取る
    let currentTime = new Date("<?= $now ?>");

    function updateClock() {
      // 時刻を1秒進める
      currentTime.setSeconds(currentTime.getSeconds() + 1);

      const year = currentTime.getFullYear();
      const month = String(currentTime.getMonth() + 1).padStart(2, '0');
      const day = String(currentTime.getDate()).padStart(2, '0');
      const hours = String(currentTime.getHours()).padStart(2, '0');
      const minutes = String(currentTime.getMinutes()).padStart(2, '0');
      const seconds = String(currentTime.getSeconds()).padStart(2, '0');

      const formatted = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
      document.getElementById('clock').textContent = formatted;
    }

    // 初期表示と1秒ごとの更新
    updateClock();
    setInterval(updateClock, 1000);
  </script>
</body>
</html>
