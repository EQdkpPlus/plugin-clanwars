<?php
/*
 * Project:     EQdkp clanwars
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2012-10-13 22:48:23 +0200 (Sa, 13. Okt 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: godmod $
 * @copyright   2008-2011 Aderyn
 * @link        http://eqdkp-plus.com
 * @package     clanwars
 * @version     $Rev: 12273 $
 *
 * $Id: settings.php 12273 2012-10-13 20:48:23Z godmod $
 */

// EQdkp required files/vars
define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'clanwars');

$eqdkp_root_path = './../../../';
include_once($eqdkp_root_path.'common.php');


class clanwarsManageTeams extends page_generic
{
  /**
   * __dependencies
   * Get module dependencies
   */
  public static function __shortcuts()
  {
    $shortcuts = array('form' => array('form', array('manage_teams')));
    return array_merge(parent::$shortcuts, $shortcuts);
  }


  public function __construct()
  {
    // plugin installed?
    if (!$this->pm->check('clanwars', PLUGIN_INSTALLED))
      message_die($this->user->lang('cw_not_installed'));
	  
	$this->user->check_auth('a_clanwars_manage_teams');  

    $handler = array(
      'save' => array('process' => 'save', 'csrf' => true),
	  'upd'	=> array('process' => 'update', 'csrf'=>false),
    );
    parent::__construct(null, $handler, array('clanwars_teams', 'name'), null, 'selected_ids[]');

    $this->process();
  }

  public function delete(){
	$messages = array();
	if(count($this->in->getArray('selected_ids', 'int')) > 0) {
		$arrResult = array();
		foreach($this->in->getArray('selected_ids', 'int') as $intTeamID){
			$arrResult[] = $this->pdh->put('clanwars_teams', 'delete_team', array($intTeamID));
		}
		
		if (in_array(false, $arrResult)){
			$messages[] = array('title' => $this->user->lang('del_no_suc'), 'text' => $this->user->lang('cw_delete_teams_no_suc'), 'color' => 'red');
		} else {
			$messages[] = array('title' => $this->user->lang('del_suc'), 'text' => $this->user->lang('cw_delete_teams_suc'), 'color' => 'green');
		}
	}
	$this->display($messages);
  }
  
  private function fields(){
  
	$arrUser = $this->pdh->aget('user', 'name', 0, array($this->pdh->sort($this->pdh->get('user', 'id_list'), 'user', 'name', 'asc')));
	natcasesort($arrUser);
	
	$arrClans = $this->pdh->aget('clanwars_clans', 'name', 0, array($this->pdh->sort($this->pdh->get('clanwars_clans', 'id_list', array()), 'clanwars_clans', 'name', 'asc')));
	natcasesort($arrClans);
	
	$arrGames = $this->pdh->aget('clanwars_games', 'name', 0, array($this->pdh->get('clanwars_games', 'id_list', array(true))));
	natcasesort($arrGames);
	
	return array(
		'name' => array(
			'type'		=> 'text',
			'size'		=> 40,
		),
		'description' => array(
			'type'		=> 'bbcodeeditor',
		),
		'icon' => array(
			'type'			=> 'imageuploader',
			'imgpath'		=> $this->pfh->FolderPath('team_icons', 'clanwars'),
			'returnFormat'	=> 'in_data',
			'storageFolder' => $this->pfh->FolderPath('team_icons', 'clanwars', 'absolute'),
		),
		'members' => array(
			'type'		=> 'multiselect',
			'options'	=> $arrUser,
		),
		'clanID' => array(
			'type'		=> 'dropdown',
			'options'	=> $arrClans,
		),	
		'gameID' => array(
			'type'		=> 'dropdown',
			'options'	=> $arrGames,
		),
	);
  }

  public function save() {
		$this->form->add_fields($this->fields());
		$arrSave = $this->form->return_values();
		
		$intTeamID = $this->in->get('t', 0);
		
		$arrResult = false;
		if ($intTeamID){
			$arrResult = $this->pdh->put('clanwars_teams', 'update_team', array($intTeamID, $arrSave));
		} else {
			$arrResult = $this->pdh->put('clanwars_teams', 'add_team', array($arrSave));
		}
		
		$messages = array();
		if ($arrResult){
			$messages[] = array('title' => $this->user->lang('save_suc'), 'text' => $this->user->lang('cw_save_teams_suc'), 'color' => 'green');
		} else {
			$messages[] = array('title' => $this->user->lang('save_nosuc'), 'text' => $this->user->lang('cw_save_teams_no_suc'), 'color' => 'red');
		}
		
		$this->display($messages);
  }
  
  public function update(){
		$intTeamID = $this->in->get('t', 0);
		$strTeamName = ($intTeamID) ? $this->pdh->get('clanwars_teams', 'name', array($intTeamID)) : $this->user->lang('cw_add_team');
  
		// initialize form class
		$this->form->lang_prefix = 'cw_teams_';
		$this->form->use_tabs = false;
		$this->form->use_fieldsets = false;
		
		$this->form->add_fields($this->fields());
		
		if ($intTeamID){
			$arrData = $this->pdh->get('clanwars_teams', 'data', array($intTeamID));
		} else $arrData = array();
		
		// Output the form, pass values in
		$this->form->output($arrData);
  
		$this->tpl->assign_vars(array(
			'TEAM_NAME'	=> $strTeamName,
			'TEAMID'		=> $intTeamID,
		));
  
		$this->core->set_vars(array(
			'page_title'        => $this->user->lang('cw_manage_teams').': '.$strTeamName,
			'template_path'     => $this->pm->get_data('clanwars', 'template_path'),
			'template_file'     => 'admin/manage_teams_edit.html',
			'display'           => true,)
		);
  }


  public function display($messages = false) {
		if($messages && count($messages)){
			$this->pdh->process_hook_queue();
			$this->core->messages($messages);
		}
		
		$intLimit = 50;
		
		$arrViewList = $this->pdh->get('clanwars_teams', 'id_list', array());
		
		$hptt_page_settings = array(
			'name' => 'hptt_clanwars_teams',
			'table_main_sub' => '%intTeamID%',
			'table_subs' => array('%intTeamID%', '%link_url%', '%link_url_suffix%'),
			'page_ref' => 'admin/manage_teams.php',
			'show_numbers' => false,
			'show_select_boxes' => true,
			'show_detail_twink' => false,
			'table_sort_col' => 1,
			'table_sort_dir' => 'desc',
			'selectboxes_checkall'	=> true,
			'table_presets' => array(
				array('name' => 'clanwars_teams_actions', 'sort' => false, 'th_add' => 'width="20"', 'td_add' => ''),
				array('name' => 'clanwars_teams_name', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_teams_icon', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_teams_gameID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_teams_clanID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_teams_members', 'sort' => true, 'th_add' => '', 'td_add' => ''),
			),
		
		);
		$hptt = $this->get_hptt($hptt_page_settings, $arrViewList, $arrViewList, array('%link_url%' => 'manage_teams.php', '%link_url_suffix%' => '&amp;upd=true'));
		$strPageSuffix = '&amp;start='.$this->in->get('start', 0);
		$strSortSuffix = '?sort='.$this->in->get('sort');
	
	
		$intTeamCount = count($arrViewList);
	
		$strFooterText = sprintf($this->user->lang('hptt_default_part_footcount'), $intTeamCount, $intLimit);
	
		$this->confirm_delete($this->user->lang('cw_confirm_delete_teams'));
  
		$this->tpl->assign_vars(array(
			'TEAMS_LIST' 		=> $hptt->get_html_table($this->in->get('sort'), $strPageSuffix, $this->in->get('start', 0), $intLimit, $strFooterText),
			'PAGINATION' 		=> generate_pagination('manage_teams.php'.$strSortSuffix, $intTeamCount, $intLimit, $this->in->get('start', 0)),
			'HPTT_COLUMN_COUNT'	=> $hptt->get_column_count())
		);
   
		$this->core->set_vars(array(
			'page_title'        => $this->user->lang('cw_manage_teams'),
			'template_path'     => $this->pm->get_data('clanwars', 'template_path'),
			'template_file'     => 'admin/manage_teams.html',
			'display'           => true,)
		);
  }
}

registry::register('clanwarsManageTeams');
?>