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

class CalendarModelDailyevents extends CalendarModelBase
{
	protected function _buildQueryWhere( &$query )
	{
		$filter = $this->getState( 'filter' );
		$filter_id_from = $this->getState( 'filter_id_from' );
		$filter_id_to = $this->getState( 'filter_id_to' );
		$filter_name = $this->getState( 'filter_name' );
		$filter_venue_id = $this->getState( 'filter_venue_id' );
		
		if ( $filter )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter ) ) ) . '%' );
			
			$where = array( );
			$where[] = 'LOWER(tbl.dailyevent_id) LIKE ' . $key;
			$where[] = 'LOWER(tbl.dailyevent_name) LIKE ' . $key;
			
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( strlen( $filter_id_from ) )
		{
			if ( strlen( $filter_id_to ) )
			{
				$query->where( 'tbl.dailyevent_id >= ' . ( int ) $filter_id_from );
			}
			else
			{
				$query->where( 'tbl.dailyevent_id = ' . ( int ) $filter_id_from );
			}
		}
		
		if ( strlen( $filter_id_to ) )
		{
			$query->where( 'tbl.dailyevent_id <= ' . ( int ) $filter_id_to );
		}
		
		if ( $filter_name )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_name ) ) ) . '%' );
			$where = array( );
			$where[] = 'LOWER(tbl.dailyevent_name) LIKE ' . $key;
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( strlen( $filter_venue_id ) )
		{
			$query->having( 'tbl.venue_id = ' . (int) $filter_venue_id );
			
		}
	}
	
	public function getList( $refresh = false )
	{
		$list = parent::getList( $refresh );
		foreach ( $list as $item )
		{
			$item->link = 'index.php?option=com_calendar&view=dailyevents&task=edit&id=' . $item->dailyevent_id;
		}
		return $list;
	}
}
