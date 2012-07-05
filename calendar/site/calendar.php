<?php
/**
 * @package Calendar
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

// Check the registry to see if our Calendar class has been overridden
if ( !class_exists( 'Calendar' ) ) JLoader::register( "Calendar", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_calendar" . DS . "defines.php" );

// load the config class
Calendar::load( 'Calendar', 'defines' );

// before executing any tasks, check the integrity of the installation
Calendar::getClass( 'CalendarHelperDiagnostics', 'helpers.diagnostics' )->checkInstallation( );

// set the options array
$options = array( 'site' => 'site', 'type' => 'components', 'ext' => 'com_calendar' );

// Require the base controller
Calendar::load( 'CalendarController', 'controller', $options );

// Require specific controller if requested
$controller = JRequest::getWord( 'controller', JRequest::getVar( 'view' ) );
if ( !Calendar::load( 'CalendarController' . $controller, "controllers.$controller", $options ) ) $controller = '';

if ( empty( $controller ) )
{
	// redirect to default
	$redirect = "index.php?option=com_calendar&view=month";
	$redirect = JRoute::_( $redirect, false );
	JFactory::getApplication( )->redirect( $redirect );
}

$doc = JFactory::getDocument( );
$uri = JURI::getInstance( );
$js = "var com_calendar = {};\n";
$js .= "com_calendar.jbase = '" . $uri->root( ) . "';\n";
$doc->addScriptDeclaration( $js );

// load the plugins
JPluginHelper::importPlugin( 'calendar' );

// Create the controller
$classname = 'CalendarController' . $controller;
$controller = Calendar::getClass( $classname );

// ensure a valid task exists
$task = JRequest::getVar( 'task' );
if ( empty( $task ) )
{
	$task = 'display';
}
JRequest::setVar( 'task', $task );

// Perform the requested task
$controller->execute( $task );

// Redirect if set by the controller
$controller->redirect( );

?>