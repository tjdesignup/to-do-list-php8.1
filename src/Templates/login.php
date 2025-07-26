<div id='login-container'>
<div id='login'>
<?php if ($message): ?>
  <div id="flash-message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php require_once "error.php"; ?>
<h1>TO-DO List</h1>
<h3>Log in</h3>
<form method="POST" id="login-form" action="" novalidate>
  <input id="email" type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" placeholder="Email" />
  <input id="password" type="password" name="password" value="" placeholder="Password" />
  <input type="hidden" name="csrfToken" value="<?php echo htmlspecialchars($csrfToken ?? ''); ?>" />
  <button type="submit">Log in</button>
</form>
<a href="/register" onclick="loadView('register'); return false;">New here? Sign up</a>
</div>
</div>