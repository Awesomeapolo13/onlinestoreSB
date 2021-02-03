-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 02 2021 г., 17:27
-- Версия сервера: 5.7.29
-- Версия PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `onlinestoredb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `path`, `type`) VALUES
(1, 'Женщины', '/?category=female', 'female'),
(2, 'Мужчины', '/?category=male', 'male'),
(3, 'Дети', '/?category=child', 'child'),
(4, 'Аксессуары', '/?category=accessory', 'accessory');

-- --------------------------------------------------------

--
-- Структура таблицы `category_product`
--

CREATE TABLE `category_product` (
  `category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `category_product`
--

INSERT INTO `category_product` (`category_id`, `product_id`) VALUES
(1, 1),
(1, 2),
(3, 2),
(1, 3),
(3, 3),
(4, 3),
(1, 4),
(3, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(2, 18),
(3, 18),
(2, 24),
(2, 25);

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'Оператор', 'Оператор – может заходить в административный интерфейс и видеть список заказов.'),
(2, 'Администратор', 'Администратор – может заходить в административный интерфейс, видеть список заказов и управлять товарами.');

-- --------------------------------------------------------

--
-- Структура таблицы `group_user`
--

CREATE TABLE `group_user` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `group_user`
--

INSERT INTO `group_user` (`user_id`, `group_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(3, 2),
(4, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `patronymic` varchar(255) DEFAULT NULL,
  `delivery` varchar(255) NOT NULL,
  `pay` varchar(255) NOT NULL,
  `comment` text,
  `product_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `home` varchar(255) DEFAULT NULL,
  `apartment` varchar(255) DEFAULT NULL,
  `price` decimal(11,2) NOT NULL,
  `done` tinyint(4) NOT NULL DEFAULT '0',
  `email` varchar(235) NOT NULL,
  `phone` varchar(235) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `name`, `surname`, `patronymic`, `delivery`, `pay`, `comment`, `product_id`, `timestamp`, `city`, `street`, `home`, `apartment`, `price`, `done`, `email`, `phone`) VALUES
(1, 'Игорь', 'Петренко', 'Петрович', 'dev-no', 'cash', 'Нечего Вам добавить', 4, '2020-11-14 10:40:56', NULL, NULL, NULL, NULL, '2999.00', 1, 'petrenCO@g-mail.com', '+7(910) 435-83-43'),
(2, 'Елена', 'Карпенко', 'Петровна', 'dev-yes', 'card', 'Нужно снять баллы при покупке', 2, '2020-12-15 10:41:21', 'Москва', 'Тверская', '23', '50', '2399.00', 1, 'germanlexa@mail.ru', '+7 (915) 944-91-69'),
(3, 'Светлана', 'Александрова', 'Олеговна', 'dev-yes', 'card', 'Без комментариев!', 4, '2020-12-15 11:20:28', 'Москва', 'Новый Арбат', '4', '3', '1279.00', 1, 'pochta@mail.ru', '+7(945) 461-05-03'),
(4, 'Сергеева', 'Нина', 'Николаевна', 'dev-no', 'cash', 'Сдача с 10 тыщ', 4, '2020-12-16 06:48:56', NULL, NULL, NULL, NULL, '2999.00', 1, 'serg@ya.ru', '+7(910) 435-83-43'),
(6, 'Владлена', 'Карпенко', 'Владеленовна', 'dev-no', 'card', 'Нет', 4, '2020-12-20 08:06:05', '', '', '', '', '1279.00', 0, 'vladlena@yandex.ru', '+7 915 944-91-69'),
(7, 'James', 'Forest', 'Poll', 'dev-no', 'cash', 'I have many cash!', 4, '2020-12-20 12:41:56', '', '', '', '', '2999.00', 0, 'roling@mail.ru', '+7(910) 435-83-43'),
(9, 'Сергей', 'Никитчук', 'Степанович', 'dev-yes', 'cash', 'Сдача с 10 тыщ', 18, '2021-01-20 13:00:31', 'Саратов', 'Ленина', '33', '78', '7999.00', 0, 'nickfury@mail.ru', '+7 (974) 546-80-27'),
(10, 'Николай', 'Николаев', NULL, 'dev-no', 'card', 'sdcfsdcf', 5, '2021-01-20 13:50:10', NULL, NULL, NULL, NULL, '3999.00', 0, 'germanlexa@mail.ru', '+7(947)-827-02-47'),
(12, 'Марина', 'Светлова', 'Евгеньевна', 'dev-no', 'cash', '', 9, '2021-01-28 10:21:19', NULL, NULL, NULL, NULL, '4999.00', 0, 'svetaNetu@eandex.ru', '+7(927)328-28-37');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `img_name` varchar(255) NOT NULL,
  `new` tinyint(1) NOT NULL DEFAULT '0',
  `sale` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `img_name`, `new`, `sale`) VALUES
(1, 'Платье со складками', '6899.00', 'product-1.jpg', 1, 0),
(2, 'Блузка клечатая', '2399.00', 'product-2.jpg', 0, 1),
(3, 'Часы наручные со стрелками', '1599.00', 'product-3.jpg', 0, 0),
(4, 'Штаны в полоску', '999.00', 'product-4.jpg', 0, 1),
(5, 'Пальто осеннее', '3999.00', 'product-5.jpg', 1, 0),
(6, 'Красное вечернее платье', '2999.00', 'product-6.jpg', 1, 0),
(7, 'Пальто с коротким рукавом', '5999.00', 'product-7.jpg', 1, 0),
(8, 'Джинсы', '3999.00', 'product-8.jpg', 0, 0),
(9, 'Туфли осенние', '4999.00', 'product-9.jpg', 1, 0),
(18, 'Спортивный костюм', '7999.00', 'product-11.jpg', 0, 1),
(24, 'Пальто', '15999.00', 'product-12.jpg', 1, 0),
(25, 'Рубашка хлопковая', '4999.00', 'product-13.jpg', 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `patronymic` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `patronymic`, `email`, `password`) VALUES
(1, 'Николай', 'Степанов', 'Иваночив', 'nickstepan@mail.ru', '$2y$10$N3/Dc2hYv78OQRKqWJlvB.U9JMhGR6VP42JKZ8K1AtQg40UP6Ahe6'),
(2, 'Егор', 'Захаров', 'Никтич', 'eZakh@gmail.com', '$2y$10$gUD7oYvirddob2kwsQC5puz.WYqpZh36kUZir2WcBDXdJ6PSujPEm'),
(3, 'Дмитрий', 'Майн', 'Эдуардович', 'mainHanz@ya.ru', '$2y$10$lGcUNrDZLHbkrqg2HzXMKe6QCMuXiGXxLoRA4H.tk7aGX9EAK8abO'),
(4, 'Кристофер', 'Нолан', 'Джеймс', 'interstellar@ygmail.com', '$2y$10$em3D6/UzbWMpmgChmmQEIe0nmZ7H.GDOUR4O3oo39amD/h3IZvK2.');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_id_uindex` (`id`);

--
-- Индексы таблицы `category_product`
--
ALTER TABLE `category_product`
  ADD PRIMARY KEY (`category_id`,`product_id`),
  ADD KEY `c_product_id_idx` (`product_id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `group_user`
--
ALTER TABLE `group_user`
  ADD KEY `c_group_id_idx` (`group_id`),
  ADD KEY `c_user_id_idx` (`user_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `c_product_id_idx` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `category_product`
--
ALTER TABLE `category_product`
  ADD CONSTRAINT `c_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ck_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `group_user`
--
ALTER TABLE `group_user`
  ADD CONSTRAINT `c_group_id` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `c_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `c_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
