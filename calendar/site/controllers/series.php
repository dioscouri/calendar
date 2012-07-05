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

class CalendarControllerSeries extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		$this->set( 'suffix', 'series' );
	}
	
	function display( )
	{
		$series_id = JRequest::getInt( 'id' );
		
		// make date and time variables
		$date->month = JRequest::getVar( 'month' );
		$date->year = JRequest::getVar( 'year' );
		
		if ( empty( $date->month ) )
		{
			$date->month = date( 'm' );
		}
		if ( empty( $date->year ) )
		{
			$date->year = date( 'Y' );
		}
		
		$date->nextmonth = date( 'm', strtotime( $date->year . '-' . $date->month . ' +1 month' ) );
		$date->nextyear = date( 'Y', strtotime( $date->year . '-' . $date->month . ' +1 month' ) );
		$date->prevmonth = date( 'm', strtotime( $date->year . '-' . $date->month . ' -1 month' ) );
		$date->prevyear = date( 'Y', strtotime( $date->year . '-' . $date->month . ' -1 month' ) );
		
		$date->weekdays = array( 'Sunday' => 'SUN', 'Monday' => 'MON', 'Tuesday' => 'TUES', 'Wednesday' => 'WED', 'Thursday' => 'THU', 'Friday' => 'FRI', 'Saturday' => 'SAT' );
		$date->weekstart = 'SUN';
		$date->weekend = 'SAT';
		$date->numberofdays = date( 't', strtotime( $date->year . '-' . $date->month . '-01' ) );
		$date->monthstartday = date( 'l', strtotime( $date->year . '-' . $date->month . '-01' ) );
		
		if ( $date->monthstartday == 'Friday' || $date->monthstartday == 'Saturday' )
		{
			$date->numberofweeks = 6;
		}
		else
		{
			$date->numberofweeks = 5;
		}
		
		// get events
		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
		JTable::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'tables' );
		$model = JModel::getInstance( 'Eventinstances', 'CalendarModel' );
		$model->setState( 'filter_series', $series_id );
		$model->setState( 'filter_enabled', 1 );
		$model->setState( 'order', 'tbl.eventinstance_date' );
		$model->setState( 'direction', 'ASC' );
		$query = $model->getQuery( );
		$query->order( 'tbl.eventinstance_start_time' );
		$model->setQuery( $query );		
		if ($events = $model->getList())
		{
		    $instance = JTable::getInstance( 'EventInstances', 'CalendarTable' );
		    foreach ($events as $item)
		    {
    		    $instance->event_full_image = $item->event_full_image;
    		    $item->image_src = $instance->getImage('src');
		    }
		}
		
		$view = $this->getView( $this->get( 'suffix' ), 'html' );
		$view->assign( 'date', $date );
		$view->assign( 'events', $events );
		
		parent::display( );		
	}
}