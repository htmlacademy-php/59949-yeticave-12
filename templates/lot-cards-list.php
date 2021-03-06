<?php foreach ($goods_list as $item) : ?>
    <li class="lots__item lot">
        <div class="lot__image">
            <img src="<?= htmlspecialchars($item['img_path']); ?>" width="350" height="260" alt="">
        </div>
        <div class="lot__info">
            <span class="lot__category"><?= htmlspecialchars($item['category_title']); ?></span>
            <h3 class="lot__title">
                <a class="text-link" href="lot.php?id=<?= $item['id']; ?>">
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

