<?php

/**
 * Проверяет переданное значение на пустоту и неравенство нулю
 * @param null|string $val строкове значение
 * @return bool результат проверки
 */
function isNotEmpty(?string $val): bool {
    if (empty($val) && $val !== '0') {
        return false;
    }
    return true;
}

/**
 * Проверяет переданный почтовый адрес на корректность формата
 * @param string $email адрес электронной почты
 * @return bool результат проверки
 */
function isCorrectEmailFormat(string $email): bool {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
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
 * Проверяет что файл выбран
 * @param null|array $file
 * @return bool
 */
function isFileSelected(?array $file): bool {
    $UPLOAD_ERR_NO_FILE = 4;

    if (!$file || $file['error'] && $file['error'] === $UPLOAD_ERR_NO_FILE) {
        return false;
    }
    return true;
}

/**
 * Проверяет тип переданного файла на основе указанных параметров
 * @param array $file
 * @param string $type1
 * @param string $type2
 * @return bool
 */
function isFileTypeCorrect(array $file, string $type1, string $type2): bool {
    $fileType = mime_content_type($file['tmp_name']);

    if ($fileType !== $type1 && $fileType !== $type2) {
        return false;
    }
    return true;
}

/**
 * Проверяет размер файла на максимально допустимый размер
 * @param array $file
 * @param int $maxSize
 * @return bool
 */
function isFileSizeCorrect(array $file, int $maxSize):bool {
    if ($file['size'] > $maxSize) {
        return false;
    }
    return true;
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
    $len = mb_strlen($val, "UTF-8");

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
        $key = $rule['field_name'];
        $data_value = $data[$key];

        foreach ($rule['validations'] as $validation) {
            $is_valid = $validation['method']($data_value, $validation['param1'], $validation['param2']);

            if (!$is_valid) {
                $errors[$key] = $validation['error_msg'];
                break;
            }
        }
    }

    return $errors;
}
