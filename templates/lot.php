<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories_list as $category) : ?>
                <li class="nav__item">
                    <a href="pages/all-lots.html"><?= htmlspecialchars($category['title']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="lot-item container">
        <h2><?= htmlspecialchars($lot['title']); ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= htmlspecialchars($lot['img_path']); ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['category_title']); ?></span></p>
                <p class="lot-item__description"><?= htmlspecialchars($lot['description']); ?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <?php [$hours, $minutes] = lotTimeLeftCalc($lot['expiry_dt']) ?>
                        <div class="lot-item__timer timer <?= $hours < 1 ? 'timer--finishing' : '' ?>">
                            <?= $hours ?>:<?= $minutes ?>
                        </div>
                    <?php ?>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= formatPrice($lot['current_price']); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= formatPrice($lot['bet_step']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
