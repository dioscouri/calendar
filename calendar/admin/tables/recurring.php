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

Calendar::load( 'CalendarTable', 'tables._base' );

class CalendarTableRecurring extends CalendarTable
{
	function CalendarTableRecurring( &$db )
	{
		$tbl_key = 'recurring_id';
		$tbl_suffix = 'recurring';
		$this->set( '_suffix', $tbl_suffix );
		$name = "calendar";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );
	}
	
	/**
	 * Creates event instances for a recurring event for up to 1 year from $startdate
	 * unless enddate is specifically provided, then create instances until reaching enddate
	 * 
	 * @return return_type
	 */
	function createEventInstances( $startdate=null, $enddate=null )
	{
	    // TODO Do some sanity checks to make sure the recurring event is correctly created, to 
	    // prevent neverending eventinstance populations
	    
	    if (empty($startdate))
	    {
	        $startdate = $this->recurring_start_date;
	    }
	    
	    if (empty($enddate))
	    {
	        $enddate = $this->getFinishDate( $startdate );
	    }

	    Calendar::load( 'CalendarHelperBase', 'helpers.base' );
	    $helper = CalendarHelperBase::getInstance();
	    
	    $occurances = $this->getOccurances();
	    $count = $occurances;
	    
	    $error = false;
	    $currentdate = $startdate;
	    while ($currentdate <= $enddate)
	    {
	        
	        // create instance
	        if ($this->createEventInstance($currentdate))
	        {
    	        // increase count
    	        $count++;
    	        
    	        // inc $currentdate to nextdate using recurring_repeats
                $currentdate = $this->getNextDate( $currentdate );
	        }
	            else
	        {
	            $error = true;
	            break;
	        }
	        
	    }
	    
	    if ($count > $occurances)
	    {
	        // Update the recurring event's count
	        $this->recurring_finishes_date = $this->getEndDate();
	        $this->recurring_current_date = $this->getMaxInstanceDate();
	        $this->recurring_instances = $count;
	        $this->store();
	        
	        $this->_created = $count - $occurances;
	    }
	    
	    if ($error)
	    {
	        return false;
	    }
	    return true;
	}
	
	/**
	 * Creates a single event instance from a recurring event
	 * on the provided date
	 * 
	 * @return return_type
	 */
	function createEventInstance( $startdate=null )
	{
		if (empty($startdate))
	    {
	        $startdate = $this->recurring_start_date;
	    }
	    
	    JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_calendar'.DS.'tables' );
	    $eventinstance = JTable::getInstance( 'EventInstances', 'CalendarTable' );
        $eventinstance->eventinstance_name = $this->recurring_name;
        $eventinstance->eventinstance_alias = $this->recurring_alias;
        $eventinstance->eventinstance_published = $this->recurring_published;
        $eventinstance->eventinstance_description = $this->recurring_description;
        $eventinstance->eventinstance_date = $startdate;
        $eventinstance->eventinstance_start_time = $this->recurring_start_time;
        $eventinstance->eventinstance_end_time = $this->recurring_end_time;
        $eventinstance->event_id = $this->event_id;
        $eventinstance->venue_id = $this->venue_id;
        $eventinstance->actionbutton_id = $this->actionbutton_id;
        $eventinstance->actionbutton_url = $this->recurring_actionbutton_url;
        $eventinstance->actionbutton_string = $this->recurring_actionbutton_string;
        $eventinstance->eventinstance_params = $this->recurring_params;
        $eventinstance->eventinstance_recurring = '1';
        $eventinstance->recurring_id = $this->recurring_id;
        if (!$eventinstance->save())
        {
            $this->setError( $eventinstance->getError() );
            return false;
        }
        return $eventinstance;
	}
	
	/**
	 * Gets the count from the DB
	 * 
	 * @return return_type
	 */
	function getOccurances()
	{
	    $db = $this->getDBO();
	    $db->setQuery( "SELECT COUNT(`eventinstance_id`) AS count FROM #__calendar_eventinstances WHERE `recurring_id` = '$this->recurring_id';");
	    $result = $db->loadObject();
	    return $result->count;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return return_type
	 */
	function getMaxInstanceDate()
	{
	    $db = $this->getDBO();
	    $db->setQuery( "SELECT MAX(`eventinstance_date`) AS max FROM #__calendar_eventinstances WHERE `recurring_id` = '$this->recurring_id';");
	    $result = $db->loadObject();
	    return $result->max;
	}
	
	/**
	 * Gets the date of the next instance of a recurring event
	 *  
	 * @param unknown_type $currentdate
	 * @return return_type
	 */
	function getNextDate( $startdate=null )
	{
		if (empty($startdate))
	    {
	        $startdate = $this->recurring_start_date;
	    }
	    
	    Calendar::load( 'CalendarHelperBase', 'helpers.base' );
	    $helper = CalendarHelperBase::getInstance();
	    
	    switch ( $this->recurring_repeats )
	    {
	        case "daily":
	            $datevars = $helper->setDateVariables( $startdate, null, 'daily', $this->daily_repeats_every );
	            $nextdate = $datevars->nextdate;	            
	            break;
	        case "weekdays":
	            // TODO Mon - Fri
	            // get the next day unless $startdate is a Fri, at which point get the next Mon
	            break;
	        case "mon_wed_fri":
	            // TODO mon_wed_fri
	            break;
	        case "tue_thur":
	            // TODO tue_thur
	            break;
	        case "weekly":
	            // TODO Account for the selected days of the week when it occurs, $this->weekly_repeats_on
	            $datevars = $helper->setDateVariables( $startdate, null, 'weekly', $this->weekly_repeats_every );
	            $nextdate = $datevars->nextdate;
	            break;
	        case "monthly":
	            $datevars = $helper->setDateVariables( $startdate, null, 'monthly', $this->monthly_repeats_every );
	            $nextdate = $datevars->nextdate;
	            break;
	        case "yearly":
	            $datevars = $helper->setDateVariables( $startdate, null, 'yearly', $this->yearly_repeats_every );
	            $nextdate = $datevars->nextdate;
	            break;
	        default:
	            $nextdate = null;
	            break;
	    }
	    
	    return $nextdate;
	}
	
	/**
	 * When creating eventinstances for a recurring event,
	 * we only go out 1 year into the future from the specified date
	 * 
	 * @return return_type
	 */
	function getFinishDate( $startdate=null )
	{
		if (empty($startdate))
	    {
	        $startdate = $this->recurring_start_date;
	    }
	    
	    Calendar::load( 'CalendarHelperBase', 'helpers.base' );
	    $helper = CalendarHelperBase::getInstance();
	    $datevars = $helper->setDateVariables( $startdate, null, 'yearly' );
	    
	    $finishdate = $datevars->nextdate;
	    $enddate = $this->getEndDate();

	    if (!empty($enddate) && $finishdate > $enddate)
	    {
	        $finishdate = $enddate;
	    }
	    
	    return $finishdate;
	}
	
	/**
	 * Gets the last event date for this recurring event
	 * 
	 * @return return_type
	 */
	function getEndDate()
	{
	    switch ($this->recurring_end_type)
	    {
	        case "date":
	            $enddate = $this->recurring_end_date;
	            break;
	        case "occurances":
	            $enddate = $this->getEndDateFromOccurances();
	            break;
	        case "never":
	        default:
	            $enddate = null;
	            break;
	    }
	    return $enddate;
	}
	
	/**
	 * TODO Finish this
	 * 
	 * Based on the num of occurances for this event, 
	 * calculate when it should end
	 * 
	 * @return return_type
	 */
	function getEndDateFromOccurances( $startdate=null )
	{
		if (empty($startdate))
	    {
	        $startdate = $this->recurring_start_date;
	    }
	    
	    $count = 1;
	    $currentdate = $startdate;
	    if ($startdate != $this->recurring_start_date)
	    {
	        $count = $this->getOccurances();
	    }
	    
	    $max = $this->recurring_end_occurances;
	    while ($count < $max)
	    {
	        $currentdate = $this->getNextDate( $currentdate );
	        $count++; 
	    }
	    
	    $enddate = $currentdate;

	    return $enddate;
	}
}
