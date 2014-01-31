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
		$shortcuts = array('pdc', 'db', 'user', 'pdh', 'time', 'env', 'config');
		return array_merge(parent::$shortcuts, $shortcuts);
	}				
	
	public $default_lang = 'english';
	public $clanwars_fightus = null;

	public $hooks = array(
		'clanwars_fightus_update',
	);		
			
	public $presets = array(
		'clanwars_fightus_id' => array('id', array('%intFightUsID%'), array()),
		'clanwars_fightus_nick' => array('nick', array('%intFightUsID%'), array()),
		'clanwars_fightus_email' => array('email', array('%intFightUsID%'), array()),
		'clanwars_fightus_additionalcontact' => array('additionalcontact', array('%intFightUsID%'), array()),
		'clanwars_fightus_gameid' => array('gameid', array('%intFightUsID%'), array()),
		'clanwars_fightus_teamid' => array('teamid', array('%intFightUsID%'), array()),
		'clanwars_fightus_clanname' => array('clanname', array('%intFightUsID%'), array()),
		'clanwars_fightus_shortname' => array('shortname', array('%intFightUsID%'), array()),
		'clanwars_fightus_country' => array('country', array('%intFightUsID%'), array()),
		'clanwars_fightus_website' => array('website', array('%intFightUsID%'), array()),
		'clanwars_fightus_date' => array('date', array('%intFightUsID%'), array()),
		'clanwars_fightus_status' => array('status', array('%intFightUsID%'), array()),
		'clanwars_fightus_message' => array('message', array('%intFightUsID%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_'.clanwars_fightus.'_table');
			
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
						'additionalcontact'	=> $drow['additionalContact'],
						'gameid'			=> (int)$drow['gameID'],
						'teamid'			=> (int)$drow['teamID'],
						'clanname'			=> $drow['clanname'],
						'shortname'			=> $drow['shortname'],
						'country'			=> $drow['country'],
						'website'			=> $drow['website'],
						'date'				=> (int)$drow['date'],
						'status'			=> (int)$drow['status'],
						'message'			=> (int)$drow['message'],
					);
				}
				
				$this->pdc->put('pdh_clanwars_fightus_table', $this->clanwars_fightus, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list(){
			return array_keys($this->clanwars_fightus);
		}
				
		/**
		 * Returns id for $intFightUsID				
		 * @param integer $intFightUsID
		 * @return multitype id
		 */
		 public function get_id($intFightUsID){
			if (isset($this->clanwars_fightus[$intFightUsID])){
				return $this->clanwars_fightus[$intFightUsID]['id'];
			}
			return false;
		}

		/**
		 * Returns nick for $intFightUsID				
		 * @param integer $intFightUsID
		 * @return multitype nick
		 */
		 public function get_nick($intFightUsID){
			if (isset($this->clanwars_fightus[$intFightUsID])){
				return $this->clanwars_fightus[$intFightUsID]['nick'];
			}
			return false;
		}

		/**
		 * Returns email for $intFightUsID				
		 * @param integer $intFightUsID
		 * @return multitype email
		 */
		 public function get_email($intFightUsID){
			if (isset($this->clanwars_fightus[$intFightUsID])){
				return $this->clanwars_fightus[$intFightUsID]['email'];
			}
			return false;
		}

		/**
		 * Returns additionalContact for $intFightUsID				
		 * @param integer $intFightUsID
		 * @return multitype additionalContact
		 */
		 public function get_additionalcontact($intFightUsID){
			if (isset($this->clanwars_fightus[$intFightUsID])){
				return $this->clanwars_fightus[$intFightUsID]['additionalcontact'];
			}
			return false;
		}

		/**
		 * Returns gameID for $intFightUsID				
		 * @param integer $intFightUsID
		 * @return multitype gameID
		 */
		 public function get_gameid($intFightUsID){
			if (isset($this->clanwars_fightus[$intFightUsID])){
				return $this->clanwars_fightus[$intFightUsID]['gameid'];
			}
			return false;
		}

		/**
		 * Returns teamID for $intFightUsID				
		 * @param integer $intFightUsID
		 * @return multitype teamID
		 */
		 public function get_teamid($intFightUsID){
			if (isset($this->clanwars_fightus[$intFightUsID])){
				return $this->clanwars_fightus[$intFightUsID]['teamid'];
			}
			return false;
		}

		/**
		 * Returns clanname for $intFightUsID				
		 * @param integer $intFightUsID
		 * @return multitype clanname
		 */
		 public function get_clanname($intFightUsID){
			if (isset($this->clanwars_fightus[$intFightUsID])){
				return $this->clanwars_fightus[$intFightUsID]['clanname'];
			}
			return false;
		}

		/**
		 * Returns shortname for $intFightUsID				
		 * @param integer $intFightUsID
		 * @return multitype shortname
		 */
		 public function get_shortname($intFightUsID){
			if (isset($this->clanwars_fightus[$intFightUsID])){
				return $this->clanwars_fightus[$intFightUsID]['shortname'];
			}
			return false;
		}

		/**
		 * Returns country for $intFightUsID				
		 * @param integer $intFightUsID
		 * @return multitype country
		 */
		 public function get_country($intFightUsID){
			if (isset($this->clanwars_fightus[$intFightUsID])){
				return $this->clanwars_fightus[$intFightUsID]['country'];
			}
			return false;
		}

		/**
		 * Returns website for $intFightUsID				
		 * @param integer $intFightUsID
		 * @return multitype website
		 */
		 public function get_website($intFightUsID){
			if (isset($this->clanwars_fightus[$intFightUsID])){
				return $this->clanwars_fightus[$intFightUsID]['website'];
			}
			return false;
		}

		/**
		 * Returns date for $intFightUsID				
		 * @param integer $intFightUsID
		 * @return multitype date
		 */
		 public function get_date($intFightUsID){
			if (isset($this->clanwars_fightus[$intFightUsID])){
				return $this->clanwars_fightus[$intFightUsID]['date'];
			}
			return false;
		}

		/**
		 * Returns status for $intFightUsID				
		 * @param integer $intFightUsID
		 * @return multitype status
		 */
		 public function get_status($intFightUsID){
			if (isset($this->clanwars_fightus[$intFightUsID])){
				return $this->clanwars_fightus[$intFightUsID]['status'];
			}
			return false;
		}

		/**
		 * Returns message for $intFightUsID				
		 * @param integer $intFightUsID
		 * @return multitype message
		 */
		 public function get_message($intFightUsID){
			if (isset($this->clanwars_fightus[$intFightUsID])){
				return $this->clanwars_fightus[$intFightUsID]['message'];
			}
			return false;
		}

	}//end class
}//end if
?>