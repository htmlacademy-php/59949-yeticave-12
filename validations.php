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
 * Проверяет что значение является числом больше нуля
 * @param string $name имя поля в массиве $_POST
 * @return string|null
 */
function isNumGreaterThanZero(string $name): ?string {
    $val = $_POST[$name];

    if (!empty($val) && !is_numeric($val) || is_numeric($val) && $val < 1) {
        return 'Значение должно быть числом больше нуля';
    }
    return null;
}

/**
 * Проверяет что значение является целым числом больше нуля
 * @param string $name имя поля в массиве $_POST
 * @return string|null
 */
function isIntGreaterThanZero(string $name): ?string {
    $val = $_POST[$name];

    if (!empty($val) && !ctype_digit($val) || ctype_digit($val) && $val < 1) {
        return 'Значение должно быть целым числом больше нуля';
    }
    return null;
}

/**
 * Проверяет формат даты и что она больше текущей
 * @param string $name имя поля в массиве $_POST
 * @return string|null
 */
function isCorrectDate(string $name): ?string {
    $val = $_POST[$name];
    $date_now = date("Y-m-d");

    if ($val && !validateDateFormat($val)) {
        return 'Некорректный формат даты';
    }
    if ($val && $val <= $date_now) {
        return 'Значение должно быть больше текущей даты, хотя бы на один день';
    }
    return null;
}

/**
 * Валидирует форму по заданному списку обязательных полей и возвращает массив ошибок
 * @param array $required_fields список обязательных полей
 * @param string|null $file_name необязательное имя файлового поля
 * @return array массив ошибок
 */
function validateForm(array $required_fields, array $rules, ?string $file_name = null):array
{
    $errors = validateRequiredFields($required_fields);

    if ($file_name) {
        $errors =  array_merge($errors, validateImgFile($file_name));
    }

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errorMsg = $rule();

            if ($errorMsg) {
                $errors[$key] = $errorMsg;
            }
        }
    }
    return $errors;
}
