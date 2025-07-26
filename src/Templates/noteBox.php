<div id="notes-box">
    <?php if(!empty($notes)):?>
        <?php foreach ($notes as $note): ?>   
            <a href="/note?id=<?= $note['id'] ?>">
                <div id="note">
                <p><?php echo htmlspecialchars($note['title'] ?? '');?></p>
                <p><?php echo htmlspecialchars($note['created_at'] ?? '');?></p>
                <p><?php echo htmlspecialchars($note['deadline'] ?? '');?></p>
                <p><?php echo htmlspecialchars($note['content'] ?? '');?></p>
                </div>
            </a> 
        <?php endforeach;?>  
    <?php endif;?>  
</div>