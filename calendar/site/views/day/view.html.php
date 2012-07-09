<?php
/**
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Calendar::load( 'CalendarViewBase', "views.base", array( 'site' => 'site', 'type' => 'components', 'ext' => 'com_calendar' ) );

class CalendarViewDay extends CalendarViewBase
{
	function _default( $tpl = '' )
	{
		// order data by time
		$model = $this->getModel( );
		$model->setState( 'order', 'tbl.eventinstance_date' );
		$model->setState( 'direction', 'ASC' );
		$query = $model->getQuery( );
		$query->order( 'tbl.eventinstance_start_time' );
		$model->setQuery( $query );
		
		$items = $model->getList( );
		
		$this->assign( 'items', $items );
		
		parent::_default( $tpl );
	}
}
