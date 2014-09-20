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
	
	public $default_lang = 'english';
	public $clanwars_clans = null;
	
	private $countries = false;

	public $hooks = array(
		'clanwars_clans_update',
	);		
			
	public $presets = array(
		'clanwars_clans_id' => array('id', array('%intClanID%'), array()),
		'clanwars_clans_name' => array('name', array('%intClanID%'), array()),
		'clanwars_clans_shortname' => array('shortname', array('%intClanID%'), array()),
		'clanwars_clans_country' => array('country', array('%intClanID%'), array()),
		'clanwars_clans_country_ext' => array('country', array('%intClanID%', true), array()),
		'clanwars_clans_website' => array('website', array('%intClanID%'), array()),
		'clanwars_clans_estdate' => array('estdate', array('%intClanID%'), array()),
		'clanwars_clans_icon' => array('icon', array('%intClanID%'), array()),
		'clanwars_clans_tag' => array('tag', array('%intClanID%'), array()),
		'clanwars_clans_tag_position' => array('tag_position', array('%intClanID%'), array()),
		'clanwars_clans_own_clan' => array('own_clan', array('%intClanID%'), array()),
		'clanwars_clans_actions' => array('actions', array('%intClanID%', '%link_url%', '%link_url_suffix%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_clanwars_clans_table');
			
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
						'icon'			=> $drow['icon'],
						'tag'			=> $drow['tag'],
						'tag_position'	=> (int)$drow['tag_position'],
						'own_clan'		=> (int)$drow['own_clan'],
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
		
		public function get_actions($intClanID, $baseurl, $url_suffix=''){
			return "<a href='".$baseurl.$this->SID.'&amp;c='.$intClanID.$url_suffix."'>
				<i class='fa fa-pencil fa-lg' title='".$this->user->lang('edit')."'></i>
			</a>";
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
		
		public function get_html_country($intClanID, $blnWithCountryName = false){
			$country = $this->get_country($intClanID);
			if ($country && strlen($country)){
				$this->init_countries();
				if (!$blnWithCountryName){
					return '<img src="'.$this->server_path.'images/flags/'.strtolower($country).'.svg" alt="'.$country.'" class="coretip" height="16" data-coretip="'.ucfirst(strtolower($this->countries[$country])).'" />';
				} else {
					return '<img src="'.$this->server_path.'images/flags/'.strtolower($country).'.svg" alt="'.$country.'" height="16" /> '.ucfirst(strtolower($this->countries[$country]));
				}
			}
			return '';
		}
		
		public function get_clanID_decorated($intClanID){
			$strOut = $this->get_html_country($intClanID).' <a href="'.$this->routing->build(array('clanwars', 'Clan'), $this->get_name($intClanID).', c'.$intClanID).'">'.$this->get_name($intClanID).'</a>';
			return $strOut;
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
		
		public function get_html_website($intClanID){
			if (strlen($this->get_website($intClanID))){
				return '<a href="'.$this->get_website($intClanID).'" rel="nofollow"><i class="fa fa-external-link"></i>'.$this->get_website($intClanID).'</a>';
			}
			return '';
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
		
		public function get_html_estdate($intClanID){
			return $this->time->user_date($this->get_estdate($intClanID));
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
		
		public function get_html_icon($intClanID, $intSize=32){
			$strIcon = $this->get_icon($intClanID);
			if ($strIcon && strlen($strIcon)){
				$strExtension = pathinfo($strIcon, PATHINFO_EXTENSION);
				$strIconName = md5('clan_'.$intClanID.$strIcon).'_'.intval($intSize).'.'.$strExtension;
				$strThumbnailIcon = $this->pfh->FolderPath('thumbnails', 'clanwars').$strIconName;
				if (is_file($strThumbnailIcon)){
					return '<img src="'.$this->pfh->FolderPath('thumbnails', 'clanwars', 'absolute').$strIconName.'" alt="'.$this->get_name($intClanID).'"/>';
				} else {
					$strFullImage = $this->pfh->FolderPath('clan_icons', 'clanwars').$strIcon;
					$this->pfh->thumbnail($strFullImage, $this->pfh->FolderPath('thumbnails', 'clanwars'), $strIconName, intval($intSize));
					return '<img src="'.$this->pfh->FolderPath('thumbnails', 'clanwars', 'absolute').$strIconName.'" alt="'.$this->get_name($intClanID).'"/>';
				}
			}
			
			return '';
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
		
		/**
		 * Returns own_clan for $intClanID				
		 * @param integer $intClanID
		 * @return multitype own_clan
		 */
		 public function get_own_clan($intClanID){
			if (isset($this->clanwars_clans[$intClanID])){
				return $this->clanwars_clans[$intClanID]['own_clan'];
			}
			return false;
		}
		
		public function get_clanIDforName($strClanname){
			foreach($this->clanwars_clans as $key => $val){
				if (stripos($val['name'], $strClanname) !== false){
					return $key;
				}
			}
			return false;
		}
		
		private function init_countries(){
			if (!$this->countries){
				include($this->root_path.'core/country_states.php');
				$this->countries = $country_array;
			}
		}
		
		public function get_games_for_clan($intClanID){
			$arrTeams = $this->pdh->get('clanwars_teams', 'teamsForClan', array($intClanID));
			$arrOut = array();
			foreach($arrTeams as $intTeamID){
				$arrOut[] = $this->pdh->get('clanwars_teams', 'gameID', array($intTeamID));
			}
			
			return array_unique($arrOut);
		}

	}//end class
}//end if
?>