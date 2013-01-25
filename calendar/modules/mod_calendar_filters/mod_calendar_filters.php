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
$helper = new modCalendarFiltersHelper( $params );

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

$types = $helper->getTypes();
$venues = $helper->getVenues();
$popular_searches = $helper->getPopularSearches();

if (JRequest::getVar('view') == 'event' || JRequest::getVar('view') == 'events') 
{
    //require ( JModuleHelper::getLayoutPath( 'mod_calendar_filters', 'event' ) );
} 
    else 
{
    require ( JModuleHelper::getLayoutPath( 'mod_calendar_filters', $params->get('layout', 'default') ) );
}
?>