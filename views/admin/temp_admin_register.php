<h2>管理者アカウント作成</h2>

<?php if (isset($message)): ?>
  <p><?= $message ?></p>
<?php endif; ?>

<form method="POST">
  <label>ユーザー名: <input type="text" name="username" required></label><br>
  <label>パスワード: <input type="password" name="password" required></label><br>
  <button type="submit">作成</button>
</form>
