CREATE TABLE IF NOT EXISTS `#__pagebuilderck_options` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;