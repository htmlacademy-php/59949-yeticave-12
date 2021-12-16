<main>
    <?= $categories_list; ?>

    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($_GET['search']); ?></span>»</h2>
            <ul class="lots__list">
                <?php if (empty($goods_list)) : ?>
                    <h3>Ничего не найдено по вашему запросу</h3>
                <?php else : ?>
                    <?= $lot_cards_list; ?>
                <?php endif; ?>
            </ul>
        </section>
    </div>
</main>
