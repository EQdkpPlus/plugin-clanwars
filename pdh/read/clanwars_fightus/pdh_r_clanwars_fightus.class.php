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
				
if ( !class_exists( "pdh_r_clanwars_fightus" ) ) {
	class pdh_r_clanwars_fightus extends pdh_r_generic{
		public static function __shortcuts() {
		$shortcuts = array();
		return array_merge(parent::$shortcuts, $shortcuts);
	}				
	
	public $default_lang = 'english';
	public $clanwars_fightus = null;

	public $hooks = array(
		'clanwars_fightus_update',
	);
	
	private $countries = false;
			
	public $presets = array(
		'clanwars_fightus_id' => array('id', array('%intFightusID%'), array()),
		'clanwars_fightus_nick' => array('nick', array('%intFightusID%'), array()),
		'clanwars_fightus_email' => array('email', array('%intFightusID%'), array()),
		'clanwars_fightus_additionalContact' => array('additionalContact', array('%intFightusID%'), array()),
		'clanwars_fightus_gameID' => array('gameID', array('%intFightusID%'), array()),
		'clanwars_fightus_teamID' => array('teamID', array('%intFightusID%'), array()),
		'clanwars_fightus_clanname' => array('clanname', array('%intFightusID%'), array()),
		'clanwars_fightus_shortname' => array('shortname', array('%intFightusID%'), array()),
		'clanwars_fightus_country' => array('country', array('%intFightusID%'), array()),
		'clanwars_fightus_website' => array('website', array('%intFightusID%'), array()),
		'clanwars_fightus_date' => array('date', array('%intFightusID%'), array()),
		'clanwars_fightus_status' => array('status', array('%intFightusID%'), array()),
		'clanwars_fightus_message' => array('message', array('%intFightusID%'), array()),
		'clanwars_fightus_actions' => array('actions', array('%intFightusID%', '%link_url%', '%link_url_suffix%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_clanwars_fightus_table');
			
			$this->clanwars_fightus = NULL;
	}
					
	public function init(){
			$this->clanwars_fightus	= $this->pdc->get('pdh_clanwars_fightus_table');				
					
			if($this->clanwars_fightus !== NULL){
				return true;
			}		

			$objQuery = $this->db->query('SELECT * FROM __clanwars_fightus');
			if($objQuery){
				while($drow = $objQuery->fetchAssoc()){
					$this->clanwars_fightus[(int)$drow['id']] = array(
						'id'				=> (int)$drow['id'],
						'nick'				=> $drow['nick'],
						'email'				=> $drow['email'],
						'additionalContact'	=> $drow['additionalContact'],
						'gameID'			=> (int)$drow['gameID'],
						'teamID'			=> (int)$drow['teamID'],
						'clanname'			=> $drow['clanname'],
						'shortname'			=> $drow['shortname'],
						'country'			=> $drow['country'],
						'website'			=> $drow['website'],
						'date'				=> (int)$drow['date'],
						'status'			=> (int)$drow['status'],
						'message'			=> $drow['message'],

					);
				}
				
				$this->pdc->put('pdh_clanwars_fightus_table', $this->clanwars_fightus, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list(){
			if ($this->clanwars_fightus === null) return array();
			return array_keys($this->clanwars_fightus);
		}
		
		/**
		 * Get all data of Element with $strID
		 * @return multitype: Array with all data
		 */				
		public function get_data($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID];
			}
			return false;
		}
				
		/**
		 * Returns id for $intFightusID				
		 * @param integer $intFightusID
		 * @return multitype id
		 */
		 public function get_id($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID]['id'];
			}
			return false;
		}
		
		public function get_actions($intFightusID, $baseurl, $url_suffix=''){
			return "<a href='".$baseurl.$this->SID.'&amp;f='.$intFightusID.$url_suffix."'>
				<i class='fa fa-pencil fa-lg' title='".$this->user->lang('edit')."'></i>
			</a>
			
			<a href='manage_clans.php".$this->SID."&upd=true&convert_fightus=".$intFightusID."'>
				<i class='fa fa-home fa-lg' title='".$this->user->lang('cw_convert_to_clan')."'></i>
			</a>
			
			<a href='manage_wars.php".$this->SID."&upd=true&convert_fightus=".$intFightusID."'>
				<i class='fa fa-crosshairs fa-lg' title='".$this->user->lang('cw_convert_to_war')."'></i>
			</a>
			
			<a href='".$this->routing->build('editcalendarevent').'&hookapp=clanwars&hookid='.$intFightusID."'>
				<i class='fa fa-calendar fa-lg' title='".$this->user->lang('cw_convert_to_calendarevent')."'></i>
			</a>
			
			";
		}

		/**
		 * Returns nick for $intFightusID				
		 * @param integer $intFightusID
		 * @return multitype nick
		 */
		 public function get_nick($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID]['nick'];
			}
			return false;
		}

		/**
		 * Returns email for $intFightusID				
		 * @param integer $intFightusID
		 * @return multitype email
		 */
		 public function get_email($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID]['email'];
			}
			return false;
		}

		/**
		 * Returns additionalContact for $intFightusID				
		 * @param integer $intFightusID
		 * @return multitype additionalContact
		 */
		 public function get_additionalContact($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID]['additionalContact'];
			}
			return false;
		}

		/**
		 * Returns gameID for $intFightusID				
		 * @param integer $intFightusID
		 * @return multitype gameID
		 */
		 public function get_gameID($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID]['gameID'];
			}
			return false;
		}
		
		public function get_html_gameID($intFightusID){
			$intGameID = $this->get_gameID($intFightusID);
			$strGameName = $this->pdh->get('clanwars_games', 'name', array($intGameID));
			return $strGameName;
		}

		/**
		 * Returns teamID for $intFightusID				
		 * @param integer $intFightusID
		 * @return multitype teamID
		 */
		 public function get_teamID($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID]['teamID'];
			}
			return false;
		}
		
		public function get_html_teamID($intFightusID){
			$intTeamID = $this->get_teamID($intFightusID);
			$strTeamname = $this->pdh->get('clanwars_teams', 'name', array($intTeamID));
			return $strTeamname;
		}

		/**
		 * Returns clanname for $intFightusID				
		 * @param integer $intFightusID
		 * @return multitype clanname
		 */
		 public function get_clanname($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID]['clanname'];
			}
			return false;
		}

		/**
		 * Returns shortname for $intFightusID				
		 * @param integer $intFightusID
		 * @return multitype shortname
		 */
		 public function get_shortname($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID]['shortname'];
			}
			return false;
		}

		/**
		 * Returns country for $intFightusID				
		 * @param integer $intFightusID
		 * @return multitype country
		 */
		 public function get_country($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID]['country'];
			}
			return false;
		}
		
		public function get_html_country($intFightusID, $blnWithCountryName = false){
			$country = $this->get_country($intFightusID);
			if ($country && strlen($country)){
				$this->init_countries();
				if (!$blnWithCountryName){
					return '<img src="'.$this->server_path.'images/flags/'.strtolower($country).'.png" alt="'.$country.'" class="coretip" data-coretip="'.ucfirst(strtolower($this->countries[$country])).'" />';
				} else {
					return '<img src="'.$this->server_path.'images/flags/'.strtolower($country).'.png" alt="'.$country.'" /> '.ucfirst(strtolower($this->countries[$country]));
				}
			}
			return '';
		}

		/**
		 * Returns website for $intFightusID				
		 * @param integer $intFightusID
		 * @return multitype website
		 */
		 public function get_website($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID]['website'];
			}
			return false;
		}

		/**
		 * Returns date for $intFightusID				
		 * @param integer $intFightusID
		 * @return multitype date
		 */
		 public function get_date($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID]['date'];
			}
			return false;
		}
		
		public function get_html_date($intFightusID){
			return $this->time->user_date($this->get_date($intFightusID), true);
		}

		/**
		 * Returns status for $intFightusID				
		 * @param integer $intFightusID
		 * @return multitype status
		 */
		 public function get_status($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID]['status'];
			}
			return false;
		}
		
		public function get_html_status($intFightusID){
			$arrStatus = $this->user->lang('cw_fightus_status');
			return $arrStatus[$this->get_status($intFightusID)];
		}

		/**
		 * Returns message for $intFightusID				
		 * @param integer $intFightusID
		 * @return multitype message
		 */
		 public function get_message($intFightusID){
			if (isset($this->clanwars_fightus[$intFightusID])){
				return $this->clanwars_fightus[$intFightusID]['message'];
			}
			return false;
		}
		
		public function get_delete_name($intFightusID){
			return $this->get_clanname($intFightusID).', '.$this->get_html_date($intFightusID);
		}
		
		private function init_countries(){
			if (!$this->countries){
				include($this->root_path.'core/country_states.php');
				$this->countries = $country_array;
			}
		}

	}//end class
}//end if
?>