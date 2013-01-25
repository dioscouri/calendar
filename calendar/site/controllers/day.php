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

class CalendarControllerDay extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		$this->set( 'suffix', 'day' );
	}
	
	function _setModelState( )
	{
		$state = parent::_setModelState( );
		$app = JFactory::getApplication( );
		$model = $this->getModel( $this->get( 'suffix' ) );
		$ns = $app->getName().'::'.'com.calendar.mod.categories';
		
        Calendar::load( 'CalendarHelperBase', 'helpers.base' );
        $event_helper = CalendarHelperBase::getInstance( 'event' );
        $state = $event_helper->getState();
		
		$state['limit'] = '';
		$state['filter_id_from'] = $app->getUserStateFromRequest( $ns . 'id_from', 'filter_id_from', '', '' );
		$state['filter_id_to'] = $app->getUserStateFromRequest( $ns . 'id_to', 'filter_id_to', '', '' );
		$state['filter_name'] = $app->getUserStateFromRequest( $ns . 'name', 'filter_name', '', '' );
		$state['filter_enabled'] = '1';
	    $state['order'] = 'tbl.eventinstance_date';
	    $state['direction'] = 'ASC';
	    $state['filter_date_from'] = $state['date'];
        $state['filter_datetype'] = 'date';
	    $state['filter_date_to'] = $state['filter_date_from'];
	    
        JRequest::setVar('month', $state['month'] );
        JRequest::setVar('year', $state['year'] );
                
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		return $state;
	}
	
	function display($cachable=false, $urlparams = false)
	{
	    $this->_setModelState();
	    $model = $this->getModel( $this->get( 'suffix' ) );
	    $state = $model->getState();
	    $view = $this->getView( $this->get( 'suffix' ), 'html' );
	    
		JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		$calendar = JTable::getInstance( 'Calendars', 'CalendarTable' );
		$calendar->load( $state->calendar_id );

		// TODO do a validity check -- certain calendars should only display events from today => forward,
		// so add that as a boolean param for #__calendars_calendars, and enforce it here
		$defines = Calendar::getInstance();
		$document = JFactory::getDocument();
		$document->addScriptDeclaration( "window.async_actionbuttons = " . $defines->get('async_actionbuttons') );
		
		if ($model->pingTessituraWebAPI())
		{
		    $list = $model->getList();
			if ($defines->get('async_actionbuttons')) {
		        $ids = array();
		        $availability = array();
		    } else {
		        $ids = DSCHelper::getColumn( $list, 'dataSourceID' );
		        $availability = $model->getAvailability( $ids );		        
		    }
		}
		else
		{
		    $list = array();
		    $ids = array();
		    $availability = array();
		    $view->set('no_items', true);
		    $view->set('no_pagination', true);
		}
	    
		$date_navigation = new JObject();
		$date_navigation->current = $state->filter_date_from;
		$date_navigation->prev = date('Y-m-d', strtotime( $state->filter_date_from . ' -1 days' ) );
		$date_navigation->next = date('Y-m-d', strtotime( $state->filter_date_from . ' +1 days' ) );		
		
		$count = count($list);
		
		$view->assign( 'date_navigation', $date_navigation );
		$view->assign( 'count', $count );
		$view->assign( 'calendar', $calendar );
		$view->assign( 'availability', $availability );
		
		parent::display($cachable, $urlparams);
	}
	
	/**
	 * For filtering calendars by categories.
	 * Is expected to be called via Ajax.
	 * 
	 * @param $categories array
	 * @param $type string primary or secondary type
	 * @return void
	 */
	function filterprimary()
	{	
	// make date and time variables
		$this->_setModelState();
	    $model = $this->getModel( $this->get( 'suffix' ) );
	    $state = $model->getState();
	    
	    // take filter categories and do filtering
		$elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
		
		$vars = new JObject();
		Calendar::load( 'CalendarHelperBase', 'helpers.base' );
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
				$categories[$element->name] = $element->value;
			}
		}
		
		$model->setState( 'filter_primary_categories', $categories );
		
		// order event instances by date and time
		$model->setState( 'order', 'tbl.eventinstance_date' );
		$model->setState( 'direction', 'ASC' );
		$query = $model->getQuery( );
		$query->order( 'tbl.eventinstance_start_time' );
		$model->setQuery( $query );
		
		$list = $model->getList();
		$vars->items = $list;
	    		
		$date->current = $state->filter_date_from; 
		
		$date->month = date( 'm', strtotime($date->current) );
		$date->year = date( 'Y', strtotime($date->current) );
		$date->month_name = date( 'F', strtotime( $date->year . '-' . $date->month . '-01' ) );
		
		// date and time variables 
		$date->hours = $this->getHours( );
		$date->nonworkingdays = $this->getNonWorkingDays( );
		
		// navigation dates
		$date->nextdaydate = date( 'Y-m-d', strtotime( $date->current . ' +1 day' ) );
		$date->nextmonth   = date( 'm',     strtotime( $date->current . ' +1 day' ) );
		$date->nextyear    = date( 'Y',     strtotime( $date->current . ' +1 day' ) );
		$date->prevdaydate = date( 'Y-m-d', strtotime( $date->current . ' -1 day' ) );
		$date->prevmonth   = date( 'm',     strtotime( $date->current . ' -1 day' ) );
		$date->prevyear    = date( 'Y',     strtotime( $date->current . ' -1 day' ) );
		
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
				
		// attach variables to the $vars object
		$vars->date = $date;
		$vars->days = $days;
				
		$module_html = $this->loadModule( JRequest::getVar('module_id') );
		$html = $this->getLayout( 'default', $vars, 'month' );
		
		$return = array();
		$return['content'] = $html;
		$return['module'] = $module_html; 		
		echo ( json_encode( $return ) );
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
	// make date and time variables
		$this->_setModelState();
	    $model = $this->getModel( $this->get( 'suffix' ) );
	    $state = $model->getState();
	    
	    // get categories for filtering		
		// take filter categories and do filtering
		$elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
		
		$vars = new JObject();
		Calendar::load( 'CalendarHelperBase', 'helpers.base' );
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
			    		
		$date->current = $state->filter_date_from; 
		$date->month = date( 'm', strtotime($date->current) );
		$date->year = date( 'Y', strtotime($date->current) );
		$date->month_name = date( 'F', strtotime( $date->year . '-' . $date->month . '-01' ) );
		
		// date and time variables 
		$date->hours = $this->getHours( );
		$date->nonworkingdays = $this->getNonWorkingDays( );
		
		// navigation dates
		$date->nextdaydate = date( 'Y-m-d', strtotime( $date->current . ' +1 day' ) );
		$date->nextmonth   = date( 'm',     strtotime( $date->current . ' +1 day' ) );
		$date->nextyear    = date( 'Y',     strtotime( $date->current . ' +1 day' ) );
		$date->prevdaydate = date( 'Y-m-d', strtotime( $date->current . ' -1 day' ) );
		$date->prevmonth   = date( 'm',     strtotime( $date->current . ' -1 day' ) );
		$date->prevyear    = date( 'Y',     strtotime( $date->current . ' -1 day' ) );
		
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
		
		$html = $this->getLayout( 'default', $vars, 'day' ); 
		echo ( json_encode( array('msg'=>$html) ) );
	}
}