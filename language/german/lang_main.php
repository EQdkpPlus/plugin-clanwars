<?php
/*
 * Project:     EQdkp guildrequest
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2011-09-02 10:09:49 +0200 (Fr, 02. Sep 2011) $
 * -----------------------------------------------------------------------
 * @author      $Author: Aderyn $
 * @copyright   2008-2011 Aderyn
 * @link        http://eqdkp-plus.com
 * @package     guildrequest
 * @version     $Rev: 11183 $
 *
 * $Id: lang_main.php 11183 2011-09-02 08:09:49Z Aderyn $
 */

if (!defined('EQDKP_INC'))
{
    header('HTTP/1.0 404 Not Found');exit;
}

$lang = array(
  'clanwars'                    => 'ClanWars',

  // Description
  'clanwars_short_desc'         => 'ClanWars',
  'clanwars_long_desc'          => 'Verwalte die Wars deines Clans, sowie Awards und FightUs-Anfragen',
  
	'cw_manage_awards'			=> 'Awards verwalten',
	'cw_manage_categories'		=> 'Kategorien verwalten',
	'cw_manage_clans'			=> 'Clans verwalten',
	'cw_manage_fightus'			=> 'FightUs-Anfragen verwalten',
	'cw_manage_games'			=> 'Spiele verwalten',
	'cw_manage_teams'			=> 'Teams verwalten',
	'cw_manage_wars'			=> 'Matches verwalten',
	'cw_add_fightus'			=> 'FightUs-Anfrage stellen',
	'cw_not_installed'			=> 'Das ClanWars-Plugin ist nicht installiert.',
	
	//Awards
	'cw_confirm_delete_awards'	=> 'Bist Du sicher, dass Du diese Awards %s löschen willst?',
	'cw_add_award'				=> 'Award hinzufügen',
	'cw_delete_selected_awards' => 'Ausgewählte Awards löschen',
	'cw_delete_awards_suc'		=> 'Die ausgewählten Awards wurden erfolgreich gelöscht',
	'cw_delete_awards_no_suc'	=> 'Beim Löschen der ausgewählten Awards ist ein Fehler aufgetreten.',
	'cw_awards_f_event'			=> 'Event',
	'cw_awards_f_date'			=> 'Datum',
	'cw_awards_f_rank'			=> 'Platzierung',
	'cw_awards_f_teamID'		=> 'Team',
	'cw_awards_f_help_teamID'	=> 'Wähle ein Team oder ein Spieler aus, das den Award bekommen hat.',
	'cw_awards_f_gameID'		=> 'Spiel',
	'cw_awards_f_help_gameID'	=> 'Wenn der Award zu einem Team gehört, und kein Spiel ausgewählt ist, wird das Spiel des Teams verwendet',
	'cw_awards_f_userID'		=> 'Spieler',
	'cw_awards_f_help_userID'	=> 'Wähle ein Spieler oder ein Team aus, das den Award bekommen hat.',
	'cw_awards_f_website'		=> 'Weblink',
	'cw_awards_f_help_website'	=> 'zum Beispiel zu einem Nachbericht',
	'cw_save_awards_suc'		=> 'Der Award wurde erfolgreich gespeichert',
	'cw_save_awards_no_suc'		=> 'Beim Speichern des Awards ist ein Fehler aufgetreten.',
	
	//Categories
	'cw_confirm_delete_categories'	=> 'Bist Du sicher, dass Du diese Kategorien %s löschen willst?',
	'cw_add_category'				=> 'Kategorie hinzufügen',
	'cw_delete_selected_categories' => 'Ausgewählte Kategorien löschen',
	'cw_delete_categories_suc'		=> 'Die ausgewählten Kategorien wurden erfolgreich gelöscht',
	'cw_delete_categories_no_suc'	=> 'Beim Löschen der ausgewählten Kategorien ist ein Fehler aufgetreten.',
	'cw_save_categories_suc'		=> 'Der Award wurde erfolgreich gespeichert',
	'cw_save_categories_no_suc'		=> 'Beim Speichern des Kategorien ist ein Fehler aufgetreten.',
	'cw_categories_f_name'			=> 'Name',
	'cw_categories_f_icon'			=> 'Icon',
	'cw_categories_f_text'			=> 'Text',
	'cw_categories_f_website'		=> 'Website',
 );

?>
