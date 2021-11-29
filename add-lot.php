<?php

require_once('db-methods.php');
require_once('helpers.php');
require_once('data/data.php');
require_once('queries/categories.php');
require_once('queries/create-lot.php');
require_once ('validations.php');

$db_conn = get_db_connect();

if (!$db_conn) {
    $error = get_db_connection_error();
    show_error($error);
    exit();
}

$categories_list = get_categories($db_conn);

if (!$categories_list) {
    $error = get_db_error($db_conn);
    show_error($error);
    exit();
}

$errors = [];

$form_required_fields = [
    ['name'=>'lot-name', 'error_msg'=>'Введите наименование лота'],
    ['name'=>'lot-rate', 'error_msg'=>'Введите начальную цену'],
    ['name'=>'lot-step', 'error_msg'=>'Введите шаг ставки'],
    ['name'=>'lot-date', 'error_msg'=>'Введите дату завершения торгов'],
    ['name'=>'category', 'error_msg'=>'Выберите категорию'],
    ['name'=>'message', 'error_msg'=>'Напишите описание лота']
];

$form_validate_rules = [
    'lot-name' => function() {
        return isCorrectLength('lot-name', 3, 50);
    },
    'message' => function() {
        return isCorrectLength('message', 10, 1000);
    },
    'lot-rate' => function() {
        return isNumGreaterThanZero('lot-rate');
    },
    'lot-step' => function() {
        return isIntGreaterThanZero('lot-step');
    },
    'lot-date' => function() {
        return isCorrectDate('lot-date');
    }
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = validateForm($form_required_fields, $form_validate_rules, 'lot-img');

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

            if (!$lot_id) {
                $error = get_db_error($db_conn);
                show_error($error);
                exit();
            }

            header("Location: lot.php?id=" . $lot_id);
        }
    }
}

show_screen('add-lot.php', 'Добавление лота', 'errors', $errors, $categories_list);
