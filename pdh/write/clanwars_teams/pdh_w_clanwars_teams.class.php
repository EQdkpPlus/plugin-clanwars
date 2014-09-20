<?php
/*
* Project:		EQdkp-Plus
* License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:			http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:		2007
* Date:			$Date: 2013-09-10 22:04:59 +0200 (Di, 10 Sep 2013) $
* -----------------------------------------------------------------------
* @author		$Author: godmod $
* @copyright	2006-2011 EQdkp-Plus Developer Team
* @link			http://eqdkp-plus.com
* @package		eqdkpplus
* @version		$Rev: 13558 $
*
* $Id: pdh_w_clanwars_teams.class.php 13558 2013-09-10 20:04:59Z godmod $
*/

if(!defined('EQDKP_INC')) {
	die('Do not access this file directly.');
}

if(!class_exists('pdh_w_clanwars_teams')) {
	class pdh_w_clanwars_teams extends pdh_w_generic {

		public function __construct() {
			parent::__construct();
		}
		
		public function add_team($arrData) {
			$arrData['icon'] = str_replace($this->pfh->FileLink('team_icons', 'clanwars', 'absolute'), '', $arrData['icon']);
				
			$arrSet = array(
				'name'			=> $arrData['name'],
				'description'	=> $arrData['description'],
				'icon'			=> $arrData['icon'],
				'members'		=> serialize($arrData['members']),
				'gameID'		=> (int)$arrData['gameID'],
				'clanID'		=> (int)$arrData['clanID'],
			);
			
			$objQuery = $this->db->prepare("INSERT INTO __clanwars_teams :p")->set($arrSet)->execute();
			
			if($objQuery) {
				$id = $objQuery->insertId;
				$this->pdh->enqueue_hook('clanwars_teams_update', array($id));
				return $id;
			}
			return false;
		}

		public function update_team($id, $arrData) {
			$arrData['icon'] = str_replace($this->pfh->FileLink('team_icons', 'clanwars', 'absolute'), '', $arrData['icon']);
			
			$arrSet = array(
				'name'			=> $arrData['name'],
				'description'	=> $arrData['description'],
				'icon'			=> $arrData['icon'],
				'members'		=> serialize($arrData['members']),
				'gameID'		=> (int)$arrData['gameID'],
				'clanID'		=> (int)$arrData['clanID'],
			);
			
			$objQuery = $this->db->prepare("UPDATE __clanwars_teams :p WHERE id =?")->set($arrSet)->execute($id);
			
			if($objQuery) {				
				$this->pdh->enqueue_hook('clanwars_teams_update', array($id));
				return true;
			}
			return false;
		}

		public function delete_team($intAwardID) {
			
			$objQuery = $this->db->prepare("DELETE FROM __clanwars_teams WHERE id = ?;")->execute($intAwardID);

			if($objQuery) {
				$this->pdh->enqueue_hook('clanwars_teams_update', array($intAwardID));
				return true;
			}

			return false;
		}
		
		public function reset() {
			$this->db->query("TRUNCATE TABLE __clanwars_teams;");
			$this->pdh->enqueue_hook('clanwars_teams_update');
		}
	}
}
?>