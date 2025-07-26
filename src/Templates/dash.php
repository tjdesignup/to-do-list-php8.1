<div id="dash-container">
    <div id="menu">
        <p>TO-DO LIST</p>
        <p>EMAIL</p>
        <div id="menu-bottom">
        <p><a href="/logout" id="button-like">Logout</a></p>
        <p><a href="/unregister" id="button-like">Unregister</a></p>
        </div>
    </div>
    <div id="button-container">
        <p><a href="/note" id="button-like">Add Note</a></p>
        <?php require_once "error.php"; ?>
        <form id="delete-note-form" method="POST" action="/dashboard" type="hidden" novalidate>
            <input type="hidden" name="action" value="deleteAllNotes">
            <input type="hidden" name="csrfToken" value="<?php echo htmlspecialchars($csrfToken ?? ''); ?>" />
        <button type="submit">Delete All</button>
        </form>
    </div>
      <?php echo $content ?? ''; ?>
</div>