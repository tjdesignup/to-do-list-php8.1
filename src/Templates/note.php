<div id='new-note'>
<h3>Create Note</h3>
<?php require_once "error.php"; ?>
<form method="POST" id="add-note-form" action="/note?id=<?= $data['id'] ?? ''?>" novalidate>
  <input type="hidden" name="action" value="<?= $data['action']?>">
  <input id="title" type="text" name="title" value="<?= $data['title'] ?? ''?>" placeholder="Title" />
  <input id="deadline" type="date" name="deadline" value="<?= isset($data['deadline']) ? date('Y-m-d', strtotime($data['deadline'])) : '' ?>" placeholder="Deadline" />
  <textarea id="text" style="width: 100%; height:150px;" name="text" placeholder="Zadej text"><?= $data['content'] ?? ''?></textarea>
  <input type="hidden" name="csrfToken" value="<?php echo htmlspecialchars($csrfToken ?? ''); ?>" />
  <button type="submit"><?= $data['buttonName']?></button>
</form>
<p><a href="/dashboard" id="button-like">Cancel</a></p>
</div>