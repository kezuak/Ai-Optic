-- DROP TABLE IF EXISTS `#__pagebuilderck_templates`;

CREATE TABLE IF NOT EXISTS `#__pagebuilderck_pages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` int(10) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT '1970-01-02 00:00:00',
  `modified` datetime NOT NULL DEFAULT '1970-01-02 00:00:00',
  `catid` varchar(255) NOT NULL,
  `created_by` int(10) NOT NULL,
  `params` text NOT NULL,
  `access` int(10) NOT NULL,
  `hits` int(10) NOT NULL,
  `featured` tinyint(3) NOT NULL,
  `htmlcode` longtext NOT NULL,
  `checked_out` varchar(10) NOT NULL,
  `categories` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__pagebuilderck_elements` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `type` varchar(50) NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` int(10) NOT NULL DEFAULT '1',
  `catid` varchar(255) NOT NULL,
  `htmlcode` longtext NOT NULL,
  `checked_out` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

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