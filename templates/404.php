<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
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
        <section class="lot-item container">
            <h2>404 Страница не найдена</h2>
            <p>Данной страницы не существует на сайте.</p>
        </section>
    </main>
<script src="flatpickr.js"></script>
<script src="script.js"></script>
</body>
</html>
