
--
-- Структура таблицы `rooms__imgs`
--

CREATE TABLE IF NOT EXISTS `rooms__imgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `src` varchar(255) NOT NULL,
  `main` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `rooms__imgs`
--
ALTER TABLE `rooms__imgs`
  ADD CONSTRAINT `rooms__imgs_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms__rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
