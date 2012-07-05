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

class CalendarControllerEvents extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		$this->set( 'suffix', 'events' );
	}
	
	function view( )
	{
		JRequest::setVar( 'layout', 'view' );

		$event_id = JRequest::getInt( 'id' );
		$instance_id = JRequest::getInt( 'instance_id' );
		
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		$instance = JTable::getInstance( 'EventInstances', 'CalendarTable' );
		$instance->load( $instance_id );
		$instance->trimProperties();
		$instance->bindObjects();
		
		if ( $instance->event_id != $event_id )
		{
		    echo JText::_( "Invalid Event Instance" );
		    return;
		}
		
		JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
		$model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
		$model->setState( 'filter_event', $instance->event_id );
		$model->setState( 'filter_date_from', $instance->eventinstance_date );
		$model->setState( 'order', 'tbl.eventinstance_date' );
		$model->setState( 'direction', 'ASC' );
        $query = $model->getQuery();
        $query->where( "tbl.eventinstance_id != '" . $instance->eventinstance_id ."'" );
        $query->order( 'tbl.eventinstance_start_time' );
        $model->setQuery( $query );
		$instance->more_dates = $model->getList();
		
		$view = $this->getView( $this->get( 'suffix' ), 'html' );
		$view->assign( 'instance', $instance );
		
        Calendar::load( 'CalendarHelperBase', 'helpers._base' );
        $event_helper = CalendarHelperBase::getInstance( 'event' );
        $previous_state = $event_helper->getState();
		$view->assign( 'previous_state', $previous_state );
		
		$back_url = $event_helper->getBackToCalendarURL( $instance );
		$view->assign( 'back_url', $back_url );
		
        Calendar::load( 'DisqusAPI', 'library.disqus.disqusapi' );
        Calendar::load( 'CalendarArticle', 'library.article' );
        Calendar::load( 'CalendarHelperICal', 'helpers.ical' );
        Calendar::load( 'CalendarHelperCategory', 'helpers.category' );

        $document = &JFactory::getDocument();
        $document->setTitle( strip_tags( $instance->event_short_title ) );
        $document->setDescription( strip_tags( htmlspecialchars_decode( $instance->event_short_description ) ) );
        
		parent::display( );
		return;
	}
	
	/**
	 * For filtering calendars by primary categories.
	 * Is expected to be called via Ajax.
	 * 
	 * @param $categories array
	 * @param $type string primary or secondary type
	 * @return void
	 */
	function filterprimary()
	{	
		$this->_setModelState();
	    $model = $this->getModel( 'month' );
	    
		$state = array();
	    $app = JFactory::getApplication( );
	    $ns = $this->getNamespace( );
		$m = JRequest::getVar( 'month' );
		$y = JRequest::getVar( 'year' );
		if( empty( $m ) && empty( $y ) )
		{
			$state['month'] = date( 'm' );
			$state['year'] = date( 'Y' );
		}		
		else 
		{
			$state['month'] = $app->getUserStateFromRequest( $ns . 'month', 'month', '', '' );
			$state['year'] = $app->getUserStateFromRequest( $ns . 'year', 'year', '', '' );
		}
		$state['filter_date_from'] = $state['year'] . '-' . $state['month'] . '-01';
        $state['filter_datetype'] = 'month';
        
	    Calendar::load( 'CalendarHelperBase', 'helpers._base' );
	    $helper = CalendarHelperBase::getInstance();
	    $datevars = $helper->setDateVariables( $state['filter_date_from'], null, 'monthly' );
	    $state['filter_date_to'] = $datevars->nextdate;
        
	    $state['order'] = 'tbl.eventinstance_date';
	    $state['direction'] = 'ASC';
	    
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		
	    $state = $model->getState();
	    
		// get categories for filtering
		$elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
		
		$vars = new JObject();
		Calendar::load( 'CalendarHelperBase', 'helpers._base' );
		$helper = new CalendarHelperBase();
		$values = $helper->elementsToArray( $elements );
		$item_id = $values['Itemid'];
		$vars->item_id = $item_id;
		$vars->values = $values;
		
		$categories = array();
		foreach($elements as $element)
		{
			if($element->checked && strpos ( $element->name , 'primary_cat_' ) !== false )
			{
				$categories[] = $element->value;
			}
		}
		
		$model->setState( 'filter_primary_categories', $categories );
		$model->setState( 'order', 'tbl.eventinstance_date' );
		$model->setState( 'direction', 'ASC' );
		$query = $model->getQuery( );
		$query->order( 'tbl.eventinstance_start_time' );
		$model->setQuery( $query );
		
	    $list = $model->getList();
	    $vars->items = $list;
	   
		// make date and time variables
		$date = new JObject();
		$date->current = $state->filter_date_from;
		$date->month = $state->month;
		$date->year = $state->year;
		$date->month_name = date( 'F', strtotime( $date->year . '-' . $date->month . '-01' ) );
		
		$date->nextmonth = date( 'm', strtotime( $date->year . '-' . $date->month . ' +1 month' ) );
		$date->nextyear = date( 'Y', strtotime( $date->year . '-' . $date->month . ' +1 month' ) );
		$date->prevmonth = date( 'm', strtotime( $date->year . '-' . $date->month . ' -1 month' ) );
		$date->prevyear = date( 'Y', strtotime( $date->year . '-' . $date->month . ' -1 month' ) );
		
		$date->weekdays = array( 'Sunday' => 'SUN', 'Monday' => 'MON', 'Tuesday' => 'TUES', 'Wednesday' => 'WED', 'Thursday' => 'THU', 'Friday' => 'FRI', 'Saturday' => 'SAT' );
		$date->weekstart = 'SUN';
		$date->weekend = 'SAT';
		$date->numberofdays = date( 't', strtotime( $date->year . '-' . $date->month . '-01' ) );
		$date->monthstartday = date( 'l', strtotime( $date->year . '-' . $date->month . '-01' ) );
		
		if ( $date->monthstartday == 'Friday' || $date->monthstartday == 'Saturday' )
		{
			$date->numberofweeks = 6;
		}
		else
		{
			$date->numberofweeks = 5;
		}

		$instance = JTable::getInstance( 'EventInstances', 'CalendarTable' );
		
		$days = array();
		foreach ($list as $item)
		{
		    $day = $item->eventinstance_date;
		    if (empty($days[$day]))
		    {
		        $days[$day] = new JObject();
		        $days[$day]->dateTime = strtotime( $day );
		        $days[$day]->dateMySQL = $day;
		        $days[$day]->events = array();
		    }
		    
		    $instance->event_full_image = $item->event_full_image;
		    $item->image_src = $instance->getImage('src');
		    $days[$day]->events[] = $item;
		}
			
		$vars->date = $date;
		$vars->days = $days;
				
		$html = $this->getLayout( 'default', $vars, 'month' ); 		
		echo ( json_encode( array('msg'=>$html) ) );
	}
	
	/**
	 * For filtering calendars by secondary categories.
	 * Is expected to be called via Ajax.
	 * 
	 * @param $categories array
	 * @param $type string primary or secondary type
	 * @return void
	 */
	function filtersecondary()
	{	
		$this->_setModelState();
	    $model = $this->getModel( 'month' );

	    $state = array();
	    $app = JFactory::getApplication( );
	    $ns = $this->getNamespace( );
		$m = JRequest::getVar( 'month' );
		$y = JRequest::getVar( 'year' );
		if( empty( $m ) && empty( $y ) )
		{
			$state['month'] = date( 'm' );
			$state['year'] = date( 'Y' );
		}		
		else 
		{
			$state['month'] = $app->getUserStateFromRequest( $ns . 'month', 'month', '', '' );
			$state['year'] = $app->getUserStateFromRequest( $ns . 'year', 'year', '', '' );
		}
		$state['filter_date_from'] = $state['year'] . '-' . $state['month'] . '-01';
        $state['filter_datetype'] = 'month';
        
	    Calendar::load( 'CalendarHelperBase', 'helpers._base' );
	    $helper = CalendarHelperBase::getInstance();
	    $datevars = $helper->setDateVariables( $state['filter_date_from'], null, 'monthly' );
	    $state['filter_date_to'] = $datevars->nextdate;
        
	    $state['order'] = 'tbl.eventinstance_date';
	    $state['direction'] = 'ASC';
	    
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		
        $state = $model->getState();	    
		// get categories for filtering		
		// take filter categories and do filtering
		$elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
		
		$vars = new JObject();
		Calendar::load( 'CalendarHelperBase', 'helpers._base' );
		$helper = new CalendarHelperBase();
		$values = $helper->elementsToArray( $elements );
		$item_id = $values['Itemid'];
		$vars->item_id = $item_id;
		$vars->values = $values;
		
		foreach($elements as $element)
		{
			if($element->checked && $element->name == 'secondary_category' )
			{
				$filter_category = $element->value;
			}
		}
		$model->setState( 'filter_secondary_category', $filter_category );
		
		$model->setState( 'order', 'tbl.eventinstance_date' );
		$model->setState( 'direction', 'ASC' );
		$query = $model->getQuery( );
		$query->order( 'tbl.eventinstance_start_time' );
		$model->setQuery( $query );
		
	    $list = $model->getList();
	    $vars->items = $list;
	    
		// make date and time variables
		$date = new JObject();
		$date->current = $state->filter_date_from;
		$date->month = $state->month;
		$date->year = $state->year;
		$date->month_name = date( 'F', strtotime( $date->year . '-' . $date->month . '-01' ) );
		
		$date->nextmonth = date( 'm', strtotime( $date->year . '-' . $date->month . ' +1 month' ) );
		$date->nextyear = date( 'Y', strtotime( $date->year . '-' . $date->month . ' +1 month' ) );
		$date->prevmonth = date( 'm', strtotime( $date->year . '-' . $date->month . ' -1 month' ) );
		$date->prevyear = date( 'Y', strtotime( $date->year . '-' . $date->month . ' -1 month' ) );
		
		$date->weekdays = array( 'Sunday' => 'SUN', 'Monday' => 'MON', 'Tuesday' => 'TUES', 'Wednesday' => 'WED', 'Thursday' => 'THU', 'Friday' => 'FRI', 'Saturday' => 'SAT' );
		$date->weekstart = 'SUN';
		$date->weekend = 'SAT';
		$date->numberofdays = date( 't', strtotime( $date->year . '-' . $date->month . '-01' ) );
		$date->monthstartday = date( 'l', strtotime( $date->year . '-' . $date->month . '-01' ) );
		
		if ( $date->monthstartday == 'Friday' || $date->monthstartday == 'Saturday' )
		{
			$date->numberofweeks = 6;
		}
		else
		{
			$date->numberofweeks = 5;
		}

		$instance = JTable::getInstance( 'EventInstances', 'CalendarTable' );
		
		$days = array();
		foreach ($list as $item)
		{
		    $day = $item->eventinstance_date;
		    if (empty($days[$day]))
		    {
		        $days[$day] = new JObject();
		        $days[$day]->dateTime = strtotime( $day );
		        $days[$day]->dateMySQL = $day;
		        $days[$day]->events = array();
		    }
		    
		    $instance->event_full_image = $item->event_full_image;
		    $item->image_src = $instance->getImage('src');
		    $days[$day]->events[] = $item;
		}
		
		$vars->date = $date;
		$vars->days = $days;
		
		$html = $this->getLayout( 'default', $vars, 'month' ); 
		echo ( json_encode( array('msg'=>$html) ) );
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return return_type
	 */
	function downloadICal()
	{
	    $instance_id = JRequest::getInt( 'instance_id' );
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		$instance = JTable::getInstance( 'EventInstances', 'CalendarTable' );
		$instance->load( $instance_id );
		
		if (empty($instance->eventinstance_id))
		{
		    return;
		}
		
		$instance->bindObjects();
		
		Calendar::load( 'CalendarHelperICal', 'helpers.ical' );
		$helper = new CalendarHelperICal();
		$helper->instance = $instance;
		$helper->download();
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return return_type
	 */
	function getEvents()
	{
	    $launch_date = '2011-08-03';
	    $day_after_launch_date = '2011-08-04';
	    
	    JRequest::setVar('format', 'json');
	    JLoader::import( 'com_calendar.library.json', JPATH_ADMINISTRATOR . DS . 'components' );
	    
	    JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
	    $model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
	    $model->setState('filter_enabled', '1' );
	    
	    $date = JRequest::getVar('date');
	    switch ($date)
	    {
	        case "featured":
	            // startdate == today + 2
	            $jdate = JFactory::getDate( strtotime('today +2 days') );
	            $day = $jdate->toFormat( '%Y-%m-%d' );
	            $model->setState('filter_date_from', $day );
	            $model->setState('filter_datetype', 'date' );
	            
	            $model->setState('filter_digital_signage', '1' );
	            $limit = JRequest::getInt('limit', 3);
	            $model->setState('limit', $limit );
	            $obj_name = "var objFeatured";
	            break;
	        case "tomorrow":
	            $jdate = JFactory::getDate( strtotime('tomorrow') );
	            $day = $jdate->toFormat( '%Y-%m-%d' );
	    	    if ($day < $day_after_launch_date)
	            {
	                //$day = $day_after_launch_date;
	            }
        	    $model->setState('filter_date_from', $day );
        	    $model->setState('filter_date_to', $day );
        	    $model->setState('filter_datetype', 'date' );
        	    $obj_name = "var objTomorrow";
	            break;
	        case "today":
	            $jdate = JFactory::getDate( strtotime('today') );
	            $day = $jdate->toFormat( '%Y-%m-%d' );
	            if ($day < $launch_date)
	            {
	                //$day = $launch_date;
	            }
        	    $model->setState('filter_date_from', $day );
        	    $model->setState('filter_date_to', $day );
        	    $model->setState('filter_datetype', 'date' );
        	    $obj_name = "var objToday";
	            break;
	        default:
	            if (empty($date))
	            {
	                $jdate = JFactory::getDate( strtotime('today') );
	            } 
	                else
	            {
	                $jdate = JFactory::getDate( strtotime( $date ) );
	            }
	            $day = $jdate->toFormat( '%Y-%m-%d');
        	    $model->setState('filter_date_from', $day );
        	    $model->setState('filter_date_to', $day );
        	    $model->setState('filter_datetype', 'date' );
        	    $obj_name = "var objDate";
	            break;
	    }
		$query = $model->getQuery( );
		$query->order( 'tbl.eventinstance_date' );
		$query->order( 'tbl.eventinstance_start_time' );
		$query->group( 'tbl.event_id' );
		$model->setQuery( $query );
		
		if (!$list = $model->getList())
		{
		    $list = array();
		    $object = new stdClass();
		    $object->error = JText::_( "No Events Scheduled" );
		    $list[] = $object;
		} 
		    else
		{
		    $keys = array( 'eventinstance_date', 'eventinstance_start_time', 'event_short_title', 'event_full_image', 'event_id', 'eventinstance_id' );
		    foreach ($list as $item)
		    {
		        $props = get_object_vars( $item );
		        foreach ($props as $key=>$prop)
		        {
		            if (!in_array($key, $keys))
		            {
		                unset($item->$key);
		            }
		        }
		    }
		}
	    
		$response = new stdClass();
		$response->data = $list;
		$string = json_encode( $response );
		$string = $obj_name . " = " . $string;
		echo $string;
	}
}
