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

class CalendarControllerThree extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		$this->set( 'suffix', 'three' );
	}
	
	function _setModelState( )
	{
		$state = parent::_setModelState( );
		$app = JFactory::getApplication( );
		$model = $this->getModel( $this->get( 'suffix' ) );
		$ns = $this->getNamespace( );
		
		$state['limit'] = '';
		$state['filter_id_from'] = $app->getUserStateFromRequest( $ns . 'id_from', 'filter_id_from', '', '' );
		$state['filter_id_to'] = $app->getUserStateFromRequest( $ns . 'id_to', 'filter_id_to', '', '' );
		$state['filter_name'] = $app->getUserStateFromRequest( $ns . 'name', 'filter_name', '', '' );
		$state['filter_enabled'] = '1';
				
		$d = JRequest::getVar( 'current_date' );
		if( empty( $d ) )
		{
			$state['filter_date_from'] =  date( 'Y-m-d');
		}		
		else 
		{
			$state['filter_date_from'] = $app->getUserStateFromRequest( $ns . 'filter_date_from', 'current_date', '', '' );
		}		
        $state['filter_datetype'] = 'date';
        
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		return $state;
	}
	
	function display( )
	{
		// make date and time variables
		$this->_setModelState();
	    $model = $this->getModel( $this->get( 'suffix' ) );
	    $state = $model->getState();
	    		
		$date->current = $state->filter_date_from; 
		
		$date->month = date( 'm', strtotime($date->current) );
		$date->year = date( 'Y', strtotime($date->current) );
		
		// date time variables
		$date->days = $this->getDays( $date->current );
		$date->hours = $this->getHours( );
		
		// datetime matrix
		$datetime = array( );
		for ( $i = 0; $i < 3; $i++ )
		{
			for ( $j = 0; $j < 24; $j++ )
			{
				$dayskey = $date->days[$i];
				$hourskey = $date->hours[$j];
				$datetime[$dayskey][$hourskey] = '';
			}
		}
		$date->datetime = $datetime;
		
		// navigation dates
		$date->nextthreedate = date( 'Y-m-d', strtotime( $date->current . ' +3 days' ) );
		$date->nextmonth     = date( 'm',     strtotime( $date->current . ' +3 days' ) );
		$date->nextyear      = date( 'Y',     strtotime( $date->current . ' +3 days' ) );
		$date->prevthreedate = date( 'Y-m-d', strtotime( $date->current . ' -3 days' ) );
		$date->prevmonth     = date( 'm',     strtotime( $date->current . ' -3 days' ) );
		$date->prevyear      = date( 'Y',     strtotime( $date->current . ' -3 days' ) );
		
		// aditional variables
		$date->startday = date( 'd', strtotime( $date->days[0] ) );
		$date->startmonth = date( 'm', strtotime( $date->days[0] ) );
		$date->startmonthname = date( 'F', strtotime( $date->days[0] ) );
		$date->startyear = date( 'Y', strtotime( $date->days[0] ) );
		$date->endday = date( 'd', strtotime( $date->days[2] ) );
		$date->endmonth = date( 'm', strtotime( $date->days[2] ) );
		$date->endmonthname = date( 'F', strtotime( $date->days[2] ) );
		$date->endyear = date( 'Y', strtotime( $date->days[2] ) );
		$date->nonworkingdays = $this->getNonWorkingDays( );
		
		$view = $this->getView( $this->get( 'suffix' ), 'html' );
		$view->assign( 'date', $date );
		
		parent::display( );
	}
	
	/**
	 * Gets three dates array (given date +2)
	 * 
	 * @param string $date
	 * @return array $days
	 */
	function getDays( $date )
	{
		$days = array( );
		
		$days[0] = date( 'Y-m-d', strtotime( $date ) );
		$days[1] = date( 'Y-m-d', strtotime( $date . ' +1 day' ) );
		$days[2] = date( 'Y-m-d', strtotime( $date . ' +2 days' ) );
		
		return $days;
	}
	
	/**
	 * Makes hours array
	 * 
	 * @param void
	 * @return array of strings with hours (am/pm)
	 */
	function getHours( )
	{
		$hours = array( );
		
		$hours[0] = '12am';
		for ( $i = 1; $i < 12; $i++ )
		{
			$hours[] = $i . 'am';
		}
		
		$hours[12] = '12pm';
		$counter = 1;
		for ( $i = 13; $i < 24; $i++ )
		{
			$hours[] = $counter . 'pm';
			$counter++;
		}
		
		return $hours;
	}
	
	/**
	 * Returns array of non-working days
	 * 
	 * @param void
	 * @return array non working days
	 */
	function getNonWorkingDays( )
	{
		$config = Calendar::getInstance( );
		
		$str_days = $config->get( 'non_working_days' );
		
		$non_working_days = array( );
		$str_days = @preg_replace( '/\s/', '', $str_days );
		$non_working_days = explode( ',', $str_days );
		
		return $non_working_days;
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
	    		
		$date->current = $state->filter_date_from; 
		
		$date->month = date( 'm', strtotime($date->current) );
		$date->year = date( 'Y', strtotime($date->current) );
		
		// date time variables
		$date->days = $this->getDays( $date->current );
		$date->hours = $this->getHours( );
		
		// datetime matrix
		$datetime = array( );
		for ( $i = 0; $i < 3; $i++ )
		{
			for ( $j = 0; $j < 24; $j++ )
			{
				$dayskey = $date->days[$i];
				$hourskey = $date->hours[$j];
				$datetime[$dayskey][$hourskey] = '';
			}
		}
		$date->datetime = $datetime;
		
		// navigation dates
		$date->nextthreedate = date( 'Y-m-d', strtotime( $date->current . ' +3 days' ) );
		$date->nextmonth     = date( 'm',     strtotime( $date->current . ' +3 days' ) );
		$date->nextyear      = date( 'Y',     strtotime( $date->current . ' +3 days' ) );
		$date->prevthreedate = date( 'Y-m-d', strtotime( $date->current . ' -3 days' ) );
		$date->prevmonth     = date( 'm',     strtotime( $date->current . ' -3 days' ) );
		$date->prevyear      = date( 'Y',     strtotime( $date->current . ' -3 days' ) );
		
		// aditional variables
		$date->startday = date( 'd', strtotime( $date->days[0] ) );
		$date->startmonth = date( 'm', strtotime( $date->days[0] ) );
		$date->startmonthname = date( 'F', strtotime( $date->days[0] ) );
		$date->startyear = date( 'Y', strtotime( $date->days[0] ) );
		$date->endday = date( 'd', strtotime( $date->days[2] ) );
		$date->endmonth = date( 'm', strtotime( $date->days[2] ) );
		$date->endmonthname = date( 'F', strtotime( $date->days[2] ) );
		$date->endyear = date( 'Y', strtotime( $date->days[2] ) );
		$date->nonworkingdays = $this->getNonWorkingDays( );
		
		// take filter categories and do filtering
		$elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
		
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
		
		// attach variables to the $vars object
		$vars->date = $date;
		$vars->items = $model->getList( );
		
		// echo output
		$html = $this->getLayout( 'default', $vars, 'three' ); 
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
		// make date and time variables
		$this->_setModelState();
	    $model = $this->getModel( $this->get( 'suffix' ) );
	    $state = $model->getState();
	    		
		$date->current = $state->filter_date_from; 
		
		$date->month = date( 'm', strtotime($date->current) );
		$date->year = date( 'Y', strtotime($date->current) );
		
		// date time variables
		$date->days = $this->getDays( $date->current );
		$date->hours = $this->getHours( );
		
		// datetime matrix
		$datetime = array( );
		for ( $i = 0; $i < 3; $i++ )
		{
			for ( $j = 0; $j < 24; $j++ )
			{
				$dayskey = $date->days[$i];
				$hourskey = $date->hours[$j];
				$datetime[$dayskey][$hourskey] = '';
			}
		}
		$date->datetime = $datetime;
		
		// navigation dates
		$date->nextthreedate = date( 'Y-m-d', strtotime( $date->current . ' +3 days' ) );
		$date->nextmonth     = date( 'm',     strtotime( $date->current . ' +3 days' ) );
		$date->nextyear      = date( 'Y',     strtotime( $date->current . ' +3 days' ) );
		$date->prevthreedate = date( 'Y-m-d', strtotime( $date->current . ' -3 days' ) );
		$date->prevmonth     = date( 'm',     strtotime( $date->current . ' -3 days' ) );
		$date->prevyear      = date( 'Y',     strtotime( $date->current . ' -3 days' ) );
		
		// aditional variables
		$date->startday = date( 'd', strtotime( $date->days[0] ) );
		$date->startmonth = date( 'm', strtotime( $date->days[0] ) );
		$date->startmonthname = date( 'F', strtotime( $date->days[0] ) );
		$date->startyear = date( 'Y', strtotime( $date->days[0] ) );
		$date->endday = date( 'd', strtotime( $date->days[2] ) );
		$date->endmonth = date( 'm', strtotime( $date->days[2] ) );
		$date->endmonthname = date( 'F', strtotime( $date->days[2] ) );
		$date->endyear = date( 'Y', strtotime( $date->days[2] ) );
		$date->nonworkingdays = $this->getNonWorkingDays( );
		
		// get categories for filtering
		
		$filter_category = JRequest::getVar( 'filter_category' );
		$model->setState( 'filter_secondary_category', $filter_category );
		
		$model->setState( 'order', 'tbl.eventinstance_date' );
		$model->setState( 'direction', 'ASC' );
		$query = $model->getQuery( );
		$query->order( 'tbl.eventinstance_time' );
		$model->setQuery( $query );
		
		$vars->date = $date;
		$vars->items = $model->getList( );
		
		$html = $this->getLayout( 'default', $vars, 'three' ); 
		echo ( json_encode( array('msg'=>$html) ) );
	}
}
