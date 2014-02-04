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
				
if ( !class_exists( "pdh_r_clanwars_clans" ) ) {
	class pdh_r_clanwars_clans extends pdh_r_generic{
		public static function __shortcuts() {
		$shortcuts = array('pdc', 'db', 'user', 'pdh', 'time', 'env', 'config');
		return array_merge(parent::$shortcuts, $shortcuts);
	}				
	
	public $default_lang = 'english';
	public $clanwars_clans = null;

	public $hooks = array(
		'clanwars_clans_update',
	);		
			
	public $presets = array(
		'clanwars_clans_id' => array('id', array('%intClanID%'), array()),
		'clanwars_clans_name' => array('name', array('%intClanID%'), array()),
		'clanwars_clans_shortname' => array('shortname', array('%intClanID%'), array()),
		'clanwars_clans_country' => array('country', array('%intClanID%'), array()),
		'clanwars_clans_website' => array('website', array('%intClanID%'), array()),
		'clanwars_clans_estdate' => array('estdate', array('%intClanID%'), array()),
		'clanwars_clans_icon' => array('icon', array('%intClanID%'), array()),
		'clanwars_clans_tag' => array('tag', array('%intClanID%'), array()),
		'clanwars_clans_tag_position' => array('tag_position', array('%intClanID%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_'.clanwars_clans.'_table');
			
			$this->clanwars_clans = NULL;
	}
					
	public function init(){
			$this->clanwars_clans	= $this->pdc->get('pdh_clanwars_clans_table');				
					
			if($this->clanwars_clans !== NULL){
				return true;
			}		

			$objQuery = $this->db->query('SELECT * FROM __clanwars_clans');
			if($objQuery){
				while($drow = $objQuery->fetchAssoc()){
					$this->clanwars_clans[(int)$drow['id']] = array(
						'id'			=> (int)$drow['id'],
						'name'			=> $drow['name'],
						'shortname'		=> $drow['shortname'],
						'country'		=> $drow['country'],
						'website'		=> $drow['website'],
						'estdate'		=> (int)$drow['estdate'],
						'icon'			=> (int)$drow['icon'],
						'tag'			=> $drow['tag'],
						'tag_position'	=> (int)$drow['tag_position'],
					);
				}
				
				$this->pdc->put('pdh_clanwars_clans_table', $this->clanwars_clans, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list(){
			if ($this->clanwars_clans === null) return array();
			return array_keys($this->clanwars_clans);
		}
		
		/**
		 * Get all data of Element with $strID
		 * @return multitype: Array with all data
		 */				
		public function get_data($intClanID){
			if (isset($this->clanwars_clans[$intClanID])){
				return $this->clanwars_clans[$intClanID];
			}
			return false;
		}
				
		/**
		 * Returns id for $intClanID				
		 * @param integer $intClanID
		 * @return multitype id
		 */
		 public function get_id($intClanID){
			if (isset($this->clanwars_clans[$intClanID])){
				return $this->clanwars_clans[$intClanID]['id'];
			}
			return false;
		}

		/**
		 * Returns name for $intClanID				
		 * @param integer $intClanID
		 * @return multitype name
		 */
		 public function get_name($intClanID){
			if (isset($this->clanwars_clans[$intClanID])){
				return $this->clanwars_clans[$intClanID]['name'];
			}
			return false;
		}

		/**
		 * Returns shortname for $intClanID				
		 * @param integer $intClanID
		 * @return multitype shortname
		 */
		 public function get_shortname($intClanID){
			if (isset($this->clanwars_clans[$intClanID])){
				return $this->clanwars_clans[$intClanID]['shortname'];
			}
			return false;
		}

		/**
		 * Returns country for $intClanID				
		 * @param integer $intClanID
		 * @return multitype country
		 */
		 public function get_country($intClanID){
			if (isset($this->clanwars_clans[$intClanID])){
				return $this->clanwars_clans[$intClanID]['country'];
			}
			return false;
		}

		/**
		 * Returns website for $intClanID				
		 * @param integer $intClanID
		 * @return multitype website
		 */
		 public function get_website($intClanID){
			if (isset($this->clanwars_clans[$intClanID])){
				return $this->clanwars_clans[$intClanID]['website'];
			}
			return false;
		}

		/**
		 * Returns estdate for $intClanID				
		 * @param integer $intClanID
		 * @return multitype estdate
		 */
		 public function get_estdate($intClanID){
			if (isset($this->clanwars_clans[$intClanID])){
				return $this->clanwars_clans[$intClanID]['estdate'];
			}
			return false;
		}

		/**
		 * Returns icon for $intClanID				
		 * @param integer $intClanID
		 * @return multitype icon
		 */
		 public function get_icon($intClanID){
			if (isset($this->clanwars_clans[$intClanID])){
				return $this->clanwars_clans[$intClanID]['icon'];
			}
			return false;
		}

		/**
		 * Returns tag for $intClanID				
		 * @param integer $intClanID
		 * @return multitype tag
		 */
		 public function get_tag($intClanID){
			if (isset($this->clanwars_clans[$intClanID])){
				return $this->clanwars_clans[$intClanID]['tag'];
			}
			return false;
		}

		/**
		 * Returns tag_position for $intClanID				
		 * @param integer $intClanID
		 * @return multitype tag_position
		 */
		 public function get_tag_position($intClanID){
			if (isset($this->clanwars_clans[$intClanID])){
				return $this->clanwars_clans[$intClanID]['tag_position'];
			}
			return false;
		}

	}//end class
}//end if
?>