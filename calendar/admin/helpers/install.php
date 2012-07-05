<?php
/**
 * @version	1.5
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Calendar::load( 'CalendarHelperBase', 'helpers._base' );

class CalendarHelperInstall extends CalendarHelperBase 
{
	/**
	 * Performs basic checks on your Calendar installation to ensure it is configured OK
	 * @return unknown_type
	 */
	function createCalendar() 
	{
		// TODO create a calendar installation configuration
	}

}