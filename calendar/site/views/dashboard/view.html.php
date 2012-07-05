<?php
/**
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Calendar::load( 'CalendarViewBase', "views._base", array( 'site' => 'site', 'type' => 'components', 'ext' => 'com_calendar' ) );

class CalendarViewDashboard extends CalendarViewBase
{
	function display( $tpl = null )
	{
		parent::display( $tpl );
	}
}
