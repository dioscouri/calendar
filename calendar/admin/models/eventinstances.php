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

Calendar::load( 'CalendarModelBase', 'models._base' );

class CalendarModelEventinstances extends CalendarModelBase
{
	protected function _buildQueryWhere( &$query )
	{
		$filter = $this->getState( 'filter' );
		$filter_id_from = $this->getState( 'filter_id_from' );
		$filter_id_to = $this->getState( 'filter_id_to' );
		$filter_name = $this->getState( 'filter_name' );
		$filter_enabled = $this->getState( 'filter_enabled' );
		$filter_primary_category = $this->getState( 'filter_primary_category' );
		$filter_primary_categories = $this->getState( 'filter_primary_categories' );
		$filter_secondary_category = $this->getState( 'filter_secondary_category' );
		$filter_secondary_categories = $this->getState( 'filter_secondary_categories' );
		$filter_event = $this->getState( 'filter_event' );
		$filter_venue = $this->getState( 'filter_venue' );
		$filter_short_title = $this->getState( 'filter_short_title' );
		$filter_long_title = $this->getState( 'filter_long_title' );
		$filter_short_description = $this->getState( 'filter_short_description' );
		$filter_long_description = $this->getState( 'filter_long_description' );
		$filter_search = $this->getState( 'filter_search' );
		$filter_title = $this->getState( 'filter_title' );
		$filter_description = $this->getState( 'filter_description' );
		$filter_series =  $this->getState( 'filter_series' );
		$filter_upcoming_enabled = $this->getState( 'filter_upcoming_enabled' );
        $filter_date_from	= $this->getState('filter_date_from');
        $filter_date_to		= $this->getState('filter_date_to');
        $filter_datetype	= $this->getState('filter_datetype');
        $filter_digital_signage	= $this->getState('filter_digital_signage');
        $filter_types = $this->getState( 'filter_types' );
        $type = $this->getState( 'type' );
        
		if ( $filter )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter ) ) ) . '%' );
			
			$where = array( );
			$where[] = 'LOWER(tbl.eventinstance_id) LIKE ' . $key;
			$where[] = 'LOWER(tbl.eventinstance_name) LIKE ' . $key;
			
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( strlen( $filter_id_from ) )
		{
			if ( strlen( $filter_id_to ) )
			{
				$query->where( 'tbl.eventinstance_id >= ' . ( int ) $filter_id_from );
			}
			else
			{
				$query->where( 'tbl.eventinstance_id = ' . ( int ) $filter_id_from );
			}
		}
		
		if ( strlen( $filter_id_to ) )
		{
			$query->where( 'tbl.eventinstance_id <= ' . ( int ) $filter_id_to );
		}
		
		if ( $filter_name )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_name ) ) ) . '%' );
			$where = array( );
			$where[] = 'LOWER(tbl.eventinstance_name) LIKE ' . $key;
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( strlen( $filter_enabled ) )
		{
			$query->where( 'events.event_published = ' . ( int ) $filter_enabled );
		}
		
		// Filter for one primary category
		if ( strlen( $filter_primary_category ) )
		{
			if ($filter_primary_category > 0) {
				$query->where( 'events.event_primary_category_id = ' . ( int ) $filter_primary_category );
			}
		}
		
		// Filter with array of primary categories
		if ( !empty( $filter_primary_categories ) && is_array( $filter_primary_categories ) )
		{
			$where = array();
			foreach( $filter_primary_categories as $category_id )
			{
				if ($category_id > 0) {
					$where[] = 'events.event_primary_category_id = ' . ( int ) $category_id;
				}
			}
			
			if (!empty($where)) {
				$query->where( '(' . implode( ' OR ', $where ) . ')' );
			}
				
		}
		
		// Filter for one primary category
		if ( strlen( $filter_secondary_category ) )
		{
			$query->where( 'scatxref.category_id = ' . ( int ) $filter_secondary_category );
		}
		
		// Filter with array of secondary categories
		if ( !empty( $filter_secondary_categories ) && is_array( $filter_secondary_categories ) )
		{
			$where = array();
			foreach( $filter_secondary_categories as $category_id )
			{
				$where[] = 'scatxref.category_id = ' . ( int ) $category_id;
			}
			
			$query->where( '(' . implode( ' OR ', $where ) . ')' );	
		}

		if ( strlen( $filter_event ) )
		{
			$query->where( 'tbl.event_id = ' . ( int ) $filter_event );
		}
		
		if ( strlen( $filter_venue ) )
		{
			$query->where( 'tbl.venue_id = ' . ( int ) $filter_venue );
		}
		
		if ( strlen( $filter_short_title ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_short_title ) ) ) . '%' );	
			$query->where( 'LOWER(events.event_short_title) LIKE ' . $key );
		}
	
		if ( strlen( $filter_long_title ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_long_title ) ) ) . '%' );	
			$query->where( 'LOWER(events.event_long_title) LIKE ' . $key );
		}
	
		if ( strlen( $filter_short_description ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_short_description ) ) ) . '%' );	
			$query->where( 'LOWER(events.event_short_description) LIKE ' . $key );
		}
	
		if ( strlen( $filter_long_description ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_long_description ) ) ) . '%' );	
			$query->where( 'LOWER(events.event_long_description) LIKE ' . $key );
		}
		
		if ( strlen( $filter_search ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_search ) ) ) . '%' );
			
			$where = array( );
			$where[] = 'LOWER(events.event_short_title) LIKE ' . $key;
			$where[] = 'LOWER(events.event_long_title) LIKE ' . $key;
			$where[] = 'LOWER(events.event_short_description) LIKE ' . $key;
			$where[] = 'LOWER(events.event_long_description) LIKE ' . $key;
			
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( strlen( $filter_title ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_title ) ) ) . '%' );
			
			$where = array( );
			$where[] = 'LOWER(events.event_short_title) LIKE ' . $key;
			$where[] = 'LOWER(events.event_long_title) LIKE ' . $key;
			
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( strlen( $filter_description ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_description ) ) ) . '%' );
			
			$where = array( );
			$where[] = 'LOWER(events.event_short_description) LIKE ' . $key;
			$where[] = 'LOWER(events.event_long_description) LIKE ' . $key;
			
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( strlen( $filter_series ) )
		{
			$query->where( 'events.series_id = ' . ( int ) $filter_series );
		}
		
	    if (strlen($filter_date_from))
        {
        	switch ($filter_datetype)
        	{
        		case "three":
        	    case "week":
        		case "month":
        			$query->where("tbl.eventinstance_date >= '".$filter_date_from."'");		
        		  break;
        		case "created":
        			$query->where("tbl.eventinstance_created_date >= '".$filter_date_from."'");		
        		  break;
        		case "date":
        		default:
        			$query->where("tbl.eventinstance_date >= '".$filter_date_from."'");		
        		  break;
        	}
       	}
       	
		if (strlen($filter_date_to))
        {
			switch ($filter_datetype)
        	{
        	    case "three":
        	    case "week":
        		case "month":
        			$query->where("tbl.eventinstance_date < '".$filter_date_to."'");		
        		  break;
        		case "created":
        			$query->where("tbl.eventinstance_created_date <= '".$filter_date_to."'");		
        		  break;
        		case "date":
        		default:
        			$query->where("tbl.eventinstance_date <= '".$filter_date_to."'");		
        		  break;
        	}
       	}
       	
		if ( strlen( $filter_upcoming_enabled ) )
		{
			$query->having( 'events.event_upcoming_enabled = ' . (int) $filter_upcoming_enabled );
		}
		
		if ( strlen( $filter_digital_signage ) )
		{
			$query->where( 'events.digital_signage = ' . ( int ) $filter_digital_signage );
		}
		
		if ( !empty( $filter_types ) && is_array( $filter_types ) && empty( $type ))
		{
		    $where = array();
		    foreach( $filter_types as $id )
		    {
		        $where[] = 'events.type_id = ' . ( int ) $id;
		    }
		     
		    $query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( !empty( $type ) )
		{
		    $query->where( 'events.type_id = ' . ( int ) $type );
		}
	}
	
	protected function _buildQueryJoins( &$query )
	{
		$query->join( 'LEFT', '#__calendar_events AS events ON tbl.event_id = events.event_id' );
		$query->join( 'LEFT', '#__calendar_venues AS venues ON tbl.venue_id = venues.venue_id' );
		$query->join( 'LEFT', '#__calendar_recurring AS recurring ON tbl.recurring_id = recurring.recurring_id' );
		$query->join( 'LEFT', '#__calendar_actionbuttons AS ab ON tbl.actionbutton_id = ab.actionbutton_id' );
		
		$filter_primary_category = $this->getState( 'filter_primary_category' );
		$filter_primary_categories = $this->getState( 'filter_primary_categories' );
		if ( strlen( $filter_primary_category ) || !empty( $filter_primary_categories ) )
		{
			
		}
		$query->join( 'LEFT', '#__calendar_categories AS pcategory ON events.event_primary_category_id = pcategory.category_id' );
		
		$filter_secondary_category = $this->getState( 'filter_secondary_category' );
		$filter_secondary_categories = $this->getState( 'filter_secondary_categories' );
		if ( strlen( $filter_secondary_category ) || !empty( $filter_secondary_categories ) )
		{
			$query->join( 'LEFT', '#__calendar_eventcategories AS scatxref ON tbl.event_id = scatxref.event_id' );
			$query->join( 'LEFT', '#__calendar_categories AS scategories ON scatxref.category_id = scategories.category_id' );
		}
	}
	
	protected function _buildQueryFields( &$query )
	{
		$fields = array( );
		$fields[] = " events.* ";
		$fields[] = " venues.venue_name ";
		$fields[] = " recurring.recurring_end_type ";
		$fields[] = " recurring.recurring_end_occurances ";
		$fields[] = " recurring.recurring_finishes ";
		$fields[] = " recurring.recurring_finishes_date ";
		$fields[] = " ab.* ";
		
		$filter_primary_category = $this->getState( 'filter_primary_category' );
		if ( strlen( $filter_primary_category ) )
		{

		}
			$fields[] = " pcategory.category_id AS pcategory_id ";
			$fields[] = " pcategory.category_name AS pcategory_name ";
			$fields[] = " pcategory.category_class AS primary_category_class ";
		
		$filter_secondary_category = $this->getState( 'filter_secondary_category' );
		$filter_secondary_categories = $this->getState( 'filter_secondary_categories' );
		if ( strlen( $filter_secondary_category ) || !empty( $filter_secondary_categories ) )
		{
			$fields[] = " scategories.category_id AS scategory_id ";
			$fields[] = " scategories.category_name AS scategory_name ";
		}
		
		$query->select( $this->getState( 'select', 'tbl.*' ) );
		$query->select( $fields );
	}
	
	public function getList( $refresh=false )
	{
		$list = parent::getList( $refresh );
		foreach ( $list as $item )
		{
			$item->link = 'index.php?option=com_calendar&view=eventinstances&task=edit&id=' . $item->eventinstance_id;
			$item->link_view = 'index.php?option=com_calendar&view=events&task=view&id=' . $item->event_id . '&instance_id=' . $item->eventinstance_id;
		}
		return $list;
	}
	
	function getUsedPrimaryCategories( $calendar_id=null )
	{
	    JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
	    $model = JModel::getInstance( 'Eventinstances', 'CalendarModel' );
	    $query = new CalendarQuery();
	    $query->join( 'LEFT', '#__calendar_events AS events ON tbl.event_id = events.event_id' );
	    $query->join( 'LEFT', '#__calendar_categories AS pcategory ON events.event_primary_category_id = pcategory.category_id' );
        $query->order( 'pcategory.ordering' );
        
	    $fields = array( );
	    $fields[] = " DISTINCT(events.event_primary_category_id) ";
		$fields[] = " pcategory.category_id AS category_id ";
		$fields[] = " pcategory.category_name AS category_name ";
		$fields[] = " pcategory.category_class AS category_class ";
		
		$query->select( $fields );
        $name = $this->getTable( )->getTableName( );
		$query->from( $name . ' AS tbl' );
		
		if (!empty( $calendar_id )) {
		    JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		    $calendar = JTable::getInstance( 'Calendars', 'CalendarTable' );
		    $calendar->load( $calendar_id );
		    $category_ids = explode( ',', $calendar->calendar_filter_primary_categories );
		    foreach ($category_ids as &$cat) {
		        $cat = trim( $cat );
		    }
		    $query->where( "pcategory.category_id IN ('" . implode( "', '", $category_ids ) . "')" );
		}
		
		$db = $this->getDBO();
		$db->setQuery( (string) $query ); 
		$items = $db->loadObjectList();
		
		return $items;
		
	}
}
