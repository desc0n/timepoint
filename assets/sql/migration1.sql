--
-- Структура таблицы `rooms__rooms`
--

CREATE TABLE IF NOT EXISTS `rooms__rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `rooms__rooms`
--

INSERT INTO `rooms__rooms` (`id`, `title`, `price`) VALUES
(1, 'Номер 11', 2000),
(2, 'Номер 12', 3000),
(3, 'Номер 21', 4000),
(4, 'Номер 22', 5000);