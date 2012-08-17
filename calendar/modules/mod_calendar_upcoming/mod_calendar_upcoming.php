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
$items = $helper->getItems();

if (!empty($items->today_items) || !empty($items->this_week_items)) {
    require JModuleHelper::getLayoutPath( 'mod_calendar_upcoming', $params->get('layout', 'default') );
}