
--
-- Структура таблицы `conveniences`
--

CREATE TABLE IF NOT EXISTS `conveniences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `conveniences`
--

INSERT INTO `conveniences` (`id`, `value`) VALUES
(1, 'Двуспальная кровать'),
(3, 'Удобное рабочее место'),
(4, 'Кондиционер'),
(5, 'Бесплатный Wi-Fi'),
(6, 'Телевизор'),
(7, 'Холодильник'),
(8, 'Фен');

-- --------------------------------------------------------

--
-- Структура таблицы `rooms__conveniences`
--

CREATE TABLE IF NOT EXISTS `rooms__conveniences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `convenience_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`),
  KEY `convenience_id` (`convenience_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `rooms__conveniences`
--
ALTER TABLE `rooms__conveniences`
  ADD CONSTRAINT `rooms__conveniences_ibfk_2` FOREIGN KEY (`convenience_id`) REFERENCES `conveniences` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rooms__conveniences_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms__rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
