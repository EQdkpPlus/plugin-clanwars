<?php
/*
 * Project:     EQdkp guildrequest
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2012-10-13 22:48:23 +0200 (Sa, 13. Okt 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: godmod $
 * @copyright   2008-2011 Aderyn
 * @link        http://eqdkp-plus.com
 * @package     guildrequest
 * @version     $Rev: 12273 $
 *
 * $Id: archive.php 12273 2012-10-13 20:48:23Z godmod $
 */


class clanwars_pageobject extends pageobject {
  /**
   * __dependencies
   * Get module dependencies
   */
  public static function __shortcuts()
  {
    $shortcuts = array();
   	return array_merge(parent::__shortcuts(), $shortcuts);
  }  
  
  /**
   * Constructor
   */
  public function __construct()
  {
    // plugin installed?
    if (!$this->pm->check('clanwars', PLUGIN_INSTALLED))
      message_die($this->user->lang('cw_plugin_not_installed'));
    
    $handler = array(
    	'awards'	=> array('process' => 'view_awards'),
    	'matches'	=> array('process' => 'view_matches'),
    	'm'			=> array('process' => 'view_match'),
    	'c'			=> array('process' => 'view_clan'),
    	'cat'		=> array('process' => 'view_category'),
    	't'			=> array('process' => 'view_team'),
    	'g'			=> array('process' => 'view_game'),
    		
    	'u'			=> array('process' => 'view_user'),
    	'games'		=> array('process' => 'view_games'),
    	'clan'		=> array('process' => 'view_this_clan'),
    	'clans' 	=> array('process' => 'view_clans'),
    	'categories'=> array('process' => 'view_categories'),
    		
    );
    parent::__construct(false, $handler);

    $this->process();
  }
  
  public function view_games(){
  	$arrGames = $this->pdh->get('clanwars_games', 'id_list', array(true));
  	
  	
  }
  
  
  public function view_game(){
  	$intGameID = $this->in->get('g', 0);
  	$strGamename = $this->pdh->get('clanwars_games', 'name', array($intGameID));
  	
  	$intLimit = 50;
  	 
  	$arrViewList = $this->pdh->get('clanwars_wars', 'wars_for_game', array($intGameID));
  	 
  	$hptt_page_settings = array(
  			'name' => 'hptt_clanwars_gamematches_frontend',
  			'table_main_sub' => '%intWarID%',
  			'table_subs' => array('%intWarID%', '%link_url%', '%link_url_suffix%'),
  			'page_ref' => 'admin/manage_wars.php',
  			'show_numbers' => false,
  			'show_select_boxes' => false,
  			'show_detail_twink' => false,
  			'table_sort_col' => 0,
  			'table_sort_dir' => 'desc',
  			'selectboxes_checkall'	=> false,
  			'table_presets' => array(
  					array('name' => 'clanwars_wars_date', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_opponent', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_result', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_ownTeamID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  			),
  			 
  	);
  	$hptt = $this->get_hptt($hptt_page_settings, $arrViewList, $arrViewList, array('%link_url%' => $this->strPath, '%link_url_suffix%' => '&amp;upd=true', '%link_fe%' => true));
  	$hptt->setPageRef($this->strPath);
  	$strPageSuffix = '&amp;start='.$this->in->get('start', 0);
  	$strSortSuffix = '?sort='.$this->in->get('sort');
  	
  	$intWarCount = count($arrViewList);
  	
  	$strFooterText = sprintf($intLimit, $intWarCount, $intLimit);
  	 
  	$this->tpl->assign_vars(array(
  			'MATCHES_LIST' 		=> $hptt->get_html_table($this->in->get('sort'), $strPageSuffix, $this->in->get('start', 0), $intLimit, $strFooterText, $intGameID),
  			'PAGINATION' 		=> generate_pagination($this->strPath.$this->SID.$sort_suffix, $intWarCount, $intLimit, $this->in->get('start', 0)),
  			'HPTT_COLUMN_COUNT'	=> $hptt->get_column_count(),
  	));
  	 
  	//Statistics
  	 
  	$colorArray = array('#3c3', '#e23b30', '#d5d5d5');
  	$stat = $this->pdh->get('clanwars_games', 'statistics', array($intGameID, false));

  	$chartArray = array();
  	$chartArray[] = array('value' => $stat['wins'], 'name' => $this->user->lang('cw_wins').': '.$stat['wins']);
  	$chartArray[] = array('value' => $stat['loss'], 'name' => $this->user->lang('cw_loss').': '.$stat['loss']);
  	$chartArray[] = array('value' => $stat['equal'], 'name' => $this->user->lang('cw_equal').': '.$stat['equal']);
  		
  	$this->tpl->assign_vars(array(
  			'CHART' 	=> $this->jquery->charts('pie', 'game_'.$intGameID, $chartArray, array('title' => $this->pdh->get('clanwars_games', 'name', array($intGameID)), 'color_array' => $colorArray, 'legend' => true)),
  			'GAMENAME'	=> $strGamename,
  	));

  	 
  	// -- EQDKP ---------------------------------------------------------------
  	$this->core->set_vars(array (
  			'page_title'    => $this->user->lang('cw_game').': '.$strGamename,
  			'template_path' => $this->pm->get_data('clanwars', 'template_path'),
  			'template_file' => 'game.html',
  			'display'       => true
  	));
  }
  
  public function view_team(){
  	$intTeamID = $this->in->get('t', 0);
  	$strTeamname = $this->pdh->get('clanwars_teams', 'name', array($intTeamID));
  	
  	$intLimit = 50;
  	
  	$arrViewList = $this->pdh->get('clanwars_wars', 'wars_for_team', array($intTeamID));
  	
  	$hptt_page_settings = array(
  			'name' => 'hptt_clanwars_teammatches_frontend',
  			'table_main_sub' => '%intWarID%',
  			'table_subs' => array('%intWarID%', '%link_url%', '%link_url_suffix%'),
  			'page_ref' => 'admin/manage_wars.php',
  			'show_numbers' => false,
  			'show_select_boxes' => false,
  			'show_detail_twink' => false,
  			'table_sort_col' => 0,
  			'table_sort_dir' => 'desc',
  			'selectboxes_checkall'	=> false,
  			'table_presets' => array(
  					array('name' => 'clanwars_wars_date', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_gameID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_result', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_ownTeamID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  			),
  	
  	);
  	$hptt = $this->get_hptt($hptt_page_settings, $arrViewList, $arrViewList, array('%link_url%' => $this->strPath, '%link_url_suffix%' => '&amp;upd=true', '%link_fe%' => true));
  	$hptt->setPageRef($this->strPath);
  	$strPageSuffix = '&amp;start='.$this->in->get('start', 0);
  	$strSortSuffix = '?sort='.$this->in->get('sort');
  	 
  	$intWarCount = count($arrViewList);
  	 
  	$strFooterText = sprintf($intLimit, $intWarCount, $intLimit);
  	
  	$this->tpl->assign_vars(array(
  			'MATCHES_LIST' 		=> $hptt->get_html_table($this->in->get('sort'), $strPageSuffix, $this->in->get('start', 0), $intLimit, $strFooterText, $intTeamID),
  			'PAGINATION' 		=> generate_pagination($this->strPath.$this->SID.$sort_suffix, $intWarCount, $intLimit, $this->in->get('start', 0)),
  			'HPTT_COLUMN_COUNT'	=> $hptt->get_column_count(),
  			'TEAMNAME' 			=> $strTeamname,
  			'TEAMDESC'			=> $this->bbcode->toHTML($this->pdh->get('clanwars_teams', 'description', array($intTeamID))),
  			'TEAMICON'			=> $this->pdh->geth('clanwars_teams', 'icon', array($intTeamID, 120)),
  	));
  	
  	//Statistics
  	$arrGames = array();
  	foreach($arrViewList as $intWarID){
  		$arrGames[] = $this->pdh->get('clanwars_wars', 'gameID', array($intWarID));
  	}
  	$arrGames = array_unique($arrGames);
  	
  	$colorArray = array('#3c3', '#e23b30', '#d5d5d5');
  	foreach($arrGames as $intGameID){
  		$stat = $this->pdh->get('clanwars_games', 'team_statistics', array($intGameID, $intTeamID));
  		$chartArray = array();
  		$chartArray[] = array('value' => $stat['wins'], 'name' => $this->user->lang('cw_wins').': '.$stat['wins']);
  		$chartArray[] = array('value' => $stat['loss'], 'name' => $this->user->lang('cw_loss').': '.$stat['loss']);
  		$chartArray[] = array('value' => $stat['equal'], 'name' => $this->user->lang('cw_equal').': '.$stat['equal']);
  	
  		$this->tpl->assign_block_vars('gamechart_row', array(
  			'CHART' => $this->jquery->charts('pie', 'game_'.$intGameID, $chartArray, array('title' => $this->pdh->get('clanwars_games', 'name', array($intGameID)), 'color_array' => $colorArray, 'legend' => true)),
  		));
  	}
  	  	 
  	// -- EQDKP ---------------------------------------------------------------
  	$this->core->set_vars(array (
  			'page_title'    => $this->user->lang('cw_team').': '.$strTeamname,
  			'template_path' => $this->pm->get_data('clanwars', 'template_path'),
  			'template_file' => 'team.html',
  			'display'       => true
  	));
  	
  }
  
  public function view_category(){
  	$intCategoryID = $this->in->get('cat', 0);
  	
  	$strCategoryname = $this->pdh->get('clanwars_categories', 'name', array($intCategoryID));
  	
  	$intLimit = 50;
  	 
  	$arrViewList = $this->pdh->get('clanwars_wars', 'wars_for_category', array($intCategoryID));
  	 
  	$hptt_page_settings = array(
  			'name' => 'hptt_clanwars_catmatches_frontend',
  			'table_main_sub' => '%intWarID%',
  			'table_subs' => array('%intWarID%', '%link_url%', '%link_url_suffix%'),
  			'page_ref' => 'admin/manage_wars.php',
  			'show_numbers' => false,
  			'show_select_boxes' => false,
  			'show_detail_twink' => false,
  			'table_sort_col' => 0,
  			'table_sort_dir' => 'desc',
  			'selectboxes_checkall'	=> false,
  			'table_presets' => array(
  					array('name' => 'clanwars_wars_date', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_gameID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_opponent', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_result', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_ownTeamID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  			),
  			 
  	);
  	$hptt = $this->get_hptt($hptt_page_settings, $arrViewList, $arrViewList, array('%link_url%' => $this->strPath, '%link_url_suffix%' => '&amp;upd=true', '%link_fe%' => true));
  	$hptt->setPageRef($this->strPath);
  	$strPageSuffix = '&amp;start='.$this->in->get('start', 0);
  	$strSortSuffix = '?sort='.$this->in->get('sort');
  	
  	$intWarCount = count($arrViewList);
  	
  	$strFooterText = sprintf($intLimit, $intWarCount, $intLimit);

  	$this->tpl->assign_vars(array(
  			'MATCHES_LIST' 		=> $hptt->get_html_table($this->in->get('sort'), $strPageSuffix, $this->in->get('start', 0), $intLimit, $strFooterText, $intCategoryID),
  			'PAGINATION' 		=> generate_pagination($this->strPath.$this->SID.$sort_suffix, $intWarCount, $intLimit, $this->in->get('start', 0)),
  			'HPTT_COLUMN_COUNT'	=> $hptt->get_column_count(),
  			'CATEGORYNAME' 		=> $strCategoryname,
  	));
  	
  	// -- EQDKP ---------------------------------------------------------------
  	$this->core->set_vars(array (
  			'page_title'    => $this->user->lang('cw_category').': '.$strCategoryname,
  			'template_path' => $this->pm->get_data('clanwars', 'template_path'),
  			'template_file' => 'category.html',
  			'display'       => true
  	));
  }
  
  public function view_clan(){
  	$intClanID = $this->in->get('c', 0);
  	
  	$strClanname = $this->pdh->get('clanwars_clans', 'name', array($intClanID));
  	
  	$strClanweb = (strlen($this->pdh->geth('clanwars_clans', 'website', array($intClanID)))) ? ', '.$this->pdh->geth('clanwars_clans', 'website', array($intClanID)) : '';
  	
  	$this->tpl->assign_vars(array(
  		'CLANNAME' => $strClanname,
  		'CLANLOGO' => $this->pdh->geth('clanwars_clans', 'icon', array($intClanID, 120)),
  		'CLANCOUNTRY' => $this->pdh->geth('clanwars_clans', 'country', array($intClanID)),
  		'CLAN_EST' => $this->pdh->geth('clanwars_clans', 'estdate', array($intClanID)),
  		'CLAN_WEB' => $strClanweb,
  	));
  	
  	//Teams
  	$arrTeams = $this->pdh->get('clanwars_teams', 'teamsForClan', array($intClanID));
  	
  	$hptt_page_settings = array(
  			'name' => 'hptt_clanwars_clanteams_frontend',
  			'table_main_sub' => '%intTeamID%',
  			'table_subs' => array('%intTeamID%', '%link_url%', '%link_url_suffix%'),
  			'page_ref' => 'admin/manage_teams.php',
  			'show_numbers' => false,
  			'show_select_boxes' => false,
  			'show_detail_twink' => false,
  			'table_sort_col' => 0,
  			'table_sort_dir' => 'asc',
  			'table_presets' => array(
  					array('name' => 'clanwars_teams_name_decorated', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_teams_gameID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_teams_members', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  			),
  	
  	);
  	$hptt = $this->get_hptt($hptt_page_settings, $arrTeams, $arrTeams, array('%link_url%' => 'manage_teams.php', '%link_url_suffix%' => '&amp;upd=true', '%link_fe%' => true));
  	$hptt->setPageRef($this->strPath);
  	$strPageSuffix = '&amp;start='.$this->in->get('start', 0);
  	$strSortSuffix = '?sort='.$this->in->get('sort');
  	
  	
  	$intTeamCount = count($arrTeams);
  	$intLimit = 0;
  	$strFooterText = sprintf($intLimit, $intTeamCount, $intLimit);
  	
  	$this->confirm_delete($this->user->lang('cw_confirm_delete_teams'));
  	
  	$this->tpl->assign_vars(array(
  			'TEAMS_LIST' 		=> $hptt->get_html_table($this->in->get('sort'), $strPageSuffix, null, $intLimit, $strFooterText, $intClanID),
  			//'PAGINATION' 		=> generate_pagination($this->strPath.$strSortSuffix, $intTeamCount, $intLimit, $this->in->get('start', 0)),
  			'HPTT_COLUMN_COUNT'	=> $hptt->get_column_count())
  	);
  	 
  	
  	//Matches
  	$intLimit = 50;
  	$arrViewList = $arrMatches = $this->pdh->get('clanwars_wars', 'wars_for_clan', array($intClanID));
  	
  	$hptt_page_settings = array(
  			'name' => 'hptt_clanwars_clanwars_frontend',
  			'table_main_sub' => '%intWarID%',
  			'table_subs' => array('%intWarID%', '%link_url%', '%link_url_suffix%'),
  			'page_ref' => 'admin/manage_wars.php',
  			'show_numbers' => false,
  			'show_select_boxes' => false,
  			'show_detail_twink' => false,
  			'table_sort_col' => 0,
  			'table_sort_dir' => 'desc',
  			'selectboxes_checkall'	=> false,
  			'table_presets' => array(
  					array('name' => 'clanwars_wars_date', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_gameID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_teamID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_result', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_ownTeamID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_categoryID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  			),
  			 
  	);
  	$hptt = $this->get_hptt($hptt_page_settings, $arrViewList, $arrViewList, array('%link_url%' => $this->strPath, '%link_url_suffix%' => '&amp;upd=true', '%link_fe%' => true));
  	$hptt->setPageRef($this->strPath);
  	$strPageSuffix = '&amp;start='.$this->in->get('start', 0);
  	$strSortSuffix = '?sort='.$this->in->get('sort');
  	
  	$intWarCount = count($arrViewList);
  	
  	$strFooterText = sprintf($intLimit, $intWarCount, $intLimit);
  	
  	 
  	$this->tpl->assign_vars(array(
  			'MATCHES_LIST' 		=> $hptt->get_html_table($this->in->get('sort'), $strPageSuffix, $this->in->get('start', 0), $intLimit, $strFooterText, $intClanID),
  			'PAGINATION' 		=> generate_pagination($this->strPath.$this->SID.$sort_suffix, $intWarCount, $intLimit, $this->in->get('start', 0)),
  			'HPTT_COLUMN_COUNT'	=> $hptt->get_column_count())
  	);
  	
  	$colorArray = array('#3c3', '#e23b30', '#d5d5d5');
  	
  	//Statistics
  	foreach($arrTeams as $intTeamID){
  		$stat = $this->pdh->get('clanwars_teams', 'statistics', array($intTeamID));
  		$chartArray = array();
  		$chartArray[] = array('value' => $stat['wins'], 'name' => $this->user->lang('cw_wins').': '.$stat['wins']);
  		$chartArray[] = array('value' => $stat['loss'], 'name' => $this->user->lang('cw_loss').': '.$stat['loss']);
  		$chartArray[] = array('value' => $stat['equal'], 'name' => $this->user->lang('cw_equal').': '.$stat['equal']);
  		
  		$this->tpl->assign_block_vars('teamchart_row', array(
  			'CHART' => $this->jquery->charts('pie', 'team_'.$intTeamID, $chartArray, array('title' => $this->pdh->get('clanwars_teams', 'name', array($intTeamID)), 'color_array' => $colorArray, 'legend' => true)),
  		));
  		
  	}
  	
  	$arrGames = $this->pdh->get('clanwars_clans', 'games_for_clan', array($intClanID));
	foreach($arrGames as $intGameID){
		$stat = $this->pdh->get('clanwars_games', 'statistics', array($intGameID, $intClanID));
		$chartArray = array();
  		$chartArray[] = array('value' => $stat['wins'], 'name' => $this->user->lang('cw_wins').': '.$stat['wins']);
  		$chartArray[] = array('value' => $stat['loss'], 'name' => $this->user->lang('cw_loss').': '.$stat['loss']);
  		$chartArray[] = array('value' => $stat['equal'], 'name' => $this->user->lang('cw_equal').': '.$stat['equal']);
  		
  		$this->tpl->assign_block_vars('gamechart_row', array(
  			'CHART' => $this->jquery->charts('pie', 'game_'.$intGameID, $chartArray, array('title' => $this->pdh->get('clanwars_games', 'name', array($intGameID)), 'color_array' => $colorArray, 'legend' => true)),
  		));
	}
  	
  	
  	// -- EQDKP ---------------------------------------------------------------
  	$this->core->set_vars(array (
  			'page_title'    => $this->user->lang('cw_clan').': '.$strClanname,
  			'template_path' => $this->pm->get_data('clanwars', 'template_path'),
  			'template_file' => 'clan.html',
  			'display'       => true
  	));

  }
  
  public function view_match(){
  	$intMatchID = $this->in->get('m', 0);
  	
  	$arrData = $this->pdh->get('clanwars_wars', 'data', array($intMatchID));
  	
  	$intClanID = $arrData['clanID'];
  	$intOwnClanID = $this->pdh->get('clanwars_teams', 'clanID', array($arrData['ownTeamID']));
  	
  	$strMatchname = $this->pdh->geth('clanwars_wars', 'date', array($intMatchID)).', '.$this->pdh->geth('clanwars_wars', 'gameID', array($intMatchID)).', '.$this->pdh->get('clanwars_clans', 'name', array($intClanID));
  	
  	$this->tpl->assign_vars(array(
  		'MATCHNAME'=> $strMatchname,
  		'CLANTEAM' => $this->pdh->geth('clanwars_wars', 'teamID', array($intMatchID, true)),
  		'CLANNAME' => $this->pdh->get('clanwars_clans', 'clanID_decorated', array($intClanID)),
  		'CLANLOGO' => $this->pdh->geth('clanwars_clans', 'icon', array($intClanID, 120)),	
  			
  		'OWN_CLANLOGO' => $this->pdh->geth('clanwars_clans', 'icon', array($intOwnClanID, 120)),	
  		'OWN_CLANTEAM' => $this->pdh->geth('clanwars_wars', 'ownTeamID', array($intMatchID, true)),
  		'OWN_CLANNAME' => $this->pdh->get('clanwars_clans', 'clanID_decorated', array($intOwnClanID)),
  			
  		'WAR_RESULT'	=> $this->pdh->geth('clanwars_wars', 'result', array($intMatchID)),
  		'WAR_VERSUS'	=> $this->pdh->geth('clanwars_wars', 'playerCount', array($intMatchID)),
  			
  		'WAR_DATE'		=> $this->pdh->geth('clanwars_wars', 'date', array($intMatchID)),
  		'WAR_GAME'		=> $this->pdh->geth('clanwars_wars', 'gameID', array($intMatchID, true)),
  		'WAR_CATEGORY'	=> $this->pdh->geth('clanwars_wars', 'categoryID', array($intMatchID, true)),
  		'WAR_WEBSITE'   => $this->pdh->geth('clanwars_wars', 'website', array($intMatchID, true)),
  		'WAR_OWNREPORT' => $this->pdh->geth('clanwars_wars', 'ownReport', array($intMatchID, true)),
  		'WAR_REPORT' 	=> $this->pdh->geth('clanwars_wars', 'report', array($intMatchID, true)),
  	));
  	
  	if ((int)$arrData['activateComments'] == 1){
  		
  		$this->comments->SetVars(array(
  				'attach_id'	=> $intMatchID,
  				'page'		=> 'clanwars',
  				'auth'		=> 'a_clanwars_manage_wars',
  		));
  		$this->tpl->assign_vars(array(
  				'COMMENTS'		=> $this->comments->Show(),
  		));
  	}
  	
  	
  	// -- EQDKP ---------------------------------------------------------------
  	$this->core->set_vars(array (
  			'page_title'    => $this->user->lang('cw_match').': '.$strMatchname,
  			'template_path' => $this->pm->get_data('clanwars', 'template_path'),
  			'template_file' => 'match.html',
  			'display'       => true
  	));
  }
  
  public function view_matches(){
  	$intLimit = 50;
  	
  	$arrViewList = $this->pdh->get('clanwars_wars', 'id_list', array());
  	
  	$hptt_page_settings = array(
  			'name' => 'hptt_clanwars_wars_frontend',
  			'table_main_sub' => '%intWarID%',
  			'table_subs' => array('%intWarID%', '%link_url%', '%link_url_suffix%'),
  			'page_ref' => 'admin/manage_wars.php',
  			'show_numbers' => false,
  			'show_select_boxes' => false,
  			'show_detail_twink' => false,
  			'table_sort_col' => 0,
  			'table_sort_dir' => 'desc',
  			'selectboxes_checkall'	=> false,
  			'table_presets' => array(
  					array('name' => 'clanwars_wars_date', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_gameID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_opponent', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_result', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_ownTeamID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_wars_categoryID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  			),
  	
  	);
  	$hptt = $this->get_hptt($hptt_page_settings, $arrViewList, $arrViewList, array('%link_url%' => $this->strPath, '%link_url_suffix%' => '&amp;upd=true', '%link_fe%' => true));
  	$hptt->setPageRef($this->strPath);
  	$strPageSuffix = '&amp;start='.$this->in->get('start', 0);
  	$strSortSuffix = '?sort='.$this->in->get('sort');

  	$intWarCount = count($arrViewList);

  	$strFooterText = sprintf($intLimit, $intWarCount, $intLimit);

  	
  	$this->tpl->assign_vars(array(
  			'MATCHES_LIST' 		=> $hptt->get_html_table($this->in->get('sort'), $strPageSuffix, $this->in->get('start', 0), $intLimit, $strFooterText),
  			'PAGINATION' 		=> generate_pagination($this->strPath.$this->SID.$sort_suffix, $intWarCount, $intLimit, $this->in->get('start', 0)),
  			'HPTT_COLUMN_COUNT'	=> $hptt->get_column_count())
  	);
  	 
  	// -- EQDKP ---------------------------------------------------------------
  	$this->core->set_vars(array (
  			'page_title'    => $this->user->lang('cw_matches'),
  			'template_path' => $this->pm->get_data('clanwars', 'template_path'),
  			'template_file' => 'matches.html',
  			'display'       => true
  	));
  }
  
  
  public function view_awards(){
  	$intLimit = 50;
  	 
  	$arrViewList = $this->pdh->get('clanwars_awards', 'id_list', array());
  	 
  	$hptt_page_settings = array(
  			'name' => 'hptt_clanwars_awards_frontend',
  			'table_main_sub' => '%intAwardID%',
  			'table_subs' => array('%intAwardID%', '%link_url%', '%link_url_suffix%'),
  			'page_ref' => 'admin/manage_awards.php',
  			'show_numbers' => false,
  			'show_select_boxes' => true,
  			'show_detail_twink' => false,
  			'table_sort_col' => 0,
  			'table_sort_dir' => 'desc',
  			'selectboxes_checkall'	=> false,
  			'table_presets' => array(
  					array('name' => 'clanwars_awards_date', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_awards_event', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_awards_gameID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_awards_winner', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  					array('name' => 'clanwars_awards_rank', 'sort' => true, 'th_add' => '', 'td_add' => ''),
  			),
  			 
  	);
  	$hptt = $this->get_hptt($hptt_page_settings, $arrViewList, $arrViewList, array('%link_url%' =>  $this->strPath, '%link_url_suffix%' => '&amp;upd=true',  '%link_fe%' => true));
  	$hptt->setPageRef($this->strPath);
  	$strPageSuffix = '&amp;start='.$this->in->get('start', 0);
  	$strSortSuffix = '?sort='.$this->in->get('sort');
  	 
  	 
  	$intAwardCount = count($arrViewList);
  	 
  	$strFooterText = sprintf($intLimit, $intAwardCount, $intLimit);
  	 
  	$this->tpl->assign_vars(array(
  			'AWARDS_LIST' 		=> $hptt->get_html_table($this->in->get('sort'), $strPageSuffix, $this->in->get('start', 0), $intLimit, $strFooterText),
  			'PAGINATION' 		=> generate_pagination($this->strPath.$this->SID.$strSortSuffix, $intAwardCount, $intLimit, $this->in->get('start', 0)),
  			'HPTT_COLUMN_COUNT'	=> $hptt->get_column_count())
  	);
  	 
  	// -- EQDKP ---------------------------------------------------------------
  	$this->core->set_vars(array (
  			'page_title'    => $this->user->lang('cw_awards'),
  			'template_path' => $this->pm->get_data('clanwars', 'template_path'),
  			'template_file' => 'awards.html',
  			'display'       => true
  	));
  
  }

    
  public function display(){
  	
  }

}
?>