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
				
if ( !class_exists( "pdh_r_clanwars_awards" ) ) {
	class pdh_r_clanwars_awards extends pdh_r_generic{
		public static function __shortcuts() {
		$shortcuts = array('pdc', 'db', 'user', 'pdh', 'time', 'env', 'config');
		return array_merge(parent::$shortcuts, $shortcuts);
	}				
	
	public $default_lang = 'english';
	public $clanwars_awards = null;

	public $hooks = array(
		'clanwars_awards_update',
	);		
			
	public $presets = array(
		'clanwars_awards_id' => array('id', array('%intAwardID%'), array()),
		'clanwars_awards_event' => array('event', array('%intAwardID%'), array()),
		'clanwars_awards_date' => array('date', array('%intAwardID%'), array()),
		'clanwars_awards_rank' => array('rank', array('%intAwardID%'), array()),
		'clanwars_awards_gameID' => array('gameID', array('%intAwardID%'), array()),
		'clanwars_awards_teamID' => array('teamID', array('%intAwardID%'), array()),
		'clanwars_awards_userID' => array('userID', array('%intAwardID%'), array()),
		'clanwars_awards_website' => array('website', array('%intAwardID%'), array()),
		'clanwars_awards_actions' => array('actions', array('%intAwardID%', '%link_url%', '%link_url_suffix%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_'.clanwars_awards.'_table');
			
			$this->clanwars_awards = NULL;
	}
					
	public function init(){
			$this->clanwars_awards	= $this->pdc->get('pdh_clanwars_awards_table');				
					
			if($this->clanwars_awards !== NULL){
				return true;
			}		

			$objQuery = $this->db->query('SELECT * FROM __clanwars_awards');
			if($objQuery){
				while($drow = $objQuery->fetchAssoc()){
					$this->clanwars_awards[(int)$drow['id']] = array(
						'id'			=> (int)$drow['id'],
						'event'			=> $drow['event'],
						'date'			=> (int)$drow['date'],
						'rank'			=> (int)$drow['rank'],
						'gameID'		=> (int)$drow['gameID'],
						'teamID'		=> (int)$drow['teamID'],
						'userID'		=> (int)$drow['userID'],
						'website'		=> $drow['website'],

					);
				}
				
				$this->pdc->put('pdh_clanwars_awards_table', $this->clanwars_awards, null);
			}

		}	//end init function

		
		public function get_actions($intAwardID, $baseurl, $url_suffix=''){
			return "<a href='".$baseurl.$this->SID.'&amp;a='.$intAwardID.$url_suffix."'>
				<i class='fa fa-pencil fa-lg' title='".$this->user->lang('edit')."'></i>
			</a>";
		}
		
		
		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list(){
			if ($this->clanwars_awards === null) return array();
			return array_keys($this->clanwars_awards);
		}
		
		/**
		 * Get all data of Element with $strID
		 * @return multitype: Array with all data
		 */				
		public function get_data($intAwardID){
			if (isset($this->clanwars_awards[$intAwardID])){
				return $this->clanwars_awards[$intAwardID];
			}
			return false;
		}
				
		/**
		 * Returns id for $intAwardID				
		 * @param integer $intAwardID
		 * @return multitype id
		 */
		 public function get_id($intAwardID){
			if (isset($this->clanwars_awards[$intAwardID])){
				return $this->clanwars_awards[$intAwardID]['id'];
			}
			return false;
		}

		/**
		 * Returns event for $intAwardID				
		 * @param integer $intAwardID
		 * @return multitype event
		 */
		 public function get_event($intAwardID){
			if (isset($this->clanwars_awards[$intAwardID])){
				return $this->clanwars_awards[$intAwardID]['event'];
			}
			return false;
		}

		/**
		 * Returns date for $intAwardID				
		 * @param integer $intAwardID
		 * @return multitype date
		 */
		 public function get_date($intAwardID){
			if (isset($this->clanwars_awards[$intAwardID])){
				return $this->clanwars_awards[$intAwardID]['date'];
			}
			return false;
		}
		
		public function get_html_date($intAwardID){
			return $this->time->user_date($this->get_date($intAwardID));
		}

		/**
		 * Returns rank for $intAwardID				
		 * @param integer $intAwardID
		 * @return multitype rank
		 */
		 public function get_rank($intAwardID){
			if (isset($this->clanwars_awards[$intAwardID])){
				return $this->clanwars_awards[$intAwardID]['rank'];
			}
			return false;
		}
		
		public function get_html_rank($intAwardID){
			$intRank = $this->get_rank($intAwardID);
			if ($intRank == 1) {
				return '<i class="fa fa-lg fa-trophy" style="color: #FFD700"></i> (1.)';
			} elseif($intRank == 2) {
				return '<i class="fa fa-lg fa-trophy" style="color: #E3E4E5"></i> (2.)';
			} elseif($intRank == 3){
				return '<i class="fa fa-lg fa-trophy" style="color: #CD7F32"></i> (3.)';
			} else return $intRank.'.';
		}

		/**
		 * Returns gameID for $intAwardID				
		 * @param integer $intAwardID
		 * @return multitype gameID
		 */
		 public function get_gameID($intAwardID){
			$intTeamID = $this->get_teamID($intAwardID);
			$intGameID = 0;
			
			if ($this->clanwars_awards[$intAwardID]['gameID'] > 0){
				$intGameID = $this->clanwars_awards[$intAwardID]['gameID'];
			} elseif($intTeamID > 0){
				$intGameID = $this->pdh->get('clanwars_teams', 'gameID', array($intTeamID));
			}
		 
			return $intGameID;
		}
		
		public function get_html_gameID($intAwardID){
			$intGameID = $this->get_gameID($intAwardID);
			$strGameName = $this->pdh->get('clanwars_games', 'name', array($intGameID));
			return $strGameName;
		}

		/**
		 * Returns teamID for $intAwardID				
		 * @param integer $intAwardID
		 * @return multitype teamID
		 */
		 public function get_teamID($intAwardID){
			if (isset($this->clanwars_awards[$intAwardID])){
				return $this->clanwars_awards[$intAwardID]['teamID'];
			}
			return false;
		}
		
		public function get_html_teamID($intAwardID){
			$intTeamID = $this->get_teamID($intAwardID);
			$strTeamname = $this->pdh->get('clanwars_teams', 'name', array($intTeamID));
			return $strTeamname;
		}
		
		/**
		 * Returns userID for $intAwardID				
		 * @param integer $intAwardID
		 * @return multitype userID
		 */
		 public function get_userID($intAwardID){
			if (isset($this->clanwars_awards[$intAwardID])){
				return $this->clanwars_awards[$intAwardID]['userID'];
			}
			return false;
		}
		
		public function get_html_userID($intAwardID){
			$intUserID = $this->get_userID($intAwardID);
			if ($intUserID > 0) return $this->pdh->geth('user', 'country', array($intUserID)).' '.$this->pdh->get('user', 'name', array($intUserID));
			return '';
		}

		/**
		 * Returns website for $intAwardID				
		 * @param integer $intAwardID
		 * @return multitype website
		 */
		 public function get_website($intAwardID){
			if (isset($this->clanwars_awards[$intAwardID])){
				return $this->clanwars_awards[$intAwardID]['website'];
			}
			return false;
		}
		
		public function get_delete_name($intAwardID){
			return $this->get_event($intAwardID).', '.$this->get_html_date($intAwardID);
		}

	}//end class
}//end if
?>