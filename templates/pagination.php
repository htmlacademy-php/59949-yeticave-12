<?php if (count($pages) > 1 && in_array($cur_page, $pages)): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <?php if ($cur_page <= 1): ?>
                <a>Назад</a>
            <?php else : ?>
                <a href="<?= calcPageUrl($cur_page - 1); ?>">Назад</a>
            <?php endif; ?>
        </li>
        <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?= ($page == $cur_page) ? 'pagination-item-active' : ''; ?>">
                <a href="<?= calcPageUrl($page); ?>"><?= $page; ?></a>
            </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next">
            <?php if ($cur_page >= count($pages)): ?>
                <a>Вперед</a>
            <?php else : ?>
                <a href="<?= calcPageUrl($cur_page + 1); ?>">Вперед</a>
            <?php endif; ?>
        </li>
    </ul>
<?php endif; ?>
