<main class="content">
    <h1>Поздравляем с победой</h1>
    <p>Здравствуйте, <?= htmlspecialchars($data['name']); ?></p>
    <p>Ваша ставка для лота
        <a href="http://localhost/lot.php?id=<?= htmlspecialchars($data['lot_id']); ?>">
            <?= htmlspecialchars($data['title']); ?>
        </a>
        победила.
    </p>
    <p>Перейдите по ссылке <a href="http://localhost/my-bets.php">мои ставки</a>,
        чтобы связаться с автором объявления</p>
    <small>Интернет-Аукцион "YetiCave"</small>
</main>
