<?php
/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function dbGetPrepareStmt($link, $sql, $data = [])
{
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
            } elseif (is_string($value)) {
                $type = 's';
            } elseif (is_double($value)) {
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
function getNounPluralForm(int $number, string $one, string $two, string $many): string
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
function includeTemplate($name, array $data = [])
{
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
    $price_int = ceil($price);

    if ($price_int <= 1000) {
        return $price_int . '₽';
    }
    $formatted_price = number_format($price_int, 0, '.', ' ');
    return $formatted_price . '₽';
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
    $filtered_date = htmlspecialchars($date);

    if (strtotime($filtered_date) <= time()) {
        return ['00', '00'];
    }

    $lot_exp_date = date_create($filtered_date);
    $current_date = date_create();

    $date_diff = date_diff($lot_exp_date, $current_date);

    $days_left = date_interval_format($date_diff, "%a");
    $hours_left = date_interval_format($date_diff, "%H");
    $minutes_left = date_interval_format($date_diff, "%I");

    $hours_left = intval($hours_left) + intval($days_left) * 24;

    return [$hours_left, $minutes_left];
}


/**
 * @param array $data
 * @param string $field_name
 * @return null|string
 */
function calcTimeHavePassed(array $data, string $field_name): ?string
{
    if (empty($data[$field_name]) || empty($data['date']) || empty($data['time'])) {
        return null;
    }

    $dt_now = date_create("now");
    $val_date = date_create($data[$field_name]);

    $dt_diff = date_diff($val_date, $dt_now);
    $days_diff = date_interval_format($dt_diff, "%d");
    $hours_diff = date_interval_format($dt_diff, "%h");
    $mins_diff = date_interval_format($dt_diff, "%i");

    $yesterday = date("d.m.y", strtotime("yesterday"));

    if ($days_diff == 0 && $hours_diff == 0 && $mins_diff == 0) {
        return 'Только что';
    } elseif ($days_diff == 0 && $hours_diff == 0 && $mins_diff > 0) {
        $noun = getNounPluralForm($mins_diff, 'минуту', 'минуты', 'минут');
        return "$mins_diff $noun назад";
    } elseif ($days_diff == 0 && $hours_diff == 1 && $mins_diff == 0) {
        return 'Час назад';
    } elseif ($data['date'] == $yesterday) {
        return 'Вчера в ' . $data['time'];
    } elseif ($days_diff == 0 && $hours_diff > 0 && $mins_diff > 0) {
        return 'Сегодня в ' . $data['time'];
    } else {
        return $data['date'] . ' в ' . $data['time'];
    }
}

/**
 * Проверяет наличие файла и переносит из временной папки в локальную
 * @param array $file
 * @return string|null вернет ссылку на файл или null
 */
function moveFileToLocalPath(array $file): ?string
{
    if (!isset($file) || $file['size'] === 0) {
        return null;
    }
    $file_name = $file['name'] ?? '';
    $file_path = __DIR__ . '/uploads/';
    $file_url = '/uploads/' . $file_name;

    $is_success = move_uploaded_file($file['tmp_name'] ?? '', $file_path . $file_name);

    return $is_success ? $file_url : null;
}

/**
 * Отрисовывает шаблон страницы с ошибкой и её текстом
 * @param string $error текст ошибки в виде строки
 * @return void
 */
function showError(string $error)
{
    print(includeTemplate('error.php', ['error' => $error, 'title' => 'GifTube - Страница ошибки']));
}

/**
 * Отрисовывает экран страницы на основе переданных параметров
 * @param array $params списое параметров
 * @return void
 */
function showScreen(array $params)
{
    $page_content = includeTemplate($params['file'], [
        'lot' => $params['lot'] ?? '',
        'bets' => $params['bets'] ?? '',
        'user_bets' => $params['user_bets'] ?? '',
        'errors' => $params['errors'] ?? '',
        'is_visible' => $params['is_visible'] ?? '',
        'category' => $params['category'] ?? '',
        'categories_list' => $params['categories_list'] ?? '',
        'pagination_templ' => $params['pagination_tmpl'] ?? '',
        'lot_cards_list_templ' => $params['lot_cards_list_tmpl'] ?? '',
        'categories_list_templ' => $params['categories_list_tmpl'] ?? ''
    ]);

    $layout_content = includeTemplate('layout.php', [
        'user' => getSessionUser(),
        'content' => $page_content,
        'title' => 'GifTube - ' . $params['title'],
        'categories_list_templ' => $params['categories_list_tmpl']
    ]);

    print($layout_content);
}

/**
 * @param array $pages
 * @param int $cur_page
 * @return string
 */
function getPaginationTemplate(array $pages = [], int $cur_page = 1)
{
    return includeTemplate('pagination.php', [
        'pages' => $pages,
        'cur_page' => $cur_page
    ]);
}

/**
 * @param array $lots_list
 * @return string|null
 */
function getLotCardsListTemplate(array $lots_list)
{
    if (!$lots_list) {
        return null;
    }
    return includeTemplate('lot-cards-list.php', [
        'goods_list' => $lots_list
    ]);
}

/**
 * @param $categories
 * @return string
 */
function getCategoriesListTemplate($categories)
{
    return includeTemplate('categories-list.php', [
        'categories_list' => $categories,
    ]);
}

/**
 * Получение значение переменной из url
 * @param string $name имя переменной в url
 * @return string|null
 */
function getByNameFromUrl(string $name): ?string
{
    return filter_input(INPUT_GET, $name, FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Фильтрует массив данных на основе совпадения с ключами из массива правил
 * @param array $data
 * @param array $rules
 * @return array
 */
function filterDataByRules(array $data, array $rules): array
{
    $filteredData = [];

    foreach ($rules as $rule) {
        $key = $rule['field_name'] ?? '';

        if (array_key_exists($key, $data)) {
            $filteredData[$key] = $data[$key];
        }
    }

    return $filteredData;
}

/**
 * Вычисляет количество страниц и смещение на основе общего количества элементов, элементов на страницу и текущей страницы
 * @param int $items_count общее количество элементов
 * @param int $items_per_page количество элементов на страницу
 * @return array параметры пагинации
 */
function getPaginationParams(int $items_count, int $items_per_page): array
{
    $current_page = $_GET['page'] ?? 1;

    $pages_count = ceil($items_count / $items_per_page);
    $offset = ($current_page - 1) * $items_per_page;
    $pages = range(1, $pages_count > 1 ? $pages_count : 1);

    return [$pages, $offset, $current_page];
}

/**
 * Проверяет наличие пользовательской сессии и возвращает данные пользователя
 * @return array|null
 */
function getSessionUser(): ?array
{
    return isset($_SESSION['user']) ? $_SESSION['user'][0] : null;
}

/**
 * Возвращает значение минимально допустимой ставки на лот
 * @return int|null
 */
function getLotMinBetValue(): ?int
{
    if (isset($_SESSION['lot-info'])) {
        $lot = $_SESSION['lot-info'];

        $lot_price = $lot['current_price'] ?? $lot['initial_price'];
        return $lot_price + $lot['bet_step'];
    }

    return null;
}

/**
 * Проверяет условие для показа формы добавления ставки на лот
 * Форма не показывается если:
 * - пользователь не авторизован;
 * - срок размещения лота истёк;
 * - лот создан текущим пользователем;
 * - последняя ставка сделана текущим пользователем.
 * @param $lot
 * @param $user
 * @param $bets
 * @return bool
 */
function betFormIsVisible($lot, $user, $bets)
{
    if (empty($user) || empty($user['id']) || empty($lot['author']) || empty($lot['expiry_dt']) || isset($lot['winner'])) {
        return false;
    }

    $user_is_last_bet_author = false;
    $user_is_lot_author = $lot['author'] === intval($user['id']);

    $lot_has_expired = lotTimeLeftCalc($lot['expiry_dt']) === ['00', '00'];

    if (!empty($bets)) {
        usort($bets, function ($a, $b) {
            if (empty($a['created_at']) || empty($b['created_at'])) {
                return null;
            }
            return strtotime($b["created_at"]) - strtotime($a["created_at"]);
        });

        $user_is_last_bet_author = ($bets[0]['user_id'] ?? null) === intval($user['id']);
    }

    if ($user_is_lot_author || $user_is_last_bet_author || $lot_has_expired) {
        return false;
    }

    return true;
}

/**
 * Отправка писем победителям аукциона
 * Генерирует текст из шаблона писмьа на основе переданных данных
 * Создает транспорт
 * Создает сообщение
 * Отправляет сообщение
 * @param array $data данные по выигравшим лотам и победителям
 * @return void
 */
function sendLettersToTheWinners(array $data)
{
    foreach($data as $value) {
        $email_layout = includeTemplate('email.php', [
            'data' => $value
        ]);

        $transport = configTransport();
        $message = createMessage($value, $email_layout);

        sendEmail($transport, $message);
    }
}

/**
 * Принимает на вход строку, удаляет пробелы по краям и экранирует спец.символы
 * @param string|null $input
 * @return string
 */
function sanitize(?string $input):string
{
    return htmlspecialchars(trim($input));
}

/**
 * Принимает на вход номер страницы и возвращает текущий адрес с измененным/добавленным параметром page
 * @param int $page
 * @return string
 */
function calcPageUrl(int $page):string
{
    parse_str($_SERVER['QUERY_STRING'], $query_string);
    $query_string['page'] = $page;
    $rdr_str = http_build_query($query_string);

    return $_SERVER['PHP_SELF'] . '?' . $rdr_str;
}
