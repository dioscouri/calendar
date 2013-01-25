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

class CalendarTableEventinstances extends CalendarTable
{
	/**
	 * Could this be abstracted into the base?
	 * 
	 * @param $db
	 * @return unknown_type
	 */
	function CalendarTableEventinstances( &$db )
	{
		$tbl_key = 'eventinstance_id';
		$tbl_suffix = 'eventinstances';
		$this->set( '_suffix', $tbl_suffix );
		$name = "calendar";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );
	}
	
	function check( )
	{
		if ( empty( $this->datasource_id ) )
		{
			$this->setError( JText::_( "Data Source ID Required" ) );
		}
		
		return parent::check();
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
	            $return = ( JFile::exists( Calendar::getPath( 'events_images' ) . DS . $this->event_full_image ) ) ? Calendar::getUrl( 'events_images' ) . $this->event_full_image : $this->event_full_image;
	            break;
	        default:
        		$image = $this->eventinstance_full_image;
        		Calendar::load( 'CalendarHelperImage', 'helpers.image' );
        		$return = CalendarHelperImage::getImage( 'eventinstances', $image, $this->eventinstance_name, $type, $url );
	            break;
	    }
		
		return $return;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return return_type
	 */
	function bindObjects()
	{
	    $this->bindEventObject();
	    $this->bindRecurringObject();
	    $this->bindSeriesObject();
	    $this->bindVenueObject();
		$this->image_src = $this->getImage('src');
		$this->link_view = 'index.php?option=com_calendar&view=events&task=view&id=' . $this->event_id . '&instance_id=' . $this->eventinstance_id;
		$this->bindPrimaryCategoryObject();
		$this->bindActionButtonObject();
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return return_type
	 */
	function bindEventObject()
	{
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		$table = JTable::getInstance( 'Events', 'CalendarTable' );
		
	    if (!empty($this->event_id))
	    {
	        $table->load( $this->event_id );
	    }
	    
		$table->trimProperties();
		
		$properties = $this->getProperties();
		$table_properties = $table->getProperties();

		foreach ($table_properties as $prop=>$value)
		{
		    if (!array_key_exists($prop, $properties))
		    {
		        $this->$prop = $table->$prop;
		    }
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return return_type
	 */
	function bindRecurringObject()
	{
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		$table = JTable::getInstance( 'Recurring', 'CalendarTable' );
		
	    if (!empty($this->recurring_id))
	    {
	        $table->load( $this->recurring_id );
	    }
	    
		$table->trimProperties();
		
		$properties = $this->getProperties();
		$table_properties = $table->getProperties();

		foreach ($table_properties as $prop=>$value)
		{
		    if (!array_key_exists($prop, $properties))
		    {
		        $this->$prop = $table->$prop;
		    }
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return return_type
	 */
	function bindSeriesObject()
	{
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		$table = JTable::getInstance( 'Series', 'CalendarTable' );
		
	    if (!empty($this->series_id))
	    {
	        $table->load( $this->series_id );
	    }
	    
		$table->trimProperties();
		
		$properties = $this->getProperties();
		$table_properties = $table->getProperties();

		foreach ($table_properties as $prop=>$value)
		{
		    if (!array_key_exists($prop, $properties))
		    {
		        $this->$prop = $table->$prop;
		    }
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return return_type
	 */
	function bindVenueObject()
	{
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		$table = JTable::getInstance( 'Venues', 'CalendarTable' );
		
	    if (!empty($this->venue_id))
	    {
	        $table->load( $this->venue_id );
	    }
	    
		$table->trimProperties();
		
		$properties = $this->getProperties();
		$table_properties = $table->getProperties();

		foreach ($table_properties as $prop=>$value)
		{
		    if (!array_key_exists($prop, $properties))
		    {
		        $this->$prop = $table->$prop;
		    }
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return return_type
	 */
	function bindPrimaryCategoryObject()
	{
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		$table = JTable::getInstance( 'Categories', 'CalendarTable' );
		
	    if (!empty($this->event_primary_category_id))
	    {
	        $table->load( $this->event_primary_category_id );
	    }
	    
		$table->trimProperties();
		
		$properties = $this->getProperties();
		$table_properties = $table->getProperties();

		foreach ($table_properties as $prop=>$value)
		{
		    $key_name = "primary_" . $prop;
		    if (!array_key_exists($key_name, $properties))
		    {
		        $this->$key_name = $table->$prop;
		    }
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return return_type
	 */
	function bindActionButtonObject()
	{
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		$table = JTable::getInstance( 'ActionButtons', 'CalendarTable' );
		
	    if (!empty($this->actionbutton_id))
	    {
	        $table->load( $this->actionbutton_id );
	    }
	    
		$table->trimProperties();
		
		$properties = $this->getProperties();
		$table_properties = $table->getProperties();

		foreach ($table_properties as $prop=>$value)
		{
		    $key_name = $prop;
		    if (!array_key_exists($key_name, $properties))
		    {
		        $this->$key_name = $table->$prop;
		    }
		}
	}
}
