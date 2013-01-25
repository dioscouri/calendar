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

class CalendarHelperEvent extends CalendarHelperBase
{
	/**
	 * Gets values submitted by ajax request
	 * @return return_type
	 */
	function getSelectedValues()
	{
	    if (empty($this->values))
	    {
    		$elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
    		Calendar::load( 'CalendarHelperBase', 'helpers.base' );
    		$helper = new CalendarHelperBase();
    		$values = $helper->elementsToArray( $elements );
            $this->values = $values;	        
	    }
	    return $this->values;
	}
	
	/**
	 * Gets user state of selected values
	 * 
	 * @return return_type
	 */
	function getState()
	{
	    if (empty($this->state))
	    {
	        $request = JRequest::get('request');
	        
	        $db = JFactory::getDBO();
	        $session = JFactory::getSession();
	        $app = JFactory::getApplication();
            $ns = $app->getName().'::'.'com.calendar.state';
	        
    	    $state = array();
    	    // $state['primary_categories'] = array() of IDs
    	    // $state['secondary_category'] = array() of IDs
    	    // $state['view'] = string of the last viewed view other than 'events'
    	    // $state['month'] = int
    	    // $state['year'] = int
    	    // $state['date'] = string (can be null)
    	    // $state['calendar_id'] = int (default set in config)
    	    // $state['layout'] = string (can be null)
    	    // $state['filter_types'] = array() of IDs (can be null)
    	    // $state['filter_venues'] = array() of IDs (can be null)
    	    
    	    $default_date = date('Y-m-d');
    	    $config = Calendar::getInstance();
    	    if ($config->get('default_date'))
    	    {
    	        $default_date = $config->get('default_date');
    	    }
            
    	    $calendar_id = JRequest::getInt( 'calendar_id' );
    	    if (empty($calendar_id)) {
    	        $calendar_id = $session->get( $ns . '.calendar_id' );
    	    }
    	    JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
    	    $calendar_model = JModel::getInstance( 'Calendars', 'CalendarModel' );
    	    $calendar_model->setId( $calendar_id );
    	    $calendar = $calendar_model->getItem();
    	    
    	    // Set the defaults for the state using the calendar object    	    
    	    if (!empty($calendar->default_date) && $calendar->default_date != '0000-00-00') {
    	        $default_date = $calendar->default_date;
    	    }
    	    
    	    $filter_types = array();
            if (!empty($calendar->calendar_filter_types)) {
                $exploded = explode( ',', $calendar->calendar_filter_types );
                foreach ($exploded as $exploded_item) {
                    $exploded_item = trim( $exploded_item );
                    $filter_types[] = $exploded_item;
                }
            }
            
            $filter_venues = array();
            if (!empty($calendar->calendar_filter_venues)) {
                $exploded = explode( ',', $calendar->calendar_filter_venues );
                foreach ($exploded as $exploded_item) {
                    $exploded_item = trim( $exploded_item );
                    $filter_venues[] = $exploded_item;
                }
            }
                
            $date = $session->get( $ns . '.date', $default_date );
    	    $view = JRequest::getVar( 'view' );
    	    $day = $app->getUserStateFromRequest( $ns . 'day', 'day', date('d'), '' );
            $month = $app->getUserStateFromRequest( $ns . 'month', 'month', date('m'), '' );
            $year = $app->getUserStateFromRequest( $ns . 'year', 'year', date('Y'), '' );
            $request_date = JRequest::getVar( 'date', $default_date );
            if (!empty($request_date)) {
                $day = date('d', strtotime( $date ) );
                $month = date('m', strtotime( $date ) );
                $year = date('Y', strtotime( $date ) );
                $session->set( $ns . '.date', $request_date );
                $date = $request_date;
            }
            
            /**
             *
             * Ok, defaults are all set, now lets check the user state
             */
            
            $v = JRequest::getVar('v');
            $reset = JRequest::getVar('reset');

        	// if the view == events, the user is viewing an event, so use the previous state values
            // otherwise, update the state values
            $primary_categories = array();
            $secondary_category = '';
            if ($reset == '1')
            {
                if (!empty($calendar->calendar_default_view)) {
                    $view = $calendar->calendar_default_view;
                }
                
                $day = date('d', strtotime( $default_date ) );
                $month = date('m', strtotime( $default_date ) );
                $year = date('Y', strtotime( $default_date ) );
                $date = date('Y-m-d', strtotime( $default_date ) );
                if ($view == 'week')
                {
                    $given_day = date("w", strtotime( $default_date ) );
                    $diff = ($given_day - 1);
                    $string = "$default_date -$diff days";
                    $date = date( "Y-m-d", strtotime( $string ) );
                }
                
                if (!empty($request_date)) {
                	$date = $request_date;
                }
                
                // by default, all used categories are checked
                //echo "Setting default values<br/>";
        		$model = JModel::getInstance( 'Eventinstances', 'CalendarModel' );
        		if ($categories = $model->getUsedPrimaryCategories( $calendar_id ))
        		{
        		    foreach ($categories as $cat)
        		    {
        		        $primary_categories[] = $cat->category_id;
        		    }
        		}
        		
                $session->set( $ns . '.primary_categories', $primary_categories );
                $session->set( $ns . '.secondary_category', $secondary_category );
                $session->set( $ns . '.view', $view );
                $session->set( $ns . '.day', $day );
                $session->set( $ns . '.month', $month );
                $session->set( $ns . '.year', $year );
                $session->set( $ns . '.date', $date );
                $session->set( $ns . '.calendar_id', $calendar_id );
                $session->set( $ns . '.default_date', $default_date );
                $session->set( $ns . '.filter_types', $filter_types );
                $session->set( $ns . '.filter_venues', $filter_venues );
            }
            elseif (!empty($v) && $v == '2')
            {
                //echo "Ajax Update, so using json values<br/>";
                // we're doing an ajax update
        		$values = $this->getSelectedValues();
        		if (!empty($values['_checked']['primary_category']))
        		{
        		    $primary_categories = $values['_checked']['primary_category'];
        		}
        		
        		if (!empty($values['_checked']['secondary_category']))
        		{
        		    $secondary_category = $values['_checked']['secondary_category'];
        		}

        		if (!empty($values['_checked']['event_type']))
        		{
        		    foreach ($values['_checked']['event_type'] as $type) 
        		    {
        		        if (!in_array($type, $filter_types)) 
        		        {
        		            $filter_types[] = $type;
        		        }
        		    }
        		}
                
                $session->set( $ns . '.primary_categories', $primary_categories );
                $session->set( $ns . '.secondary_category', $secondary_category );
                $session->set( $ns . '.view', $view );
                $session->set( $ns . '.day', $day );
                $session->set( $ns . '.month', $month );
                $session->set( $ns . '.year', $year );
                $session->set( $ns . '.date', $date );
                $session->set( $ns . '.calendar_id', $calendar_id );
                $session->set( $ns . '.default_date', $default_date );
                $session->set( $ns . '.filter_types', $filter_types );
                $session->set( $ns . '.filter_venues', $filter_venues );                
            }
            elseif ($view == "events" || $view == "event")
            {
                $day = date('d', strtotime( $default_date ) );
                $month = date('m', strtotime( $default_date ) );
                $year = date('Y', strtotime( $default_date ) );
                $date = date('Y-m-d', strtotime( $default_date ) );
                // by default, all used categories are checked
        		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
        		$model = JModel::getInstance( 'Eventinstances', 'CalendarModel' );
        		if ($categories = $model->getUsedPrimaryCategories( $calendar_id ))
        		{
        		    foreach ($categories as $cat)
        		    {
        		        $primary_categories[] = $cat->category_id;
        		    }
        		}
                
                //echo "View == Events/Event, so using previous state<br/>";
                $primary_categories = $session->get( $ns . '.primary_categories', $primary_categories );
                $secondary_category = $session->get( $ns . '.secondary_category' );
                $view = $session->get( $ns . '.view', 'month' );
                $day = $session->get( $ns . '.day', $day );
                $month = $session->get( $ns . '.month', $month );
                $year = $session->get( $ns . '.year', $year );
                $date = $session->get( $ns . '.date', $date );
                $calendar_id = $session->get( $ns . '.calendar_id', $calendar_id );
                $default_date = $session->get( $ns . '.default_date', $default_date );
                $filter_types = $session->get( $ns . '.filter_types', $filter_types );
                $filter_venues = $session->get( $ns . '.filter_venues', $filter_venues );
            }
            else
            {
                // browsing the calendar, update the dynamic filter states, not anything else
                $date = $session->get( $ns . '.date', $date );
                
                $primary_categories = $session->get( $ns . '.primary_categories' );
                $secondary_category = $session->get( $ns . '.secondary_category' );
                $session->set( $ns . '.view', $view );
                $session->set( $ns . '.day', $day );
                $session->set( $ns . '.month', $month );
                $session->set( $ns . '.year', $year );
                $session->set( $ns . '.date', $date );
                $session->set( $ns . '.calendar_id', $calendar_id );
                $session->set( $ns . '.default_date', $default_date );
                
                $filter_types = $session->get( $ns . '.filter_types', $filter_types );
                $filter_venues = $session->get( $ns . '.filter_venues', $filter_venues );
                $toggles = array();
                if (!empty($request['filter_toggle'])) { $toggles = $request['filter_toggle']; }
                foreach ($toggles as $toggle_type=>$toggle_value) 
                {
                    switch ($toggle_type) {
                        case "type":
                            if (in_array($toggle_value, $filter_types)) {
                                $key = array_search($toggle_value, $filter_types);
                                unset($filter_types[$key]);
                            } else {
                                $filter_types[] = $toggle_value;
                            }
                            break;
                        case "venue":
                            if (in_array($toggle_value, $filter_venues)) {
                                $key = array_search($toggle_value, $filter_venues);
                                unset($filter_venues[$key]);
                            } else {
                                $filter_venues[] = $toggle_value;
                            }
                            break;
                    }
                }
                $session->set( $ns . '.filter_types', $filter_types );
                $session->set( $ns . '.filter_venues', $filter_venues );
            }
            
            if (empty($primary_categories))
            {
                JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
                $model = JModel::getInstance( 'Eventinstances', 'CalendarModel' );
                if ($categories = $model->getUsedPrimaryCategories( $calendar_id ))
                {
                    foreach ($categories as $cat)
                    {
                        $primary_categories[] = $cat->category_id;
                    }
                }
            }
            
            if (empty($primary_categories))
            {
                $primary_categories = array( '-1' );
            }
            
    	    $state['filter_primary_categories'] = (array) $primary_categories;
    	    $state['filter_secondary_category'] = $secondary_category;
    	    $state['view'] = $view;
    	    $state['day'] = $day;
    	    $state['month'] = $month;
    	    $state['year'] = $year;
    	    $state['date'] = $date;
    	    $state['calendar_id'] = $calendar_id;
    	    $state['default_date'] = $default_date;
    	    $state['filter_types'] = $filter_types;
    	    $state['filter_venues'] = $filter_venues;

    	    switch($state['view']) 
    	    {
    	        case "month":
    	            $state['filter_date_from'] = $state['date'];
    	            $state['filter_date_to'] = date('Y-m-d', strtotime( $state['filter_date_from'] . '+30 days' ) );
    	            break;
	            case "week":
	                $state['filter_date_from'] = $state['date'];
	                $state['filter_date_to'] = date('Y-m-d', strtotime( $state['filter_date_from'] . '+7 days' ) );
	                break;
                case "day":
                    $state['filter_date_from'] = $state['date'];
                    $state['filter_date_to'] = $state['filter_date_from'];
                    break;    	                    
    	    }
    	    
    	    $this->state = $state;
	    }

	    if (empty($v)) {
    	    // echo "helper.getState: " . Calendar::dump( $this->state );
	    }
	    return $this->state;
	}
    
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $instance
	 * @param unknown_type $referer
	 * @return return_type
	 */
    function getBackToCalendarURL( $state=null )
    {
        if (empty($state)) {
            $state = $this->getState();
        }
                
        $view = $state["view"];
        $date = $state["date"];
        $url = "index.php?option=com_calendar&view=" . $view . "&date=" . $date;
        return $url;
    }
    
	/**
	 * Returns events related by primary category
	 * 
	 * @param int $event_id
	 * @return object list $related_events
	 */
	function relatedEventsByCategory( $event_id )
	{
		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
		$model = JModel::getInstance( 'Events', 'CalendarModel' );
		$model->setId( $event_id );
		$event = $model->getItem( );
		
		$model = JModel::getInstance( 'Events', 'CalendarModel' );
		$model->setState( 'filter_category', $event->event_primary_category_id );
		$related_events = $model->getList();
		
		foreach ($related_events as $key => $related_event)
		{
			if($related_event->event_id == $event_id)
			{
				unset($related_events[$key]);
			}
		}
		
		return $related_events;
	}
	
	/**
	 * Returns events related by series
	 * 
	 * @param int $event_id
	 * @return object list $related_events
	 */
	function relatedEventsBySeries( $event_id )
	{
		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
		$model = JModel::getInstance( 'Events', 'CalendarModel' );
		$model->setId( $event_id );
		$event = $model->getItem( );
		
		$model = JModel::getInstance( 'Events', 'CalendarModel' );
		$model->setState( 'filter_series', $event->series_id );
		$related_events = $model->getList();
		
		foreach ($related_events as $key => $related_event)
		{
			if($related_event->event_id == $event_id)
			{
				unset($related_events[$key]);
			}
		}
		
		return $related_events;
	}
}