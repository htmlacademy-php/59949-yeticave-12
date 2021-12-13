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

    <form class="form container <?= !empty($errors) ? 'form--invalid' : ''; ?>" action="login.php" method="post">
        <h2>Вход</h2>
        <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : '' ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $_POST['email'] ?? '' ?>">
            <span class="form__error"><?= $errors['email'] ?></span>
        </div>
        <div class="form__item <?= isset($errors['password']) ? 'form__item--invalid' : '' ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= $_POST['password'] ?? '' ?>">
            <span class="form__error"><?= $errors['password'] ?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>
