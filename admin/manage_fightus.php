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

//TODO: Email bei Statuswechsel versenden


class clanwarsManageFightus extends page_generic
{
  /**
   * __dependencies
   * Get module dependencies
   */
  public static function __shortcuts()
  {
    $shortcuts = array('form' => array('form', array('manage_fightus')), 'email' => 'MyMailer');
    return array_merge(parent::$shortcuts, $shortcuts);
  }


  public function __construct()
  {
    // plugin installed?
    if (!$this->pm->check('clanwars', PLUGIN_INSTALLED))
      message_die($this->user->lang('cw_not_installed'));
	  
	$this->user->check_auth('a_clanwars_manage_fightus');  

    $handler = array(
      'save' => array('process' => 'save', 'csrf' => true),
	  'upd'	=> array('process' => 'update', 'csrf'=>false),
    );
    parent::__construct(null, $handler, array('clanwars_fightus', 'delete_name'), null, 'selected_ids[]');

    $this->process();
  }

  public function delete(){
	$messages = array();
	if(count($this->in->getArray('selected_ids', 'int')) > 0) {
		$arrResult = array();
		foreach($this->in->getArray('selected_ids', 'int') as $intFightusID){
			$arrResult[] = $this->pdh->put('clanwars_fightus', 'delete_fightus', array($intFightusID));
		}
		
		if (in_array(false, $arrResult)){
			$messages[] = array('title' => $this->user->lang('del_no_suc'), 'text' => $this->user->lang('cw_delete_fightus_no_suc'), 'color' => 'red');
		} else {
			$messages[] = array('title' => $this->user->lang('del_suc'), 'text' => $this->user->lang('cw_delete_fightus_suc'), 'color' => 'green');
		}
	}
	$this->display($messages);
  }
  
  private function fields(){
  
	$arrOwnTeams = $this->pdh->aget('clanwars_teams', 'name', 0, array($this->pdh->sort($this->pdh->get('clanwars_teams', 'id_list', array(true)), 'clanwars_teams', 'name', 'asc')));
	$arrOwnTeams[0] = "";
	natcasesort($arrOwnTeams);
	
	$arrGames = $this->pdh->aget('clanwars_games', 'name', 0, array($this->pdh->get('clanwars_games', 'id_list', array(true))));
	$arrGames[0] = "";
	natcasesort($arrGames);
	
	
	$cfile = $this->root_path.'core/country_states.php';
	if (file_exists($cfile)){
		include($cfile);
	}
	
	return array(
		'clan' => array(
			'clanname' => array(
				'type'		=> 'text',
				'size'		=> 40,
			),
			'shortname' => array(
				'type'		=> 'text',
				'size'		=> 5,
			),
			'country' => array(
				'type'		=> 'dropdown',
				'options'	=> $country_array,
			),
			'website' => array(
				'type'		=> 'text',
				'size'		=> 40,
			),
			'nick' => array(
				'type'		=> 'text',
				'size'		=> 40,
			),
			'email' => array(
				'type'		=> 'text',
				'size'		=> 40,
			),
			'additionalContact' => array(
				'type'		=> 'text',
				'size'		=> 40,
			)),					
			 
		'war' => array(
			'date'	=> array(
				'type'			=> 'datepicker',
				'allow_empty'	=> false,
				'year_range'	=> '-1:+2',
				'change_fields' => true,
				'timepicker'	=> true,
			),
			'gameID' => array(
				'type'		=> 'dropdown',
				'options'	=> $arrGames,
			),
			'teamID' => array(
				'type'		=> 'dropdown',
				'options'	=> $arrOwnTeams,
			),
			'message' => array(
				'type'		=> 'bbcodeeditor',
			)),
		'status' => array(
			'status' => array(
				'type'		=> 'dropdown',
				'options'	=> $this->user->lang('cw_fightus_status'),
			),				
			'mail_message' => array(
				'type'		=> 'bbcodeeditor',
			),
		),
	);
  }

  public function save() {
		$this->form->add_fieldsets($this->fields());
		$arrSave = $this->form->return_values();
		
		$intFightusID = $this->in->get('f', 0);
		
		$arrResult = false;
		$blnSendMail = false;
		
		if ($intFightusID){
			$arrOldStatus = $this->pdh->get('clanwars_fightus', 'status', array($intFightusID));
			if ($arrSave['status'] != $arrOldStatus) $blnSendMail = true;
			
			$arrResult = $this->pdh->put('clanwars_fightus', 'update_fightus', array($intFightusID, $arrSave));
		} else {
			$arrResult = $this->pdh->put('clanwars_fightus', 'add_fightus', array($arrSave));
			if ($arrSave['status'] != 0) $blnSendMail = true;
		}
		
		if ($blnSendMail){
			$arrStatus = $this->user->lang('cw_fightus_status');
			
			$bodyvars = array(
				'F_NICKNAME'	=> $arrSave['nick'],
				'COMMENT'		=> (strlen($arrSave['mail_message'])) ? '----------------------------<br />'.$this->bbcode->toHTML($arrSave['mail_message']).'<br />----------------------------<br />' : '',
				'F_STATUS'		=> $arrStatus[$arrSave['status']],
				'F_DATE'		=> $this->time->user_date($arrSave['date'], true),
				'GUILDTAG'		=> $this->config->get('guildtag'),
				'F_TEAM'		=> $this->pdh->geth('clanwars_fightus', 'teamID', array($arrSave['teamID'])),
				'F_GAME'		=> $this->pdh->geth('clanwars_fightus', 'gameID', array($arrSave['gameID'])),
			);
			
			$this->email->SendMailFromAdmin($arrSave['email'], $this->user->lang('cw_fightus_email_subject'), $this->root_path.'plugins/clanwars/language/'.$this->user->data['user_lang'].'/email/fightus_statuschange.html', $bodyvars);		
		}
		
		$messages = array();
		if ($arrResult){
			$messages[] = array('title' => $this->user->lang('save_suc'), 'text' => $this->user->lang('cw_save_fightus_suc'), 'color' => 'green');
		} else {
			$messages[] = array('title' => $this->user->lang('save_nosuc'), 'text' => $this->user->lang('cw_save_fightus_no_suc'), 'color' => 'red');
		}
		
		$this->display($messages);
  }
  
  public function update(){
		$intFightusID = $this->in->get('f', 0);
		$strFightusName = ($intFightusID) ? $this->pdh->get('clanwars_fightus', 'clanname', array($intFightusID)).', '.$this->pdh->geth('clanwars_fightus', 'date', array($intFightusID)) : $this->user->lang('cw_add_fightus');
  
		// initialize form class
		$this->form->lang_prefix = 'cw_fightus_';
		$this->form->use_tabs = false;
		$this->form->use_fieldsets = true;
		
		$this->form->add_fieldsets($this->fields());
		
		if ($intFightusID){
			$arrData = $this->pdh->get('clanwars_fightus', 'data', array($intFightusID));
		} else $arrData = array();
		
		$optionsmenu = array(
			1 => array(
				'name'	=> $this->user->lang('cw_convert_to_clan'),
				'link'	=> 'manage_clans.php'.$this->SID.'&upd=true&convert_fightus='.$intFightusID,
				'icon'	=> 'fa-home',
				'perm'	=> true
			),
			2 => array(
				'name'	=> $this->user->lang('cw_convert_to_war'),
				'link'	=> 'manage_wars.php'.$this->SID.'&upd=true&convert_fightus='.$intFightusID,
				'icon'	=> 'fa-crosshairs',
				'perm'	=> true
			),
			3 => array(
				'name'	=> $this->user->lang('cw_convert_to_calendarevent'),
				'link'	=> $this->routing->build('editcalendarevent').'&hookapp=clanwars&hookid='.$intFightusID,
				'icon'	=> 'fa-calendar',
				'perm'	=> true
			),
		);
		
		// Output the form, pass values in
		$this->form->output($arrData);
  
		$this->tpl->assign_vars(array(
			'FIGHTUS_NAME'	=> $strFightusName,
			'FIGHTUSID'		=> $intFightusID,
			'MENU_OPTIONS'	=> ($intFightusID) ? $this->jquery->DropDownMenu('colortab', $optionsmenu, '<i class="fa fa-cog fa-lg"></i> '.$this->user->lang('actions')) : '',
		));
  
		$this->core->set_vars(array(
			'page_title'        => $this->user->lang('cw_manage_fightus').': '.$strFightusName,
			'template_path'     => $this->pm->get_data('clanwars', 'template_path'),
			'template_file'     => 'admin/manage_fightus_edit.html',
			'display'           => true,)
		);
  }


  public function display($messages = false) {
		if($messages && count($messages)){
			$this->pdh->process_hook_queue();
			$this->core->messages($messages);
		}
		
		$intLimit = 50;
		
		$arrViewList = $this->pdh->get('clanwars_fightus', 'id_list', array());
		
		$hptt_page_settings = array(
			'name' => 'hptt_clanwars_fightus',
			'table_main_sub' => '%intFightusID%',
			'table_subs' => array('%intFightusID%', '%link_url%', '%link_url_suffix%'),
			'page_ref' => 'admin/manage_fightus.php',
			'show_numbers' => false,
			'show_select_boxes' => true,
			'show_detail_twink' => false,
			'table_sort_col' => 1,
			'table_sort_dir' => 'desc',
			'selectboxes_checkall'	=> true,
			'table_presets' => array(
				array('name' => 'clanwars_fightus_actions', 'sort' => false, 'th_add' => 'width="20"', 'td_add' => 'nowrap="nowrap"'),
				array('name' => 'clanwars_fightus_date', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_fightus_clanname', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_fightus_country', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_fightus_gameID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_fightus_teamID', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_fightus_status', 'sort' => true, 'th_add' => '', 'td_add' => ''),		
			),
		
		);
		$hptt = $this->get_hptt($hptt_page_settings, $arrViewList, $arrViewList, array('%link_url%' => 'manage_fightus.php', '%link_url_suffix%' => '&amp;upd=true'));
		$strPageSuffix = '&amp;start='.$this->in->get('start', 0);
		$strSortSuffix = '?sort='.$this->in->get('sort');
	
	
		$intFightusCount = count($arrViewList);
	
		$strFooterText = sprintf($this->user->lang('hptt_default_part_footcount'), $intFightusCount, $intLimit);
	
		$this->confirm_delete($this->user->lang('cw_confirm_delete_fightus'));
  
		$this->tpl->assign_vars(array(
			'FIGHTUS_LIST' 		=> $hptt->get_html_table($this->in->get('sort'), $strPageSuffix, $this->in->get('start', 0), $intLimit, $strFooterText),
			'PAGINATION' 		=> generate_pagination('manage_fightus.php'.$strSortSuffix, $intFightusCount, $intLimit, $this->in->get('start', 0)),
			'HPTT_COLUMN_COUNT'	=> $hptt->get_column_count())
		);
   
		$this->core->set_vars(array(
			'page_title'        => $this->user->lang('cw_manage_fightus'),
			'template_path'     => $this->pm->get_data('clanwars', 'template_path'),
			'template_file'     => 'admin/manage_fightus.html',
			'display'           => true,)
		);
  }
}

registry::register('clanwarsManageFightus');
?>