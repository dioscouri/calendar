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

if ( !class_exists( 'Calendar' ) ) JLoader::register( "Calendar", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_calendar" . DS . "defines.php" );

Calendar::load( "CalendarHelperRoute", 'helpers.route' );

/**
 * Build the route
 * Is just a wrapper for CalendarHelperRoute::build()
 * 
 * @param unknown_type $query
 * @return unknown_type
 */
function CalendarBuildRoute( &$query )
{
	return CalendarHelperRoute::build( $query );
}

/**
 * Parse the url segments
 * Is just a wrapper for CalendarHelperRoute::parse()
 * 
 * @param unknown_type $segments
 * @return unknown_type
 */
function CalendarParseRoute( $segments )
{
	return CalendarHelperRoute::parse( $segments );
}
