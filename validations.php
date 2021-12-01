<?php

/**
 * Проверяет переданное значение на пустоту и неравенство нулю
 * @param string $val строкове значение
 * @return bool результат проверки
 */
function isNotEmpty(string $val): bool {
    if (empty($val) && $val !== '0') {
        return false;
    }
    return true;
}

/**
 * Проверяет переданную дату на соответствие указанному формату
 * @param string $date дата в виде строки
 * @param string $format формат даты в ввиде строки
 * @return bool истинное значение при совпадении форматов
 */
function isCorrectDateFormat(string $date, string $format): bool
{
    $dt = DateTime::createFromFormat($format, $date);
    return $dt && $dt->format($format) === $date;
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
 * @param string $val строковое значение
 * @return boolean результат проверки
 */
function isNumGreaterThanZero(string $val): bool {
    if (empty($val) || !is_numeric($val) || $val <= 0) {
        return false;
    }
    return true;
}

/**
 * Проверяет что значение является целым числом больше нуля
 * @param string $val строковое значение
 * @return boolean результат проверки
 */
function isIntGreaterThanZero(string $val): bool {
    if (empty($val) || !ctype_digit($val) || $val <= 0) {
        return false;
    }
    return true;
}

/**
 * Проверяет строку на соответствие заданной длине
 * @param string $val строковое значение
 * @param int $min значение минимальной длины строки
 * @param int $max значение максимальной длины строки
 * @return boolean
 */
function isCorrectLength(string $val, int $min, int $max): bool {
    $len = strlen($val);

    if (!$len || $len < $min || $len > $max) {
        return false;
    }

    return true;
}

/**
 * Проверяет что переданная дата больше текущей минимум на день
 * @param string $val дата в виде строки
 * @return boolean
 */
function isDateMinOneDayGreater(string $val): bool {
    $date_now = date("Y-m-d");

    if (!$val || $val <= $date_now) {
        return false;
    }

    return true;
}

/**
 * Валидирует переданные данные на основе переданных правил
 * @param array $data данные
 * @param array $rules правила
 * @return array массив ошибок
 */
function validateForm(array $data, array $rules): array {
    $errors = [];

    foreach ($rules as $rule) {
        $data_value = $data[$rule['field_name']];

        foreach ($rule['validations'] as $validation) {
            $is_valid = $validation['method']($data_value, $validation['param1'], $validation['param2']);

            if (!$is_valid) {
                $errors[$rule['field_name']] = $validation['error_msg'];
                break;
            }
        }
    }

    return $errors;
}
