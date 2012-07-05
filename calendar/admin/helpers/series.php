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

Calendar::load( 'CalendarHelperBase', 'helpers._base' );
jimport( 'joomla.filesystem.file' );

class CalendarHelperSeries extends CalendarHelperBase
{
	/**
	 * Creates series new record from string (name)
	 * 
	 * @param string $series_name
	 * @return int new series id
	 */
	function createSeriesFromName( $series_name )
	{
	    JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		$table = JTable::getInstance( 'Series', 'CalendarTable' );
		
		$table->series_name = $series_name;
		$table->series_title = $series_name;
		$table->series_full_image = 'no image';
		
		if ( !$table->save( ) )
		{
			$this->message = $table->getError( );
			$this->messagetype = 'notice';
			return null;
		}
		
		return $table->series_id;
	}
	
	/**
	 * Returns series name
	 * 
	 * @param int $series_id
	 * @return string series name
	 */
	function getSeriesName( $series_id )
	{
		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
		$model = JModel::getInstance( 'Series', 'CalendarModel' );
		$model->setId( $series_id );
		$series = $model->getItem( );
		
		$return = '';
		if (!empty($series->series_name))
		{
		    $return = $series->series_name;
		}
		return $return;
	}
}
