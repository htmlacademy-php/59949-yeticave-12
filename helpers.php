<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Форматирует цену товара и всегда возвращает целочисленное значение с символом рубля
 * @param float $price Цена лота в виде числа, целого или с плавающей точкой
 * @return string Итоговая цена
 */
function formatPrice(float $price): string
{
    $priceInt = ceil($price);

    if ($priceInt <= 1000) {
        return $priceInt . '₽';
    }
    $formattedPrice = number_format($priceInt, 0, '.', ' ');
    return $formattedPrice . '₽';
}

/**
 * Возвращает массив, где первый элемент — целое количество часов до истечения срока жизни лота,
 * а второй — остаток в минутах
 * @param string $date дата окончания жизни лота
 * @return array количество часов и минут до истечения лота
 */
function lotTimeLeftCalc(string $date): array
{
    date_default_timezone_set("Europe/Moscow");
    $filteredDate = htmlspecialchars($date);

    if (strtotime($filteredDate) <= time()) {
        return ['00', '00'];
    }

    $lotExpDate = date_create($filteredDate);
    $currentDate = date_create();

    $dateDiff = date_diff($lotExpDate, $currentDate);

    $daysLeft = date_interval_format($dateDiff, "%d");
    $hoursLeft = date_interval_format($dateDiff, "%H");
    $minutesLeft = date_interval_format($dateDiff, "%I");

    $hoursLeft = intval($hoursLeft) + intval($daysLeft) * 24;

    return [$hoursLeft, $minutesLeft];
}

/**
 * Проверяет список обязательных полей на наличие данных переданных методом POST
 * Возвращает массив ошибок, где ключ - название поля, значение - текст ошибки
 * @param array $fields список полей с названием поля и текстом ошибки
 * @return array список ошибок
 */
function validateRequiredFields(array $fields): array {
    $errors = [];

    foreach ($fields as $field) {
        $val = $_POST[$field['name']];

        if (empty($val) && $val !== '0') {
            $errors[$field['name']] = $field['text'];
        }
    }
    return $errors;
}

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
 * Проверяет наличие файла изображения в массиве $_FILES и валидирует по типу и размеру
 * @param string $fieldName строковое название поля в массиве $_FILES
 * @return array список ошибок
 */
function validateImgFile(string $fieldName): array
{
    $UPLOAD_ERR_NO_FILE = 4;
    $errors = [];

    $file = $_FILES[$fieldName];

    if ($file['error'] && $file['error'] === $UPLOAD_ERR_NO_FILE) {
        $errors[$fieldName] = 'Добавьте изображение лота';
    } else if ($file['size']) {
        $fileType = mime_content_type($file['tmp_name']);

        if ($fileType !== 'image/jpeg' && $fileType !== 'image/png') {
            $errors[$fieldName] = 'Изображение в формате jpeg/png';
        }
        if ($file['size'] > 2000000) {
            $errors[$fieldName] = 'Максимальный размер файла: 2Мб';
        }
    }
    return $errors;
}

/**
 * Проверяет наличие файла изображения в массиве $_FILES и переносит из временной папки в локальную
 * @param string $fieldName строковое название поля в массиве $_FILES
 * @return bool вернет true в случае успешного переноса файла
 */
function copyFileToLocalPath(string $fieldName): bool
{
    if (isset($_FILES[$fieldName])) {
        $file = $_FILES[$fieldName];

        $fileName = $file['name'];
        $filePath = __DIR__ . '/uploads/';

        return move_uploaded_file($file['tmp_name'], $filePath . $fileName);
    }
    return false;
}
