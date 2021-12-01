<?php

$lot_name_min_len = 3;
$lot_name_max_len = 50;

$lot_descr_min_len = 10;
$lot_descr_max_len = 1000;


$validation_rules = [
    [
        'field_name' => 'lot-name',
        'validations' => [
            ['method' => 'isNotEmpty', 'error_msg' => 'Введите наименование лота'],
            ['method' => 'isCorrectLength', 'param1' => $lot_name_min_len, 'param2' => $lot_name_max_len, 'error_msg' => "Значение должно быть от $lot_name_min_len до $lot_name_max_len символов"]
        ]
    ],
    [
        'field_name' => 'lot-rate',
        'validations' => [
            ['method' => 'isNotEmpty', 'error_msg' => 'Введите начальную цену'],
            ['method' => 'isNumGreaterThanZero', 'error_msg' => 'Значение должно быть числом больше нуля']
        ]
    ],
    [
        'field_name' => 'lot-step',
        'validations' => [
            ['method' => 'isNotEmpty', 'error_msg' => 'Введите шаг ставки'],
            ['method' => 'isIntGreaterThanZero', 'error_msg' => 'Значение должно быть целым числом больше нуля']
        ]
    ],
    [
        'field_name' => 'lot-date',
        'validations' => [
            ['method' => 'isNotEmpty', 'error_msg' => 'Введите дату завершения торгов'],
            ['method' => 'isCorrectDateFormat', 'param1' => 'Y-m-d', 'error_msg' => 'Некорректный формат даты'],
            ['method' => 'isDateMinOneDayGreater', 'error_msg' => 'Значение должно быть больше текущей даты, хотя бы на один день']
        ]
    ],
    [
        'field_name' => 'category',
        'validations' => [
            ['method' => 'isNotEmpty', 'error_msg' => 'Выберите категорию']
        ]
    ],
    [
        'field_name' => 'message',
        'validations' => [
            ['method' => 'isNotEmpty', 'error_msg' => 'Напишите описание лота'],
            ['method' => 'isCorrectLength', 'param1' => $lot_descr_min_len, 'param2' => $lot_descr_max_len, 'error_msg' => "Значение должно быть от $lot_descr_min_len до $lot_descr_max_len символов"]
        ]
    ]
];
