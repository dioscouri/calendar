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

class CalendarModelSecondCategories extends CalendarModelBase
{
	protected function _buildQueryWhere( &$query )
	{
		$filter = $this->getState( 'filter' );
		$filter_id_from = $this->getState( 'filter_id_from' );
		$filter_id_to = $this->getState( 'filter_id_to' );
		$filter_name = $this->getState( 'filter_name' );
		$enabled = $this->getState( 'filter_enabled' );
		$parentid = $this->getState( 'filter_parentid' );
		$level = $this->getState( 'filter_level' );
		$filter_event = $this->getState( 'filter_event' );
		$filter_ids = $this->getState( 'filter_ids' );
		
		if ( $filter )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter ) ) ) . '%' );
			
			$where = array( );
			$where[] = 'LOWER(tbl.category_id) LIKE ' . $key;
			$where[] = 'LOWER(tbl.category_name) LIKE ' . $key;
			$where[] = 'LOWER(tbl.category_description) LIKE ' . $key;
			
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		if ( strlen( $filter_id_from ) )
		{
			if ( strlen( $filter_id_to ) )
			{
				$query->where( 'tbl.category_id >= ' . ( int ) $filter_id_from );
			}
			else
			{
				$query->where( 'tbl.category_id = ' . ( int ) $filter_id_from );
			}
		}
		if ( strlen( $filter_id_to ) )
		{
			$query->where( 'tbl.category_id <= ' . ( int ) $filter_id_to );
		}
		if ( strlen( $filter_name ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_name ) ) ) . '%' );
			$query->where( 'LOWER(tbl.category_name) LIKE ' . $key );
		}
		if ( strlen( $enabled ) )
		{
			$query->where( 'tbl.category_enabled = ' . $this->_db->Quote( $enabled ) );
		}
		if ( strlen( $parentid ) )
		{
			$parent = $this->getTable( );
			$parent->load( $parentid );
			if ( !empty( $parent->category_id ) )
			{
				$query->where( 'tbl.lft BETWEEN ' . $parent->lft . ' AND ' . $parent->rgt );
			}
		}
		
		if ( strlen( $level ) )
		{
			$query->where( "tbl.parent_id = '$level'" );
			if ( $level > 1 )
			{
				$query->where( "parent.category_id = '$level'" );
			}
		}
		
		if ( !empty( $filter_ids ) && is_array( $filter_ids ) )
		{
		    $where = array();
		    foreach( $filter_ids as $id )
		    {
		        $where[] = 'tbl.category_id = ' . ( int ) $id;
		    }
		    	
		    $query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		$query->where( 'tbl.isroot != 1' );
		$query->where( 'tbl.lft BETWEEN parent.lft AND parent.rgt' );
	}
	
	/**
	 * Builds FROM tables list for the query
	 */
	protected function _buildQueryFrom( &$query )
	{
		$name = $this->getTable( )->getTableName( );
		$query->from( $name . ' AS tbl' );
		$query->from( $name . ' AS parent' );
		
	}
	
	protected function _buildQueryFields( &$query )
	{
		$level = $this->getState( 'filter_level' );
		
		$field = array( );
		$field[] = " COUNT(parent.category_id)-1 AS level ";
		$field[] = " CONCAT( REPEAT(' ', COUNT(parent.category_name) - 1), tbl.category_name) AS name ";
		
		if ( $level > 1 )
		{
			$field[] = " parent.category_id AS parent_category_id ";
			$field[] = " parent.category_name AS parent_category_name ";
		}
		
		$field[] = "
            (
            SELECT 
                COUNT(*)
            FROM
                #__calendar_eventcategories AS xref 
            WHERE 
                xref.category_id = tbl.category_id 
            ) 
        AS events_count ";
		
		$query->select( $this->getState( 'select', 'tbl.*' ) );
		$query->select( $field );
	}
	
	/**
	 * Builds a GROUP BY clause for the query
	 */
	protected function _buildQueryGroup( &$query )
	{
		$query->group( 'tbl.category_id' );
	}
	
	/**
	 * Builds a generic SELECT COUNT(*) query
	 */
	protected function _buildResultQuery( )
	{
		$grouped_query = new CalendarQuery( );
		$grouped_query->select( $this->getState( 'select', 'COUNT(*)' ) );
		
		$this->_buildQueryFrom( $grouped_query );
		$this->_buildQueryJoins( $grouped_query );
		$this->_buildQueryWhere( $grouped_query );
		$this->_buildQueryGroup( $grouped_query );
		$this->_buildQueryHaving( $grouped_query );
		
		$query = new CalendarQuery( );
		$query->select( 'COUNT(*)' );
		$query->from( '(' . $grouped_query . ') as grouped_count' );
		
		// Allow plugins to edit the query object
		$suffix = ucfirst( $this->getName( ) );
		$dispatcher = JDispatcher::getInstance( );
		$dispatcher->trigger( 'onAfterBuildResultQuery' . $suffix, array( &$query ) );
		
		return $query;
	}
	
	public function getList( $refresh = false )
	{
		$list = parent::getList( $refresh );
		
		// If no item in the list, return an array()
		if ( empty( $list ) )
		{
			return array( );
		}
		
		foreach ( $list as $item )
		{
			$item->slug = $item->category_alias ? ":$item->category_alias" : "";
			$item->link = 'index.php?option=com_calendar&view=secondcategories&task=edit&id=' . $item->category_id;
		}
		
		return $list;
	}
}
