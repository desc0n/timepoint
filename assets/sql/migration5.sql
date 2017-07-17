
CREATE TABLE IF NOT EXISTS `reservations__reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `arrival_at` datetime NOT NULL,
  `departure_at` datetime NOT NULL,
  `status_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`),
  KEY `status_id` (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `reservations__statuses`
--

CREATE TABLE IF NOT EXISTS `reservations__statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `reservations__reservations`
--
ALTER TABLE `reservations__reservations`
  ADD CONSTRAINT `reservations__reservations_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms__rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservations__reservations_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `reservations__statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
