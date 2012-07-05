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

require_once( dirname(__FILE__).DS.'helper.php' );

if ( !class_exists( 'Calendar' ) ) JLoader::register( "Calendar", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_calendar" . DS . "defines.php" );

// include lang files
$element = strtolower( 'com_Calendar' );
$lang = &JFactory::getLanguage( );
$lang->load( $element, JPATH_BASE );
$lang->load( $element, JPATH_ADMINISTRATOR );

$helper = new modCalendarCategoriesHelper( $params );
$state = $helper->getState();
$primary_categories = $helper->getPrimaryCategories( $params->get('calendar_id') );
$secondary_categories = $helper->getSecondaryCategories( $params->get('calendar_id') );

$view = $state['view'] ? $state['view'] : 'month';
$month = $state['month'];
$year = $state['year'];
$current_date = $state['current_date'];

$item_id = $params->get('item_id', JRequest::getInt('Itemid') );
$calendar_id = $params->get('calendar_id', JRequest::getInt('calendar_id', $state['calendar_id'] ) );

$v = JRequest::getVar( 'v' );

$updating_calendar = JText::_( "Updating Calendar" );
$onclick_primary = "calendarUpdateCategories( 'calendar-content', document.calendarCategories, '$month', '$year', '$current_date', '$view', '$module->id', 'event-filter', '$updating_calendar', true );";
$onclick_secondary = $onclick_primary;

// $url_primary = "index.php?option=com_calendar&format=raw&controller=" . $view . "&task=filterprimary&month=" . $month . "&year=" . $year . "&current_date=" . $current_date;
// $url_secondary = "index.php?option=com_calendar&format=raw&controller=" . $view . "&task=filtersecondary&month=" . $month . "&year=" . $year . "&current_date=" . $current_date;
// $onclick_primary = "calendarDoTask('$url_primary', 'calendar_content', document.calendarCategories, '" . JText::_('Updating Calendar') . "' ); calendarUpdateCategoriesModule( '$module->id', 'event-filter', document.calendarCategories, '$month', '$year', '$current_date', '$view' );";
// $onclick_secondary = "calendarDoTask('$url_secondary', 'calendar_content', document.calendarCategories, '" . JText::_('Updating Calendar') . "' ); calendarUpdateCategoriesModule( '$module->id', 'event-filter', document.calendarCategories, '$month', '$year', '$current_date', '$view' );";
 
require ( JModuleHelper::getLayoutPath( 'mod_calendar_categories' ) );

?>