CREATE TABLE IF NOT EXISTS `#__pagebuilderck_styles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` int(10) NOT NULL,
  `htmlcode` text NOT NULL,
  `checked_out` varchar(10) NOT NULL,
  `stylecode` text NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;