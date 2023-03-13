-- noinspection SqlNoDataSourceInspectionForFile

/* Задание 4. Mysql */

/* https://dbfiddle.uk/GiDpnTsB */

/*    1) Составьте список пользователей users, которые осуществили хотя бы один заказ orders в интернет магазине. */

SELECT x.*
FROM users x
         JOIN orders o ON o.user_id = x.id
GROUP BY x.id;


/*    2)  Выведите список товаров products и разделов catalogs, который соответствует товару. */

SELECT *
FROM products p
         LEFT JOIN catalogs c ON c.id = p.id;


/*    3)  В базе данных shop и sample присутствуют одни и те же таблицы.
  * Переместите запись id = 1 из таблицы shop.users в таблицу sample.users. Используйте транзакции. */

START TRANSACTION;

INSERT INTO sample.users(name, birthday_at, created_at, updated_at)
SELECT name, birthday_at, created_at, updated_at
FROM shop.users u
WHERE id = 1;

DELETE
FROM shop.users
WHERE id = 1;

COMMIT;


/*    4)  Выведите одного случайного пользователя из таблицы shop.users, старше 30 лет, сделавшего минимум 3 заказа за последние полгода */

SELECT u.id, u.name, u.birthday_at, COUNT(*) orders
FROM users u
         JOIN orders o ON o.user_id = u.id AND o.created_at > NOW() - INTERVAL 6 MONTH
WHERE u.birthday_at < NOW() - INTERVAL 30 YEAR
GROUP BY 1
HAVING COUNT(*) >= 3
ORDER BY RAND()
LIMIT 1;