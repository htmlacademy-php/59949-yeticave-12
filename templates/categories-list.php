<nav class="nav">
    <ul class="nav__list container">
        <?php if ($categories_list): ?>
            <?php foreach ($categories_list as $category) : ?>
                <li class="nav__item <?= isset($_GET['category']) && $_GET['category'] == $category['id'] ? 'nav__item--current' : '' ?>">
                    <a href="lots-by-categories.php?category=<?= $category['id'] ?>">
                        <?= htmlspecialchars($category['title']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else : ?>
            <li class="nav__item" style="min-height: 50px"></li>
        <?php endif; ?>
    </ul>
</nav>
