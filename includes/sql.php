<?php
/*
 * Project:     EQdkp Shoutbox
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2011-08-09 10:00:07 +0200 (Di, 09. Aug 2011) $
 * -----------------------------------------------------------------------
 * @author      $Author: Aderyn $
 * @copyright   2008-2011 Aderyn
 * @link        http://eqdkp-plus.com
 * @package     shoutbox
 * @version     $Rev: 10949 $
 *
 * $Id: sql.php 10949 2011-08-09 08:00:07Z Aderyn $
 */

if (!defined('EQDKP_INC'))
{
  header('HTTP/1.0 404 Not Found');exit;
}

$clanwarsSQL = array(

  'uninstall' => array(
    1     => 'DROP TABLE IF EXISTS `__clanwars_awards`',
	2     => 'DROP TABLE IF EXISTS `__clanwars_categories`',
	3     => 'DROP TABLE IF EXISTS `__clanwars_clans`',
	4     => 'DROP TABLE IF EXISTS `__clanwars_fightus`',
	5     => 'DROP TABLE IF EXISTS `__clanwars_games`',
	6     => 'DROP TABLE IF EXISTS `__clanwars_teams`',
	7     => 'DROP TABLE IF EXISTS `__clanwars_wars`',
  ),

  'install'   => array(
	1 => "CREATE TABLE `__clanwars_awards` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`event` TEXT NULL,
	`date` INT(10) UNSIGNED NULL DEFAULT NULL,
	`rank` INT(10) UNSIGNED NULL DEFAULT NULL,
	`gameID` INT(10) UNSIGNED NULL DEFAULT NULL,
	`teamID` INT(10) UNSIGNED NULL DEFAULT NULL,
	`userID` INT(10) UNSIGNED NULL DEFAULT NULL,
	`website` TEXT NULL,
	PRIMARY KEY (`id`)
)
DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
",
	2 => "CREATE TABLE `__clanwars_categories` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` TEXT NOT NULL,
	`website` TEXT NULL,
	`icon` VARCHAR(255) NULL DEFAULT NULL,
	`text` TEXT NULL,
	PRIMARY KEY (`id`)
)
DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
",
	
	3 => "CREATE TABLE `__clanwars_clans` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` TEXT NOT NULL,
	`shortname` VARCHAR(50) NOT NULL,
	`country` VARCHAR(3) NOT NULL,
	`website` TEXT NOT NULL,
	`estdate` INT(10) NOT NULL,
	`icon` VARCHAR(255) NOT NULL COLLATE 'utf8_bin',
	`tag` VARCHAR(50) NOT NULL,
	`tag_position` INT(2) NOT NULL,
	`own_clan` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
",
	4 => "CREATE TABLE `__clanwars_fightus` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`nick` VARCHAR(255) NULL DEFAULT NULL,
	`email` VARCHAR(255) NULL DEFAULT NULL,
	`additionalContact` VARCHAR(255) NULL DEFAULT NULL,
	`gameID` INT(11) NULL DEFAULT NULL,
	`teamID` INT(11) NOT NULL,
	`clanname` TEXT NOT NULL,
	`shortname` VARCHAR(50) NOT NULL,
	`country` VARCHAR(3) NOT NULL,
	`website` TEXT NOT NULL,
	`date` INT(11) NOT NULL,
	`status` INT(11) NOT NULL,
	`message` TEXT NOT NULL COLLATE 'utf8_bin',
	PRIMARY KEY (`id`)
)
DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
",
	5 => "CREATE TABLE `__clanwars_games` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` TEXT NULL,
	`version` VARCHAR(100) NULL DEFAULT NULL,
	`icon` VARCHAR(255) NULL DEFAULT NULL,
	`pubdate` INT(10) NULL DEFAULT NULL,
	`genre` VARCHAR(100) NULL DEFAULT NULL,
	`company` VARCHAR(255) NULL DEFAULT NULL,
	`website` TEXT NULL,
	`usk` INT(11) NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
",
	6 => "CREATE TABLE `__clanwars_teams` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` TEXT NULL COLLATE 'utf8_bin',
	`description` TEXT NULL COLLATE 'utf8_bin',
	`icon` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_bin',
	`members` TEXT NULL COLLATE 'utf8_bin',
	`gameID` INT(11) NULL DEFAULT NULL,
	`clanID` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
",
	7 => "CREATE TABLE `__clanwars_wars` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`gameID` INT(10) UNSIGNED NOT NULL,
	`categoryID` INT(10) UNSIGNED NOT NULL,
	`clanID` INT(10) UNSIGNED NOT NULL,
	`teamID` INT(10) UNSIGNED NOT NULL,
	`players` TEXT NOT NULL,
	`ownTeamID` INT(10) UNSIGNED NOT NULL,
	`ownPlayers` TEXT NOT NULL,
	`playerCount` TEXT NOT NULL,
	`date` INT(10) UNSIGNED NOT NULL,
	`status` INT(10) UNSIGNED NOT NULL,
	`result` TEXT NOT NULL,
	`website` TEXT NOT NULL,
	`report` TEXT NOT NULL,
	`ownReport` TEXT NOT NULL,
	`activateComments` INT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
",
)
  
  
);

?>