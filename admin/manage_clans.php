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

class clanwarsManageClans extends page_generic
{
  /**
   * __dependencies
   * Get module dependencies
   */
  public static function __shortcuts()
  {
    $shortcuts = array('form' => array('form', array('manage_clans')));
    return array_merge(parent::$shortcuts, $shortcuts);
  }


  public function __construct()
  {
    // plugin installed?
    if (!$this->pm->check('clanwars', PLUGIN_INSTALLED))
      message_die($this->user->lang('cw_not_installed'));
	  
	$this->user->check_auth('a_clanwars_manage_clans');  

    $handler = array(
      'save' => array('process' => 'save', 'csrf' => true),
	  'upd'	=> array('process' => 'update', 'csrf'=>false),
    );
    parent::__construct(null, $handler, array('clanwars_clans', 'name'), null, 'selected_ids[]');

    $this->process();
  }

  public function delete(){
	$messages = array();
	if(count($this->in->getArray('selected_ids', 'int')) > 0) {
		$arrResult = array();
		foreach($this->in->getArray('selected_ids', 'int') as $intClanID){
			$arrResult[] = $this->pdh->put('clanwars_clans', 'delete_clan', array($intClanID));
		}
		
		if (in_array(false, $arrResult)){
			$messages[] = array('title' => $this->user->lang('del_no_suc'), 'text' => $this->user->lang('cw_delete_clans_no_suc'), 'color' => 'red');
		} else {
			$messages[] = array('title' => $this->user->lang('del_suc'), 'text' => $this->user->lang('cw_delete_clans_suc'), 'color' => 'green');
		}
	}
	$this->display($messages);
  }
  
  private function fields(){
  
		$cfile = $this->root_path.'core/country_states.php';
		if (file_exists($cfile)){
			include($cfile);
		}
	
	return array(
		'name' => array(
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
		'tag' => array(
			'type'		=> 'text',
			'size'		=> 20,
		),
		'tag_position' => array(
			'type'		=> 'dropdown',
			'options'	=> $this->user->lang('cw_tag_positions'),
		),
		'icon' => array(
			'type'			=> 'imageuploader',
			'imgpath'		=> $this->pfh->FolderPath('clan_icons', 'clanwars'),
			'returnFormat'	=> 'in_data',
			'storageFolder' => $this->pfh->FolderPath('clan_icons', 'clanwars', 'absolute'),
		),
		'website' => array(
			'type'		=> 'text',
			'size'		=> 40,
		),
		'estdate' => array(
			'type'		=> 'datepicker',
			'year_range'	=> '-80:+1',
			'change_fields' => true,
		),
		'own_clan' => array(
			'type'		=> 'radio',
			'options'	=> array (
				'1'   => $this->user->lang('yes'),
				'0'   => $this->user->lang('no'),			
			)
		),
	);
  }

  public function save() {
		$this->form->add_fields($this->fields());
		$arrSave = $this->form->return_values();
		
		$intClanID = $this->in->get('a', 0);
		
		$arrResult = false;
		if ($intClanID){
			$arrResult = $this->pdh->put('clanwars_clans', 'update_clan', array($intClanID, $arrSave));
		} else {
			$arrResult = $this->pdh->put('clanwars_clans', 'add_clan', array($arrSave));
		}
		
		$messages = array();
		if ($arrResult){
			$messages[] = array('title' => $this->user->lang('save_suc'), 'text' => $this->user->lang('cw_save_clans_suc'), 'color' => 'green');
		} else {
			$messages[] = array('title' => $this->user->lang('save_nosuc'), 'text' => $this->user->lang('cw_save_clans_no_suc'), 'color' => 'red');
		}
		
		$this->display($messages);
  }
  
  public function update(){
		$intClanID = $this->in->get('c', 0);
		$strClanName = ($intClanID) ? $this->pdh->get('clanwars_clans', 'name', array($intClanID)) : $this->user->lang('cw_add_clan');
  
		// initialize form class
		$this->form->lang_prefix = 'cw_clans_';
		$this->form->use_tabs = false;
		$this->form->use_fieldsets = false;
		
		$this->form->add_fields($this->fields());
		
		if ($intClanID){
			$arrData = $this->pdh->get('clanwars_clans', 'data', array($intClanID));
		} elseif($this->in->get('convert_fightus', 0)) {
			$intFightusID = $this->in->get('convert_fightus', 0);
		
			$arrData = array(
				'website' => $this->pdh->get('clanwars_fightus', 'website', array($intFightusID)),
				'name' => $this->pdh->get('clanwars_fightus', 'clanname', array($intFightusID)),
				'shortname' => $this->pdh->get('clanwars_fightus', 'shortname', array($intFightusID)),
				'country' => $this->pdh->get('clanwars_fightus', 'country', array($intFightusID)),
				'website' => $this->pdh->get('clanwars_fightus', 'website', array($intFightusID)),
			);
			
		} else $arrData = array();
		
		// Output the form, pass values in
		$this->form->output($arrData);
  
		$this->tpl->assign_vars(array(
			'CLAN_NAME'		=> $strClanName,
			'CLANID'		=> $intClanID,
		));
  
		$this->core->set_vars(array(
			'page_title'        => $this->user->lang('cw_manage_clans').': '.$strClanName,
			'template_path'     => $this->pm->get_data('clanwars', 'template_path'),
			'template_file'     => 'admin/manage_clans_edit.html',
			'display'           => true,)
		);
  }


  public function display($messages = false) {
		if($messages && count($messages)){
			$this->pdh->process_hook_queue();
			$this->core->messages($messages);
		}
		
		$intLimit = 50;
		
		$arrViewList = $this->pdh->get('clanwars_clans', 'id_list', array());
		
		$hptt_page_settings = array(
			'name' => 'hptt_clanwars_clans',
			'table_main_sub' => '%intClanID%',
			'table_subs' => array('%intClanID%', '%link_url%', '%link_url_suffix%'),
			'page_ref' => 'admin/manage_clans.php',
			'show_numbers' => false,
			'show_select_boxes' => true,
			'show_detail_twink' => false,
			'table_sort_col' => 1,
			'table_sort_dir' => 'desc',
			'selectboxes_checkall'	=> true,
			'table_presets' => array(
				array('name' => 'clanwars_clans_actions', 'sort' => false, 'th_add' => 'width="20"', 'td_add' => ''),
				array('name' => 'clanwars_clans_name', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_clans_shortname', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_clans_country_ext', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_clans_icon', 'sort' => true, 'th_add' => '', 'td_add' => ''),
			),
		
		);
		$hptt = $this->get_hptt($hptt_page_settings, $arrViewList, $arrViewList, array('%link_url%' => 'manage_clans.php', '%link_url_suffix%' => '&amp;upd=true'));
		$strPageSuffix = '&amp;start='.$this->in->get('start', 0);
		$strSortSuffix = '?sort='.$this->in->get('sort');
	
	
		$intClanCount = count($arrViewList);
	
		$strFooterText = sprintf($this->user->lang('hptt_default_part_footcount'), $intClanCount, $intLimit);
	
		$this->confirm_delete($this->user->lang('cw_confirm_delete_clans'));
  
		$this->tpl->assign_vars(array(
			'CLAN_LIST' 		=> $hptt->get_html_table($this->in->get('sort'), $strPageSuffix, $this->in->get('start', 0), $intLimit, $strFooterText),
			'PAGINATION' 		=> generate_pagination('manage_clans.php'.$strSortSuffix, $intClanCount, $intLimit, $this->in->get('start', 0)),
			'HPTT_COLUMN_COUNT'	=> $hptt->get_column_count())
		);
   
		$this->core->set_vars(array(
			'page_title'        => $this->user->lang('cw_manage_clans'),
			'template_path'     => $this->pm->get_data('clanwars', 'template_path'),
			'template_file'     => 'admin/manage_clans.html',
			'display'           => true,)
		);
  }
}

registry::register('clanwarsManageClans');
?>