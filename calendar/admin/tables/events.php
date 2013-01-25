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

class CalendarTableEvents extends CalendarTable
{
	/**
	 * 
	 * @param $db
	 * @return unknown_type
	 */
	function CalendarTableEvents( &$db )
	{
		$tbl_key = 'event_id';
		$tbl_suffix = 'events';
		$this->set( '_suffix', $tbl_suffix );
		$name = "calendar";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );
	}

	/**
	 * (non-PHPdoc)
	 * @see JTable::check()
	 */
	function check()
	{
		if ( empty( $this->event_short_title ) )
		{
			$this->setError( JText::_( "Event Short Title Required" ) );
		}
		
		$nullDate = $this->_db->getNullDate( );
		if ( empty( $this->event_created_date ) || $this->event_created_date == $nullDate )
		{
			$date = JFactory::getDate( );
			$this->event_created_date = $date->toMysql( );
		}
		
		jimport( 'joomla.filter.output' );
		if ( empty( $this->event_alias ) )
		{
			$this->event_alias = $this->event_short_title;
		}
		$this->event_alias = JFilterOutput::stringURLSafe( $this->event_alias );
		
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
		$this->event_modified_date = $date->toMysql( );
		$store = parent::store( $updateNulls );
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
		$image = $this->event_full_image;
        jimport( 'joomla.filesystem.file' );
        $image = ( JFile::exists( Calendar::getPath( 'events_images' ) . DS . $image ) ) ? Calendar::getUrl( 'events_images' ) . $image : $image;
        
		if (empty($image))
		{
		    $image = Calendar::getUrl( "images" ) . "noimage.png";
		}
		
		if ($url)
		{
		    $return = $image;
		}
		else
		{
			switch ($type)
    		{
    		    case "src":
    		    case "source":
    		        $return = $image;
    		        break;
    		    default:
    		        $alt = $this->event_short_title;
    		        $return = "<img src='" . $image . "' alt='" . JText::_( $alt ) . "' title='" . JText::_( $alt ) . "' align='middle' border='0' />";
    		        break;
    		}		    
		}
		
		/*
		Calendar::load( 'CalendarHelperImage', 'helpers.image' );
		$image_html = CalendarHelperImage::getImage( 'events', $image, $this->event_short_title, $type, $url );
		*/
		
		return $return;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $secondary_categories
	 * @return return_type
	 */
	function storeSecondaryCategories( $secondary_categories )
	{
	    // delete all existing ones
	    $db = $this->getDBO();
	    $db->setQuery( "DELETE FROM #__calendar_eventcategories WHERE `event_id` = '$this->event_id';");
	    $db->query();
	    
	    // save new ones
	    JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_publications/tables' );
	    foreach( $secondary_categories as $secondary_category)
	    {
	        $table = JTable::getInstance( 'EventCategories', 'CalendarTable' );
	        $table->event_id = $this->event_id;
	        $table->category_id = $secondary_category;
	        $table->store();
	    }
	}
}
