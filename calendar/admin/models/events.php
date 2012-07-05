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

class CalendarModelEvents extends CalendarModelBase
{
	protected function _buildQueryWhere( &$query )
	{
		$filter = $this->getState( 'filter' );
		$filter_id_from = $this->getState( 'filter_id_from' );
		$filter_id_to = $this->getState( 'filter_id_to' );
		$filter_name = $this->getState( 'filter_name' );
		$filter_enabled = $this->getState( 'filter_enabled' );
		$filter_category = $this->getState( 'filter_category' );
		$filter_eventcategories = $this->getState( 'filter_eventcategories' );
		$filter_series = $this->getState( 'filter_series' );
		$filter_secondary_categories = $this->getState( 'filter_secondary_categories' );
		$filter_venue_name = $this->getState( 'filter_venue_name' ); 
		$filter_venue_id = $this->getState( 'filter_venue_id' );
		$filter_upcoming_enabled = $this->getState( 'filter_upcoming_enabled' );
		$filter_digital_signage = $this->getState( 'filter_digital_signage' );
		
		if ( $filter )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter ) ) ) . '%' );
			
			$where = array( );
			$where[] = 'LOWER(tbl.event_id) LIKE ' . $key;
			$where[] = 'LOWER(tbl.event_short_title) LIKE ' . $key;
			$where[] = 'LOWER(tbl.event_long_title) LIKE ' . $key;
			$where[] = 'LOWER(tbl.event_alias) LIKE ' . $key;
			$where[] = 'LOWER(tbl.event_short_description) LIKE ' . $key;
			$where[] = 'LOWER(tbl.event_long_description) LIKE ' . $key;
						
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( strlen( $filter_enabled ) )
		{
			$query->where( 'tbl.event_published = ' . ( int ) $filter_enabled );
		}
		
		if ( strlen( $filter_id_from ) )
		{
			if ( strlen( $filter_id_to ) )
			{
				$query->where( 'tbl.event_id >= ' . ( int ) $filter_id_from );
			}
			else
			{
				$query->where( 'tbl.event_id = ' . ( int ) $filter_id_from );
			}
		}
		
		if ( strlen( $filter_id_to ) )
		{
			$query->where( 'tbl.event_id <= ' . ( int ) $filter_id_to );
		}
		
		if ( $filter_name )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_name ) ) ) . '%' );
			$where = array( );
			$where[] = 'LOWER(tbl.event_short_title) LIKE ' . $key;
			$where[] = 'LOWER(tbl.event_long_title) LIKE ' . $key;
			$where[] = 'LOWER(tbl.event_alias) LIKE ' . $key;
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		// filter primary category
		if ( strlen( $filter_category ) )
		{
			$query->where( 'c.category_id = ' . ( int ) $filter_category );
		}
		
		//filter secondary category
		if ( strlen( $filter_eventcategories ) )
		{
			$query->where( 'scat.category_id = ' . ( int ) $filter_eventcategories );
		}
		
		if ( strlen( $filter_secondary_categories ) )
		{
			$query->where( 'scat.event_id = ' . ( int ) $filter_secondary_categories );
		}
		
		if ( strlen( $filter_series ) )
		{
			$query->where( 'tbl.series_id = ' . ( int ) $filter_series );
		}

		if ( strlen( $filter_venue_name ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_venue_name ) ) ) . '%' );
			$where = array( );
			$where[] = 'LOWER(venue_names) LIKE ' . $key;
			$query->having( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( strlen( $filter_venue_id ) )
		{
			$query->having( 'venue_id = ' . (int) $filter_venue_id );
		}

		if ( strlen( $filter_upcoming_enabled ) )
		{
			$query->having( 'tbl.event_upcoming_enabled = ' . (int) $filter_upcoming_enabled );
		}

		if ( strlen( $filter_digital_signage ) )
		{
			$query->having( 'tbl.digital_signage = ' . (int) $filter_digital_signage );
		}		
		
	}
	
	protected function _buildQueryJoins( &$query )
	{
		$query->join( 'LEFT', '#__calendar_categories AS c ON tbl.event_primary_category_id = c.category_id' );
		
		$filter_eventcategories = $this->getState( 'filter_eventcategories' );
		$filter_secondary_categories = $this->getState( 'filter_secondary_categories' );
		if ( strlen( $filter_eventcategories ) || strlen( $filter_secondary_categories ))
		{
			$query->join( 'LEFT', '#__calendar_eventcategories AS scat ON tbl.event_id = scat.event_id' );
			$query->join( 'LEFT', '#__calendar_secondcategories AS cats ON scat.category_id = cats.category_id' );
		}
		$query->join( 'LEFT', '#__calendar_series AS series ON tbl.series_id = series.series_id' );
	}
	
	protected function _buildQueryFields( &$query )
	{
		$fields = array( );
		$fields[] = " series.series_name ";
		
		// primary category is always present
		$fields[] = " c.category_id AS category_id ";
		$fields[] = " c.category_name AS category_name ";
		
		$filter_eventcategories = $this->getState( 'filter_eventcategories' );
		$filter_secondary_categories = $this->getState( 'filter_secondary_categories' );
		if ( strlen( $filter_eventcategories ) || strlen( $filter_secondary_categories ) )
		{
			$fields[] = " cats.category_id AS secondarycat_id ";
			$fields[] = " cats.category_name AS secondarycat_name ";
		}

		$filter_venue_name = $this->getState( 'filter_venue_name' );
		if ( strlen( $filter_venue_name ) )
		{
    		$fields[] = "
                (
                SELECT 
                    GROUP_CONCAT( DISTINCT(venue.venue_name) SEPARATOR ', ' )
                FROM
                    #__calendar_venues AS venue
                LEFT JOIN #__calendar_eventinstances AS ei ON venue.venue_id = ei.venue_id
                WHERE 
                    ei.event_id = tbl.event_id
                GROUP BY ei.event_id 
                ) 
            AS venue_names ";
		}
		
		$filter_venue_id = $this->getState( 'filter_venue_id' );
		if ( strlen( $filter_venue_id ) )
		{
    		$fields[] = "
                (
                SELECT 
                    GROUP_CONCAT( DISTINCT(venue.venue_id) SEPARATOR ' , ' )
                FROM
                    #__calendar_venues AS venue
                LEFT JOIN #__calendar_eventinstances AS ei ON venue.venue_id = ei.venue_id
                WHERE 
                    ei.event_id = tbl.event_id
                    AND ei.venue_id = '$filter_venue_id'
                GROUP BY ei.event_id 
                ) 
            AS venue_id ";
		}
		
		$query->select( $this->getState( 'select', 'tbl.*' ) );
		$query->select( $fields );
	}
	
	/**
	 * Builds a generic ORDER BY clause based on the model's state
	 */
	protected function _buildQueryOrder( &$query )
	{
		$order = 'tbl.event_id';
		$direction = $this->_db->getEscaped( strtoupper( $this->getState( 'direction' ) ) );
		
		if ( $order )
		{
			$query->order( "$order $direction" );
		}
		
		// TODO Find an abstract way to determine if order is a valid field in query
		// if (in_array($order, $this->getTable()->getColumns())) does not work
		// because you could be ordering by a field from one of the JOINed tables
		if ( in_array( 'ordering', $this->getTable( )->getColumns( ) ) )
		{
			$query->order( 'ordering ASC' );
		}
	}
	
	public function getList( $refresh=false )
	{
		$list = parent::getList( $refresh );
		
		// If no item in the list, return an array()
		if ( empty( $list ) )
		{
			return array( );
		}
		
		foreach ( $list as $item )
		{
			$item->link = 'index.php?option=com_calendar&view=events&task=edit&id=' . $item->event_id;
			$item->link_view = 'index.php?option=com_calendar&view=events&task=view&id=' . $item->event_id;
		}
		return $list;
	}
	
	/**
	 * Returns secondary categories list
	 * 
	 * @param $event_id;
	 * @return array;
	 */
	function getSecondaryCategoriesString( $event_id )
	{
	    $string = '';
	    
		JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
		$model = JModel::getInstance( 'EventCategories', 'CalendarModel' );
		$model->setState( 'filter_event', $event_id );
		
		if ( $categories = $model->getList( ) )
		{
			$cats = array( );
			foreach ( $categories as $category )
			{
				$cats[] = JText::_( $category->category_name );
			}
			$string = implode( ', ', $cats );
		}
		
		return $string;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $event_id
	 * @return return_type
	 */
	function getDatesString( $event_id )
	{
	    $string = JText::_( 'None' );

	    JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
		$model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
		$model->setState( 'filter_event', $event_id );
		$model->setState( 'filter_enabled', '1' );
		$model->setState( 'order', 'tbl.eventinstance_date' );
		$model->setState( 'direction', 'ASC' );
		
		if ( $items = $model->getList( ) )
		{
		    $count = count($items);
		    $less = $count-1;
		    $firstdate = $items[0]->eventinstance_date;
		    $lastdate = $items[$less]->eventinstance_date;
			if (!empty($items[$less]->recurring_id) && !$items[$less]->recurring_finishes && $items[$less]->recurring_end_type == 'never')
			{
			    $lastdate = JText::_( 'forever' );
			}
			
			if ($count == '1')
			{
			    $string = $firstdate; 
			}
    			else
			{
			    $string = sprintf( JText::_( 'X_OCCURANCES_FROM_A_TO_B' ), $count, $firstdate, $lastdate );
			}
		}
		
	    return $string;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $event_id
	 * @return return_type
	 */
	function getVenuesString( $event_id )
	{
	    $string = JText::_( 'None' );

	    JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
		$model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
		$model->setState( 'filter_event', $event_id );
		$model->setState( 'filter_enabled', '1' );
		$model->setState( 'order', 'venues.venue_name' );
		$model->setState( 'direction', 'ASC' );
		$query = $model->getQuery();
		$query->group( 'venues.venue_id' );
		$model->setQuery( $query );
		
		if ( $items = $model->getList( ) )
		{
		    $array = array();
            foreach ($items as $item)
            {
                $array[] = $item->venue_name;
            }
            $string = implode( ', ', $array );
		}
		
	    return $string;
	}
	
	function getRelated( $id, $num='3' )
	{
	    JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
	    $model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
	    $eventinstance = $model->getTable();
	    $eventinstance->load( $id );
	    $eventinstance->bindEventObject();
	    $related_events = array();
		
		$model->setState( 'filter_primary_category', $eventinstance->event_primary_category_id );
		$model->setState( 'filter_date_from', $eventinstance->eventinstance_date );
		$model->setState( 'limit', $num );
        $query = $model->getQuery();
        $query->where( "tbl.event_id != '" . $eventinstance->event_id ."'" );
        $model->setQuery( $query );
        $related_events = $model->getList();
        
        $current_count = count($related_events);
		if ($current_count < $num)
		{
		    $remainder = $num - $current_count;
		    // TODO Do we try to get more?
		}
		
		$return = array();
		foreach ($related_events as $event)
		{
    		$instance = JTable::getInstance( 'EventInstances', 'CalendarTable' );
    		$instance->load( $event->eventinstance_id );
    		$instance->trimProperties();
    		$instance->bindObjects();

    		$return[] = $instance; 
		}
		
		return $return;
	}
	
	function getNextInstance( $event_id, $date=null )
	{
	    $filter_date_from = $date;
	    if (empty($filter_date_from))
	    {
	        $jdate = JFactory::getDate();
	        $filter_date_from = $jdate->toFormat('%Y-%m-%d');
	    }
	    JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
	    $model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
		$model->setState( 'filter_event', $event_id );
		$model->setState( 'filter_enabled', '1' );
		$model->setState( 'filter_date_from', $filter_date_from );
		$model->setState( 'limit', '1' );
		$model->setState( 'order', 'tbl.eventinstance_date' );
		$model->setState( 'direction', 'ASC' );
		if ($items = $model->getList(true))
		{
		    $item = $items[0];
		}
    		else
		{
		    // try to get the most recent instance
		    $model->setState( 'filter_date_from', '' );
		    $model->setState( 'direction', 'DESC' );
    		if ($items = $model->getList(true))
    		{
    		    $item = $items[0];
    		}
        		else
    		{
    		    JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
    		    $item = JTable::getInstance( 'EventInstances', 'CalendarTable' );
    		}
		}
		return $item;
	}
}
