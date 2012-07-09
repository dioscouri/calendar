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

class CalendarHelperVenue extends CalendarHelperBase
{
	/**
	 * Returns html for creating action button
	 * from given venue id
	 * 
	 * @param int venue id
	 * @return string venue name
	 */
	function getVenueName( $venue_id )
	{
		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
		$model = JModel::getInstance( 'Venues', 'CalendarModel' );
		$model->setId( $venue_id );
		$venue = $model->getItem( );
		
		$return = '';
		if (!empty($venue->venue_name))
		{
		    $return = $venue->venue_name;
		}
		return $return;
	}
}
