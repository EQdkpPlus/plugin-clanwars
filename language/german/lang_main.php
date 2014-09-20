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
	'cw_save_categories_suc'		=> 'Die Kategorie wurde erfolgreich gespeichert',
	'cw_save_categories_no_suc'		=> 'Beim Speichern des Kategorien ist ein Fehler aufgetreten.',
	'cw_categories_f_name'			=> 'Name',
	'cw_categories_f_icon'			=> 'Icon',
	'cw_categories_f_text'			=> 'Text',
	'cw_categories_f_website'		=> 'Website',
	
	//Clans
	'cw_confirm_delete_clans'	=> 'Bist Du sicher, dass Du diese Clans %s löschen willst?',
	'cw_add_clan'				=> 'Clan hinzufügen',
	'cw_delete_selected_clans'	=> 'Ausgewählte Clans löschen',
	'cw_delete_clans_suc'		=> 'Die ausgewählten Clans wurden erfolgreich gelöscht',
	'cw_delete_clans_no_suc'	=> 'Beim Löschen der ausgewählten Clans ist ein Fehler aufgetreten.',
	'cw_save_clans_suc'			=> 'Der Award wurde erfolgreich gespeichert',
	'cw_save_clans_no_suc'		=> 'Beim Speichern des Clans ist ein Fehler aufgetreten.',
	'cw_clans_f_name'			=> 'Name',
	'cw_clans_f_icon'			=> 'Icon',
	'cw_clans_f_shortname'		=> 'Kürzel',
	'cw_clans_f_country'		=> 'Land',
	'cw_clans_f_tag'			=> 'Clantag',
	'cw_clans_f_tag_position'	=> 'Clantag-Position',
	'cw_tag_positions'			=> array(0 => '', 1 => 'vor Benutzername', 2 => 'nach Benutzername'),
	'cw_clans_f_website'		=> 'Website',
	'cw_clans_f_estdate'		=> 'Gründungstag',
	'cw_clans_f_own_clan'		=> 'Ist eigener Clan',
	'cw_clans_f_help_own_clan'	=> 'Setze diese Option auf ja, wenn es sich um Deinen eigenen Clan handelt. Die zugehörigen Teams werden dann z.B. auf der FightUs-Seite angezeigt',
	
	//Fightus
	'cw_confirm_delete_fightus'	=> 'Bist Du sicher, dass Du diese FightUs-Anfragen %s löschen willst?',
	'cw_delete_selected_fightus' => 'Ausgewählte FightUs-Anfragen löschen',
	'cw_delete_fightus_suc'		=> 'Die ausgewählten FightUs-Anfragen wurden erfolgreich gelöscht',
	'cw_delete_fightus_no_suc'	=> 'Beim Löschen der ausgewählten FightUs-Anfragen ist ein Fehler aufgetreten.',
	'cw_save_fightus_suc'		=> 'Die FightUs-Anfrage wurde erfolgreich gespeichert',
	'cw_save_fightus_no_suc'	=> 'Beim Speichern der FightUs-Anfrage ist ein Fehler aufgetreten.',
	'cw_fightus_fs_clan'		=> 'Clan-Informationen',
	'cw_fightus_fs_war'			=> 'Fight-Informationen',
	'cw_fightus_status'			=> array(0 => 'neu', 1 => 'in Diskussion', 2 => 'Angenommen', 3 => 'zu späteren Zeitpunkt', 4 => 'Abgelehnt'),
	'cw_fightus_f_website'		=> 'Website',
	'cw_fightus_f_nick'			=> 'Nickname',
	'cw_fightus_f_email'		=> 'Email-Adresse',
	'cw_fightus_f_additionalContact'		=> 'Zusätzliche Kontaktmöglichkeit',
	'cw_fightus_f_clanname'		=> 'Clanname',
	'cw_fightus_f_shortname'	=> 'Kürzel',
	'cw_fightus_f_country'		=> 'Land',
	'cw_fightus_f_date'			=> 'Datum',
	'cw_fightus_f_gameID'		=> 'Spiel',
	'cw_fightus_f_teamID'		=> 'Team',
	'cw_fightus_f_status'		=> 'Status',
	'cw_fightus_f_message'		=> 'Nachricht',
	'cw_fightus_fs_status'		=> 'Status',
	'cw_fightus_f_mail_message' => 'Email-Nachricht',
	'cw_fightus_f_help_mail_message' => 'Bei einer Statusänderung wird eine automatisch Email an den Anfragenden versendet. Verwende dieses Feld, um eine zusätzliche Mitteilung in diese Email einzufügen.',
	'cw_convert_to_clan'		=> 'Clan erstellen',
	'cw_convert_to_war'			=> 'Match erstellen',
	'cw_convert_to_calendarevent' => 'Kalendereintrag erstellen',
	'cw_fightus_email_subject'	=> 'Statusänderung FightUs-Anfrage',
	'cw_calendar_name'			=> 'Clanwar gegen %1$s',
	'cw_calendar_note'			=> 'Spiel %1$s, Team %2$s',
	
	//Games
	'cw_confirm_delete_games'	=> 'Bist Du sicher, dass Du diese Spiele %s löschen willst?',
	'cw_add_game'				=> 'Spiel hinzufügen',
	'cw_delete_selected_games'	=> 'Ausgewählte Spiele löschen',
	'cw_delete_games_suc'		=> 'Die ausgewählten Spiele wurden erfolgreich gelöscht',
	'cw_delete_games_no_suc'	=> 'Beim Löschen der ausgewählten Spiele ist ein Fehler aufgetreten.',
	'cw_save_games_suc'			=> 'Das Spiel wurde erfolgreich gespeichert',
	'cw_save_games_no_suc'		=> 'Beim Speichern des Spiels ist ein Fehler aufgetreten.',
	'cw_games_f_name'			=> 'Name',
	'cw_games_f_version'		=> 'Version',
	'cw_games_f_icon'			=> 'Icon',
	'cw_games_f_pubdate'		=> 'Veröffentlichungsdatum',
	'cw_games_f_genre'			=> 'Genre',
	'cw_games_f_company'		=> 'Hersteller',
	'cw_games_f_website'		=> 'Website',
	'cw_games_f_usk'			=> 'USK',
	
	//Teams
	'cw_confirm_delete_teams'	=> 'Bist Du sicher, dass Du diese Teams %s löschen willst?',
	'cw_add_team'				=> 'Team hinzufügen',
	'cw_delete_selected_teams'	=> 'Ausgewählte Teams löschen',
	'cw_delete_teams_suc'		=> 'Die ausgewählten Teams wurden erfolgreich gelöscht',
	'cw_delete_teams_no_suc'	=> 'Beim Löschen der ausgewählten Teams ist ein Fehler aufgetreten.',
	'cw_save_teams_suc'			=> 'Das Team wurde erfolgreich gespeichert',
	'cw_save_teams_no_suc'		=> 'Beim Speichern des Teams ist ein Fehler aufgetreten.',
	'cw_teams_f_name'			=> 'Name',
	'cw_teams_f_description'	=> 'Beschreibung',
	'cw_teams_f_icon'			=> 'Icon',
	'cw_teams_f_members'		=> 'Mitglieder',
	'cw_teams_f_clanID'			=> 'Clan',
	'cw_teams_f_gameID'			=> 'Spiel',
	
	//Wars
	'cw_confirm_delete_wars'	=> 'Bist Du sicher, dass Du diese Matches %s löschen willst?',
	'cw_add_war'				=> 'Match hinzufügen',
	'cw_delete_selected_wars'	=> 'Ausgewählte Matches löschen',
	'cw_delete_wars_suc'		=> 'Die ausgewählten Matches wurden erfolgreich gelöscht',
	'cw_delete_wars_no_suc'		=> 'Beim Löschen der ausgewählten Matches ist ein Fehler aufgetreten.',
	'cw_save_wars_suc'			=> 'Das Match wurde erfolgreich gespeichert',
	'cw_save_wars_no_suc'		=> 'Beim Speichern des Matches ist ein Fehler aufgetreten.',
	'cw_wars_fs_team'			=> 'Team-Informationen',
	'cw_wars_fs_war'			=> 'Match-Informationen',
	'cw_wars_f_clanID'			=> 'Gegnerischer Clan',
	'cw_wars_f_teamID'			=> 'Gegnerisches Team',
	'cw_wars_f_players'			=> 'Gegnerische Spieler',
	'cw_wars_f_help_players'	=> 'Trage hier in jede Zeile einen gegnerischen Spieler ein, sofern sie von den Teammitgliedern abweichen',
	'cw_wars_f_ownTeamID'		=> 'Eigenes Team',
	'cw_wars_f_ownPlayers'		=> 'Eigene Spieler',
	'cw_wars_f_help_ownPlayers'	=> 'Wähle die eigenen Spieler aus, sofern sie von den Teammitgliedern abweichen',
	'cw_wars_f_date'			=> 'Datum',
	'cw_wars_f_gameID'			=> 'Spiel',
	'cw_wars_f_categoryID'		=> 'Kategorie',
	'cw_wars_f_playerCount'		=> 'Spielerzahl (Eigene vs. Gegner)',
	'cw_wars_f_result'			=> 'Ergebnis (Eigene zu Gegner-Runden)',
	'cw_wars_f_website'			=> 'Website',
	'cw_wars_f_ownReport'		=> 'Spielbericht',
	'cw_wars_f_report'			=> 'Spielbericht des Gegners',
	'cw_wars_f_activateComments'=> 'Kommentare zum Match zulassen',
		
	'cw_awards'					=> 'Awards',
	'cw_matches'				=> 'Matches',
	'cw_match'					=> 'Match',
	'cw_versus'					=> 'gegen',
	'cw_matchdetails'			=> 'Matchdetails',
	'cw_clan'					=> 'Clan',
	'cw_est'					=> 'Established since ',
	'cw_teams'					=> 'Teams',
	'cw_statistics'				=> 'Statistiken',
	'cw_statistics_info'		=> 'Die Statistiken sind aus Sicht unseres Clans.',
	'cw_games'					=> 'Spiele',
		
	'cw_wins'					=> 'Gewonnen',
	'cw_loss'					=> 'Verloren',
	'cw_equal'					=> 'Unentschieden',
	'cw_categories'				=> 'Kategorien',
	'cw_category'				=> 'Kategorie',
	'cw_team'					=> 'Team',
	'cw_game'					=> 'Spiel',
 );

?>