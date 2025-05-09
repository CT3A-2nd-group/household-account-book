<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>家計簿アプリ（仮）へようこそ！！
</head>
<body>
    <header>
        <h1>家計簿アプリ</h1>
    </header>
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
</body>
</html>
