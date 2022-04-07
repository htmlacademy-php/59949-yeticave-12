<main>
    <?= $categories_list_templ; ?>

    <div class="container">
        <section class="lots">
            <h2>Все лоты в категории <span>«<?= $category['title'] ?>»</span></h2>
            <ul class="lots__list">
                <?php if (empty($lot_cards_list_templ)) : ?>
                    <h3>Лотов не обнаружено</h3>
                <?php else : ?>
                    <?= $lot_cards_list_templ; ?>
                <?php endif; ?>
            </ul>

            <?= $pagination_templ; ?>
        </section>
    </div>
</main>
