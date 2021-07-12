INSERT INTO categories
    (title, code)
VALUES
    ('Доски и лыжи', 'boards'),
    ('Крепления', 'attachment'),
    ('Ботинки', 'boots'),
    ('Одежда', 'clothing'),
    ('Инструменты', 'tools'),
    ('Разное', 'other');

INSERT INTO users
    (email, name, password, contact)
VALUES
    ('johndoe@mail.com', 'John Doe', 'anonymous', '+10158144155'),
    ('maxpain@mail.com', 'Max Payne', 'revenge', '+23072001'),
    ('hopper@mail.com', 'Grace Hopper', 'cobol', '+19061992');

INSERT INTO lots
    (expiry_dt, title, description, img_path, initial_price, bet_step, category_id, author)
VALUES
    ('2021-07-06', '2014 Rossignol District Snowboard', 'good stuff', 'img/lot-1.jpg', 10999, 500, 1, 1),
    ('2021-07-08', 'DC Ply Mens 2016/2017 Snowboard', 'good stuff', 'img/lot-2.jpg', 15999, 1000, 1, 2),
    ('2021-07-13', 'Крепления Union Contact Pro 2015 года размер L/XL', 'good stuff', 'img/lot-3.jpg', 8000, 300, 2, 3),
    ('2021-07-19', 'Ботинки для сноуборда DC Mutiny Charocal', 'good stuff', 'img/lot-4.jpg', 10999, 400, 3, 1),
    ('2021-07-25', 'Куртка для сноуборда DC Mutiny Charocal', 'good stuff', 'img/lot-5.jpg', 7500, 250, 4, 2),
    ('2021-08-03', 'Маска Oakley Canopy', 'good stuff', 'img/lot-6.jpg', 5400, 100, 6, 3);

INSERT INTO bets
    (amount, user_id, lot_id)
VALUES
    (500, 2, 1),
    (1000, 1, 2),
    (300, 1, 3),
    (300, 2, 3),
    (400, 3, 4),
    (250, 1, 5),
    (250, 3, 5),
    (100, 2, 6),
    (100, 1, 6);

-- получение списка всех категорий
SELECT * FROM categories;

-- получение списка из 5 самых свежих, открытых лотов с вычислением конечной цены и показом названия категории товара
SELECT
    l.title, c.title category_title, initial_price, (initial_price + SUM(b.amount)) AS current_price, img_path
FROM lots l
JOIN bets b ON l.id = b.lot_id
JOIN categories c ON l.category_id = c.id
WHERE l.expiry_dt > NOW()
GROUP BY b.lot_id
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
