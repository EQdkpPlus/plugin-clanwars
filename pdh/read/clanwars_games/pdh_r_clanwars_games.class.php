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
		public static function __shortcuts() {
		$shortcuts = array('pdc', 'db', 'user', 'pdh', 'time', 'env', 'config');
		return array_merge(parent::$shortcuts, $shortcuts);
	}				
	
	public $default_lang = 'english';
	public $clanwars_games = null;

	public $hooks = array(
		'clanwars_games_update',
	);		
			
	public $presets = array(
		'clanwars_games_id' => array('id', array('%intGameID%'), array()),
		'clanwars_games_name' => array('name', array('%intGameID%'), array()),
		'clanwars_games_version' => array('version', array('%intGameID%'), array()),
		'clanwars_games_icon' => array('icon', array('%intGameID%'), array()),
		'clanwars_games_pubdate' => array('pubdate', array('%intGameID%'), array()),
		'clanwars_games_genre' => array('genre', array('%intGameID%'), array()),
		'clanwars_games_company' => array('company', array('%intGameID%'), array()),
		'clanwars_games_website' => array('website', array('%intGameID%'), array()),
		'clanwars_games_usk' => array('usk', array('%intGameID%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_'.clanwars_games.'_table');
			
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

					);
				}
				
				$this->pdc->put('pdh_clanwars_games_table', $this->clanwars_games, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list(){
			return array_keys($this->clanwars_games);
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

	}//end class
}//end if
?>