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
defined( '_JEXEC' ) or die( 'Restricted access' );

Calendar::load( 'CalendarHelperBase', 'helpers.base' );
jimport( 'joomla.filesystem.file' );

class CalendarHelperCalendar extends CalendarHelperBase
{
	/**
	 * Gets all the dates for each day in the month that matches $day_of_week
	 * @param int $month
	 * @param int $year
	 * @param string $day_of_week  Tuesday or Saturday, e.g.
	 * @return array
	 */
	function getDaysOfMonth($month, $year, $day_of_week) 
	{
	    $day_of_week = strtolower( $day_of_week );
	    $n = '1';
	    $day_of_month = strtolower( date( 'l', strtotime( $year . '-' . $month . '-' . $n ) ) );
	    $max_days_in_month = date( 't', strtotime( $year . '-' . $month . '-01' ) );
	    while ($day_of_week != $day_of_month && $n <= $max_days_in_month)
	    {
	        $n++;
	        $day_of_month = strtolower( date( 'l', strtotime( $year . '-' . $month . '-' . $n ) ) );
	    }
	    
	    $days_of_month = array();
	    while ($n <= $max_days_in_month)
	    {
	        $days_of_month[] = date( 'Y-m-d', strtotime( $year . '-' . $month . '-' . $n ) );
	        $n = $n + 7;
	    }
	    
	    return $days_of_month;
	}
}
