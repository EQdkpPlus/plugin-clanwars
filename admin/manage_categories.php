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

class clanwarsManageCategories extends page_generic
{
  /**
   * __dependencies
   * Get module dependencies
   */
  public static function __shortcuts()
  {
	$shortcuts = array('form' => array('form', array('manage_categories')));
    return array_merge(parent::$shortcuts, $shortcuts);
  }


  public function __construct()
  {
    // plugin installed?
    if (!$this->pm->check('clanwars', PLUGIN_INSTALLED))
      message_die($this->user->lang('cw_not_installed'));
	  
	$this->user->check_auth('a_clanwars_manage_categories');  

    $handler = array(
      'save' => array('process' => 'save', 'csrf' => true),
	  'upd'	=> array('process' => 'update', 'csrf'=>false),
    );
    parent::__construct(null, $handler, array('clanwars_categories', 'name'), null, 'selected_ids[]');

    $this->process();
  }

  public function delete(){
	$messages = array();
	if(count($this->in->getArray('selected_ids', 'int')) > 0) {
		$arrResult = array();
		foreach($this->in->getArray('selected_ids', 'int') as $intCategoryID){
			$arrResult[] = $this->pdh->put('clanwars_categories', 'delete_category', array($intCategoryID));
		}
		
		if (in_array(false, $arrResult)){
			$messages[] = array('title' => $this->user->lang('del_no_suc'), 'text' => $this->user->lang('cw_delete_categories_no_suc'), 'color' => 'red');
		} else {
			$messages[] = array('title' => $this->user->lang('del_suc'), 'text' => $this->user->lang('cw_delete_categories_suc'), 'color' => 'green');
		}
	}
	$this->display($messages);
  }
  
  private function fields(){
  
	$arrOwnTeams = $this->pdh->aget('clanwars_teams', 'name', 0, array($this->pdh->sort($this->pdh->get('clanwars_teams', 'id_list'), 'clanwars_teams', 'name', 'asc')));
	$arrOwnTeams[0] = "";
	natcasesort($arrOwnTeams);
	$arrGames = $this->pdh->aget('clanwars_games', 'name', 0, array($this->pdh->get('clanwars_games', 'id_list', array(true))));
	$arrGames[0] = "";
	natcasesort($arrGames);
	$arrUser = $this->pdh->aget('user', 'name', 0, array($this->pdh->sort($this->pdh->get('user', 'id_list'), 'user', 'name', 'asc')));
	$arrUser[0] = "";
	natcasesort($arrUser);
	
	return array(
		'name' => array(
			'type'		=> 'text',
			'size'		=> 40,
		),
		'icon' => array(
			'type'			=> 'imageuploader',
			'imgpath'		=> $this->pfh->FolderPath('category_icons', 'clanwars'),
			'returnFormat'	=> 'in_data',
			'storageFolder' => $this->pfh->FolderPath('category_icons', 'clanwars', 'absolute'),
		),
		'text' => array(
			'type'		=> 'textarea',
			'cols'		=> 40,
		),		
		'website' => array(
			'type'		=> 'text',
			'size'		=> 40,
		),
	);
  }

  public function save() {
		$this->form->add_fields($this->fields());
		$arrSave = $this->form->return_values();
		
		$intCategoryID = $this->in->get('c', 0);
		
		$arrResult = false;
		if ($intCategoryID){
			$arrResult = $this->pdh->put('clanwars_categories', 'update_category', array($intCategoryID, $arrSave));
		} else {
			$arrResult = $this->pdh->put('clanwars_categories', 'add_category', array($arrSave));
		}
		
		$messages = array();
		if ($arrResult){
			$messages[] = array('title' => $this->user->lang('save_suc'), 'text' => $this->user->lang('cw_save_categories_suc'), 'color' => 'green');
		} else {
			$messages[] = array('title' => $this->user->lang('save_nosuc'), 'text' => $this->user->lang('cw_save_categories_no_suc'), 'color' => 'red');
		}
		
		$this->display($messages);
  }
  
  public function update(){
		$intCategoryID = $this->in->get('c', 0);
		$strCategoryName = ($intCategoryID) ? $this->pdh->get('clanwars_categories', 'name', array($intCategoryID)) : $this->user->lang('cw_add_category');
  
		// initialize form class
		$this->form->lang_prefix = 'cw_categories_';
		$this->form->use_tabs = false;
		$this->form->use_fieldsets = false;
		
		$this->form->add_fields($this->fields());
		
		if ($intCategoryID){
			$arrData = $this->pdh->get('clanwars_categories', 'data', array($intCategoryID));
		} else $arrData = array();
		
		// Output the form, pass values in
		$this->form->output($arrData);
  
		$this->tpl->assign_vars(array(
			'CATEGORY_NAME'		=> $strCategoryName,
			'CATEGORYID'		=> $intCategoryID,
		));
  
		$this->core->set_vars(array(
			'page_title'        => $this->user->lang('cw_manage_categories').': '.$strCategoryName,
			'template_path'     => $this->pm->get_data('clanwars', 'template_path'),
			'template_file'     => 'admin/manage_categories_edit.html',
			'display'           => true,)
		);
  }


  public function display($messages = false) {
		if($messages && count($messages)){
			$this->pdh->process_hook_queue();
			$this->core->messages($messages);
		}
		
		$intLimit = 50;
		
		$arrViewList = $this->pdh->get('clanwars_categories', 'id_list', array());
		
		$hptt_page_settings = array(
			'name' => 'hptt_clanwars_categories',
			'table_main_sub' => '%intCategoryID%',
			'table_subs' => array('%intCategoryID%', '%link_url%', '%link_url_suffix%'),
			'page_ref' => 'admin/manage_categories.php',
			'show_numbers' => false,
			'show_select_boxes' => true,
			'show_detail_twink' => false,
			'table_sort_col' => 1,
			'table_sort_dir' => 'desc',
			'selectboxes_checkall'	=> true,
			'table_presets' => array(
				array('name' => 'clanwars_categories_actions', 'sort' => false, 'th_add' => 'width="20"', 'td_add' => ''),
				array('name' => 'clanwars_categories_name', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_categories_icon', 'sort' => true, 'th_add' => '', 'td_add' => ''),
				array('name' => 'clanwars_categories_website', 'sort' => true, 'th_add' => '', 'td_add' => ''),
			),
		
		);
		$hptt = $this->get_hptt($hptt_page_settings, $arrViewList, $arrViewList, array('%link_url%' => 'manage_categories.php', '%link_url_suffix%' => '&amp;upd=true'));
		$strPageSuffix = '&amp;start='.$this->in->get('start', 0);
		$strSortSuffix = '?sort='.$this->in->get('sort');
	
	
		$intCategoryCount = count($arrViewList);
	
		$strFooterText = sprintf($this->user->lang('hptt_default_part_footcount'), $intCategoryCount, $intLimit);
	
		$this->confirm_delete($this->user->lang('cw_confirm_delete_categories'));
  
		$this->tpl->assign_vars(array(
			'CATEGORY_LIST' 	=> $hptt->get_html_table($this->in->get('sort'), $strPageSuffix, $this->in->get('start', 0), $intLimit, $strFooterText),
			'PAGINATION' 		=> generate_pagination('manage_categories.php'.$strSortSuffix, $intCategoryCount, $intLimit, $this->in->get('start', 0)),
			'HPTT_COLUMN_COUNT'	=> $hptt->get_column_count())
		);
   
		$this->core->set_vars(array(
			'page_title'        => $this->user->lang('cw_manage_categories'),
			'template_path'     => $this->pm->get_data('clanwars', 'template_path'),
			'template_file'     => 'admin/manage_categories.html',
			'display'           => true,)
		);
  }
}

registry::register('clanwarsManageCategories');
?>