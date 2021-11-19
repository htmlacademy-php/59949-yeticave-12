<?php

require_once('helpers.php');
require_once('data/data.php');
require_once('db-config.php');
require_once('queries/categories.php');

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

            $sql = "INSERT INTO lots (expiry_dt, title, description, img_path, initial_price, bet_step, category_id, author) "
              . "VALUES (?, ?, ?, ?, ?, ?, ?, 1)";

            $stmt = db_get_prepare_stmt($db_conn, $sql, [$lot_date, $lot_name, $message, $file_url, $lot_rate, $lot_step, $category_id]);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $lot_id = mysqli_insert_id($db_conn);
                header("Location: lot.php?id=" . $lot_id);
            } else {
                $error = mysqli_error($db_conn);
                show_error($error);
                exit();
            }
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
