<?php
/**
 * @version	1.5
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Calendar::load( 'CalendarTable', 'tables._base' );

class CalendarTableSecondCategories extends CalendarTable
{
	/**
	 * Constructs the object
	 * @param $db
	 * @return unknown_type
	 */
	function CalendarTableSecondCategories( &$db )
	{
		$tbl_key = 'category_id';
		$tbl_suffix = 'secondcategories';
		$this->set( '_suffix', $tbl_suffix );
		$name = 'calendar';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );
	}
	
	/**
	 * Checks the integrity of the object before a save 
	 * @return unknown_type
	 */
	function check( )
	{
		$db = $this->getDBO( );
		$nullDate = $db->getNullDate( );
		if ( empty( $this->created_date ) || $this->created_date == $nullDate )
		{
			$date = JFactory::getDate( );
			$this->created_date = $date->toMysql( );
		}
		if ( empty( $this->modified_date ) || $this->modified_date == $nullDate )
		{
			$date = JFactory::getDate( );
			$this->modified_date = $date->toMysql( );
		}
		$this->filterHTML( 'category_name' );
		if ( empty( $this->category_name ) )
		{
			$this->setError( JText::_( "Name Required" ) );
			return false;
		}
		
		if ( !empty( $this->category_name ) && empty($this->category_id))
		{
		    $key = strtolower( $this->category_name );
		    $query = "SELECT * FROM #__calendar_categories WHERE LOWER( category_name ) = '$key';";
		    $db = $this->getDBO();
		    $db->setQuery( $query );
		    $result = $db->loadResult();
		    if ($result)
		    {
    			$this->setError( JText::_( "Name Must Be Unique" ) );
    			return false;		        
		    }
		}
		
		jimport( 'joomla.filter.output' );
		if ( empty( $this->category_alias ) )
		{
			$this->category_alias = $this->category_name;
		}
		$this->category_alias = JFilterOutput::stringURLSafe( $this->category_alias );
		
		return true;
	}
	
	/**
	 * Stores the object
	 * @param object
	 * @return boolean
	 */
	function store( )
	{
		$date = JFactory::getDate( );
		$this->modified_date = $date->toMysql( );
		$store = parent::store( );
		return $store;
	}
	
	function getImage( $type = 'thumb', $url = false )
	{
		$image = $this->category_full_image;
		Calendar::load( 'CalendarHelperImage', 'helpers.image' );
		$image_html = CalendarHelperImage::getImage( 'categories', $image, $this->category_name, $type, $url );
		
		return $image_html;
	}
}
