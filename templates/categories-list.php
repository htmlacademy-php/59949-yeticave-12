<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories_list as $category) : ?>
            <li class="nav__item <?= $_GET['category'] == $category['id'] ? 'nav__item--current' : '' ?>">
                <a href="lots-by-categories.php?category=<?= $category['id'] ?>">
                    <?= htmlspecialchars($category['title']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
