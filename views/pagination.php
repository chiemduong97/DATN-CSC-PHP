<link rel="stylesheet" href="css/style_pagination.css">
<div class="pagination">
    <?php
    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
    }
    if ($currentPage < $totalPage) {
        $nextPage = $currentPage + 1;
    }
    ?>
    <?php if (empty($categoryId)) : ?>
        <a href="?page=<?= $prevPage ?>">&laquo;</a>
    <?php else : ?>
        <a href="?page=<?= $prevPage ?>&categoryId=<?= $categoryId ?>">&laquo;</a>
    <?php endif; ?>

    <?php if ($currentPage > 3) : ?>
        <a>...</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
        <?php if ($i != $currentPage) : ?>
            <?php if ($i > $currentPage - 3 && $i < $currentPage + 3) : ?>
                <?php if (empty($categoryId)) : ?>
                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                <?php else : ?>
                    <a href="?page=<?= $i ?>&categoryId=<?= $categoryId ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endif; ?>
        <?php else : ?>
            <?php if (empty($categoryId)) : ?>
                <a href="?page=<?= $i ?>" class="active"><?= $i ?></a>
            <?php else : ?>
                <a href="?page=<?= $i ?>&categoryId=<?= $categoryId ?>" class="active"><?= $i ?></a>
            <?php endif; ?>

        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($currentPage < $totalPage - 2) : ?>
        <a>...</a>
    <?php endif; ?>

    <?php if (empty($categoryId)) : ?>
        <a href="?page=<?= $nextPage ?>">&raquo;</a>
    <?php else : ?>
        <a href="?page=<?= $prevPage ?>&categoryId=<?= $categoryId ?>">&raquo;</a>
    <?php endif; ?>
</div>