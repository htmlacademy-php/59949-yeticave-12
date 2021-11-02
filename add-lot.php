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

$errors = [];

$required_fields = [
    ['name'=>'lot-name', 'text'=>'Введите наименование лота'],
    ['name'=>'lot-rate', 'text'=>'Введите начальную цену'],
    ['name'=>'lot-step', 'text'=>'Введите шаг ставки'],
    ['name'=>'lot-date', 'text'=>'Введите дату завершения торгов'],
    ['name'=>'category', 'text'=>'Выберите категорию'],
    ['name'=>'message', 'text'=>'Напишите описание лота']
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo("<pre>");
    print_r("отправка формы");
    echo("</pre>");

    $errors = validateRequiredFields($required_fields);

    $UPLOAD_ERR_NO_FILE = 4;

    if ($_FILES['lot-img']['error'] && $_FILES['lot-img']['error'] === $UPLOAD_ERR_NO_FILE) {
        $errors['lot-img'] = 'Добавьте изображение лота';
    } else {
        echo("file uploaded!");
    }

    if (!count($errors)) {
        $rate = $_POST['lot-rate'];
        $step = $_POST['lot-step'];
        $date = $_POST['lot-date'];
        $dateNow = date("Y-m-d");

        if (!$rate || !is_numeric($rate)) {
            $errors['lot-rate'] = 'Значение должно быть числом больше нуля';
        }
        if (!$step || !ctype_digit($step)) {
            $errors['lot-step'] = 'Значение должно быть целым числом больше нуля';
        }
        if (!validateDate($date)) {
            $errors['lot-date'] = 'Некорректный формат даты';
        } else if (!$date || $date <= $dateNow) {
            $errors['lot-date'] = 'Значение должно быть больше текущей даты, хотя бы на один день';
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
echo("_FILES: ");
print_r($_FILES);
echo("</pre>");

print($layout_content);
