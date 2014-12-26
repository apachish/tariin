CREATE TABLE IF NOT EXISTS `#__membership_category` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`name_category` VARCHAR(255)  NOT NULL ,
`type_member` VARCHAR(255)  NOT NULL ,
`payment` VARCHAR(255)  NOT NULL ,
`description` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__membership_member` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`user_id` TEXT NOT NULL ,
`start_member` DATE NOT NULL ,
`end_member` DATE NOT NULL ,
`payment` VARCHAR(255)  NOT NULL ,
`last_visit` DATE NOT NULL ,
`category_member` VARCHAR(255)  NOT NULL ,
`state_member` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;
