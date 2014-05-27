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
* $Id: pdh_w_clanwars_fightus.class.php 13558 2013-09-10 20:04:59Z godmod $
*/

if(!defined('EQDKP_INC')) {
	die('Do not access this file directly.');
}

if(!class_exists('pdh_w_clanwars_fightus')) {
	class pdh_w_clanwars_fightus extends pdh_w_generic {

		public function __construct() {
			parent::__construct();
		}
		
		public function add_fightus($arrData) {
			$arrSet = array(
				'nick' 		=> $arrData['nick'],
				'email'		=> $arrData['email'],
				'additionalContact'		=> $arrData['additionalContact'],
				'gameID'	=> $arrData['gameID'],
				'teamID'	=> $arrData['teamID'],
				'clanname'	=> $arrData['clanname'],
				'shortname'	=> $arrData['shortname'],
				'country' 	=> $arrData['country'],
				'website' 	=> $arrData['website'],
				'date' 		=> $arrData['date'],
				'status' 	=> $arrData['status'],
				'message' 	=> $arrData['message'],
			);
			
			$objQuery = $this->db->prepare("INSERT INTO __clanwars_fightus :p")->set($arrSet)->execute();
			
			if($objQuery) {
				$id = $objQuery->insertId;
				$this->pdh->enqueue_hook('clanwars_fightus_update', array($id));
				return $id;
			}
			return false;
		}

		public function update_fightus($id, $arrData) {

			$arrSet = array(
				'nick' 		=> $arrData['nick'],
				'email'		=> $arrData['email'],
				'additionalContact'		=> $arrData['additionalContact'],
				'gameID'	=> $arrData['gameID'],
				'teamID'	=> $arrData['teamID'],
				'clanname'	=> $arrData['clanname'],
				'shortname'	=> $arrData['shortname'],
				'country' 	=> $arrData['country'],
				'website' 	=> $arrData['website'],
				'date' 		=> $arrData['date'],
				'status' 	=> $arrData['status'],
				'message' 	=> $arrData['message'],
			);
			
			$objQuery = $this->db->prepare("UPDATE __clanwars_fightus :p WHERE id =?")->set($arrSet)->execute($id);
			
			if($objQuery) {				
				$this->pdh->enqueue_hook('clanwars_fightus_update', array($id));
				return true;
			}
			return false;
		}

		public function delete_fightus($intAwardID) {
			
			$objQuery = $this->db->prepare("DELETE FROM __clanwars_fightus WHERE id = ?;")->execute($intAwardID);

			if($objQuery) {
				$this->pdh->enqueue_hook('clanwars_fightus_update', array($intAwardID));
				return true;
			}

			return false;
		}
		
		public function reset() {
			$this->db->query("TRUNCATE TABLE __clanwars_fightus;");
			$this->pdh->enqueue_hook('clanwars_fightus_update');
		}
	}
}
?>