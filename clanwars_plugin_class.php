<?php
/*
 * Project:     EQdkp clanwars
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2012-11-11 13:32:45 +0100 (So, 11. Nov 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: godmod $
 * @copyright   2008-2011 Aderyn
 * @link        http://eqdkp-plus.com
 * @package     clanwars
 * @version     $Rev: 12426 $
 *
 * $Id: clanwars_plugin_class.php 12426 2012-11-11 12:32:45Z godmod $
 */

if (!defined('EQDKP_INC'))
{
  header('HTTP/1.0 404 Not Found');
  exit;
}


/*+----------------------------------------------------------------------------
  | clanwars
  +--------------------------------------------------------------------------*/
class clanwars extends plugin_generic
{

  public $version    = '0.1.0';
  public $build      = '';
  public $copyright  = 'GodMod';
  public $vstatus    = 'Beta';
  
  protected static $apiLevel = 20;

  /**
    * Constructor
    * Initialize all informations for installing/uninstalling plugin
    */
  public function __construct()
  {
    parent::__construct();

    $this->add_data(array (
      'name'              => 'ClanWars',
      'code'              => 'clanwars',
      'path'              => 'clanwars',
      'template_path'     => 'plugins/clanwars/templates/',
      'icon'              => 'fa fa-crosshairs',
      'version'           => $this->version,
      'author'            => $this->copyright,
      'description'       => $this->user->lang('clanwars_short_desc'),
      'long_description'  => $this->user->lang('clanwars_long_desc'),
      'homepage'          => EQDKP_PROJECT_URL,
      'manuallink'        => false,
      'plus_version'      => '2.0',
      'build'             => $this->build,
    ));

    $this->add_dependency(array(
      'plus_version'      => '2.0'
    ));

    // -- Register our permissions ------------------------
    // permissions: 'a'=admins, 'u'=user
    // ('a'/'u', Permission-Name, Enable? 'Y'/'N', Language string, array of user-group-ids that should have this permission)
    // Groups: 1 = Guests, 2 = Super-Admin, 3 = Admin, 4 = Member
	$this->add_permission('u', 'add_fightus',    'Y', $this->user->lang('cw_add_fightus'),    array(1,2,3,4)); //Guests
	
    $this->add_permission('a', 'manage_games', 'N', $this->user->lang('cw_manage_games'), array(2,3));
	$this->add_permission('a', 'manage_teams', 'N', $this->user->lang('cw_manage_teams'), array(2,3));
	$this->add_permission('a', 'manage_clans', 'N', $this->user->lang('cw_manage_clans'), array(2,3));
	$this->add_permission('a', 'manage_awards', 'N', $this->user->lang('cw_manage_awards'), array(2,3));
	$this->add_permission('a', 'manage_categories', 'N', $this->user->lang('cw_manage_categories'), array(2,3));
	$this->add_permission('a', 'manage_wars', 'N', $this->user->lang('cw_manage_wars'), array(2,3));
	$this->add_permission('a', 'manage_fightus', 'N', $this->user->lang('cw_manage_fightus'), array(2,3));

    // -- PDH Modules -------------------------------------
    $this->add_pdh_read_module('clanwars_awards');
	$this->add_pdh_read_module('clanwars_categories');
	$this->add_pdh_read_module('clanwars_clans');
	$this->add_pdh_read_module('clanwars_fightus');
	$this->add_pdh_read_module('clanwars_games');
	$this->add_pdh_read_module('clanwars_teams');
	$this->add_pdh_read_module('clanwars_wars');
	
	
    $this->add_pdh_write_module('clanwars_awards');
	$this->add_pdh_write_module('clanwars_categories');
	$this->add_pdh_write_module('clanwars_clans');
	$this->add_pdh_write_module('clanwars_fightus');
	$this->add_pdh_write_module('clanwars_games');
	$this->add_pdh_write_module('clanwars_teams');
	$this->add_pdh_write_module('clanwars_wars');
	
    // -- Hooks -------------------------------------------
    #$this->add_hook('search', 'clanwars_search_hook', 'search');
	$this->add_hook('calendarevent_prefill', 'clanwars_calendarevent_prefill_hook', 'calendarevent_prefill');
    
    //Routing
    $this->routing->addRoute('Clanwars', 'clanwars', 'plugins/clanwars/pageobjects');
	
	#$this->routing->addRoute('WriteApplication', 'addrequest', 'plugins/clanwars/page_objects');
	#$this->routing->addRoute('ListApplications', 'listrequests', 'plugins/clanwars/page_objects');
	#$this->routing->addRoute('ViewApplication', 'viewrequest', 'plugins/clanwars/page_objects');
	
	// -- Menu --------------------------------------------
    $this->add_menu('admin', $this->gen_admin_menu());
	
	#$this->add_menu('main', $this->gen_main_menu());
	#$this->add_menu('settings', $this->usersettings());
  }

  /**
    * gen_admin_menu
    * Generate the Admin Menu
    */
  private function gen_admin_menu()
  {
    $admin_menu = array (array(
        'name' => $this->user->lang('clanwars'),
        'icon' => 'fa fa-crosshairs',
        1 => array (
          'link'  => 'plugins/clanwars/admin/manage_awards.php'.$this->SID,
          'text'  => $this->user->lang('cw_manage_awards'),
          'check' => 'a_clanwars_manage_awards',
          'icon'  => 'fa-trophy'
        ),
		2 => array (
          'link'  => 'plugins/clanwars/admin/manage_categories.php'.$this->SID,
          'text'  => $this->user->lang('cw_manage_categories'),
          'check' => 'a_clanwars_manage_awards',
          'icon'  => 'fa-list'
        ),
		3 => array (
          'link'  => 'plugins/clanwars/admin/manage_clans.php'.$this->SID,
          'text'  => $this->user->lang('cw_manage_clans'),
          'check' => 'a_clanwars_manage_awards',
          'icon'  => 'fa-home'
        ),
		4 => array (
          'link'  => 'plugins/clanwars/admin/manage_fightus.php'.$this->SID,
          'text'  => $this->user->lang('cw_manage_fightus'),
          'check' => 'a_clanwars_manage_fightus',
          'icon'  => 'fa-fire'
        ),
		5 => array (
          'link'  => 'plugins/clanwars/admin/manage_games.php'.$this->SID,
          'text'  => $this->user->lang('cw_manage_games'),
          'check' => 'a_clanwars_manage_games',
          'icon'  => 'fa-gamepad'
        ),
		6 => array (
          'link'  => 'plugins/clanwars/admin/manage_teams.php'.$this->SID,
          'text'  => $this->user->lang('cw_manage_teams'),
          'check' => 'a_clanwars_manage_teams',
          'icon'  => 'fa-group'
        ),
		7 => array (
          'link'  => 'plugins/clanwars/admin/manage_wars.php'.$this->SID,
          'text'  => $this->user->lang('cw_manage_wars'),
          'check' => 'a_clanwars_manage_wars',
          'icon'  => 'fa-crosshairs'
        ),
    ));

    return $admin_menu;
  }
  
    /**
    * pre_install
    * Define Installation
    */
   public function pre_install()
  {
    // include SQL and default configuration data for installation
    include($this->root_path.'plugins/clanwars/includes/sql.php');

    // define installation
    for ($i = 1; $i <= count($clanwarsSQL['install']); $i++)
      $this->add_sql(SQL_INSTALL, $clanwarsSQL['install'][$i]);
	  
	}
	
	
	/**
    * pre_uninstall
    * Define uninstallation
    */
	  public function pre_uninstall()
	  {
		// include SQL data for uninstallation
		include($this->root_path.'plugins/clanwars/includes/sql.php');

		for ($i = 1; $i <= count($clanwarsSQL['uninstall']); $i++)
		  $this->add_sql(SQL_UNINSTALL, $clanwarsSQL['uninstall'][$i]);
	  }
  
   /**
    * gen_admin_menu
    * Generate the Admin Menu
    */
  private function gen_main_menu()
  {

	$main_menu = array(
        1 => array (
          'link'  => $this->routing->build('WriteApplication', false, false, true, true),
          'text'  => $this->user->lang('gr_add'),
          'check' => 'u_clanwars_add',
		  'signedin' => 0,
        ),
		2 => array (
          'link'  => $this->routing->build('ListApplications', false, false, true, true),
          'text'  => $this->user->lang('gr_view'),
          'check' => 'u_clanwars_view',
        ),
    );

    return $main_menu;
  }
  
  private function usersettings(){
	$settings = array(
		'clanwars' => array(
			'icon' => 'fa fa-pencil-square-o',
		
		'gr_send_notification_mails'	=> array(
			'fieldtype'	=> 'checkbox',
			'default'	=> 0,
			'name'		=> 'gr_send_notification_mails',
			'language'	=> 'gr_send_notification_mails',
		),
		
		'gr_jgrowl_notifications'	=> array(
			'fieldtype'	=> 'checkbox',
			'default'	=> 0,
			'name'		=> 'gr_jgrowl_notifications',
			'language'	=> 'gr_jgrowl_notifications',
		)),
	);
	return $settings;
  }

}
?>
