<?php
/**
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Calendar::load( 'CalendarTable', 'tables._base' );

class CalendarTableSeries extends CalendarTable
{
	/**
	 * Could this be abstracted into the base?
	 * 
	 * @param $db
	 * @return unknown_type
	 */
	function CalendarTableSeries( &$db )
	{
		$tbl_key = 'series_id';
		$tbl_suffix = 'series';
		$this->set( '_suffix', $tbl_suffix );
		$name = "calendar";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );
	}
	
	function check( )
	{
		if ( empty( $this->series_name ) )
		{
			$this->setError( JText::_( "Series Name Required" ) );
			return false;
		}
		
		if ( !empty( $this->series_name ) && empty($this->series_id))
		{
		    $key = strtolower( $this->series_name );
		    $query = "SELECT * FROM #__calendar_series WHERE LOWER( series_name ) = '$key';";
		    $db = $this->getDBO();
		    $db->setQuery( $query );
		    $result = $db->loadResult();
		    if ($result)
		    {
    			$this->setError( JText::_( "Name Must Be Unique" ) );
    			return false;		        
		    }
		}
		
		if ( empty( $this->series_title ) )
		{
			$this->series_title = $this->series_name;
		}
		
		if ( empty( $this->series_full_image ) )
		{
			$this->setError( JText::_( "Series Image Required" ) );
			return false;
		}
		
		$nullDate = $this->_db->getNullDate( );
		if ( empty( $this->series_created_date ) || $this->series_created_date == $nullDate )
		{
			$date = JFactory::getDate( );
			$this->series_created_date = $date->toMysql( );
		}
		
		return true;
	}
	
	/**
	 * Stores the object
	 * @param object
	 * @return boolean
	 */
	function store( $updateNulls=false )
	{
		$date = JFactory::getDate( );
		$this->series_modified_date = $date->toMysql( );
		$store = parent::store( $updateNulls );
		return $store;
	}
	
	function getImage( $type = 'thumb', $url = false )
	{
		$image = $this->series_full_image;
		Calendar::load( 'CalendarHelperImage', 'helpers.image' );
		$image_html = CalendarHelperImage::getImage( 'series', $image, $this->series_name, $type, $url );
		
		return $image_html;
	}
}
