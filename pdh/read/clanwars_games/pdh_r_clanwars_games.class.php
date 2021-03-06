<?php
/*
* Project:		EQdkp-Plus
* License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:			http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:		2010
* Date:			$Date: 2013-01-29 17:35:08 +0100 (Di, 29 Jan 2013) $
* -----------------------------------------------------------------------
* @author		$Author: wallenium $
* @copyright	2006-2014 EQdkp-Plus Developer Team
* @link			http://eqdkp-plus.eu
* @package		eqdkpplus
* @version		$Rev: 12937 $
*
* $Id: pdh_r_articles.class.php 12937 2013-01-29 16:35:08Z wallenium $
*/

if ( !defined('EQDKP_INC') ){
	die('Do not access this file directly.');
}
				
if ( !class_exists( "pdh_r_clanwars_games" ) ) {
	class pdh_r_clanwars_games extends pdh_r_generic{
	
	public $default_lang = 'english';
	public $clanwars_games = null;

	public $hooks = array(
		'clanwars_games_update',
	);		
			
	public $presets = array(
		'clanwars_games_id' => array('id', array('%intGameID%'), array()),
		'clanwars_games_name' => array('name', array('%intGameID%'), array()),
		'clanwars_games_enabled' => array('enabled', array('%intGameID%'), array()),
		'clanwars_games_version' => array('version', array('%intGameID%'), array()),
		'clanwars_games_icon' => array('icon', array('%intGameID%'), array()),
		'clanwars_games_pubdate' => array('pubdate', array('%intGameID%'), array()),
		'clanwars_games_genre' => array('genre', array('%intGameID%'), array()),
		'clanwars_games_company' => array('company', array('%intGameID%'), array()),
		'clanwars_games_website' => array('website', array('%intGameID%'), array()),
		'clanwars_games_usk' => array('usk', array('%intGameID%'), array()),
		'clanwars_games_actions' => array('actions', array('%intGameID%', '%link_url%', '%link_url_suffix%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_clanwars_games_table');
			
			$this->clanwars_games = NULL;
	}
					
	public function init(){
			$this->clanwars_games	= $this->pdc->get('pdh_clanwars_games_table');				
					
			if($this->clanwars_games !== NULL){
				return true;
			}		

			$objQuery = $this->db->query('SELECT * FROM __clanwars_games');
			if($objQuery){
				while($drow = $objQuery->fetchAssoc()){
					$this->clanwars_games[(int)$drow['id']] = array(
						'id'			=> (int)$drow['id'],
						'name'			=> $drow['name'],
						'version'		=> $drow['version'],
						'icon'			=> $drow['icon'],
						'pubdate'		=> (int)$drow['pubdate'],
						'genre'			=> $drow['genre'],
						'company'		=> $drow['company'],
						'website'		=> $drow['website'],
						'usk'			=> (int)$drow['usk'],
						'enabled'		=> (int)$drow['enabled'],
					);
				}
				
				$this->pdc->put('pdh_clanwars_games_table', $this->clanwars_games, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list($blnEnabledOnly = false){
			if ($this->clanwars_games === null) return array();
			
			if ($blnEnabledOnly){
				$arrOut = array();
				foreach(array_keys($this->clanwars_games) as $gameID){
					if ($this->get_enabled($gameID)) $arrOut[] = $gameID;
				}
				return $arrOut;
				
			} else 
				return array_keys($this->clanwars_games);
		}
		
		/**
		 * Get all data of Element with $strID
		 * @return multitype: Array with all data
		 */				
		public function get_data($intGameID){
			if (isset($this->clanwars_games[$intGameID])){
				return $this->clanwars_games[$intGameID];
			}
			return false;
		}
		
		public function get_actions($intGameID, $baseurl, $url_suffix=''){
			return "<a href='".$baseurl.$this->SID.'&amp;g='.$intGameID.$url_suffix."'>
				<i class='fa fa-pencil fa-lg' title='".$this->user->lang('edit')."'></i>
			</a>";
		}
				
		/**
		 * Returns id for $intGameID				
		 * @param integer $intGameID
		 * @return multitype id
		 */
		 public function get_id($intGameID){
			if (isset($this->clanwars_games[$intGameID])){
				return $this->clanwars_games[$intGameID]['id'];
			}
			return false;
		}

		/**
		 * Returns name for $intGameID				
		 * @param integer $intGameID
		 * @return multitype name
		 */
		 public function get_name($intGameID){
			if (isset($this->clanwars_games[$intGameID])){
				return $this->clanwars_games[$intGameID]['name'];
			}
			return false;
		}

		/**
		 * Returns version for $intGameID				
		 * @param integer $intGameID
		 * @return multitype version
		 */
		 public function get_version($intGameID){
			if (isset($this->clanwars_games[$intGameID])){
				return $this->clanwars_games[$intGameID]['version'];
			}
			return false;
		}

		/**
		 * Returns icon for $intGameID				
		 * @param integer $intGameID
		 * @return multitype icon
		 */
		 public function get_icon($intGameID){
			if (isset($this->clanwars_games[$intGameID])){
				return $this->clanwars_games[$intGameID]['icon'];
			}
			return false;
		}
		
		public function get_html_icon($intGameID, $intSize=32){
			$strIcon = $this->get_icon($intGameID);
			if ($strIcon && strlen($strIcon)){
				$strExtension = pathinfo($strIcon, PATHINFO_EXTENSION);
				$strIconName = md5('game_'.$intGameID.$strIcon).'_'.intval($intSize).'.'.$strExtension;
				$strThumbnailIcon = $this->pfh->FolderPath('thumbnails', 'clanwars').$strIconName;
				if (is_file($strThumbnailIcon)){
					return '<img src="'.$this->pfh->FolderPath('thumbnails', 'clanwars', 'absolute').$strIconName.'" alt="'.$this->get_name($intGameID).'"/>';
				} else {
					$strFullImage = $this->pfh->FolderPath('game_icons', 'clanwars').$strIcon;
					$this->pfh->thumbnail($strFullImage, $this->pfh->FolderPath('thumbnails', 'clanwars'), $strIconName, intval($intSize));
					return '<img src="'.$this->pfh->FolderPath('thumbnails', 'clanwars', 'absolute').$strIconName.'" alt="'.$this->get_name($intGameID).'"/>';
				}
			}
			
			return '';
		}

		/**
		 * Returns pubdate for $intGameID				
		 * @param integer $intGameID
		 * @return multitype pubdate
		 */
		 public function get_pubdate($intGameID){
			if (isset($this->clanwars_games[$intGameID])){
				return $this->clanwars_games[$intGameID]['pubdate'];
			}
			return false;
		}
		
		public function get_html_pubdate($intGameID){
			if ($this->get_pubdate($intGameID)) {
				return $this->time->user_date($this->get_pubdate($intGameID));
			}
			return '';
		}

		/**
		 * Returns genre for $intGameID				
		 * @param integer $intGameID
		 * @return multitype genre
		 */
		 public function get_genre($intGameID){
			if (isset($this->clanwars_games[$intGameID])){
				return $this->clanwars_games[$intGameID]['genre'];
			}
			return false;
		}

		/**
		 * Returns company for $intGameID				
		 * @param integer $intGameID
		 * @return multitype company
		 */
		 public function get_company($intGameID){
			if (isset($this->clanwars_games[$intGameID])){
				return $this->clanwars_games[$intGameID]['company'];
			}
			return false;
		}

		/**
		 * Returns website for $intGameID				
		 * @param integer $intGameID
		 * @return multitype website
		 */
		 public function get_website($intGameID){
			if (isset($this->clanwars_games[$intGameID])){
				return $this->clanwars_games[$intGameID]['website'];
			}
			return false;
		}

		/**
		 * Returns usk for $intGameID				
		 * @param integer $intGameID
		 * @return multitype usk
		 */
		 public function get_usk($intGameID){
			if (isset($this->clanwars_games[$intGameID])){
				return $this->clanwars_games[$intGameID]['usk'];
			}
			return false;
		}
		
		/**
		 * Returns enabled for $intGameID
		 * @param integer $intGameID
		 * @return multitype enabled
		 */
		public function get_enabled($intGameID){
			if (isset($this->clanwars_games[$intGameID])){
				return $this->clanwars_games[$intGameID]['enabled'];
			}
			return false;
		}
		
		public function get_html_enabled($intGameID){
			if ($this->get_enabled($intGameID)){
				return '<a href="manage_games.php'.$this->SID.'&disable='.$intGameID.'"><i class="fa fa-check-square-o fa-lg icon-color-green"></i></a>';
			} else {
				return '<a href="manage_games.php'.$this->SID.'&enable='.$intGameID.'"><i class="fa fa-square-o fa-lg icon-color-red"></i></a>';
					
			}
		}
		
		public function get_statistics($intGameID, $intClanID=false){
			$arrStats = array('wins' => 0, 'equal' => 0, 'loss' => 0);
			if (!$intClanID){
				$arrWars = $this->pdh->get('clanwars_wars', 'id_list', array());	
			} else {
				$arrWars = $this->pdh->get('clanwars_wars', 'wars_for_clan', array($intClanID));
			}
				
			foreach($arrWars as $intWarID){
				if ($this->pdh->get('clanwars_wars', 'gameID', array($intWarID)) != $intGameID) continue;
				
				$result = $this->pdh->get('clanwars_wars', 'win', array($intWarID));
				switch($result){
					case -1: $arrStats['loss']++; break;
					case 1: $arrStats['wins']++; break;
					default: $arrStats['equal']++;
				}
			}
				
			return $arrStats;
		}
		
		public function get_team_statistics($intGameID, $intTeamID){
			$arrStats = array('wins' => 0, 'equal' => 0, 'loss' => 0);
			$arrWars = $this->pdh->get('clanwars_wars', 'wars_for_team', array($intTeamID));
			
			foreach($arrWars as $intWarID){
				if ($this->pdh->get('clanwars_wars', 'gameID', array($intWarID)) != $intGameID) continue;
			
				$result = $this->pdh->get('clanwars_wars', 'win', array($intWarID));
				switch($result){
					case -1: $arrStats['loss']++; break;
					case 1: $arrStats['wins']++; break;
					default: $arrStats['equal']++;
				}
			}
			
			return $arrStats;
		}

	}//end class
}//end if
?>