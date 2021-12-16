<main>
    <?= $categories_list; ?>

    <div class="container">
        <section class="lots">
            <h2>Все лоты в категории <span>«Доски и лыжи»</span></h2>
            <ul class="lots__list">
                <?php if (empty($goods_list)) : ?>
                    <h3>Лотов не обнаружено</h3>
                <?php else : ?>
                    <?= $lot_cards_list; ?>
                <?php endif; ?>
            </ul>
        </section>
    </div>
</main>
