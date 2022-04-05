<main>
    <?= $categories_list_templ; ?>

    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= sanitize($_GET['search']); ?></span>»</h2>
            <ul class="lots__list">
                <?php if (empty($lot_cards_list_templ)) : ?>
                    <h3>Ничего не найдено по вашему запросу</h3>
                <?php else : ?>
                    <?= $lot_cards_list_templ; ?>
                <?php endif; ?>
            </ul>
        </section>
    </div>
</main>
