<?php

$lot_name_min_len = 3;
$lot_name_max_len = 50;

$lot_descr_min_len = 10;
$lot_descr_max_len = 1000;


$lot_create_validation_rules = [
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
    ],
    [
        'field_name' => 'lot-img',
        'validations' => [
            ['method' => 'isFileSelected', 'error_msg' => 'Добавьте изображение лота'],
            ['method' => 'isFileTypeCorrect', 'param1' => 'image/jpeg', 'param2' => 'image/png', 'error_msg' => 'Изображение в формате jpeg/png'],
            ['method' => 'isFileSizeCorrect', 'param1' => 2000000, 'error_msg' => 'Максимальный размер файла: 2Мб']
        ]
    ]
];


$user_name_min_len = 4;
$user_name_max_len = 30;

$user_password_min_len = 8;
$user_password_max_len = 30;

$user_contacts_min_len = 10;
$user_contacts_max_len = 200;

$registration_validation_rules = [
    [
        'field_name' => 'email',
        'validations' => [
            ['method' => 'isNotEmpty', 'error_msg' => 'Введите e-mail'],
            ['method' => 'isCorrectEmailFormat', 'error_msg' => 'E-mail не соответсвует формату example@mail.domain']
        ]
    ],
    [
        'field_name' => 'password',
        'validations' => [
            ['method' => 'isNotEmpty', 'error_msg' => 'Введите пароль'],
            ['method' => 'isCorrectLength', 'param1' => $user_password_min_len, 'param2' => $user_password_max_len, 'error_msg' => "Значение должно быть от $user_password_min_len до $user_password_max_len символов"]
        ]
    ],
    [
        'field_name' => 'name',
        'validations' => [
            ['method' => 'isNotEmpty', 'error_msg' => 'Введите имя'],
            ['method' => 'isCorrectLength', 'param1' => $user_name_min_len, 'param2' => $user_name_max_len, 'error_msg' => "Значение должно быть от $user_name_min_len до $user_name_max_len символов"]
        ]
    ],
    [
        'field_name' => 'message',
        'validations' => [
            ['method' => 'isNotEmpty', 'error_msg' => 'Напишите как с вами связаться'],
            ['method' => 'isCorrectLength', 'param1' => $user_contacts_min_len, 'param2' => $user_contacts_max_len, 'error_msg' => "Значение должно быть от $user_contacts_min_len до $user_contacts_max_len символов"]
        ]
    ]
];

$login_validation_rules = [
    [
        'field_name' => 'email',
        'validations' => [
            ['method' => 'isNotEmpty', 'error_msg' => 'Введите e-mail'],
            ['method' => 'isCorrectEmailFormat', 'error_msg' => 'E-mail не соответсвует формату example@mail.domain']
        ]
    ],
    [
        'field_name' => 'password',
        'validations' => [
            ['method' => 'isNotEmpty', 'error_msg' => 'Введите пароль']
        ]
    ]
];

return [
    'lot-create' => $lot_create_validation_rules,
    'registration' => $registration_validation_rules,
    'login' => $login_validation_rules
];
