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
				
if ( !class_exists( "pdh_r_clanwars_teams" ) ) {
	class pdh_r_clanwars_teams extends pdh_r_generic{
		public static function __shortcuts() {
		$shortcuts = array('pdc', 'db', 'user', 'pdh', 'time', 'env', 'config');
		return array_merge(parent::$shortcuts, $shortcuts);
	}				
	
	public $default_lang = 'english';
	public $clanwars_teams = null;

	public $hooks = array(
		'clanwars_teams_update',
	);		
			
	public $presets = array(
		'clanwars_teams_id' => array('id', array('%intTeamID%'), array()),
		'clanwars_teams_name' => array('name', array('%intTeamID%'), array()),
		'clanwars_teams_description' => array('description', array('%intTeamID%'), array()),
		'clanwars_teams_icon' => array('icon', array('%intTeamID%'), array()),
		'clanwars_teams_members' => array('members', array('%intTeamID%'), array()),
		'clanwars_teams_gameid' => array('gameid', array('%intTeamID%'), array()),
		'clanwars_teams_clanid' => array('clanid', array('%intTeamID%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_'.clanwars_teams.'_table');
			
			$this->clanwars_teams = NULL;
	}
					
	public function init(){
			$this->clanwars_teams	= $this->pdc->get('pdh_clanwars_teams_table');				
					
			if($this->clanwars_teams !== NULL){
				return true;
			}		

			$objQuery = $this->db->query('SELECT * FROM __clanwars_teams');
			if($objQuery){
				while($drow = $objQuery->fetchAssoc()){
					$this->clanwars_teams[(int)$drow['id']] = array(
						'id'			=> (int)$drow['id'],
						'name'			=> $drow['name'],
						'description'	=> $drow['description'],
						'icon'			=> $drow['icon'],
						'members'		=> $drow['members'],
						'gameid'		=> (int)$drow['gameID'],
						'clanid'		=> (int)$drow['clanID'],
					);
				}
				
				$this->pdc->put('pdh_clanwars_teams_table', $this->clanwars_teams, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list($blnOnlyOwnTeams = false){
			if ($blnOnlyOwnTeams){
				if (is_array($this->clanwars_teams)){
					$arrOut = array();
					foreach($this->clanwars_teams as $key => $val){
						if ($val['clanid'] === 0) $arrOut[] = $key;
					}
					return $arrOut;
				}
			} else return ((is_array($this->clanwars_teams)) ? array_keys($this->clanwars_teams) : array());
		}
				
		/**
		 * Returns id for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype id
		 */
		 public function get_id($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['id'];
			}
			return false;
		}

		/**
		 * Returns name for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype name
		 */
		 public function get_name($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['name'];
			}
			return false;
		}

		/**
		 * Returns description for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype description
		 */
		 public function get_description($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['description'];
			}
			return false;
		}

		/**
		 * Returns icon for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype icon
		 */
		 public function get_icon($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['icon'];
			}
			return false;
		}

		/**
		 * Returns members for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype members
		 */
		 public function get_members($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['members'];
			}
			return false;
		}

		/**
		 * Returns gameID for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype gameID
		 */
		 public function get_gameid($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['gameid'];
			}
			return false;
		}

		/**
		 * Returns clanID for $intTeamID				
		 * @param integer $intTeamID
		 * @return multitype clanID
		 */
		 public function get_clanid($intTeamID){
			if (isset($this->clanwars_teams[$intTeamID])){
				return $this->clanwars_teams[$intTeamID]['clanid'];
			}
			return false;
		}

	}//end class
}//end if
?>