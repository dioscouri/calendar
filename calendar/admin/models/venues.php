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

class CalendarModelVenues extends CalendarModelBase
{
    function __construct($config = array())
    {
        parent::__construct($config);
        $this->setState( 'select', array('venue.*') );
    
        $this->loadMQ();
    }
    
	protected function _buildQueryWhere( &$query )
	{
	    /*
		$filter = $this->getState( 'filter' );
		$filter_id_from = $this->getState( 'filter_id_from' );
		$filter_id_to = $this->getState( 'filter_id_to' );
		$filter_name = $this->getState( 'filter_name' );
		
		if ( $filter )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter ) ) ) . '%' );
			
			$where = array( );
			$where[] = 'LOWER(tbl.venue_id) LIKE ' . $key;
			$where[] = 'LOWER(tbl.venue_name) LIKE ' . $key;
			
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( strlen( $filter_id_from ) )
		{
			if ( strlen( $filter_id_to ) )
			{
				$query->where( 'tbl.venue_id >= ' . ( int ) $filter_id_from );
			}
			else
			{
				$query->where( 'tbl.venue_id = ' . ( int ) $filter_id_from );
			}
		}
		
		if ( strlen( $filter_id_to ) )
		{
			$query->where( 'tbl.venue_id <= ' . ( int ) $filter_id_to );
		}
		
		if ( $filter_name )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_name ) ) ) . '%' );
			$where = array( );
			$where[] = 'LOWER(tbl.venue_name) LIKE ' . $key;
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		*/
	}
	
	protected function _buildQueryFields( &$query )
	{
	    $fields = $this->getState( 'select' );
	    if (empty($fields)) {
	        $this->setState( 'select', array('venue.*') );
	        $fields = array();
	    }
	    
	    $query->select($fields);
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
	
	    $query = \MappableQuery\MappableQuery::factory('JALC\EventsArtists\Queries\VenueQuery');
	
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
        	    
        	    $filter_admin_only = $this->getState( 'filter_admin_only' );
        	    if (strlen( $filter_admin_only ))
        	    {
        	        if ($filter_ids = $this->getIDsFromJoomlaFilter( array('admin_only'=>$filter_admin_only ) )) {
        	            foreach ($list as $key=>$item) {
        	                if (!in_array($item->getDataSourceID(), $filter_ids)) {
        	                    unset($list[$key]);
        	                }
        	            }
        	        }        	        
        	    }
        	    	    
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
	
	public function getItem( $pk=null, $refresh=false )
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
	
	            if (!empty($id_object))
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
	     
	    $item->link = 'index.php?option=com_calendar&view=venues&task=edit&id=' . $item->getDataSourceID();
	    $item->checked_out = false;
	    
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

	public function loadByID( $id_object )
	{
		$return = false;
		
	    $query = \MappableQuery\MappableQuery::factory('JALC\EventsArtists\Queries\VenueQuery');
	
	    switch ($id_object->data_source)
	    {
	        case "t":
	            //$return = $query->getByTessituraID($id_object->id);
	            $query->find(array(
	                    'tessituraID' => $id_object->id
	            ));
	            $list = $query->fetchObjects();
	            $return = $list[0];
	            break;
	        case "av":
	            $query->find(array(
	                    'artsvisionID' => $id_object->id
	            ));
	            $list = $query->fetchObjects();
	            $return = $list[0];
	            break;
	        default:
	            $query->find(array(
	                    'artsvisionID' => $id_object->id,
	                    'tessituraID' => $id_object->id
	            ));
	            $list = $query->fetchObjects();
	            $return = $list[0];
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
}
