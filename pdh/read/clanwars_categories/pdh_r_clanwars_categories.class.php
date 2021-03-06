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
				
if ( !class_exists( "pdh_r_clanwars_categories" ) ) {
	class pdh_r_clanwars_categories extends pdh_r_generic{
	
	public $default_lang = 'english';
	public $clanwars_categories = null;

	public $hooks = array(
		'clanwars_categories_update',
	);		
			
	public $presets = array(
		'clanwars_categories_id' => array('id', array('%intCategoryID%'), array()),
		'clanwars_categories_name' => array('name', array('%intCategoryID%'), array()),
		'clanwars_categories_website' => array('website', array('%intCategoryID%'), array()),
		'clanwars_categories_icon' => array('icon', array('%intCategoryID%'), array()),
		'clanwars_categories_text' => array('text', array('%intCategoryID%'), array()),
		'clanwars_categories_actions' => array('actions', array('%intCategoryID%', '%link_url%', '%link_url_suffix%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_clanwars_categories_table');
			
			$this->clanwars_categories = NULL;
	}
					
	public function init(){
			$this->clanwars_categories	= $this->pdc->get('pdh_clanwars_categories_table');				
					
			if($this->clanwars_categories !== NULL){
				return true;
			}		

			$objQuery = $this->db->query('SELECT * FROM __clanwars_categories');
			if($objQuery){
				while($drow = $objQuery->fetchAssoc()){
					$this->clanwars_categories[(int)$drow['id']] = array(
						'id'			=> (int)$drow['id'],
						'name'			=> $drow['name'],
						'website'		=> $drow['website'],
						'icon'			=> $drow['icon'],
						'text'			=> $drow['text'],
					);
				}
				
				$this->pdc->put('pdh_clanwars_categories_table', $this->clanwars_categories, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list(){
			if ($this->clanwars_categories === null) return array();
			return array_keys($this->clanwars_categories);
		}
		
		/**
		 * Get all data of Element with $strID
		 * @return multitype: Array with all data
		 */				
		public function get_data($intCategoryID){
			if (isset($this->clanwars_categories[$intCategoryID])){
				return $this->clanwars_categories[$intCategoryID];
			}
			return false;
		}
		
		public function get_actions($intCategoryID, $baseurl, $url_suffix=''){
			return "<a href='".$baseurl.$this->SID.'&amp;c='.$intCategoryID.$url_suffix."'>
				<i class='fa fa-pencil fa-lg' title='".$this->user->lang('edit')."'></i>
			</a>";
		}
				
		/**
		 * Returns id for $intCategoryID				
		 * @param integer $intCategoryID
		 * @return multitype id
		 */
		 public function get_id($intCategoryID){
			if (isset($this->clanwars_categories[$intCategoryID])){
				return $this->clanwars_categories[$intCategoryID]['id'];
			}
			return false;
		}

		/**
		 * Returns name for $intCategoryID				
		 * @param integer $intCategoryID
		 * @return multitype name
		 */
		 public function get_name($intCategoryID){
			if (isset($this->clanwars_categories[$intCategoryID])){
				return $this->clanwars_categories[$intCategoryID]['name'];
			}
			return false;
		}

		/**
		 * Returns website for $intCategoryID				
		 * @param integer $intCategoryID
		 * @return multitype website
		 */
		public function get_website($intCategoryID){
			if (isset($this->clanwars_categories[$intCategoryID])){
				return $this->clanwars_categories[$intCategoryID]['website'];
			}
			return false;
		}
		
		public function get_html_website($intCategoryID){
			$strWebsite = $this->get_website($intCategoryID);
			if ($strWebsite && strlen($strWebsite)) return '<a href="'.$strWebsite.'" target="_blank">'.$strWebsite.'</a>';
			return '';
		}

		/**
		 * Returns icon for $intCategoryID				
		 * @param integer $intCategoryID
		 * @return multitype icon
		 */
		 public function get_icon($intCategoryID){
			if (isset($this->clanwars_categories[$intCategoryID])){
				return $this->clanwars_categories[$intCategoryID]['icon'];
			}
			return false;
		}
		
		public function get_html_icon($intCategoryID, $intSize=32){
			$strIcon = $this->get_icon($intCategoryID);
			if ($strIcon && strlen($strIcon)){
				$strExtension = pathinfo($strIcon, PATHINFO_EXTENSION);
				$strIconName = md5('cat_'.$intCategoryID.$strIcon).'_'.intval($intSize).'.'.$strExtension;
				$strThumbnailIcon = $this->pfh->FolderPath('thumbnails', 'clanwars').$strIconName;
				if (is_file($strThumbnailIcon)){
					return '<img src="'.$this->pfh->FolderPath('thumbnails', 'clanwars', 'absolute').$strIconName.'" alt="'.$this->get_name($intCategoryID).'"/>';
				} else {
					$strFullImage = $this->pfh->FolderPath('category_icons', 'clanwars').$strIcon;
					$this->pfh->thumbnail($strFullImage, $this->pfh->FolderPath('thumbnails', 'clanwars'), $strIconName, intval($intSize));
					return '<img src="'.$this->pfh->FolderPath('thumbnails', 'clanwars', 'absolute').$strIconName.'" alt="'.$this->get_name($intCategoryID).'"/>';
				}
			}
			
			return '';
		}
		
		

		/**
		 * Returns text for $intCategoryID				
		 * @param integer $intCategoryID
		 * @return multitype text
		 */
		 public function get_text($intCategoryID){
			if (isset($this->clanwars_categories[$intCategoryID])){
				return $this->clanwars_categories[$intCategoryID]['text'];
			}
			return false;
		}

	}//end class
}//end if
?>