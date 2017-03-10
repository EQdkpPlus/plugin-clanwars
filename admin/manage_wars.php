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


class clanwarsManageWars extends page_generic
{
  /**
   * __dependencies
   * Get module dependencies
   */
  public static function __shortcuts()
  {
    $shortcuts = array('form' => array('form', array('manage_wars')));
    return array_merge(parent::$shortcuts, $shortcuts);
  }


  public function __construct()
  {
    // plugin installed?
    if (!$this->pm->check('clanwars', PLUGIN_INSTALLED))
      message_die($this->user->lang('cw_not_installed'));
	  
	$this->user->check_auth('a_clanwars_manage_wars');

    $handler = array(
      'save' => array('process' => 'save', 'csrf' => true),
	  'upd'	=> array('process' => 'update', 'csrf'=>false),
    );
    parent::__construct(null, $handler, array('clanwars_wars', 'delete_name'), null, 'selected_ids[]');

    $this->process();
  }

  public function delete(){
	$messages = array();
	if(count($this->in->getArray('selected_ids', 'int')) > 0) {
		$arrResult = array();
		foreach($this->in->getArray('selected_ids', 'int') as $intWarID){
			$arrResult[] = $this->pdh->put('clanwars_wars', 'delete_war', array($intWarID));
		}
		
		if (in_array(false, $arrResult)){
			$messages[] = array('title' => $this->user->lang('del_no_suc'), 'text' => $this->user->lang('cw_delete_wars_no_suc'), 'color' => 'red');
		} else {
			$messages[] = array('title' => $this->user->lang('del_suc'), 'text' => $this->user->lang('cw_delete_wars_suc'), 'color' => 'green');
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
	
	$arrOwnTeams = $this->pdh->aget('clanwars_teams', 'name', 0, array($this->pdh->sort($this->pdh->get('clanwars_teams', 'id_list', array(true)), 'clanwars_teams', 'name', 'asc')));
	natcasesort($arrOwnTeams);
	
	$arrCategories = $this->pdh->aget('clanwars_categories', 'name', 0, array($this->pdh->sort($this->pdh->get('clanwars_categories', 'id_list', array(true)), 'clanwars_categories', 'name', 'asc')));
	$arrCategories[0] = "";
	natcasesort($arrCategories);
	
	$arrTeams = array();
	
	return array(
		'team' => array(
			'clanID' => array(
				'type'		=> 'dropdown',
				'options'	=> $arrClans,
				'ajax_reload' => array(array('teamID'), 'manage_wars.php'.$this->SID.'&ajax=true'),
			),
			'teamID' => array(
				'type'		=> 'dropdown',
				'options'	=> $arrTeams,
			),
			'players' => array(
				'type'		=> 'textarea',
				'row'		=> 5,
				'cols'		=> 30,
			),
			'ownTeamID' => array(
				'type'		=> 'dropdown',
				'options'	=> $arrOwnTeams,
			),
			'ownPlayers' => array(
				'type'		=> 'multiselect',
				'options'	=> $arrUser,
			),
		),
		'war'	=> array(
			'date'	=> array(
				'type'			=> 'datepicker',
				'allow_empty'	=> false,
				'year_range'	=> '-10:+5',
				'change_fields' => true,
				'timepicker'	=> true,
			),
			'gameID' => array(
				'type'		=> 'dropdown',
				'options'	=> $arrGames,
			),
			'categoryID' => array(
				'type'		=> 'dropdown',
				'options'	=> $arrCategories,
			),
			'playerCount' => array(
				'type'		=> 'text',
				'size'		=> 1,
				'text2'		=> ' vs. '.(new htext('playerCount2', array('size' => 1)))->output(),
			),
			'result' => array(
				'type'		=> 'text',
				'size'		=> 1,
				'text2'		=> ' : '.(new htext('result2', array('size' => 1)))->output(),
			),
			'website' => array(
				'type'		=> 'text',
				'size'		=> 40,
			),
			'ownReport' => array(
				'type'		=> 'bbcodeeditor',
			),
			'report' => array(
				'type'		=> 'bbcodeeditor',
			),
			'activateComments' => array(
				'type'		=> 'radio',
			),
		)
	);
  }

  public function save() {
		$this->form->add_fieldsets($this->fields());
		$arrSave = $this->form->return_values();
		
		$arrSave['result2'] = $this->in->get('result2', 0);
		$arrSave['playerCount2'] = $this->in->get('playerCount2', 0);

		$intWarID = $this->in->get('w', 0);
		
		$arrResult = false;
		if ($intWarID){
			$arrResult = $this->pdh->put('clanwars_wars', 'update_war', array($intWarID, $arrSave));
		} else {
			$arrResult = $this->pdh->put('clanwars_wars', 'add_war', array($arrSave));
		}
		
		$messages = array();
		if ($arrResult){
			$messages[] = array('title' => $this->user->lang('save_suc'), 'text' => $this->user->lang('cw_save_wars_suc'), 'color' => 'green');
		} else {
			$messages[] = array('title' => $this->user->lang('save_nosuc'), 'text' => $this->user->lang('cw_save_wars_no_suc'), 'color' => 'red');
		}
		
		$this->display($messages);
  }
  
  public function update(){
		$intWarID = $this->in->get('w', 0);
		$strWarName = ($intWarID) ? $this->pdh->geth('clanwars_wars', 'date', array($intWarID)).', '.$this->pdh->geth('clanwars_wars', 'clanID', array($intWarID))  : $this->user->lang('cw_add_war');
  
		// initialize form class
		$this->form->lang_prefix = 'cw_wars_';
		$this->form->use_fieldsets = true;
		
		$arrFields = $this->fields();
		if($intWarID){
			$arrData = $this->pdh->get('clanwars_wars', 'data', array($intWarID));
			$arrFields['war']['playerCount']['text2'] = ' vs. '.(new htext('playerCount2', array('size' => 1, 'value' => $arrData['playerCount'][1])))->output();
			$arrData['playerCount'] = $arrData['playerCount'][0];
			
			$arrFields['war']['result']['text2'] = ' vs. '.(new htext('result2', array('size' => 1, 'value' => $arrData['result'][1])))->output();
			$arrData['result'] = $arrData['result'][0];
			
			$arrData['players'] = implode("\r\n", $arrData['players']);
			
		} elseif($this->in->get('convert_fightus')) {
			$intFightusID = $this->in->get('convert_fightus');
			$arrData = array(
				'date'		=> $this->pdh->get('clanwars_fightus', 'date', array($intFightusID)),
				'gameID'	=> $this->pdh->get('clanwars_fightus', 'gameID', array($intFightusID)),
				'ownTeamID' => $this->pdh->get('clanwars_fightus', 'teamID', array($intFightusID)),
			);
			$intClanID = $this->pdh->get('clanwars_clans', 'clanIDforName', array($this->pdh->get('clanwars_fightus', 'clanname', array($intFightusID))));
			if ($intClanID) $arrData['clanID'] = $intClanID;
		} else $arrData = array();
		
		$this->form->add_fieldsets($arrFields);
		
		
		// Output the form, pass values in
		$this->form->output($arrData);
  
		$this->tpl->assign_vars(array(
			'WAR_NAME'	=> $strWarName,
			'WARID'		=> $intWarID,
		));
  
		$this->core->set_vars(array(
			'page_title'        => $this->user->lang('cw_manage_wars').': '.$strWarName,
			'template_path'     => $this->pm->get_data('clanwars', 'template_path'),
			'template_file'     => 'admin/manage_wars_edit.html',
			'display'           => true,)
		);
  }


  public function display($messages = false) {
		if($messages && count($messages)){
			$this->pdh->process_hook_queue();
			$this->core->messages($messages);
		}
		
		if($this->in->get('ajax', false)) {
			$options = array(
				'options_only'	=> true,
				'options' 		=> $this->pdh->aget('clanwars_teams', 'name', 0, array($this->pdh->get('clanwars_teams', 'teamsForClan', array($this->in->get('requestid', 0))))),
			);
			echo (new hdropdown('teamID', $options))->output();
			exit;
		}
		
		$intLimit = 50;
		
		$arrViewList = $this->pdh->get('clanwars_wars', 'id_list', array());
		
		$hptt_page_settings = array(
			'name' => 'hptt_clanwars_wars',
			'table_main_sub' => '%intWarID%',
			'table_subs' => array('%intWarID%', '%link_url%', '%link_url_suffix%'),
			'page_ref' => 'admin/manage_wars.php',
			'show_numbers' => false,
			'show_select_boxes' => true,
			'show_detail_twink' => false,
			'table_sort_col' => 1,
			'table_sort_dir' => 'desc',
			'selectboxes_checkall'	=> true,
			'table_presets' => array(
				array('name' => 'clanwars_wars_actions', 'sort' => false, 'th_add' => 'width="20"', 'td_add' => ''),
				array('name' => 'clanwars_wars_date', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_wars_gameID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_wars_clanID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_wars_teamID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_wars_result', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_wars_ownTeamID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_wars_categoryID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
			),
		
		);
		$hptt = $this->get_hptt($hptt_page_settings, $arrViewList, $arrViewList, array('%link_url%' => 'manage_wars.php', '%link_url_suffix%' => '&amp;upd=true'));
		$strPageSuffix = '&amp;start='.$this->in->get('start', 0);
		$strSortSuffix = '?sort='.$this->in->get('sort');
	
	
		$intWarCount = count($arrViewList);
	
		$strFooterText = sprintf($this->user->lang('hptt_default_part_footcount'), $intWarCount, $intLimit);
	
		$this->confirm_delete($this->user->lang('cw_confirm_delete_wars'));
  
		$this->tpl->assign_vars(array(
			'WARS_LIST' 		=> $hptt->get_html_table($this->in->get('sort'), $strPageSuffix, $this->in->get('start', 0), $intLimit, $strFooterText),
			'PAGINATION' 		=> generate_pagination('manage_wars.php'.$strSortSuffix, $intWarCount, $intLimit, $this->in->get('start', 0)),
			'HPTT_COLUMN_COUNT'	=> $hptt->get_column_count())
		);
   
		$this->core->set_vars(array(
			'page_title'        => $this->user->lang('cw_manage_wars'),
			'template_path'     => $this->pm->get_data('clanwars', 'template_path'),
			'template_file'     => 'admin/manage_wars.html',
			'display'           => true,)
		);
  }
}

registry::register('clanwarsManageWars');
?>