<main>
    <?= $categories_list_templ; ?>

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
                    <form class="lot-item__form" action="lot.php" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="12 000">
                            <span class="form__error">Введите наименование лота</span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
