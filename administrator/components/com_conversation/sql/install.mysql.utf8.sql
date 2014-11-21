CREATE TABLE IF NOT EXISTS `#__conversation_message` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`message` TEXT NOT NULL ,
`father` VARCHAR(255)  NOT NULL ,
`team` INT(11)  NOT NULL ,
`opposition` VARCHAR(255)  NOT NULL ,
`agree` VARCHAR(255)  NOT NULL ,
`create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`sender` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

