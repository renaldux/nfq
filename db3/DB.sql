CREATE DATABASE dbnd3;

USE dbnd3;

CREATE TABLE IF NOT EXISTS `komentarai` (
`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `temos_id` int(11) NOT NULL,
  `komentaras` text NOT NULL,
  `autorius` varchar(160) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `temos` (
`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `pavadinimas` varchar(255) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `komentarai` ADD INDEX(`temos_id`);