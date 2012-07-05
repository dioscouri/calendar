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

class CalendarTableDailyevents extends CalendarTable
{
	/**
	 * Could this be abstracted into the base?
	 * 
	 * @param $db
	 * @return unknown_type
	 */
	function CalendarTableDailyevents( &$db )
	{
		$tbl_key = 'dailyevent_id';
		$tbl_suffix = 'dailyevents';
		$this->set( '_suffix', $tbl_suffix );
		$name = "calendar";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );
	}
	
	function check( )
	{
		// check name
		$this->filterHTML( 'dailyevent_name' );
		if ( empty( $this->dailyevent_name ) )
		{
			$this->setError( JText::_( "Dailyevent Name Required" ) );
			return false;
		}
		
		// check name unique 
		/*if ( !empty( $this->dailyevent_name ) && empty($this->dailyevent_id))
		{
		    $key = strtolower( $this->dailyevent_name );
		    $query = "SELECT * FROM #__calendar_dailyevents WHERE LOWER( dailyevent_name ) = '$key';";
		    $db = $this->getDBO();
		    $db->setQuery( $query );
		    $result = $db->loadResult();
		    if ($result)
		    {
    			$this->setError( JText::_( "Dailyevent Name Must Be Unique" ) );
    			return false;		        
		    }
		}*/
		
		// check or make alias
		jimport( 'joomla.filter.output' );
		if ( empty( $this->dailyevent_alias ) )
		{
			$this->dailyevent_alias = $this->dailyevent_name;
		}
		$this->dailyevent_alias = JFilterOutput::stringURLSafe( $this->dailyevent_alias );
	
		if ( empty( $this->dailyevent_date ) )
		{
			$this->setError( JText::_( "Date Required" ) );
			return false;
		}
		
		if ( empty( $this->dailyevent_start_time ) )
		{
			$this->setError( JText::_( "Start Time Required" ) );
			return false;
		}
		
		if ( empty( $this->dailyevent_end_time ) )
		{
			$this->setError( JText::_( "End Time Required" ) );
			return false;
		}
		
		
		$start = explode( ':', $this->dailyevent_start_time );
		$end = explode( ':', $this->dailyevent_end_time );		
		$start = mktime( $start[0], $start[1] );
		$end = mktime( $end[0], $end[1] );
		
		if($end < $start)
		{
			$this->setError( JText::_( "Start Time must be lesser than end time" ) );
			return false;
		}
		
		if ( empty( $this->venue_id ) )
		{
			$this->setError( JText::_( "Venue Required" ) );
			return false;
		}
		
		// check and make created/modified datetime
		$nullDate = $this->_db->getNullDate( );
		if ( empty( $this->dailyevent_created_date ) || $this->dailyevent_created_date == $nullDate )
		{
			$date = JFactory::getDate( );
			$this->dailyevent_created_date = $date->toMysql( );
		}
		if ( empty( $this->dailyevent_modified_date ) || $this->dailyevent_modified_date == $nullDate )
		{
			$date = JFactory::getDate( );
			$this->dailyevent_modified_date = $date->toMysql( );
		}
		
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
		$this->dailyevent_modified_date = $date->toMysql( );
		$store = parent::store( );
		return $store;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $type
	 * @param unknown_type $url
	 * @return return_type
	 */
	function getImage( $type = 'thumb', $url = false )
	{
	    
	    switch($type)
	    {
	        case "src":
	            jimport( 'joomla.filesystem.file' );
	            $return = ( JFile::exists( Calendar::getPath( 'dailyevents_images' ) . DS . $this->dailyevent_full_image ) ) ? Calendar::getUrl( 'dailyevents_images' ) . $this->dailyevent_full_image : Calendar::getUrl( 'images' ) . 'noimage.png';
	            break;
	        default:
        		$image = $this->dailyevent_full_image;
        		Calendar::load( 'CalendarHelperImage', 'helpers.image' );
        		$return = CalendarHelperImage::getImage( 'dailyevents', $image, $this->dailyevent_name, $type, $url );
	            break;
	    }
		
		return $return;
	}
}
