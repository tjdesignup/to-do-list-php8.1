<div id='register-container'>
<div id='register'>
<?php require_once "error.php"; ?>
<h1>TO-DO List</h1>
<h3>Sign up</h3>
<form id="register-form" method="POST" action="" novalidate>
  <input id="email" type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required placeholder="Email" />
  <input id="password" type="password" name="password" value="" required placeholder="Password" />
  <input type="hidden" name="csrfToken" value="<?php echo htmlspecialchars($csrfToken ?? ''); ?>" />
  <button type="submit">Sign up</button>
</form>
<a href="/login" onclick="loadView('login'); return false;">Log in</a>
</div>
</div>