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

class CalendarModelTypes extends CalendarModelBase
{
	protected function _buildQueryWhere( &$query )
	{
		$filter = $this->getState( 'filter' );
		$filter_id_from = $this->getState( 'filter_id_from' );
		$filter_id_to = $this->getState( 'filter_id_to' );
		$filter_id = $this->getState( 'filter_id' );
		$filter_name = $this->getState( 'filter_name' );
		$filter_class = $this->getState( 'filter_class' );
		$filter_admin_only = $this->getState( 'filter_admin_only' );
		
		if ( $filter )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter ) ) ) . '%' );
			
			$where = array( );
			$where[] = 'LOWER(tbl.type_id) LIKE ' . $key;
			$where[] = 'LOWER(tbl.type_name) LIKE ' . $key;
			
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( strlen( $filter_id_from ) )
		{
			if ( strlen( $filter_id_to ) )
			{
				$query->where( 'tbl.type_id >= ' . ( int ) $filter_id_from );
			}
			else
			{
				$query->where( 'tbl.type_id = ' . ( int ) $filter_id_from );
			}
		}
		
		if ( strlen( $filter_id_to ) )
		{
			$query->where( 'tbl.type_id <= ' . ( int ) $filter_id_to );
		}
		
		if ( $filter_name )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_name ) ) ) . '%' );
			$where = array( );
			$where[] = 'LOWER(tbl.type_name) LIKE ' . $key;
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( !empty( $filter_id ) ) 
		{
		    if (!is_array( $filter_id )) 
		    {
		        $filter_id = array( $filter_id );
		    }

		    $query->where( "tbl.type_id IN ('" . implode( "', '", $filter_id) . "')" );
		}
		
		if ( strlen( $filter_class ) )
		{
		    $query->where( "tbl.type_class = '" . $filter_class . "'" );
		}
		if ( strlen( $filter_admin_only ) )
		{
			$query->where( "tbl.admin_only = '" . $filter_admin_only . "'" );
		}
	}
	
	public function getList( $refresh = false )
	{
		$list = parent::getList( $refresh );
		foreach ( $list as $item )
		{
			$item->link = 'index.php?option=com_calendar&view=types&task=edit&id=' . $item->type_id;
		}
		return $list;
	}
}
