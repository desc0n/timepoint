
--
-- Структура таблицы `content__contacts`
--

CREATE TABLE IF NOT EXISTS `content__contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('address','phone','email') NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `content__contacts`
--

INSERT INTO `content__contacts` (`id`, `type`, `value`) VALUES
(1, 'address', 'г. Владивосток, ул. Посьетская, 14'),
(2, 'phone', '8 800 235 35 72');
