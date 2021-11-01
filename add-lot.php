<?php

require_once('helpers.php');
require_once('data/data.php');

$link = mysqli_connect("localhost", "root", "root","yeticave");
mysqli_set_charset($link, "utf8");

if (!$link) {
    $error = mysqli_connect_error();
    print(include_template('error.php', ['error' => $error]));
    exit();
}

$sql = 'SELECT * FROM categories';
$result = mysqli_query($link, $sql);

if(!$result) {
    print(include_template('error.php', ['error' => mysqli_error($link)]));
    exit();
}
$categoriesList = mysqli_fetch_all($result, MYSQLI_ASSOC);

$required_fields = ['lot-name', 'lot-rate', 'lot-step', 'lot-date', 'category', 'message'];
$errors = [];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo("<pre>");
    print_r("отправка формы");
    echo("</pre>");

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            switch ($field) {
                case "lot-name":
                    $errors[$field] = 'Введите наименование лота';
                    break;
                case "lot-rate":
                    $errors[$field] = 'Введите начальную цену';
                    break;
                case "lot-step":
                    $errors[$field] = 'Введите шаг ставки';
                    break;
                case "lot-date":
                    $errors[$field] = 'Введите дату завершения торгов';
                    break;
                case "category":
                    $errors[$field] = 'Выберите категорию';
                    break;
                case "message":
                    $errors[$field] = 'Напишите описание лота';
                    break;
                default:
                    $errors[$field] = 'Заполните поле';
            }
        }
    }

    if (count($errors)) {
        // показать ошибку валидации
        echo("<pre>");
        echo('errors: ');
        print_r($errors);
        echo("</pre>");
    }
}

$page_content = include_template('add-lot.php', [
    'categoriesList' => $categoriesList,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'isAuth' => $isAuth,
    'userName' => $userName,
    'content' => $page_content,
    'categoriesList' => $categoriesList,
    'title' => 'GifTube - Добавление лота'
]);

echo("<pre>");
echo("_POST: ");
print_r($_POST);
echo("</pre>");

print($layout_content);
