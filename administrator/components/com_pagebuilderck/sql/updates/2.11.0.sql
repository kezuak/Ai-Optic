UPDATE `#__pagebuilderck_pages` SET created = '1970-01-02 00:00:00' WHERE `created` = '0000-00-00 00:00:00';

ALTER TABLE `#__pagebuilderck_pages` ADD `modified` datetime NOT NULL DEFAULT '1970-01-02 00:00:00';
