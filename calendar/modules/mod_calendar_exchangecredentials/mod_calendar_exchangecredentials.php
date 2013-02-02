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

require ( JModuleHelper::getLayoutPath( 'mod_calendar_exchangecredentials', 'default' ) );

?>