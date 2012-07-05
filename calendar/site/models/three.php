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

Calendar::load( 'CalendarModelEventinstances', 'models.eventinstances' );

class CalendarModelThree extends CalendarModelEventinstances
{
	/*
	 * Required the Table of event instances it will return eventinstances table object
	 *  
	 */ 
	function &getTable( $name = 'eventinstances', $prefix = 'CalendarTable', $options = array( ) )
	{
		if ( empty( $name ) )
		{
			$name = $this->getName( );
		}
		
		JTable::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'tables' );
		if ( $table = $this->_createTable( $name, $prefix, $options ) )
		{
			return $table;
		}
		
		JError::raiseError( 0, 'Table ' . $name . ' not supported. File not found.' );
		$null = null;
		return $null;
	}
}
