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

require_once ( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'library' . DS . 'iCalcreator.class.php' );

class CalendarHelperICal extends CalendarHelperBase
{
    var $ical = null;
    
	function __construct( )
	{
		parent::__construct( );
		$this->ical = $this->createFileObject();
	}
    
	function getFileURL( $eventinstance_date, $eventinstance_time, $eventinstance_end_time, $eventinstance_location )
	{
	    //date_default_timezone_set('UTC');
		$config = Calendar::getInstance( );
		list( $year, $month, $day ) = explode( '-', $eventinstance_date );
		$config =& JFactory::getConfig();
		$offset = $config->getValue('config.offset');
		$localtime = localtime();
		if ($localtime[8] > 0)
		{
		    $offset = $offset + 1; 
		}
    	$diff = '+';
		if ($offset < 0) { $diff = '-'; }
		$eventinstance_time = date( 'H:i:s', strtotime($eventinstance_time . " " . $diff . $offset . " hours") );
		$eventinstance_end_date = date( 'Y-m-d', strtotime($eventinstance_date . " " .$eventinstance_end_time . " " . $diff . $offset . " hours") );
		$eventinstance_end_time = date( 'H:i:s', strtotime($eventinstance_end_time . " " . $diff . $offset . " hours") );
		list( $hour, $min, $sec ) = explode( ':', $eventinstance_time );
		$min = intval( $min );
		$sec = intval( $sec );
				
		Calendar::load('CalendarICal', 'library.ical');
		$ical = new CalendarICal();
		$ical->setDTStart( $eventinstance_date, $eventinstance_time );
		$ical->setDTEnd( $eventinstance_end_date, $eventinstance_end_time );
		$ical->setHtmlProperty( 'location', $eventinstance_location );
        $ical->setHtmlProperty( 'summary', strip_tags($this->instance->event_long_title) );
		$ical->setHtmlProperty( 'description', strip_tags($this->instance->event_short_description) );
		$ics = $ical->getContents();
		
		$directory = 'tmp';
		$filename = JFilterOutput::stringURLSafe( $this->instance->event_short_title . '-' . $eventinstance_date . '-' . $hour . $min ) . '.ics';
		
		$result = JFile::write( JPATH_BASE . "/" . $directory . "/" . $filename, $ics);
		if( !$result )
		{
			JError::raiseNotice( 21, JText::_('Error saving iCal file.') );
			return '#';	
		}
		else 
		{
			return $directory . '/' . $filename;
		}
		
		$config = Calendar::getInstance( );
		
		$eventinstance_date = date( 'Y-n-j', strtotime($eventinstance_date) );
		list( $year, $month, $day ) = explode( '-', $eventinstance_date );
		
		$eventinstance_time = date( 'H:i:s', strtotime($eventinstance_time) );
		list( $hour, $min, $sec ) = explode( ':', $eventinstance_time );
		$min = intval( $min );
		$sec = intval( $sec );

		// make ical
		$v = new vcalendar(); // create a new calendar instance
		$v->setConfig( 'NEWLINECHAR', PHP_EOL);
		$v->setVersion( '1.0' ); 
		//$v->setConfig( 'unique_id', $config->get( 'ical_unique_id' ) ); // set Your unique id 
		//$v->setProperty( 'method', 'PUBLISH' ); // required of some calendar software
		
		$vevent = new vevent(); // create an event calendar component
		$vevent->setProperty( 'dtstart', array( 'year'=>$year, 'month'=>$month, 'day'=>$day, 'hour'=>$hour, 'min'=>$min,  'sec'=>$sec ));
		$vevent->setProperty( 'dtend',  array( 'year'=>$year, 'month'=>$month, 'day'=>$day, 'hour'=>$hour, 'min'=>$min, 'sec'=>$sec ));
		$vevent->setProperty( 'LOCATION', $eventinstance_location ); // property name - case independent
		$vevent->setProperty( 'summary', strip_tags($this->instance->event_long_title) );
		$vevent->setProperty( 'description', strip_tags($this->instance->event_short_description) );
		//$vevent->setProperty( 'comment', strip_tags($this->instance->event_long_title) );
		//$vevent->setProperty( 'attendee', $config->get( 'ical_atendee_email' ) );
		
		$v->setComponent ( $vevent ); // add event to calendar
		
		$directory = 'tmp';
		$filename = JFilterOutput::stringURLSafe( $this->instance->event_short_title . '-' . $eventinstance_date . '-' . $hour . $min ) . '.ics';
		
		//$v->setConfig( 'format', 'xcal' );
		$v->setConfig( 'directory', $directory );
		$v->setConfig( 'filename', $filename );
		
		$result = $v->saveCalendar( );
		if( !$result )
		{
			JError::raiseNotice( 21, JText::_('Error saving iCal file.') );
			return '#';	
		}
		else 
		{
			return $directory . '/' . $filename;
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $eventinstance_date
	 * @param unknown_type $eventinstance_time
	 * @param unknown_type $eventinstance_location
	 * @return return_type
	 */
	function download()
	{
	    $eventinstance_date = $this->instance->eventinstance_date;
	    $eventinstance_start_time = $this->instance->eventinstance_start_time;
	    $eventinstance_end_time = ($this->instance->eventinstance_end_time > $this->instance->eventinstance_start_time) ? $this->instance->eventinstance_end_time : $this->instance->eventinstance_start_time;
	    $eventinstance_location = (!empty($this->instance->venue_name)) ? $this->instance->venue_name : 'First Park | Houston at 2nd Ave, New York City';
	    jimport('joomla.filesystem.file');
	    
	    $ical = $this->getFileURL( $eventinstance_date, $eventinstance_start_time, $eventinstance_end_time, $eventinstance_location );
	    $this->ical->file_path = JPATH_BASE . '/' . $ical;
	    $this->ical->file_extension = JFile::getExt($this->ical->file_path);
	    $this->ical->file_name = JFile::getName($this->ical->file_path);
	    
	    Calendar::load( 'CalendarFile', 'library.file' );
	    $file = new CalendarFile();
	    $file->download( $this->ical );
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return return_type
	 */
	function createFileObject()
	{
	    $file = new JObject();
	    
	    $file->file_path = '';
	    $file->file_extension = '';
	    $file->file_name = '';
	    
	    return $file;
	}
}