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

class CalendarModelEvents extends CalendarModelBase
{
    function __construct($config = array())
    {
        parent::__construct($config);
        $this->setState( 'select', array('show.*', 'primaryVenue.*', 'series.*', 'presenters.*') );
        
        $this->loadMQ();
    }
    
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
		$filter_upcoming_enabled = $this->getState( 'filter_upcoming_enabled' );
		$filter_digital_signage = $this->getState( 'filter_digital_signage' );
		$filter_date_from	= $this->getState('filter_date_from');
		$filter_date_to		= $this->getState('filter_date_to');
		$filter_datetype	= $this->getState('filter_datetype');
		$filter_venue_name = $this->getState( 'filter_venue_name' );
		$filter_venue_id = $this->getState( 'filter_venue_id' );
		$filter_venues = $this->getState('filter_venues');
		$filter_types = $this->getState( 'filter_types' );
		$filter_type = $this->getState( 'filter_type' );
		
		if (empty($filter_types) && !empty($filter_type)) {
		    $filter_types = array();
		    $filter_types[] = $filter_type;
		    $this->setState('filter_types', $filter_types);
		}
				
		if (empty($filter_venues) && !empty($filter_venue_id)) {
		    $filter_venues = array();
		    $filter_venues[] = $filter_venue_id;
		    $this->setState('filter_venues', $filter_venues);
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
        
		if ( strlen($filter) )
		{
			$key = trim( strtolower( $filter ) );
			$finds['title'] = $key;
			$query->find($finds);
		}
		
		if ( strlen($filter_name) )
		{
		    $key = trim( strtolower( $filter_name ) );
		    $finds['title'] = $key;
		    $query->find($finds);
		}
		
		if ( strlen( $filter_id_from ) )
		{
    		$query->find(array(
    		        'artsvisionID' => $filter_id_from,
    		        'tessituraID' => $filter_id_from
    		));
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
	                case "tp":
	                	$where_tp[] = $id_parts[1];
	                	break;
		        }
		    }
		
		    if (!empty($where_av)) {
		        $query->findByArtsvisionID($where_av);
		    } else {
		        $query->tessOnly = true;
		    }
		
		    if (!empty($where_tess)) {
		        $query->findByTessituraID($where_tess);
		    } else {
		        $query->artsvisionOnly = true;
		    }
		}

		if ( strlen( $filter_enabled ) )
		{
		    $query->findPublished();
		}
		
		if ( strlen( $filter_series ) )
		{
		    $query->findBySeriesTitle( $filter_series );
		}
		
		/*		
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
        */

		$query->setDizzysOnly(false);
		
	}
	
	protected function _buildQueryJoins( &$query )
	{
	    /*
		$query->join( 'LEFT', '#__calendar_categories AS c ON tbl.event_primary_category_id = c.category_id' );
		
		$filter_eventcategories = $this->getState( 'filter_eventcategories' );
		$filter_secondary_categories = $this->getState( 'filter_secondary_categories' );
		if ( strlen( $filter_eventcategories ) || strlen( $filter_secondary_categories ))
		{
			$query->join( 'LEFT', '#__calendar_eventcategories AS scat ON tbl.event_id = scat.event_id' );
			$query->join( 'LEFT', '#__calendar_secondcategories AS cats ON scat.category_id = cats.category_id' );
		}
		$query->join( 'LEFT', '#__calendar_series AS series ON tbl.series_id = series.series_id' );
		*/
	}
	
	protected function _buildQueryFields( &$query )
	{
	    $fields = $this->getState( 'select' );
	    if (empty($fields)) {
            $fields = array('show.*', 'primaryVenue.*', 'series.*');
            $this->setState( 'select', $fields);
	    }
		 
	    $query->select($fields);
		
		/*
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
		*/
	}
	
	/**
	 * Builds a generic ORDER BY clause based on the model's state
	 */
	protected function _buildQueryOrder( &$query )
	{
	    /*
		$order = 'tbl.event_id';
		$direction = $this->_db->getEscaped( strtoupper( $this->getState( 'direction' ) ) );
		
		if ( $order )
		{
			$query->order( "$order $direction" );
		}
		*/
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
	
	    $query = \MappableQuery\MappableQuery::factory('JALC\EventsArtists\Queries\ShowQuery');
	
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
	    
    	    $classname = strtolower( get_class($this) );
    	    $cache = JFactory::getCache( $classname . '.list', '' );
    	    $cache->setCaching($this->cache_enabled);
    	    $cache->setLifeTime($this->cache_lifetime);
    	    $list = $cache->get($cache_key);
    	    if (!$list || $refresh)
    	    {
        	    $query = $this->getQuery(true);			 
        	    $list = $query->fetchObjects( (int) $this->getState('limitstart', 0), $this->getLimit() );
	    
        	    if ( empty( $list ) )
        	    {
        	        $list = array( );
        	    }
        	     
        	    foreach ( $list as $key=>$item )
        	    {
                    $this->prepareItem( $item, $key, $refresh );
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
	
	        $classname = strtolower( get_class($this) );
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
	
	/**
	 * Matt Browne modified this method - added the $showEntity paraemter
	 * so this method doesn't call ShowQuery unnecessarily if we already have the show entity
	 * (which is the case when calling this method from CalendarModelEventinstances::prepareItem)
	 */
	public function getItem( $pk=null, $refresh=false, $showEntity=null )
	{
	    if (empty($this->_item) || $refresh)
	    {
	        $cache_key = $pk ? $pk : $this->getID();
	
	        $classname = strtolower( get_class($this) );
	        $cache = JFactory::getCache( $classname . '.item', '' );
	        $cache->setCaching($this->cache_enabled);
	        $cache->setLifeTime($this->cache_lifetime);
	        $item = $cache->get($cache_key);
	        if (!$item || $refresh)
	        {
	            $id_object = $this->getIdForQuery( $cache_key );
	
				if ($showEntity) {
					$item = $showEntity;
				}
	            elseif (!empty($id_object))
	            {
	                $item = $this->loadByID( $id_object );
	            }
	
	            if (!empty($item))
	            {
	                $this->prepareItem( $item, $id_object->id, $refresh );
	
	                $overridden_methods = $this->getOverriddenMethods( get_class($this) );
	                if (!in_array('getItem', $overridden_methods))
	                {
	                    $dispatcher = JDispatcher::getInstance();
	                    $dispatcher->trigger( 'onPrepare'.$this->getTable()->get('_suffix'), array( &$item ) );
	                }
	            }
	
	            $cache->store($item, $cache_key);
	        }	        
	
	        $this->_item = $item;
	
	    }
	
	    return $this->_item;
	}
	
	protected function prepareItem( &$item, $key=0, $refresh=false )
	{
	    $this->setJoomlaProperties( $item, $refresh );
	    
	    $item->link = 'index.php?option=com_calendar&view=events&task=edit&id=' . $item->getDataSourceID();
	    $item->link_detail = 'index.php?option=com_calendar&view=event&task=show&id=' . $item->getDataSourceID();
	    $item->checked_out = false;
	    
	    $item->event_small_image = str_replace( '_940', '_220', $item->event_full_image );
	    
	    $item->series_title = '';
	    if ($item->series) {
	        $item->series_title = @$item->series->title;
	    }
	    
	    $date_range = '';
	    if (!empty($item->firstDate)) {
	        $date_range .= $item->firstDate->format('n/j');
	    }
	    if (!empty($item->lastDate) && $item->lastDate != $item->firstDate) {
	        if (!empty($date_range)) {
	            $date_range .= '&#8211;';
	        }
	        $date_range .= $item->lastDate->format('n/j');
	    }
	    $item->date_range = $date_range;
	    
	    $event_description_short = $item->fullDescription ? $item->fullDescription : $item->shortDescription;
	    if ($this->defines->get('strip_tags_eventinstance_description_short')) {
	    	$event_description_short = strip_tags( $event_description_short, '<p><br>' );
	    }
	    $item->event_description_short = $event_description_short;
	    
	    $item->event_types = !is_array($item->event_types) ? json_decode( $item->event_types, true ) : array(); 
	    
	    $this->cacheItem( $item );
	}

	public function setJoomlaProperties( &$item, $refresh=false )
	{
	    static $joomla_properties;
	    
	    $cache_key = $item->getDataSourceID();
	    
	    if (empty($joomla_properties[$cache_key]) || $refresh)
	    {
	        $classname = strtolower( get_class($this) );
	        $cache = JFactory::getCache( $classname . '.joomla-properties', '' );
	        $cache->setCaching($this->cache_enabled);
	        $cache->setLifeTime($this->cache_lifetime);
	        $cached_item = $cache->get($cache_key);
	        if (!$cached_item || $refresh)
	        {
	            $cached_item = $this->getTable();
	            $cached_item->load( array( 'datasource_id'=>$cache_key ) );

	            $type = JModel::getInstance('Types', 'CalendarModel');
	            $type_item = $type->getItem( $cached_item->type_id );
	            $cached_item->event_type_name = @$type_item->type_name;
	            $cached_item->event_type = $type_item;

	            $cached_item->event_types_additional = array();
	            $ids = (is_array($cached_item->event_types)) ? $cached_item->event_types : json_decode( $cached_item->event_types, true );
	            if (!empty($ids)) 
	            { 
    	            $type->setState( 'filter_id', $ids );
    	            $cached_item->event_types_additional = $type->getList( true );
	            }
	            
	            $cache->store($cached_item, $cache_key);
	        }
	    
	        $joomla_properties[$cache_key] = $cached_item;
	    }

	    if (!empty($joomla_properties[$cache_key])) 
	    {
	        $vars = get_object_vars($item);
	        $props = get_object_vars($joomla_properties[$cache_key]);
	        foreach ($props as $key => $value)
	        {
	            // don't overwrite anything from primary data source
	             if (!array_key_exists($key, $vars)) 
	             {
	                 $item->$key = $value;
	             }
	        }
	    }
	}
	
	public function loadByID( $id_object, $showEntity=null )
	{
		$return = false;
		
	    $query = \MappableQuery\MappableQuery::factory('JALC\EventsArtists\Queries\ShowQuery');
	
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
            $row_ds_id = $row->getDataSourceID();
            $item_ds_id = (is_object($item) && $item->getDataSourceID()) ? $item->getDataSourceID() : null; 
            if ($row_ds_id == $item_ds_id)
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
	        $filter_date_from = date('Y-m-d');
	    }
	    $filter_date_to = date('Y-m-d 23:59:59', strtotime( $filter_date_from . ' +6 month') );
	    
	    JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
	    $model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
		$model->setState( 'filter_events', array( $event_id ) );
		$model->setState( 'filter_enabled', '1' );
		$model->setState( 'filter_date_from', $filter_date_from );
		$model->setState( 'filter_date_to', $filter_date_to );
		$model->setState( 'limit', '1' );
		
		$item = null;
		if ($items = $model->getList(true))
		{
		    $item = $items[0];
		}
		return $item;
	}
	
	/**
	 * 
	 * @param unknown_type $event_id
	 * @param unknown_type $date
	 * @return unknown
	 */
	public function getInstances( $event_id, $filter_date_from=null, $filter_date_to=null, $refresh=true )
	{
		if (empty($filter_date_from))
		{
			$filter_date_from = date('Y-m-d');
		}
		
		if (empty($filter_date_to))
		{
			$filter_date_to = date('Y-m-d', strtotime( $filter_date_from . " +8 months" ) );
		}
		 
		JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
		$model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
		$model->setState( 'filter_events', array( $event_id ) );
		$model->setState( 'filter_enabled', '1' );
		$model->setState( 'filter_date_from', $filter_date_from );
		$model->setState( 'filter_date_to', $filter_date_to );
		
		$items = $model->getList($refresh);
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
	    $query = \MappableQuery\MappableQuery::factory('JALC\EventsArtists\Queries\ShowQuery');
	    $return = $query->getAvailability( $ids );
	    return $return;
	}

	/**
	 * Given an event, will get its actionbutton
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
	    
	    $next_instance = $this->getNextInstance( $item->getDataSourceID() );
	
	    if (!empty($availability[$item->getDataSourceID()])
	            && $availability[$item->getDataSourceID()] > 0
	    		&& is_object($next_instance)
	    		&& method_exists( $next_instance, 'getPurchaseUrl' )
	            && $next_instance->getPurchaseUrl()) {
	        $return = $this->getTable( 'Actionbuttons' );
	        $return->url = $next_instance->getPurchaseUrl();
	        $return->classes = array();
	        $return->classes_span = array( 'one-line' );
	        $return->label = JText::_( $next_instance->getActionButtonLabel() );
	    } elseif (isset($availability[$item->getDataSourceID()]) && $availability[$item->getDataSourceID()] < 0) {
	        $return = $this->getTable( 'Actionbuttons' );
	        $return->url = null;
	        $return->classes = array();
	        $return->classes_span = array( 'two-lines' );
	        $return->label = 'On Sale<br/>Soon';
	        // temporarily this is disabled
	        $return = null;
	    } elseif (is_object($next_instance) && method_exists( $next_instance, 'getPurchaseUrl' ) && $next_instance->getPurchaseUrl()) {
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
	    // is the event type overriding the actionbutton?  
	    if (!empty($item->event_type->actionbutton_id)) {
	        $return = $this->getTable('Actionbuttons');
	        $return->load( $item->event_type->actionbutton_id );
	        $return->label = $return->actionbutton_name;
	        $return->url = $return->actionbutton_url_default;
	    }
	    
	    // is the event type disabling the action button entirely (keep this last)?
	    if (!empty($item->event_type->hide_actionbutton)) {
	        $return = null;
	    }
	     
	    return $return;
	}
	
	public function getFullImage( $event ) 
	{
	    $image = null;
	    return $image;
	}
	
	/**
	 * Clean the cache
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public function clearCache()
	{
	    parent::clearCache();
	    
	    $classname = strtolower( get_class($this) );
	    parent::cleanCache($classname . '.joomla-properties');
	}
}
