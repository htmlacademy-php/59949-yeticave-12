INSERT INTO categories
    (title, code)
VALUES
    ('Доски и лыжи', 'boards'),
    ('Крепления', 'attachment'),
    ('Ботинки', 'boots'),
    ('Одежда', 'clothing'),
    ('Инструменты', 'tools'),
    ('Разное', 'other');

# user password: 'qwerty13'
INSERT INTO users
    (email, name, password, contact)
VALUES
    ('johndoe@mail.com', 'John Doe', '$2y$10$yQMp7omQa6ebIZ0cPtH9Cu55MnIDYGvzMxVi.i87M0nspkcUflpHW', '+10158144155'),
    ('maxpain@mail.com', 'Max Payne', '$2y$10$yQMp7omQa6ebIZ0cPtH9Cu55MnIDYGvzMxVi.i87M0nspkcUflpHW', '+23072001'),
    ('hopper@mail.com', 'Grace Hopper', '$2y$10$yQMp7omQa6ebIZ0cPtH9Cu55MnIDYGvzMxVi.i87M0nspkcUflpHW', '+19061992');

INSERT INTO lots
    (expiry_dt, title, description, img_path, initial_price, bet_step, category_id, author)
VALUES
    ('2022-04-29', '2014 Rossignol District Snowboard', 'good stuff', 'img/lot-1.jpg', 10999, 500, 1, 1),
    ('2022-04-29', 'DC Ply Mens 2016/2017 Snowboard', 'good stuff', 'img/lot-2.jpg', 15999, 1000, 1, 2),
    ('2022-04-29', 'Крепления Union Contact Pro 2015 года размер L/XL', 'good stuff', 'img/lot-3.jpg', 8000, 300, 2, 3),
    ('2022-04-29', 'Ботинки для сноуборда DC Mutiny Charocal', 'good stuff', 'img/lot-4.jpg', 10999, 400, 3, 1),
    ('2022-04-29', 'Куртка для сноуборда DC Mutiny Charocal', 'good stuff', 'img/lot-5.jpg', 7500, 250, 4, 2),
    ('2022-04-29', 'Маска Oakley Canopy', 'good stuff', 'img/lot-6.jpg', 5400, 100, 6, 3),
    ('2022-04-29', 'Крепления Drake 50', 'good stuff', 'img/lot-7.jpg', 12400, 450, 2, 2),
    ('2022-04-29', 'Чехол для сноуборда', 'good stuff', 'img/lot-8.jpg', 9600, 200, 6, 3),
    ('2022-04-29', 'Боты сноубордические Nike', 'good stuff', 'img/lot-9.jpg', 23000, 500, 3, 1),
    ('2022-04-29', 'Маска горнолыжная Wedzi', 'good stuff', 'img/lot-10.jpg', 11600, 430, 6, 2),
    ('2022-04-29', 'Сноуброд Burton', 'good stuff', 'img/lot-11.jpg', 31900, 2000, 1, 2),
    ('2022-04-29', 'Куртки горнолыжные', 'good stuff', 'img/lot-12.jpg', 13700, 1300, 4, 3);

INSERT INTO bets
    (amount, user_id, lot_id, created_at)
VALUES
    (11500, 2, 1, '2022-04-06 15:00:00'),
    (16999, 1, 2, '2022-04-06 15:01:00'),
    (8300, 1, 3, '2022-04-06 15:02:00'),
    (8600, 2, 3, '2022-04-06 15:03:00'),
    (11400, 3, 4, '2022-04-06 15:04:00'),
    (7750, 1, 5, '2022-04-06 15:05:00'),
    (8150, 3, 5, '2022-04-06 15:06:00'),
    (5500, 2, 6, '2022-04-06 15:07:00'),
    (5700, 1, 6, '2022-04-06 15:08:00');

-- получение списка всех категорий
SELECT * FROM categories;

-- получение списка из 5 самых свежих, открытых лотов с вычислением конечной цены и показом названия категории товара
SELECT
    l.title, c.title category_title, initial_price, (initial_price + IFNULL(SUM(b.amount), 0)) AS current_price, img_path
FROM lots l
LEFT JOIN bets b ON l.id = b.lot_id
JOIN categories c ON l.category_id = c.id
WHERE l.expiry_dt > NOW()
GROUP BY l.id
ORDER BY l.created_at DESC
LIMIT 5;

-- получение лота по его ID c добавлением названия категории товара
SELECT l.*, c.title AS category_title FROM lots l
JOIN categories c ON l.category_id = c.id
WHERE l.id = 4;

-- обновление названия лота по его ID с добавлением времени обновления записи;
UPDATE lots
SET title = 'VonZipper Jetpack', updated_at = NOW()
WHERE id = 6;

-- получение списка всех ставок для лота по его ID с сортировкой по дате
SELECT * FROM bets b
WHERE b.lot_id = 3
ORDER BY created_at DESC;
