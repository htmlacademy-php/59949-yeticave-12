<main>
    <?= $categories_list_templ; ?>

    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php if (empty($user_bets)) : ?>
                <h3>У вас пока нет ставок</h3>
            <?php else : ?>
                <?php foreach ($user_bets as $item) : ?>
                    <?php if (isset($item['winner']) && $item['winner'] == $item['user_id']) : ?>
                        <tr class="rates__item rates__item--win">
                    <?php elseif (isset($item['winner']) || lotTimeLeftCalc($item['expiry_dt']) == ['00', '00']) : ?>
                        <tr class="rates__item rates__item--end">
                    <?php else : ?>
                        <tr class="rates__item">
                    <?php endif; ?>
                        <td class="rates__info">
                            <div class="rates__img">
                                <img src="<?= htmlspecialchars($item['img_path']); ?>" width="54" height="40" alt="">
                            </div>
                            <div>
                                <h3 class="rates__title">
                                    <a href="lot.php?id=<?= $item['lot_id']; ?>">
                                        <?= htmlspecialchars($item['lot_title']); ?>
                                    </a>
                                </h3>
                                <?php if (isset($item['winner']) && $item['winner'] == $item['user_id']) : ?>
                                    <p><?= htmlspecialchars($item['contact']); ?></p>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="rates__category">
                            <?= htmlspecialchars($item['category']); ?>
                        </td>
                        <td class="rates__timer">
                            <?php if (isset($item['winner']) && $item['winner'] == $item['user_id']) : ?>
                                <div class="timer timer--win">Ставка выиграла</div>
                            <?php elseif (isset($item['winner']) || lotTimeLeftCalc($item['expiry_dt']) == ['00', '00']) : ?>
                                <div class="timer timer--end">Торги окончены</div>
                            <?php else : ?>
                                <?php [$hours, $minutes] = lotTimeLeftCalc($item['expiry_dt']) ?>
                                    <div class="lot__timer timer <?= $hours < 1 ? 'timer--finishing' : '' ?>">
                                        <?= $hours ?>:<?= $minutes ?>
                                    </div>
                                <?php ?>
                            <?php endif; ?>
                        </td>
                        <td class="rates__price">
                            <?= formatPrice($item['amount']); ?>
                        </td>
                        <td class="rates__time">
                            <?= calcTimeHavePassed($item, 'bet_created'); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </section>
</main>
