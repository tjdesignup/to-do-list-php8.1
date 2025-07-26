<div id='unregister'>
<?php require_once "error.php"; ?>
<h2>Unregister</h2>
<form method="POST" id="unregister-form" action="" novalidate>
  <input id="password" type="password" name="password" value="" placeholder="Password" />
  <input type="hidden" name="csrfToken" value="<?php echo htmlspecialchars($csrfToken ?? ''); ?>" />
  <button type="submit">Unregister</button>
</form>
<p><a href="/dashboard" id="button-like">Cancel</a></p>
</div>