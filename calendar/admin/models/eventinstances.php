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

Calendar::load( 'CalendarModelBase', 'models.base' );

class CalendarModelEventinstances extends CalendarModelBase
{
    public $_items = array();
    
    function __construct($config = array())
    {
        parent::__construct($config);
        $this->setState( 'select', array('performance.*', 'show.*', 'venue.*', 'show.primaryVenue', 'show.presenters.*') );
        $this->loadMQ();
        $this->classname = 'calendarmodeleventinstances';
    }
    
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
        
        $filter_venues = $this->getState( 'filter_venues' );        
        $filter_venue_name = $this->getState( 'filter_venue_name' );
        $filter_venue_id = $this->getState( 'filter_venue_id' );        
        if (empty($filter_venues) && !empty($filter_venue_id)) {
            $filter_venues = array();
            $filter_venues[] = $filter_venue_id;
            $this->setState('filter_venues', $filter_venues);
        }
        
        $filter_events = $this->getState( 'filter_events' );
        if (empty($filter_events) && !empty($filter_event)) {
            $filter_events = array();
            $filter_events[] = $filter_event;
            $this->setState('filter_events', $filter_events);
        }
        
        if (strlen($filter_date_from))
        {
            if (!strlen($filter_date_to))
            {
                $filter_date_to = date('Y-m-d 23:59:59', strtotime( $filter_date_from . ' +1 month') );
            }
            $filter_date_to = date('Y-m-d 23:59:59', strtotime( $filter_date_to ) );
        }
        elseif (strlen($filter_date_to))
        {
            $filter_date_from = date('Y-m-d', strtotime( 'now') );
            $filter_date_to = date('Y-m-d 23:59:59', strtotime( $filter_date_to ) );
        }
        else
        {
            $filter_date_from = date('Y-m-d', strtotime( 'now') );
            $filter_date_to = date('Y-m-d 23:59:59', strtotime( $filter_date_from . ' +1 month') );
        }
		  
        $query->findByDate( new \DateTime($filter_date_from), new \DateTime($filter_date_to) );
        $this->setState('filter_date_from', $filter_date_from);
        $this->setState('filter_date_to', $filter_date_to);
        
		if ( strlen( $filter_enabled ) )
		{
			$query->findPublished();
		}
        
        if ( !empty( $filter_types ) && is_array( $filter_types ))
        {
            $filter_ids = $this->getIDsFromJoomlaFilter( array('type_id'=>$filter_types) );
            $filter_ids = $this->getIDsFromAdditionalEventTypes( $filter_types, $filter_ids );
            
            $where_av = array();
            $where_tess = array();
            foreach( $filter_ids as $id )
            {
                $id_parts = explode('-', $id);
                switch($id_parts[0]) {
                    case "av":
                        $where_av[] = $id_parts[1];
                        break;
                    case "tess":
                    case "t":
                        $where_tess[] = $id_parts[1];
                        break;
                }
            }
            
            if (!empty($where_av)) {
                $query->findByShowArtsvisionID($where_av);
            } else {
                $query->tessOnly = true;
            }
            
            if (!empty($where_tess)) {
                $query->findByShowTessituraID($where_tess);
            } else {
                $query->artsvisionOnly = true;
            }
        }
        
        if ( !empty( $filter_venues ) && is_array( $filter_venues ))
        {
            $where_av = array();
            $where_tess = array();
            foreach( $filter_venues as $id )
            {
                $id_parts = explode('-', $id);
                switch($id_parts[0]) {
                    case "av":
                        $where_av[] = $id_parts[1];
                        break;
                    case "tess":
                    case "t":
                        $where_tess[] = $id_parts[1];
                        break;
                }
                
            }

            if (!empty($where_av)) {
                $query->findByVenueArtsvisionID($where_av);
            } else {
                $query->tessOnly = true;
            }
            
            if (!empty($where_tess)) {
                $query->findByVenueTessituraID($where_tess);
            } else {
                $query->artsvisionOnly = true;
            }           
        }
        
        if ( !empty( $filter_events ) && is_array( $filter_events ))
        {
            $where_av = array();
            $where_tess = array();
            foreach( $filter_events as $id )
            {
                $id_parts = explode('-', $id);
                switch($id_parts[0]) {
                    case "av":
                        $where_av[] = $id_parts[1];
                        break;
                    case "tess":
                    case "t":
                        $where_tess[] = $id_parts[1];
                        break;
                }
        
            }
        
            if (!empty($where_av)) {
                $query->findByShowArtsvisionID($where_av);
            } else {
                $query->tessOnly = true;
            }
        
            if (!empty($where_tess)) {
                $query->findByShowTessituraID($where_tess);
            } else {
                $query->artsvisionOnly = true;
            }
        }
        
        if ( $filter )
        {
            $key = trim( strtolower( $filter ) );
            $finds['show.title'] = $key;
            $query->find($finds);
        }
        
        if ( strlen($filter_name) )
        {
        	$key = trim( strtolower( $filter_name ) );
            $finds['show.title'] = $key;
            $query->find($finds);
        }
        
        if ( strlen( $filter_id_from ) )
        {
            $query->find(array(
                    'artsvisionID' => $filter_id_from,
                    'tessituraID' => $filter_id_from
            ));
        }
        
        /*
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
		*/
		
        $query->setDizzysOnly(false);
	}
	
	protected function _buildQueryJoins( &$query )
	{
	    /*
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
		*/
	}
	
	protected function _buildQueryFields( &$query )
	{
	    $fields = $this->getState( 'select' );
	    if (empty($fields)) {
	        $this->setState( 'select', array('performance.*', 'show.*', 'venue.*') );
	        $fields = array();
	    }
	    
	    $query->select($fields);
	    
	    /*
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
		*/
	}
	
	/**
	 * Builds a generic ORDER BY clause based on the model's state
	 */
	protected function _buildQueryOrder( &$query )
	{
	}
	
	/**
	 * Builds FROM tables list for the query
	 */
	protected function _buildQueryFrom(&$query)
	{
	
	}
	
	/**
	 * Builds a generic SELECT query
	 *
	 * @return  string  SELECT query
	 */
	protected function _buildQuery( $refresh=false )
	{
	    if (!empty($this->_query) && !$refresh)
	    {
	        return $this->_query;
	    }
	
	    $query = \MappableQuery\MappableQuery::factory('JALC\EventsArtists\Queries\PerformanceQuery');
	    
        $this->_buildQueryFields($query);
	    $this->_buildQueryFrom($query);
	    $this->_buildQueryJoins($query);
	    $this->_buildQueryWhere($query);
	    $this->_buildQueryGroup($query);
	    $this->_buildQueryHaving($query);
	    $this->_buildQueryOrder($query);
	
	    return $query;
	}
	
	public function getList( $refresh=false )
	{		
	    if (empty($this->_list) || $refresh)
	    {
	        $cache_key = base64_encode(serialize($this->getState())) . '.list';
	         
	        $classname = 'calendarmodeleventinstances';
	        $cache = JFactory::getCache( $classname . '.list', '' );
	        $cache->setCaching($this->cache_enabled);
	        $cache->setLifeTime($this->cache_lifetime);
	        $list = $cache->get($cache_key);
	        if (!$list || $refresh)
	        {
	            $list = array();
	            
	            $query = $this->getQuery(true);
	            $list = $query->fetchObjects( (int) $this->getState('limitstart', 0), $this->getLimit() );
	             
	            if ( empty( $list ) )
	            {
	                $list = array( );
	            }
	    
	            foreach ( $list as $item )
	            {
	                $key = 0;
	                if ($id = $item->getID()) {
	                    $parts = explode('-', $id);
	                    $key = (int) $parts[1];
	                }
	                
	                $this->prepareItem( $item, $key, $refresh );						 
	                $this->cacheItem( $item );
	            }
	    
	            $cache->store($list, $cache_key);
	        }
	         
	        $this->_list = $list;
	    }
		 
	    return $this->_list;
	}
	
	/**
	 * Retrieves the count
	 * @return array Array of objects containing the data from the database
	 */
	public function getTotal()
	{
	    if (empty($this->_total))
	    {
	        $cache_key = base64_encode(serialize($this->getState())) . '.list-totals';
	
	        $classname = 'calendarmodeleventinstances';
	        $cache = JFactory::getCache( $classname . '.list-totals', '' );
	        $cache->setCaching($this->cache_enabled);
	        $cache->setLifeTime($this->cache_lifetime);
	        if (!$list = $cache->get($cache_key))
	        {
	            $query = $this->getQuery(true);
	            $list = $query->getTotal();
	
	            $cache->store($list, $cache_key);
	        }
	
	        $this->_total = $list;
	
	    }
	    return $this->_total;
	}
	
	public function getItem( $pk=null, $refresh=false )
	{
	    $pk = $pk ? $pk : $this->getID();
	    
	    if (empty($this->_items[$pk]) || $refresh)
	    {
	        $cache_key = $pk;
	
	        $classname = 'calendarmodeleventinstances';
	        $cache = JFactory::getCache( $classname . '.item', '' );
	        $cache->setCaching($this->cache_enabled);
	        $cache->setLifeTime($this->cache_lifetime);
	        $item = $cache->get($cache_key);
	        if (!$item || $refresh)
	        {
	            $id_object = $this->getIdForQuery( $cache_key );
	
	            if (!empty($id_object))
	            {
	                $item = $this->loadByID( $id_object );
	            }
	
	            if (!empty($item))
	            {
	                $this->prepareItem( $item, $id_object->id, $refresh );
	            }
	
	            $cache->store($item, $cache_key);
	        }
	
	        $this->_items[$pk] = $item;
	
	    }
	
	    return $this->_items[$pk];
	}
	
	protected function prepareItem( &$item, $key=0, $refresh ) 
	{
		$this->setJoomlaProperties( $item, $refresh );
		
	    $item->link = 'index.php?option=com_calendar&view=events&task=edit&id=' . $item->getShow()->getDataSourceID();
	    $item->link_edit = 'index.php?option=com_calendar&view=eventinstances&task=edit&id=' . $item->getDataSourceID();
	    $item->link_view = 'index.php?option=com_calendar&view=eventinstances&task=view&id=' . $item->event_id . '&instance_id=' . $item->eventinstance_id;
	    $item->link_detail = 'index.php?option=com_calendar&view=event&id=' . $item->eventinstance_id;
	    $item->link_show = 'index.php?option=com_calendar&view=event&task=show&id=' . $item->show->getDataSourceID();
	    $item->checked_out = false;
	     
	    $date_range = '';
	    if (!empty($item->show->firstDate)) {
	        $date_range .= $item->show->firstDate->format('n/j');
	    }
	    if (!empty($item->show->lastDate) && $item->show->lastDate != $item->show->firstDate) {
	        if (!empty($date_range)) {
	            $date_range .= '&#8211;';
	        }
	        $date_range .= $item->show->lastDate->format('n/j');
	    }
	    $item->date_range = $date_range;

	    $item->season_title = $item->show->season;
	    $item->series_title = ''; // $item->getSeriesTitle();
	    if (!empty($item->show->series)) {
	    	foreach ($item->show->series as $series) {
	    		if (empty($item->series_title)) {
	    			$item->series_title = $series->title;
	    		}
	    	}
	    }
	    $item->artist_website = ''; // where is this?
	    
        $model = JModel::getInstance('Events', 'CalendarModel');
		  
        $item->event = $model->getItem( $item->show->getDataSourceID(), $refresh, $item->show );
          
        $item->event_full_image = !empty($item->eventinstance_full_image) ? $item->eventinstance_full_image : @$item->event->event_full_image; 
        $item->event_full_image = str_replace( array('_220', '_460'), array('_940', '_940'), $item->event_full_image );
        $item->event_small_image = str_replace( array('_940', '_460'), array('_220', '_220'), $item->event_full_image );
        
	    $item->tags_site = array(); // TODO make this dependent on a filter being set to true, such as: filter_sitetags = true
	    
	    $event_description_short = $item->show->fullDescription ? $item->show->fullDescription : $item->show->shortDescription;
	    if ($this->defines->get('strip_tags_eventinstance_description_short')) {
	    	$event_description_short = strip_tags( $event_description_short, '<p><br><a><i><img>' );
	    }
	    if (!empty($item->eventinstance_description)) {
	    	$event_description_short = $item->eventinstance_description;
	    }
	    $item->event_description_short = $event_description_short;
	    
	    if (!empty($item->eventinstance_title) && $item->eventinstance_title != $item->title) {
	        $item->title = $item->eventinstance_title;
	    }
	    
	    $item->eventinstance_display_prices = $item->show->displayPrices ? $item->show->displayPrices : null;
	    if (!empty($item->eventinstance_prices)) {
	        $item->eventinstance_display_prices = $item->eventinstance_prices; 
	    }
	    
	}
	
	public function setJoomlaProperties( &$item, $refresh=false )
	{
		static $joomla_properties;
		 
		$cache_key = $item->getDataSourceID();
		 
		if (empty($joomla_properties[$cache_key]) || $refresh)
		{
			$classname = 'calendarmodeleventinstances';
			$cache = JFactory::getCache( $classname . '.joomla-properties', '' );
			$cache->setCaching($this->cache_enabled);
			$cache->setLifeTime($this->cache_lifetime);
			$cached_item = $cache->get($cache_key);
			if (!$cached_item || $refresh)
			{
				$cached_item = $this->getTable();
				$cached_item->load( array( 'datasource_id'=>$cache_key ) );
	
				$cache->store($cached_item, $cache_key);
			}
		  
			$joomla_properties[$cache_key] = $cached_item;
		}
	
		if (!empty($joomla_properties[$cache_key]))
		{
			$ignored_joomla_properties = array('eventinstance_id');
			
			$vars = get_object_vars($item);
			$props = get_object_vars($joomla_properties[$cache_key]);
			foreach ($props as $key => $value)
			{
				if (in_array($key, $ignored_joomla_properties)) {
					continue;
				}
				
				// don't overwrite anything from primary data source
				if (!array_key_exists($key, $vars))
				{
					$item->$key = $value;
				}
			}
		}
	}
	
	public function loadByID( $id_object )
	{
		$return = false;
		
	    $query = \MappableQuery\MappableQuery::factory('JALC\EventsArtists\Queries\PerformanceQuery');
	
	    switch ($id_object->data_source)
	    {
	        case "t":
	            $return = $query->getByTessituraID($id_object->id);
	            break;
	        case "av":
	            $query->setDizzysOnly(false);
	            $return = $query->getByArtsvisionID($id_object->id);
	            break;
	    }
	
	    return $return;
	}
	
	/**
	 * 
	 * @param unknown_type $pk
	 */
    public function getSurrounding( $pk=null )
	{
	    $return = array();
	    $return["prev"] = '';
	    $return["next"] = '';
	    
	    // if $pk is null, use the state from the model, where there should be a setId()
	    // if $pk is present, assume it is an eventinstance ID, get the state from the model and find the flankers
	    	    
	    // get the selected item
	    $item = $this->getItem( $pk );
	    
	    // get the list of items using the rest of the state, without any pagination limits (in case this is the list item in one of the pages of paginated lists)
	    $event_helper = new CalendarHelperEvent();
	    $state = $event_helper->getState();
	    foreach ( $state as $key => $value )
	    {
	        $this->setState( $key, $value );
	    }
	    $list = $this->getList();
	    
	    // loop through the items, looking for a match in the list against the current item, 
	    // then return the items before and after
	    $count = count($list);
	    $found = false;
	    $prev_id = '';
	    $next_id = '';
	    
	    for ($i=0; $i < $count && empty($found); $i++)
	    {
	        $row = $list[$i];
	        if ($row->getDataSourceID() == $item->getDataSourceID())
	        {
	            $found = true;
	            $prev_num = $i - 1;
	            $next_num = $i + 1;
	            if (!empty($list[$prev_num])) {
	                $prev_id = $list[$prev_num]->getDataSourceID();
	            }
	            if (!empty($list[$next_num])) {
	                $next_id = $list[$next_num]->getDataSourceID();
	            }
	        }
	    }
	     
	    $return["prev"] = $prev_id;
	    $return["next"] = $next_id;
	    return $return;
	}
	
    /**
     * 
     * @param unknown_type $key_values
     * @return multitype:NULL
     */
	public function getIDsFromJoomlaFilter( $key_values=array() ) 
	{
	    $return = array();

	    $db = $this->getDBO();
	    
	    $query = new DSCQuery();
	    $query->select( array("tbl.datasource_id") );
	    $query->from('#__calendar_events AS tbl');
	    foreach ( $key_values as $key=>$values ) 
	    {
	        $where = array();
	        foreach ($values as $value) {
	            $v = $db->Quote($value);
	            $where[] = $key . "=" . $v; 
	        }
	        $query->where( '(' . implode( ' OR ', $where ) . ')' );
	    }
	    
	    $db->setQuery( (string) $query );
	    if ($items = $db->loadObjectList()) {
	        foreach ($items as $item) {
	            if (!empty($item->datasource_id)) {
	                $return[] = $item->datasource_id;
	            }
	        }
	    }
    
	    return $return;
	}
	
	public function getIDsFromAdditionalEventTypes( $filter_types, $return=array() )
	{
	    $db = $this->getDBO();
	     
	    $query = new DSCQuery();
	    $query->select( array("tbl.datasource_id") );
	    $query->from('#__calendar_events AS tbl');
	    $query->join('left',  '#__calendar_eventtypes AS et ON et.event_id = tbl.event_id');
	    $query->where( "et.type_id IN ('" . implode( "', '", $filter_types) . "')" );
	     
	    $db->setQuery( (string) $query );
	    if ($items = $db->loadObjectList()) {
	        foreach ($items as $item) {
	            if (!empty($item->datasource_id) && !in_array($item->datasource_id, $return)) {
	                $return[] = $item->datasource_id;
	            }
	        }
	    }
	     
	    return $return;
	}

	public function getOffsetDateTime( $item ) 
	{
	    $eventinstance_date = $item->startDateTime->format('Y-m-d');
	    $eventinstance_time = $item->startDateTime->format('H:i:s');
	    $eventinstance_end_date = $item->startDateTime->format('Y-m-d');
	    $eventinstance_end_time = $item->startDateTime->format('H:i:s');
	     
	    /*
	    $eventinstance_date = $item->startDateTime->format('Y-m-d');
	    $eventinstance_time = $item->startDateTime->format('g:ia');
	    $eventinstance_end_time = $item->startDateTime->format('g:ia');
	    list( $year, $month, $day ) = explode( '-', $eventinstance_date );
	    
	    $config = JFactory::getConfig();
	    $offset = $config->getValue('config.offset');
	    $localtime = localtime();
	    if ($localtime[8] > 0)
	    {
	        $offset = $offset + 1;
	    }
	    $diff = '+';
	    if ($offset < 0) {
	        $diff = '-';
	    }
	    $offset = 0;
	    $eventinstance_time = date( 'H:i:s', strtotime($eventinstance_time . " " . $diff . $offset . " hours") );
	    $eventinstance_end_date = date( 'Y-m-d', strtotime($eventinstance_date . " " .$eventinstance_end_time . " " . $diff . $offset . " hours") );
	    $eventinstance_end_time = date( 'H:i:s', strtotime($eventinstance_end_time . " " . $diff . $offset . " hours") );
        */
	    
	    $return = new JObject();
	    //$return->diff = $diff;
	    //$return->offset = $offset;
	    //$return->localtime = $localtime;
	    $return->eventinstance_date = $eventinstance_date;
	    $return->eventinstance_time = $eventinstance_time;
	    $return->eventinstance_end_date = $eventinstance_end_date;
	    $return->eventinstance_end_time = $eventinstance_end_time;
	    
	    return $return;
	}
	
	public function getICal( $item )
	{
	    $offsetDateTime = $this->getOffsetDateTime( $item );
	    
	    $eventinstance_date = $offsetDateTime->eventinstance_date;
	    $eventinstance_time = $offsetDateTime->eventinstance_time;
	    $eventinstance_end_date = $offsetDateTime->eventinstance_end_date;
	    $eventinstance_end_time = $offsetDateTime->eventinstance_end_time;
	    
	    $eventinstance_location = $item->venue->name;
	    $eventinstance_title = $item->title;
	    $eventinstance_description = $item->show->shortDescription;
	    $ical_filename = $eventinstance_title . '_' . $eventinstance_date . '_' . $item->startDateTime->format('gia');
	    
	    Calendar::load('CalendarICal', 'library.ical');
	    $ical = new CalendarICal();
	    $ical->setDTStart( $eventinstance_date, $eventinstance_time );
	    $ical->setDTEnd( $eventinstance_end_date, $eventinstance_end_time );
	    $ical->setHtmlProperty( 'location', strip_tags($eventinstance_location) );
	    $ical->setHtmlProperty( 'summary', strip_tags($eventinstance_title) );
	    $ical->setHtmlProperty( 'description', htmlspecialchars_decode( strip_tags($eventinstance_description) ) );
	    $ics = $ical->getContents();
	    
	    $directory = 'tmp';
	    $filename = JFilterOutput::stringURLSafe( $ical_filename ) . '.ics';
	    
	    $result = JFile::write( JPATH_BASE . "/" . $directory . "/" . $filename, $ics);
	    if( !$result )
	    {
	        $this->setError( JText::_('Error saving iCal file.') );
	        $ical = false;
	    }
	    else
	    {
	        $ical = $directory . '/' . $filename;
	    }
	    
	    return $ical;
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
	
	/**
	 * Given an array of ID strings, will return an array
	 * where the keys are the ID strings,
	 * and their values are int (the number of available seats/tickets)
	 *   
	 * @param unknown_type $items
	 */
	public function getAvailability( $ids ) 
	{
	    $query = \MappableQuery\MappableQuery::factory('JALC\EventsArtists\Queries\PerformanceQuery');
	    $return = $query->getAvailability( $ids );
	    return $return;
	}

	/**
	 * Given an eventinstance, will get its actionbutton
	 * returning null if no actionbutton should be displayed
	 * otherwise returning an actionbutton object
	 *  
	 * @param object $item
	 * @param array $availability
	 */
	public function getActionbutton( $item, $availability=array() ) 
	{
		$return = null;
		
		if (!is_array($availability)) 
		{
			$availability = $this->getAvailability( array( $item->getDataSourceID() ) );
		}
		
		if (!empty($availability[$item->getDataSourceID()]) 
				&& $availability[$item->getDataSourceID()] > 0 
				&& $this->getPurchaseUrl( $item ) ) {
			$return = $this->getTable( 'Actionbuttons' );
			$return->url = $this->getPurchaseUrl( $item ); // this is the default actionbutton url
			$return->classes = array();
			$return->classes_span = array( 'one-line' );
			$return->label = JText::_( $this->getActionButtonLabel($item) );
			
			// is the actionbutton set by the event type?
			if (!empty($item->event->event_type->actionbutton_id) && empty($item->event->event_type->hide_actionbutton)) 
			{
			    $ab = $this->getTable( 'Actionbuttons' );
			    $ab->load($item->event->event_type->actionbutton_id);
			    $return->actionbutton_override_main_site = $ab->actionbutton_override_main_site; 
			    if (!empty($ab->actionbutton_override_main_site)) {
			        if (!empty($ab->actionbutton_url_default)) {
			            $return->url = $ab->actionbutton_url_default;
			        }
			        $return->label = $ab->actionbutton_name;			        
			    }
			}

			// is override set at the event level?
			if (!empty($item->event->event_actionbutton_url)) {
			    $return->url = $item->event->event_actionbutton_url;
			}
			if (!empty($item->event->event_actionbutton_label)) {
			    $return->label = $item->event->event_actionbutton_label;
			}
			
			// is override set at the eventinstance level?
			if (!empty($item->actionbutton_url)) {
			    $return->url = $item->actionbutton_url;
			}
			if (!empty($item->actionbutton_string)) {
			    $return->label = $item->actionbutton_string;
			}
			
		} elseif (isset($availability[$item->getDataSourceID()]) && $availability[$item->getDataSourceID()] < 0) { 
			$return = $this->getTable( 'Actionbuttons' );
			$return->url = null;
			$return->classes = array();
			$return->classes_span = array( 'two-lines' );
			$return->label = 'On Sale<br/>Soon';
			// temporarily this is disabled
			$return = null;
		} elseif ($this->getPurchaseUrl( $item )) { // TODO change to $this->getPurchaseUrl( $item )
			$return = $this->getTable( 'Actionbuttons' );
			$return->url = null;
			$return->classes = array( 'sold-out' );
			$return->classes_span = array( 'one-line' );
			$return->label = 'Sold Out';
        } else {
			$return = $this->getTable( 'Actionbuttons' );
			$return->url = null;
			$return->classes = array();
			$return->classes_span = array( 'two-lines' );
			$return->label = 'Call<br/>to RSVP';
			// temporarily this is disabled
			$return = null;
        }
        
        if (empty($availability)) {
            $return = null;
        }
        
        // TODO additional logic, such as whether or not the event type overrides the actionbutton url
        if (!empty($item->event->event_type->hide_actionbutton)) {
        	$return = null;
        }
        	
		return $return;
	}
}
