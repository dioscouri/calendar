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
if ( !class_exists( 'Calendar' ) ) { 
    JLoader::register( "Calendar", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_calendar" . DS . "defines.php" );
}

$parentPath = JPATH_ADMINISTRATOR . '/components/com_calendar/helpers';
DSCLoader::discover('CalendarHelper', $parentPath, true);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_calendar/library';
DSCLoader::discover('Calendar', $parentPath, true);

require_once( dirname(__FILE__).DS.'helper.php' );
$helper = new modCalendarCalendarHelper( $params );

// include lang files
$element = strtolower( 'com_Calendar' );
$lang = JFactory::getLanguage( );
$lang->load( $element, JPATH_BASE );
$lang->load( $element, JPATH_ADMINISTRATOR );

$default_handler = $params->get( 'default_handler' );
$link_handler = $default_handler;
$item_id = $params->get( 'item_id', JRequest::getInt( 'Itemid' ) );
$itemid_string = '';
if ($item_id) {
    $itemid_string = '&Itemid=' . $item_id;
}

Calendar::load( 'CalendarHelperBase', 'helpers.base' );
$event_helper = CalendarHelperBase::getInstance( 'event' );
$state = $event_helper->getState();

$date = new JObject();
$date->handler = '';
$date->navigation = new JObject();

$view = $state['view'];
$v = JRequest::getVar( 'v' );
if ($v == 2)
{
    $view = JRequest::getVar( 'handler' );
}

$date->currentdate_month = date( 'Y-m-01', strtotime( $state['date'] ) );
$date->currentdate_month_start = date( 'w', strtotime( $date->currentdate_month ) ); // 0 = Sunday, 6=Saturday
$date->currentdate_month_next = date( 'Y-m-01', strtotime( $date->currentdate_month . ' +1 month' ) );
$date->currentdate_month_prev = date( 'Y-m-01', strtotime( $date->currentdate_month . ' -1 month' ) );
$date->currentdate_dayofweek_start = date( 'w', strtotime( $state['date'] ) ); // 0 = Sunday, 6=Saturday
$date->minimum_number_of_weeks = 6;

switch ( $view )
{
    case 'week':
        $date->handler = 'week';
        $date->currentdate_dayofweek_end = date( 'w', strtotime( $state['date'] . ' +7 days' ) );
        $date->currentdate_end = date( 'Y-m-d', strtotime( $state['date'] . ' +7 days' ) );
        $date->navigation->prev = date('Y-m-d', strtotime( $state['date'] . ' -8 days' ) );
        $date->navigation->next = date('Y-m-d', strtotime( $state['date'] . ' +8 days' ) );
        break;
    case 'three':
        $date->handler = 'three';
        $date->currentdate_dayofweek_end = date( 'w', strtotime( $state['date'] . ' +3 days' ) );
        $date->currentdate_end = date( 'Y-m-d', strtotime( $state['date'] . ' +3 days' ) );
        $date->navigation->prev = date('Y-m-d', strtotime( $state['date'] . ' -4 days' ) );
        $date->navigation->next = date('Y-m-d', strtotime( $state['date'] . ' +4 days' ) );
        
        break;
    case 'day':
        $date->handler = 'day';
        $date->currentdate_dayofweek_end = date( 'w', strtotime( $state['date'] ) );
        $date->currentdate_end = date( 'Y-m-d', strtotime( $state['date'] ) );
        $date->navigation->prev = date('Y-m-d', strtotime( $state['date'] . ' -1 days' ) );
        $date->navigation->next = date('Y-m-d', strtotime( $state['date'] . ' +1 days' ) );
                
        break;
    case 'month':
    default:
        $view = "month";
        $date->handler = "month";
        $date->currentdate_dayofweek_end = date( 'w', strtotime( $state['date'] . ' +30 days' ) );
        $date->currentdate_end = date( 'Y-m-d', strtotime( $state['date'] . ' +30 days' ) );
        $date->navigation->prev = date('Y-m-d', strtotime( $state['date'] . ' -31 days' ) );
        $date->navigation->next = date('Y-m-d', strtotime( $state['date'] . ' +31 days' ) );
        
        break;
}

if (JRequest::getVar('view') == 'event' || JRequest::getVar('view') == 'events') 
{
    // get the viewed item from the userState    
    $app = JFactory::getApplication();
    $context = "com_calendar.view.event";
    $item = unserialize( $app->getUserState($context . '.item') );
    $surrounding = array();

    if (is_object($item) && is_a($item, 'JALC\EventsArtists\Entities\Performance') && method_exists($item, 'getDataSourceID')) 
    {
        $model = JModel::getInstance( 'Event', 'CalendarModel' );
        $surrounding = $model->getSurrounding( $item->getDataSourceID() );
    }
    
    /*
	$id = JRequest::getVar('id');
    $model = JModel::getInstance( 'Event', 'CalendarModel' );
    $item = $model->getItem( $id );
    $surrounding = $model->getSurrounding( $item->getDataSourceID() );
    */
    
    require ( JModuleHelper::getLayoutPath( 'mod_calendar_calendar', 'event' ) );
} 
    else 
{
    require ( JModuleHelper::getLayoutPath( 'mod_calendar_calendar', $params->get('layout', 'default') ) );
}
?>