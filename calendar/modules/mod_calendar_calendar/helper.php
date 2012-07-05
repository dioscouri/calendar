<?php
/**
 * @version    1.5
 * @package    Calendar
 * @author     Dioscouri Design
 * @link     http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */

class modCalendarCalendarHelper
{
	/**
	 * Sets the modules params as a property of the object
	 * @param unknown_type $params
	 * @return unknown_type
	 */
	function __construct( $params=null )
	{
		$this->params = $params;
	}

	function dateHasEvent( $date=null )
	{
	    if (empty($date))
	    {
	        $jdate = JFactory::getDate();
	        $date = $jdate->toFormat('%Y-%m-%d');
	    }
	    
	    JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
	    $model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
	    $model->setState( 'filter_enabled', '1' );
		$model->setState( 'filter_date_from', $date );
		$model->setState( 'filter_date_to', $date );
        $model->setState( 'limit', '1' );
	    if ($items = $model->getList())
	    {
	        return true;
	    }
	    return false;
	}
}