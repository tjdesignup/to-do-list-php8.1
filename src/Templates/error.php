<div id="error-container">
  <?php if (!empty($errors)): ?>
    <ul id="error-list">
    <?php foreach ($errors as $error): ?>
      <li><?= htmlspecialchars($error) ?></li>
    <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>