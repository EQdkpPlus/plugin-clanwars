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
				
if ( !class_exists( "pdh_r_clanwars_wars" ) ) {
	class pdh_r_clanwars_wars extends pdh_r_generic{
		public static function __shortcuts() {
		$shortcuts = array('pdc', 'db', 'user', 'pdh', 'time', 'env', 'config');
		return array_merge(parent::$shortcuts, $shortcuts);
	}				
	
	public $default_lang = 'english';
	public $clanwars_wars = null;

	public $hooks = array(
		'clanwars_wars_update',
	);		
			
	public $presets = array(
		'clanwars_wars_id' => array('id', array('%intWarID%'), array()),
		'clanwars_wars_gameid' => array('gameid', array('%intWarID%'), array()),
		'clanwars_wars_categoryid' => array('categoryid', array('%intWarID%'), array()),
		'clanwars_wars_clanid' => array('clanid', array('%intWarID%'), array()),
		'clanwars_wars_teamid' => array('teamid', array('%intWarID%'), array()),
		'clanwars_wars_players' => array('players', array('%intWarID%'), array()),
		'clanwars_wars_ownteamid' => array('ownteamid', array('%intWarID%'), array()),
		'clanwars_wars_ownplayers' => array('ownplayers', array('%intWarID%'), array()),
		'clanwars_wars_playercount' => array('playercount', array('%intWarID%'), array()),
		'clanwars_wars_date' => array('date', array('%intWarID%'), array()),
		'clanwars_wars_status' => array('status', array('%intWarID%'), array()),
		'clanwars_wars_result' => array('result', array('%intWarID%'), array()),
		'clanwars_wars_website' => array('website', array('%intWarID%'), array()),
		'clanwars_wars_report' => array('report', array('%intWarID%'), array()),
		'clanwars_wars_ownreport' => array('ownreport', array('%intWarID%'), array()),
		'clanwars_wars_activatecomments' => array('activatecomments', array('%intWarID%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_'.clanwars_wars.'_table');
			
			$this->clanwars_wars = NULL;
	}
					
	public function init(){
			$this->clanwars_wars	= $this->pdc->get('pdh_clanwars_wars_table');				
					
			if($this->clanwars_wars !== NULL){
				return true;
			}		

			$objQuery = $this->db->query('SELECT * FROM __clanwars_wars');
			if($objQuery){
				while($drow = $objQuery->fetchAssoc()){
					$this->clanwars_wars[(int)$drow['id']] = array(
						'id'				=> (int)$drow['id'],
						'gameid'			=> (int)$drow['gameID'],
						'categoryid'		=> (int)$drow['categoryID'],
						'clanid'			=> (int)$drow['clanID'],
						'teamid'			=> (int)$drow['teamID'],
						'players'			=> $drow['players'],
						'ownteamid'			=> (int)$drow['ownTeamID'],
						'ownplayers'		=> $drow['ownPlayers'],
						'playercount'		=> $drow['playerCount'],
						'date'				=> (int)$drow['date'],
						'status'			=> (int)$drow['status'],
						'result'			=> $drow['result'],
						'website'			=> $drow['website'],
						'report'			=> $drow['report'],
						'ownreport'			=> $drow['ownReport'],
						'activatecomments'	=> (int)$drow['activateComments'],
					);
				}
				
				$this->pdc->put('pdh_clanwars_wars_table', $this->clanwars_wars, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list(){
			return array_keys($this->clanwars_wars);
		}
				
		/**
		 * Returns id for $intWarID				
		 * @param integer $intWarID
		 * @return multitype id
		 */
		 public function get_id($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['id'];
			}
			return false;
		}

		/**
		 * Returns gameID for $intWarID				
		 * @param integer $intWarID
		 * @return multitype gameID
		 */
		 public function get_gameid($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['gameid'];
			}
			return false;
		}

		/**
		 * Returns categoryID for $intWarID				
		 * @param integer $intWarID
		 * @return multitype categoryID
		 */
		 public function get_categoryid($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['categoryid'];
			}
			return false;
		}

		/**
		 * Returns clanID for $intWarID				
		 * @param integer $intWarID
		 * @return multitype clanID
		 */
		 public function get_clanid($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['clanid'];
			}
			return false;
		}

		/**
		 * Returns teamID for $intWarID				
		 * @param integer $intWarID
		 * @return multitype teamID
		 */
		 public function get_teamid($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['teamid'];
			}
			return false;
		}

		/**
		 * Returns players for $intWarID				
		 * @param integer $intWarID
		 * @return multitype players
		 */
		 public function get_players($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['players'];
			}
			return false;
		}

		/**
		 * Returns ownTeamID for $intWarID				
		 * @param integer $intWarID
		 * @return multitype ownTeamID
		 */
		 public function get_ownteamid($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['ownteamid'];
			}
			return false;
		}

		/**
		 * Returns ownPlayers for $intWarID				
		 * @param integer $intWarID
		 * @return multitype ownPlayers
		 */
		 public function get_ownplayers($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['ownplayers'];
			}
			return false;
		}

		/**
		 * Returns playerCount for $intWarID				
		 * @param integer $intWarID
		 * @return multitype playerCount
		 */
		 public function get_playercount($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['playercount'];
			}
			return false;
		}

		/**
		 * Returns date for $intWarID				
		 * @param integer $intWarID
		 * @return multitype date
		 */
		 public function get_date($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['date'];
			}
			return false;
		}

		/**
		 * Returns status for $intWarID				
		 * @param integer $intWarID
		 * @return multitype status
		 */
		 public function get_status($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['status'];
			}
			return false;
		}

		/**
		 * Returns result for $intWarID				
		 * @param integer $intWarID
		 * @return multitype result
		 */
		 public function get_result($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['result'];
			}
			return false;
		}

		/**
		 * Returns website for $intWarID				
		 * @param integer $intWarID
		 * @return multitype website
		 */
		 public function get_website($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['website'];
			}
			return false;
		}

		/**
		 * Returns report for $intWarID				
		 * @param integer $intWarID
		 * @return multitype report
		 */
		 public function get_report($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['report'];
			}
			return false;
		}

		/**
		 * Returns ownReport for $intWarID				
		 * @param integer $intWarID
		 * @return multitype ownReport
		 */
		 public function get_ownreport($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['ownreport'];
			}
			return false;
		}

		/**
		 * Returns activateComments for $intWarID				
		 * @param integer $intWarID
		 * @return multitype activateComments
		 */
		 public function get_activatecomments($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['activatecomments'];
			}
			return false;
		}

	}//end class
}//end if
?>