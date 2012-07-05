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

class CalendarTableCalendars extends CalendarTable
{
	/**
	 * Could this be abstracted into the base?
	 * 
	 * @param $db
	 * @return unknown_type
	 */
	function CalendarTableCalendars( &$db )
	{
		$tbl_key = 'calendar_id';
		$tbl_suffix = 'calendars';
		$this->set( '_suffix', $tbl_suffix );
		$name = "calendar";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );
	}
	
	function check( )
	{
		// check name
		$this->filterHTML( 'calendar_name' );
		if ( empty( $this->calendar_name ) )
		{
			$this->setError( JText::_( "Calendar Name Required" ) );
			return false;
		}
		// check name unique 
		if ( !empty( $this->calendar_name ) && empty($this->calendar_id))
		{
		    $key = strtolower( $this->calendar_name );
		    $query = "SELECT * FROM #__calendar_calendars WHERE LOWER( calendar_name ) = '$key';";
		    $db = $this->getDBO();
		    $db->setQuery( $query );
		    $result = $db->loadResult();
		    if ($result)
		    {
    			$this->setError( JText::_( "Calendar Name Must Be Unique" ) );
    			return false;		        
		    }
		}
		
		// check or make alias
		jimport( 'joomla.filter.output' );
		if ( empty( $this->calendar_alias ) )
		{
			$this->calendar_alias = $this->calendar_name;
		}
		$this->calendar_alias = JFilterOutput::stringURLSafe( $this->calendar_alias );
		
		// check and make created/modified datetime
		$nullDate = $this->_db->getNullDate( );
		if ( empty( $this->calendar_created_date ) || $this->calendar_created_date == $nullDate )
		{
			$date = JFactory::getDate( );
			$this->calendar_created_date = $date->toMysql( );
		}
		if ( empty( $this->calendar_modified_date ) || $this->calendar_modified_date == $nullDate )
		{
			$date = JFactory::getDate( );
			$this->calendar_modified_date = $date->toMysql( );
		}
		
		// check if 'date from' is lower than 'date to'
		$date_from = strtotime( $this->calendar_filter_date_from );
		$date_to = strtotime( $this->calendar_filter_date_to );
		if( $date_to < $date_from )
		{
			$this->setError( JText::_( "Filter 'Date from' can't be greater than filter 'Date to'" ) );
    		return false;
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
		$this->calendar_modified_date = $date->toMysql( );
		$store = parent::store( $updateNulls );
		return $store;
	}
	
	public function getTabbedTypes( $calendar_id=null )
	{
	    if (empty($this->calendar_id) || !empty($calendar_id)) {
	        $this->load( $calendar_id );
	    }
	    
	    $return = array();

	    if (!empty($this->calendar_tabbed_types)) {
	        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
	        $exploded = explode( ',', $this->calendar_tabbed_types );
        
	        foreach ($exploded as $exploded_item) {
	            $exploded_item = trim( $exploded_item );
	            
	            $table = JTable::getInstance( 'Types', 'CalendarTable' );
	            $table->load( $exploded_item );
	            
	            $return[] = $table;
	        }
	    }
	    
	    return $return;
	}
}
