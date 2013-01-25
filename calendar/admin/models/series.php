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

class CalendarModelSeries extends CalendarModelBase
{
    function __construct($config = array())
    {
        parent::__construct($config);
        $this->setState( 'select', array('series.*') );
    
        $this->loadMQ();
    }
    
	protected function _buildQueryWhere( &$query )
	{
	    
		$filter = $this->getState( 'filter' );
		$filter_id_from = $this->getState( 'filter_id_from' );
		$filter_id_to = $this->getState( 'filter_id_to' );
		$filter_name = $this->getState( 'filter_name' );
		$filter_title = $this->getState( 'filter_title' );
		$filter_description = $this->getState( 'filter_description' );
		$filter_search = $this->getState( 'filter_search' );
		$filter_season = $this->getState( 'filter_season' );
		
		if (strlen($filter_season)) 
		{
		    $query->findBySeason( new JALC\Entities\Season($filter_season) );
		}
		
		/*
		if ( $filter )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter ) ) ) . '%' );
			
			$where = array( );
			$where[] = 'LOWER(tbl.series_id) LIKE ' . $key;
			$where[] = 'LOWER(tbl.series_name) LIKE ' . $key;
			
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( strlen( $filter_id_from ) )
		{
			if ( strlen( $filter_id_to ) )
			{
				$query->where( 'tbl.series_id >= ' . ( int ) $filter_id_from );
			}
			else
			{
				$query->where( 'tbl.series_id = ' . ( int ) $filter_id_from );
			}
		}
		
		if ( strlen( $filter_id_to ) )
		{
			$query->where( 'tbl.series_id <= ' . ( int ) $filter_id_to );
		}
		
		if ( strlen( $filter_name ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_name ) ) ) . '%' );			
			$query->where( 'LOWER(tbl.series_name) LIKE ' . $key );
		}
		
		if ( strlen( $filter_title ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_title ) ) ) . '%' );	
			$query->where( 'LOWER(tbl.series_title) LIKE ' . $key );
		}
		
		if ( strlen( $filter_description ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_description ) ) ) . '%' );	
			$query->where( 'LOWER(tbl.series_description) LIKE ' . $key );
		}
		
		if ( strlen( $filter_search ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_search ) ) ) . '%' );
			
			$where = array( );
			$where[] = 'LOWER(tbl.series_title) LIKE ' . $key;
			$where[] = 'LOWER(tbl.series_description) LIKE ' . $key;
			
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		*/
	}
	
	protected function _buildQueryFields( &$query )
	{
	    $fields = $this->getState( 'select' );
	    if (empty($fields)) {
	        $this->setState( 'select', array('series.*') );
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
	
	    $query = \MappableQuery\MappableQuery::factory('JALC\EventsArtists\Queries\SeriesQuery');
	
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
	    if (empty($this->_list))
	    {
	        $key = base64_encode(serialize($this->getState())) . '.list';
	
	        $classname = strtolower( get_class($this) );
	        $cache = JFactory::getCache( $classname . '.list', '' );
	        $cache->setCaching($this->cache_enabled);
	        $cache->setLifeTime($this->cache_lifetime);
	        $list = $cache->get($key);
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
	                $item->link = 'index.php?option=com_calendar&view=series&task=edit&id=' . $item->series_id;
	                $item->link_view = 'index.php?option=com_calendar&view=series&task=display&id=' . $item->series_id;
	                $item->checked_out = false;
	            }
	
	            $cache->store($list, $key);
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
	        $list = null;
	        
	        $key = base64_encode(serialize($this->getState())) . '.list-totals';
	
	        $classname = strtolower( get_class($this) );
	        $cache = JFactory::getCache( $classname . '.list-totals', '' );
	        $cache->setCaching($this->cache_enabled);
	        $cache->setLifeTime($this->cache_lifetime);
	        if (!$list = $cache->get($key))
	        {
	            $query = $this->getQuery(true);
	            $list = $query->getTotal();
	
	            $cache->store($list, $key);
	        }
	
	        $this->_total = $list;
	
	    }
	    return $this->_total;
	}
}
