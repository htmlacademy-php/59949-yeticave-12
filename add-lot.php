<?php

require_once('helpers.php');
require_once('data/data.php');
require_once('db-config.php');

if (!$db_conn) {
    $error = mysqli_connect_error();
    print(include_template('error.php', ['error' => $error]));
    exit();
}

$sql = 'SELECT * FROM categories';
$result = mysqli_query($db_conn, $sql);

if(!$result) {
    print(include_template('error.php', ['error' => mysqli_error($db_conn)]));
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
    $errors = validateDataAvailability($required_fields);
    $errors =  array_merge($errors, validateImgFile('lot-img'));
    $errors = array_merge($errors, validateSpecificFields($errors));

    if (!count($errors)) {
        $fileURL = copyFileToLocalPath('lot-img');

        if ($fileURL) {
            $lotDate = $_POST['lot-date'];
            $lotRate = $_POST['lot-rate'];
            $lotStep = $_POST['lot-step'];
            $lotName = $_POST['lot-name'];
            $message = $_POST['message'];
            $categoryID = $_POST['category'];

            $sql = "INSERT INTO lots (expiry_dt, title, description, img_path, initial_price, bet_step, category_id, author) "
              . "VALUES (?, ?, ?, ?, ?, ?, ?, 1)";

            $stmt = db_get_prepare_stmt($db_conn, $sql, [$lotDate, $lotName, $message, $fileURL, $lotRate, $lotStep, $categoryID]);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $lotID = mysqli_insert_id($db_conn);
                header("Location: lot.php?id=" . $lotID);
            } else {
                print(include_template('error.php', ['error' => mysqli_error($db_conn)]));
                exit();
            }
        }
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

print($layout_content);

/**
 * Проверка формата данных в конкретных полях в случае, если нет других ошибок по этим полям
 * @param array $errorsList массив ошибок
 * @return array возвращает массив ошибок
 */
function validateSpecificFields(array $errorsList): array
{
    $errors = [];

    if (!$errorsList['lot-rate']) {
        $rate = $_POST['lot-rate'];

        if (!$rate || !is_numeric($rate)) {
            $errors['lot-rate'] = 'Значение должно быть числом больше нуля';
        }
    }
    if (!$errorsList['lot-step']) {
        $step = $_POST['lot-step'];

        if (!$step || !ctype_digit($step)) {
            $errors['lot-step'] = 'Значение должно быть целым числом больше нуля';
        }
    }
    if (!$errorsList['lot-date']) {
        $date = $_POST['lot-date'];
        $dateNow = date("Y-m-d");

        if (!validateDateFormat($date)) {
            $errors['lot-date'] = 'Некорректный формат даты';
        } else if (!$date || $date <= $dateNow) {
            $errors['lot-date'] = 'Значение должно быть больше текущей даты, хотя бы на один день';
        }
    }
    return $errors;
}
