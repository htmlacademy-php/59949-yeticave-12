<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <main class="content">
        <div class="content__main-col">
            <header class="content__header">
                <h2 class="content__header-text">Ошибка</h2>
            </header>
            <article class="gif-list">
                <p class="error"><?= $error; ?></p>
            </article>
        </div>
    </main>
<script src="flatpickr.js"></script>
<script src="script.js"></script>
</body>
</html>
