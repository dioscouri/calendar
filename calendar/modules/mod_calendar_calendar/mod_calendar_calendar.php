<?php
/**
 * @version	1.5
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

// Check the registry to see if our Calendar class has been overridden
if ( !class_exists( 'Calendar' ) ) JLoader::register( "Calendar", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_calendar" . DS . "defines.php" );

require_once( dirname(__FILE__).DS.'helper.php' );
$helper = new modCalendarCalendarHelper( $params );

// include lang files
$element = strtolower( 'com_Calendar' );
$lang = &JFactory::getLanguage( );
$lang->load( $element, JPATH_BASE );
$lang->load( $element, JPATH_ADMINISTRATOR );

$default_handler = $params->get( 'default_handler' );
$link_handler = $default_handler;
$item_id = $params->get( 'item_id', JRequest::getInt( 'Itemid' ) );

Calendar::load( 'CalendarHelperBase', 'helpers._base' );
$event_helper = CalendarHelperBase::getInstance( 'event' );
$state = $event_helper->getState();
        
$date = new JObject();
$date->handler = '';

$view = $state['view']; // JRequest::getVar( 'view' );
$v = JRequest::getVar( 'v' );
if ($v == 2)
{
    $view = JRequest::getVar( 'handler' );
}
$date->current = $state['current_date']; // JRequest::getVar( 'current_date' );
$date->month = $state['month']; // JRequest::getInt( 'month' );
$date->year = $state['year']; // JRequest::getInt( 'year' );

if ( empty( $date->month ) )
{
    $date->month = '08'; // date( 'm' ); // default to august 2011, requested by Lucy
}

if ( empty( $date->year ) )
{
    $date->year = '2011'; // see above. date( 'Y' );
}

$date->nextmonthdate = date( 'Y-m-d', strtotime( $date->year . '-' . $date->month . ' +1 month' ) );
$date->prevmonthdate = date( 'Y-m-d', strtotime( $date->year . '-' . $date->month . ' -1 month' ) );
$date->nextweekdate = date( 'Y-m-d', strtotime( $date->current . ' +7 days' ) );
$date->prevweekdate = date( 'Y-m-d', strtotime( $date->current . ' -7 days' ) );
$date->nextdaydate = date( 'Y-m-d', strtotime( $date->current . ' +1 day' ) );
$date->prevdaydate = date( 'Y-m-d', strtotime( $date->current . ' -1 day' ) );

$date->weekdays = array( 'Sunday' => 'S', 'Monday' => 'M', 'Tuesday' => 'T', 'Wednesday' => 'W', 'Thursday' => 'T', 'Friday' => 'F', 'Saturday' => 'S' );
$date->weekstart = 'Sunday';
$date->weekend = 'Saturday';
$date->numberofdays = date( 't', strtotime( $date->year . '-' . $date->month . '-01' ) );
$date->monthstartday = date( 'l', strtotime( $date->year . '-' . $date->month . '-01' ) );
$date->monthstartdayofweek = date( 'w', strtotime( $date->year . '-' . $date->month . '-01' ) );
$date->monthenddayofweek = date( 'w', strtotime( $date->year . '-' . $date->month . '-' . $date->numberofdays ) );

if ( $date->monthstartday == 'Friday' || $date->monthstartday == 'Saturday' )
{
    $date->numberofweeks = 6;
}
else
{
    $date->numberofweeks = 5;
}

switch ( $view )
{
    case 'month':
        $date->handler = 'month';
        $date->prevdate = $date->prevmonthdate;
        $date->nextdate = $date->nextmonthdate;
        break;
    case 'week':
        $date->handler = 'week';
        $date->prevdate = $date->prevweekdate;
        $date->nextdate = $date->nextweekdate;
        break;
    case 'three':
        $date->handler = 'three';
        break;
    case 'day':
        $date->handler = 'day';
        $date->prevdate = $date->prevdaydate;
        $date->nextdate = $date->nextdaydate;
        break;
    default:
        $view = "month";
        $date->handler = $default_handler;
        $date->prevdate = $date->prevmonthdate;
        $date->nextdate = $date->nextmonthdate;
        break;
}

$date->prevyear = date( 'Y', strtotime( $date->prevdate ) );
$date->prevmonth = date( 'm', strtotime( $date->prevdate ) );
$date->nextyear = date( 'Y', strtotime( $date->nextdate ) );
$date->nextmonth = date( 'm', strtotime( $date->nextdate ) );

$date->range = array();
switch ( $date->handler )
{
    case 'week':
        for( $i=0; $i<7; $i++)
        {
            $date->range[] = date( 'Y-m-d', strtotime( $date->current . ' +' . $i . ' days' ) );
        }
        break;
    case 'three':
        for( $i=0; $i<3; $i++)
        {
            $date->range[] = date( 'Y-m-d', strtotime( $date->current . ' +' . $i . ' days' ) );
        }
        break;
    default:
        break;
}

$day_state = '';

$header_title = date( 'F', strtotime( $date->year . '-' . $date->month . '-01' ) ) . ' ' . $date->year;

require ( JModuleHelper::getLayoutPath( 'mod_calendar_calendar' ) );

?>