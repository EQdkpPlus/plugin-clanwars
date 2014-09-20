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
	
	public $default_lang = 'english';
	public $clanwars_wars = null;

	public $hooks = array(
		'clanwars_wars_update',
	);		
			
	public $presets = array(
		'clanwars_wars_id' => array('id', array('%intWarID%'), array()),
		'clanwars_wars_gameID' => array('gameID', array('%intWarID%', '%link_fe%'), array()),
		'clanwars_wars_categoryID' => array('categoryID', array('%intWarID%', '%link_fe%'), array()),
		'clanwars_wars_clanID' => array('clanID', array('%intWarID%'), array()),
		'clanwars_wars_teamID' => array('teamID', array('%intWarID%'), array()),
		'clanwars_wars_players' => array('players', array('%intWarID%'), array()),
		'clanwars_wars_ownTeamID' => array('ownTeamID', array('%intWarID%', '%link_fe%'), array()),
		'clanwars_wars_ownPlayers' => array('ownPlayers', array('%intWarID%'), array()),
		'clanwars_wars_playerCount' => array('playerCount', array('%intWarID%'), array()),
		'clanwars_wars_date' => array('date', array('%intWarID%'), array()),
		'clanwars_wars_status' => array('status', array('%intWarID%'), array()),
		'clanwars_wars_result' => array('result', array('%intWarID%', '%link_fe%'), array()),
		'clanwars_wars_website' => array('website', array('%intWarID%'), array()),
		'clanwars_wars_report' => array('report', array('%intWarID%'), array()),
		'clanwars_wars_ownReport' => array('ownReport', array('%intWarID%'), array()),
		'clanwars_wars_activateComments' => array('activateComments', array('%intWarID%'), array()),
		'clanwars_wars_actions' => array('actions', array('%intWarID%', '%link_url%', '%link_url_suffix%'), array()),
		'clanwars_wars_opponent' => array('opponent', array('%intWarID%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_clanwars_wars_table');
			
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
						'gameID'			=> (int)$drow['gameID'],
						'categoryID'		=> (int)$drow['categoryID'],
						'clanID'			=> (int)$drow['clanID'],
						'teamID'			=> (int)$drow['teamID'],
						'players'			=> unserialize($drow['players']),
						'ownTeamID'			=> (int)$drow['ownTeamID'],
						'ownPlayers'		=> unserialize($drow['ownPlayers']),
						'playerCount'		=> unserialize($drow['playerCount']),
						'date'				=> (int)$drow['date'],
						'status'			=> (int)$drow['status'],
						'result'			=> unserialize($drow['result']),
						'website'			=> $drow['website'],
						'report'			=> $drow['report'],
						'ownReport'			=> $drow['ownReport'],
						'activateComments'	=> (int)$drow['activateComments'],
					);
				}
				
				$this->pdc->put('pdh_clanwars_wars_table', $this->clanwars_wars, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list(){
			if ($this->clanwars_wars === null) return array();
			return array_keys($this->clanwars_wars);
		}
		
		/**
		 * Get all data of Element with $strID
		 * @return multitype: Array with all data
		 */				
		public function get_data($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID];
			}
			return false;
		}
		
		public function get_actions($intWarID, $baseurl, $url_suffix=''){
			return "<a href='".$baseurl.$this->SID.'&amp;w='.$intWarID.$url_suffix."'>
				<i class='fa fa-pencil fa-lg' title='".$this->user->lang('edit')."'></i>
			</a>";
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
		 public function get_gameID($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['gameID'];
			}
			return false;
		}
		
		public function get_html_gameID($intWarID, $blnLinkFrontend=false){
			$intGameID = $this->get_gameID($intWarID);
			$strGameName = $this->pdh->get('clanwars_games', 'name', array($intGameID));
			
			if ($blnLinkFrontend){
				return '<a href="'.$this->routing->build(array('clanwars', 'Game'), $strGameName.', g'.$intGameID).'">'.$strGameName.'</a>';
			}
			
			return $strGameName;
		}

		/**
		 * Returns categoryID for $intWarID				
		 * @param integer $intWarID
		 * @return multitype categoryID
		 */
		 public function get_categoryID($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['categoryID'];
			}
			return false;
		}
		
		public function get_html_categoryID($intWarID, $blnLinkFrontend=false){
			$intTeamID = $this->get_categoryID($intWarID);
			$strTeamname = $this->pdh->get('clanwars_categories', 'name', array($intTeamID));
			
			if ($blnLinkFrontend){
				return '<a href="'.$this->routing->build(array('clanwars', 'Category'), $strTeamname.', cat'.$intTeamID).'">'.$strTeamname.'</a>';	
			}
			return $strTeamname;
		}

		/**
		 * Returns clanID for $intWarID				
		 * @param integer $intWarID
		 * @return multitype clanID
		 */
		 public function get_clanID($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['clanID'];
			}
			return false;
		}
		
		public function get_html_clanID($intWarID){
			$intTeamID = $this->get_clanID($intWarID);
			$strTeamname = $this->pdh->get('clanwars_clans', 'name', array($intTeamID));
			return $strTeamname;
		}
		
		public function get_clanID_decorated($intWarID){
			$strOut = '<a href="'.$this->routing->build(array('clanwars', 'Clan'), $this->get_html_clanID($intWarID).', c'.$this->get_clanID($intWarID)).'">'.$this->get_html_clanID($intWarID).'</a>';
			

			return $strOut;
		}

		/**
		 * Returns teamID for $intWarID				
		 * @param integer $intWarID
		 * @return multitype teamID
		 */
		 public function get_teamID($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['teamID'];
			}
			return false;
		}
		
		public function get_html_teamID($intWarID, $blnLinkFrontend=false){
			$intTeamID = $this->get_teamID($intWarID);
			$strTeamname = $this->pdh->get('clanwars_teams', 'name', array($intTeamID));
			
			if ($blnLinkFrontend){
				return '<a href="'.$this->routing->build(array('clanwars', 'Team'), $strTeamname.', t'.$intTeamID).'">'.$strTeamname.'</a>';
			}
			
			return $strTeamname;
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
		 public function get_ownTeamID($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['ownTeamID'];
			}
			return false;
		}
		
		public function get_html_ownTeamID($intWarID, $blnLinkFrontend=false){
			$intTeamID = $this->get_ownTeamID($intWarID);
			$strTeamname = $this->pdh->get('clanwars_teams', 'name', array($intTeamID));
			
			if ($blnLinkFrontend){
				return '<a href="'.$this->routing->build(array('clanwars', 'Team'), $strTeamname.', t'.$intTeamID).'">'.$strTeamname.'</a>';	
			}
			return $strTeamname;
		}

		/**
		 * Returns ownPlayers for $intWarID				
		 * @param integer $intWarID
		 * @return multitype ownPlayers
		 */
		 public function get_ownPlayers($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['ownPlayers'];
			}
			return false;
		}

		/**
		 * Returns playerCount for $intWarID				
		 * @param integer $intWarID
		 * @return multitype playerCount
		 */
		 public function get_playerCount($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['playerCount'];
			}
			return false;
		}
		
		public function get_html_playerCount($intWarID){
			$arrPlayerCount = $this->get_playerCount($intWarID);
			return $arrPlayerCount[0].' '.$this->user->lang('cw_versus').' '.$arrPlayerCount[1];
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
		
		public function get_html_date($intWarID){
			return $this->time->user_date($this->get_date($intWarID), true);
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
		
		public function get_html_result($intWarID, $blnLinkFrontend=false){
			$arrResult = $this->get_result($intWarID);
			if ($arrResult[0] == $arrResult[1]) $class = "neutral";
			if ($arrResult[0] > $arrResult[1]) $class = "positive";
			if ($arrResult[0] < $arrResult[1]) $class = "negative";
			
			if ($blnLinkFrontend){
				$strMatchName = $this->get_html_teamID($intWarID);
				
				return '<a href="'.$this->routing->build(array('clanwars', 'Match'), $strMatchName.', m'.$intWarID).'"><span class="'.$class.'">'.$arrResult[0].' : '.$arrResult[1].'</span></a>';
			}
			
			return '<span class="'.$class.'">'.$arrResult[0].' : '.$arrResult[1].'</span>';
		}
		
		
		
		/**
		 * 1 = win, 0 = equal, -1 loss
		 */
		public function get_win($intWarID){
			$arrResult = $this->get_result($intWarID);
			if ((int)$arrResult[0] == (int)$arrResult[1]) return 0;
			if ((int)$arrResult[0] > (int)$arrResult[1]) return 1;
			if ((int)$arrResult[0] < (int)$arrResult[1]) return -1;
			
			return 0;
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
		
		public function get_html_website($intWarID){
			$strLink = $this->get_website($intWarID);
			if (strlen($strLink)){
				return '<a href="'.$strLink.'" rel="nofollow">'.$strLink.'</a>';
			}
			return '';
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
		
		public function get_html_report($intWarID){
			$strReport = $this->get_report($intWarID);
			if (strlen($strReport)){
				return $this->bbcode->toHTML($strReport);
			}
			return '';
		}

		/**
		 * Returns ownReport for $intWarID				
		 * @param integer $intWarID
		 * @return multitype ownReport
		 */
		 public function get_ownReport($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['ownReport'];
			}
			return false;
		}
		
		public function get_html_ownReport($intWarID){
			$strReport = $this->get_ownReport($intWarID);
			if (strlen($strReport)){
				return $this->bbcode->toHTML($strReport);
			}
			return '';
		}

		/**
		 * Returns activateComments for $intWarID				
		 * @param integer $intWarID
		 * @return multitype activateComments
		 */
		 public function get_activateComments($intWarID){
			if (isset($this->clanwars_wars[$intWarID])){
				return $this->clanwars_wars[$intWarID]['activateComments'];
			}
			return false;
		}
		
		public function get_html_opponent($intWarID){
			$strTeamname = $this->get_html_teamID($intWarID);
			$strOut = '<a href="'.$this->routing->build(array('clanwars', 'Team'), $strTeamname.', t'.$this->get_teamID($intWarID)).'">'.$strTeamname.'</a>';
			$strOut .= ' @ <a href="'.$this->routing->build(array('clanwars', 'Clan'), $this->get_html_clanID($intWarID).', c'.$this->get_clanID($intWarID)).'">'.$this->get_html_clanID($intWarID).'</a>';
			return $strOut;
		}
		
		public function get_wars_for_clan($intClanID){
			$arrWars = $this->get_id_list();
			$arrOut = array();
			foreach($arrWars as $intWarID){
				if ($this->get_clanID($intWarID) == $intClanID) $arrOut[] = $intWarID; 
			}
			return $arrOut;
		}
		
		public function get_wars_for_team($intTeamID){
			$arrWars = $this->get_id_list();
			$arrOut = array();
			foreach($arrWars as $intWarID){
				if ($this->get_teamID($intWarID) == $intTeamID) $arrOut[] = $intWarID;
			}
			return $arrOut;
		}
		
		public function get_wars_for_category($intCategoryID){
			$arrWars = $this->get_id_list();
			$arrOut = array();
			foreach($arrWars as $intWarID){
				if ($this->get_categoryID($intWarID) == $intCategoryID) $arrOut[] = $intWarID;
			}
			return $arrOut;
		}
		
		public function get_wars_for_game($intGameID){
			$arrWars = $this->get_id_list();
			$arrOut = array();
			foreach($arrWars as $intWarID){
				if ($this->get_gameID($intWarID) == $intGameID) $arrOut[] = $intWarID;
			}
			return $arrOut;
		}

	}//end class
}//end if
?>