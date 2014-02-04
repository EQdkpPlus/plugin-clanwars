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
* $Id: pdh_w_clanwars_awards.class.php 13558 2013-09-10 20:04:59Z godmod $
*/

if(!defined('EQDKP_INC')) {
	die('Do not access this file directly.');
}

if(!class_exists('pdh_w_clanwars_awards')) {
	class pdh_w_clanwars_awards extends pdh_w_generic {
		public static function __shortcuts() {
		$shortcuts = array('pdh', 'db'	);
		return array_merge(parent::$shortcuts, $shortcuts);
	}

		public function __construct() {
			parent::__construct();
		}
		
		public function add_award($arrData) {
			$arrSet = array(
				'event' 	=> $arrData['event'],
				'date'		=> $arrData['date'],
				'rank'		=> $arrData['rank'],
				'teamID'	=> $arrData['teamID'],
				'gameID'	=> $arrData['gameID'],
				'userID'	=> $arrData['userID'],
				'website'	=> $arrData['website'],
			);
			
			$objQuery = $this->db->prepare("INSERT INTO __clanwars_awards :p")->set($arrSet)->execute();
			
			if($objQuery) {
				$id = $objQuery->insertId;
				$this->pdh->enqueue_hook('clanwars_awards_update', array($id));
				return $id;
			}
			return false;
		}

		public function update_award($id, $arrData) {

			$arrSet = array(
				'event' 	=> $arrData['event'],
				'date'		=> $arrData['date'],
				'rank'		=> $arrData['rank'],
				'teamID'	=> $arrData['teamID'],
				'website'	=> $arrData['website'],
				'gameID'	=> $arrData['gameID'],
				'userID'	=> $arrData['userID'],
			);
			
			$objQuery = $this->db->prepare("UPDATE __clanwars_awards :p WHERE id =?")->set($arrSet)->execute($id);
			
			if($objQuery) {				
				$this->pdh->enqueue_hook('clanwars_awards_update', array($id));
				return true;
			}
			return false;
		}

		public function delete_award($intAwardID) {
			
			$objQuery = $this->db->prepare("DELETE FROM __clanwars_awards WHERE id = ?;")->execute($intAwardID);

			if($objQuery) {
				$this->pdh->enqueue_hook('clanwars_awards_update', array($intAwardID));
				return true;
			}

			return false;
		}
		
		public function reset() {
			$this->db->query("TRUNCATE TABLE __clanwars_awards;");
			$this->pdh->enqueue_hook('clanwars_awards_update');
		}
	}
}
?>