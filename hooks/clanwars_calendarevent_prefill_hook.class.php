<?php
/*
 * Project:     EQdkp guildrequest
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2012-11-11 18:36:16 +0100 (So, 11. Nov 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: godmod $
 * @copyright   2008-2011 Aderyn
 * @link        http://eqdkp-plus.com
 * @package     guildrequest
 * @version     $Rev: 12434 $
 *
 * $Id: clanwars_calendarevent_prefill_hook.class.php 12434 2012-11-11 17:36:16Z godmod $
 */

if (!defined('EQDKP_INC'))
{
  header('HTTP/1.0 404 Not Found');exit;
}


/*+----------------------------------------------------------------------------
  | clanwars_calendarevent_prefill_hook
  +--------------------------------------------------------------------------*/
if (!class_exists('clanwars_calendarevent_prefill_hook'))
{
  class clanwars_calendarevent_prefill_hook extends gen_class
  {
    /* List of dependencies */
    public static $shortcuts = array();

	/**
    * comments_save
    * Do the hook 'comments_save'
    *
    * @return array
    */
	public function calendarevent_prefill($data)
	{
		if ($data['hookapp'] != 'clanwars') return $data;

		$data['calendarevent'] = array(
			'date'	=> $this->time->time,
			'note'	=> 'Testnote',
		);
		
		return $data;
	}
  }
}
?>