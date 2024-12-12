<div class="pagination">
    <?php if ($pagination['current_page'] > 1): ?>
        <a href="?page=<?php echo $pagination['current_page'] - 1; ?>">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
        <a href="?page=<?php echo $i; ?>" <?php echo $i == $pagination['current_page'] ? 'style="font-weight: bold;"' : ''; ?>>
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
        <a href="?page=<?php echo $pagination['current_page'] + 1; ?>">Next</a>
    <?php endif; ?>
</div>