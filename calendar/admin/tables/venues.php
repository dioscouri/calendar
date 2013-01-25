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

class CalendarTableVenues extends CalendarTable
{
	/**
	 * Could this be abstracted into the base?
	 * 
	 * @param $db
	 * @return unknown_type
	 */
	function CalendarTableVenues( &$db )
	{
		$tbl_key = 'venue_id';
		$tbl_suffix = 'venues';
		$this->set( '_suffix', $tbl_suffix );
		$name = "calendar";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );
	}
	
	function check( )
	{
		if ( empty( $this->venue_name ) )
		{
			//$this->setError( JText::_( "Venue Name Required" ) );
		}
		
		if ( !empty( $this->venue_name ) && empty($this->venue_id))
		{
		    /*
		    $db = $this->getDBO();
		    
		    $key = $db->Quote( strtolower( $this->venue_name ) );
		    $query = "SELECT * FROM #__calendar_venues WHERE LOWER( `venue_name` ) = $key;";
		    
		    $db->setQuery( $query );
		    $result = $db->loadResult();
		    if ($result)
		    {
    			//$this->setError( JText::_( "Venue Name Must Be Unique" ) );
		    }
		    */
		}
		
		$nullDate = $this->_db->getNullDate( );
		if ( empty( $this->venue_created_date ) || $this->venue_created_date == $nullDate )
		{
			$date = JFactory::getDate( );
			$this->venue_created_date = $date->toMysql( );
		}
		
		if ( empty( $this->datasource_id ) )
		{
		    $this->setError( JText::_( "Data Source ID Required" ) );
		}
		
		return parent::check();
	}
	
	/**
	 * Stores the object
	 * @param object
	 * @return boolean
	 */
	function store( $updateNulls=false )
	{
		$date = JFactory::getDate( );
		$this->venue_modified_date = $date->toMysql( );
		$store = parent::store( $updateNulls );
		return $store;
	}
}
