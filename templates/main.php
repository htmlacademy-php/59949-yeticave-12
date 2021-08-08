<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">
        На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.
    </p>
    <ul class="promo__list">
        <?php foreach ($categoriesList as $category) : ?>
            <li class="promo__item promo__item--<?= $category['code'] ?>">
                <a class="promo__link" href="pages/all-lots.html"><?= htmlspecialchars($category['title']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php if (count($goodsList) === 0) : ?>
            <h3>Лотов не обнаружено</h3>
        <?php else : ?>
            <?php foreach ($goodsList as $item) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= htmlspecialchars($item['img_path']); ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= htmlspecialchars($item['category_title']); ?></span>
                        <h3 class="lot__title">
                            <a class="text-link" href="pages/lot.html">
                                <?= htmlspecialchars($item['title']); ?>
                            </a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost">
                                    <?= formatPrice($item['initial_price']); ?>
                                </span>
                            </div>
                            <?php [$hours, $minutes] = lotTimeLeftCalc($item['expiry_dt']) ?>
                                <div class="lot__timer timer <?= $hours < 1 ? 'timer--finishing' : '' ?>">
                                    <?= $hours ?>:<?= $minutes ?>
                                </div>
                            <?php ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</section>
