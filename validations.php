<?php

/**
 * Проверяет переданную дату на соответствие указанному или дефолтному формату 'ГГГГ-ММ-ДД'
 * @param string $date дата в виде строки
 * @param string $format формат даты в ввиде строки
 * @return bool истинное значение при совпадении форматов
 */
function validateDateFormat(string $date, string $format = 'Y-m-d'): bool
{
    $dt = DateTime::createFromFormat($format, $date);
    return $dt && $dt->format($format) === $date;
}

/**
 * Проверяет список обязательных полей на наличие данных переданных методом POST
 * Возвращает массив ошибок, где ключ - название поля, значение - текст ошибки
 * @param array $fields список полей с названием поля и текстом ошибки
 * @return array список ошибок
 */
function validateRequiredFields(array $fields): array
{
    $errors = [];

    foreach ($fields as $field) {
        $val = $_POST[$field['name']];

        if (empty($val) && $val !== '0') {
            $errors[$field['name']] = $field['error_msg'];
        }
    }
    return $errors;
}

/**
 * Проверяет наличие файла изображения в массиве $_FILES и валидирует по типу и размеру
 * @param string $field_name строковое название поля в массиве $_FILES
 * @return array список ошибок
 */
function validateImgFile(string $field_name): array
{
    $UPLOAD_ERR_NO_FILE = 4;
    $errors = [];

    $file = $_FILES[$field_name];

    if ($file['error'] && $file['error'] === $UPLOAD_ERR_NO_FILE) {
        $errors[$field_name] = 'Добавьте изображение лота';
    } else if ($file['size']) {
        $fileType = mime_content_type($file['tmp_name']);

        if ($fileType !== 'image/jpeg' && $fileType !== 'image/png') {
            $errors[$field_name] = 'Изображение в формате jpeg/png';
        }
        if ($file['size'] > 2000000) {
            $errors[$field_name] = 'Максимальный размер файла: 2Мб';
        }
    }
    return $errors;
}

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
