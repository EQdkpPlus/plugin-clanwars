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
* $Id: pdh_w_clanwars_clans.class.php 13558 2013-09-10 20:04:59Z godmod $
*/

if(!defined('EQDKP_INC')) {
	die('Do not access this file directly.');
}

if(!class_exists('pdh_w_clanwars_clans')) {
	class pdh_w_clanwars_clans extends pdh_w_generic {

		public function __construct() {
			parent::__construct();
		}
		
		public function add_clan($arrData) {
			$arrData['icon'] = str_replace($this->pfh->FileLink('clan_icons', 'clanwars', 'absolute'), '', $arrData['icon']);
			
			$arrSet = array(
				'name' 			=> $arrData['name'],
				'shortname'		=> $arrData['shortname'],
				'country'		=> $arrData['country'],
				'website'		=> $arrData['website'],
				'estdate'		=> $arrData['estdate'],
				'icon'			=> $arrData['icon'],
				'tag'			=> $arrData['tag'],
				'tag_position'	=> $arrData['tag_position'],
				'website'		=> $arrData['website'],
				'own_clan'		=> $arrData['own_clan'],
			);
			
			$objQuery = $this->db->prepare("INSERT INTO __clanwars_clans :p")->set($arrSet)->execute();
			
			if($objQuery) {
				$id = $objQuery->insertId;
				$this->pdh->enqueue_hook('clanwars_clans_update', array($id));
				return $id;
			}
			return false;
		}

		public function update_clan($id, $arrData) {
			$arrData['icon'] = str_replace($this->pfh->FileLink('clan_icons', 'clanwars', 'absolute'), '', $arrData['icon']);
			
			$arrSet = array(
				'name' 			=> $arrData['name'],
				'shortname'		=> $arrData['shortname'],
				'country'		=> $arrData['country'],
				'website'		=> $arrData['website'],
				'estdate'		=> $arrData['estdate'],
				'icon'			=> $arrData['icon'],
				'tag'			=> $arrData['tag'],
				'tag_position'	=> $arrData['tag_position'],
				'website'		=> $arrData['website'],
				'own_clan'		=> $arrData['own_clan'],
			);
			
			$objQuery = $this->db->prepare("UPDATE __clanwars_clans :p WHERE id =?")->set($arrSet)->execute($id);
			
			if($objQuery) {				
				$this->pdh->enqueue_hook('clanwars_clans_update', array($id));
				return true;
			}
			return false;
		}

		public function delete_clan($intAwardID) {
			
			$objQuery = $this->db->prepare("DELETE FROM __clanwars_clans WHERE id = ?;")->execute($intAwardID);

			if($objQuery) {
				$this->pdh->enqueue_hook('clanwars_clans_update', array($intAwardID));
				return true;
			}

			return false;
		}
		
		public function reset() {
			$this->db->query("TRUNCATE TABLE __clanwars_clans;");
			$this->pdh->enqueue_hook('clanwars_clans_update');
		}
	}
}
?>