<?php

require_once('helpers.php');
require_once('data/data.php');
require_once('queries/categories.php');
require_once('queries/create-lot.php');

$categories_list = fetch_categories($db_conn);

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
    $errors = validateDataAvailability($required_fields);
    $errors =  array_merge($errors, validateImgFile('lot-img'));
    $errors = array_merge($errors, validateSpecificFields($errors));

    if (empty($errors)) {
        $file_url = copyFileToLocalPath('lot-img');

        if ($file_url) {
            $lot_date = $_POST['lot-date'];
            $lot_rate = $_POST['lot-rate'];
            $lot_step = $_POST['lot-step'];
            $lot_name = $_POST['lot-name'];
            $message = $_POST['message'];
            $category_id = $_POST['category'];

            $lot_id = create_lot($db_conn, [$lot_date, $lot_name, $message, $file_url, $lot_rate, $lot_step, $category_id]);
            header("Location: lot.php?id=" . $lot_id);
        }
    }
}

$page_content = include_template('add-lot.php', [
    'categories_list' => $categories_list,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $page_content,
    'categories_list' => $categories_list,
    'title' => 'GifTube - Добавление лота'
]);

print($layout_content);

/**
 * Проверка формата данных в конкретных полях в случае, если нет других ошибок по этим полям
 * @param array $errors_list массив ошибок
 * @return array возвращает массив ошибок
 */
function validateSpecificFields(array $errors_list): array
{
    $errors = [];

    if (!$errors_list['lot-rate']) {
        $rate = $_POST['lot-rate'];

        if (!$rate || !is_numeric($rate)) {
            $errors['lot-rate'] = 'Значение должно быть числом больше нуля';
        }
    }
    if (!$errors_list['lot-step']) {
        $step = $_POST['lot-step'];

        if (!$step || !ctype_digit($step)) {
            $errors['lot-step'] = 'Значение должно быть целым числом больше нуля';
        }
    }
    if (!$errors_list['lot-date']) {
        $date = $_POST['lot-date'];
        $date_now = date("Y-m-d");

        if (!validateDateFormat($date)) {
            $errors['lot-date'] = 'Некорректный формат даты';
        } else if (!$date || $date <= $date_now) {
            $errors['lot-date'] = 'Значение должно быть больше текущей даты, хотя бы на один день';
        }
    }
    return $errors;
}
