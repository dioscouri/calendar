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

class CalendarModelSeries extends CalendarModelBase
{
	protected function _buildQueryWhere( &$query )
	{
		$filter = $this->getState( 'filter' );
		$filter_id_from = $this->getState( 'filter_id_from' );
		$filter_id_to = $this->getState( 'filter_id_to' );
		$filter_name = $this->getState( 'filter_name' );
		$filter_title = $this->getState( 'filter_title' );
		$filter_description = $this->getState( 'filter_description' );
		$filter_search = $this->getState( 'filter_search' );
		
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
	}
	
	public function getList( $refresh = false )
	{
		$list = parent::getList( $refresh );
		foreach ( $list as $item )
		{
			$item->link = 'index.php?option=com_calendar&view=series&task=edit&id=' . $item->series_id;
			$item->link_view = 'index.php?option=com_calendar&view=series&task=display&id=' . $item->series_id;
		}
		return $list;
	}
}
