CREATE TABLE IF NOT EXISTS `#__pagebuilderck_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` int(10) NOT NULL DEFAULT '1',
  `checked_out` varchar(10) NOT NULL,
  `created` datetime NOT NULL DEFAULT '1970-01-02 00:00:00',
  `modified` datetime NOT NULL DEFAULT '1970-01-02 00:00:00',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;