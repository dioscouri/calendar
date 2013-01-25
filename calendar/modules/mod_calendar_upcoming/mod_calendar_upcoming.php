<?php
/**
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

if ( !class_exists( 'Calendar' ) ) { 
    JLoader::register( "Calendar", JPATH_ADMINISTRATOR . "/components/com_calendar/defines.php" ); 
}

$element = strtolower( 'com_calendar' );
$lang = JFactory::getLanguage();
$lang->load( $element, JPATH_BASE );

require_once dirname(__FILE__).'/helper.php';
$helper = new modCalendarUpcomingHelper( $params );

$defines = Calendar::getInstance();
$document = JFactory::getDocument();
$document->addScriptDeclaration( "window.async_actionbuttons = " . $defines->get('async_actionbuttons') );

$model = $helper->getModel();
if ($model->pingTessituraWebAPI()) {
    $items = $helper->getItems();
    if ($defines->get('async_actionbuttons')) {
        $availability = array();
    } else {
        $availability = $helper->getAvailability( array_merge( $items->today_items, $items->this_week_items ) );
    }
    $availability_today = $availability;
    $availability_week = $availability;    
} else {
    $items = new stdClass();
    $items->today_items = array();
    $items->this_week_items = array();
}

$item_id = $params->get( 'item_id', JRequest::getInt( 'Itemid' ) );
$itemid_string = '';
if ($item_id) {
    $itemid_string = '&Itemid=' . $item_id;
}

if (!empty($items->today_items) || !empty($items->this_week_items)) {
    require JModuleHelper::getLayoutPath( 'mod_calendar_upcoming', $params->get('layout', 'default') );
}