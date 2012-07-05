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
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.model' );

class modCalendarCategoriesHelper
{
	/**
	 * Sets the modules params as a property of the object
	 * @param unknown_type $params
	 * @return unknown_type
	 */
	function __construct( $params )
	{
		$this->params = $params;
	}
	
	/**
	 * Gets primary categories
	 * 
	 * @param void
	 * @return $categories array of used categories as primary category
	 */
	function getPrimaryCategories( $calendar_id=null )
	{
		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
		$model = JModel::getInstance( 'Eventinstances', 'CalendarModel' );
		$categories = $model->getUsedPrimaryCategories( $calendar_id );
		
		$v = JRequest::getVar('v');
		$state = $this->getState();		
		$primary_cats_count = count($state['filter_primary_categories']);
		
	    foreach ($categories as $key=>$category)
        {
            if (empty($category->category_id) || empty($category->category_name))
            {
                unset($categories[$key]);
                continue;
            }
            $category->checked = '';
            if (in_array($category->category_id, $state['filter_primary_categories']) || ($state['filter_primary_categories'][0] == '-1' && $primary_cats_count == '1') )
            {
                $category->checked = "checked='checked'";
            }
        }
		
		return $categories;
	}
	
	/**
	 * Gets secondary categories
	 * 
	 * @param void
	 * @return $categories array of used categories as secondary categories
	 */
	function getSecondaryCategories( $calendar_id=null )
	{
	    $state = $this->getState();
	    $filter_primary_categories = $state['filter_primary_categories'];
	    
		$v = JRequest::getVar('v');

		$calendar = JTable::getInstance( 'Calendars', 'CalendarTable' );
		if (!empty( $calendar_id )) {
		    JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		    $calendar->load( $calendar_id );
		}
		
		$state = $this->getState();
		
		$view = $state['view']; // JRequest::getVar( 'view', 'month' );
		$year = $state['year']; // JRequest::getVar( 'year' );
		$month = $state['month']; // JRequest::getVar( 'month' );
		$current_date = $state['current_date']; // JRequest::getVar( 'current_date' );
		$date_from = $current_date ? $current_date : $year . '-' . $month . '-01';
		
	    Calendar::load( 'CalendarHelperBase', 'helpers._base' );
	    $helper = CalendarHelperBase::getInstance();
	    $datevars = $helper->setDateVariables( $date_from, null, $view );
	    $date_to = $datevars->nextdate;

		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
		$model = JModel::getInstance( 'SecondCategories', 'CalendarModel' );
		$model->setState( 'order', 'tbl.ordering' );
		if (!empty($calendar->calendar_filter_secondary_categories)) {
		    $category_ids = explode( ',', $calendar->calendar_filter_secondary_categories );
		    foreach ($category_ids as &$cat) {
		        $cat = trim( $cat );
		    }
		    $model->setState( 'filter_ids', $category_ids );
		}
		
		$secondary_cats = array();
		
		$table = $model->getTable();
		$table->category_id = 0;
		$table->category_name = JText::_( "All" );
		$model_eventinstances = JModel::getInstance( 'Eventinstances', 'CalendarModel' );
		$model_eventinstances->setState( 'filter_secondary_category', '' );
		$model_eventinstances->setState( 'filter_enabled', '1' );
		$model_eventinstances->setState( 'filter_datetype', $view );
		$model_eventinstances->setState( 'filter_date_from', $date_from );
		$model_eventinstances->setState( 'filter_date_to', $date_to );
		$model_eventinstances->setState( 'filter_primary_categories', $filter_primary_categories );
		$table->instancescount = $model_eventinstances->getTotal( true );

		$table->checked = '';
		if ($state['filter_secondary_category'] == 0)
		{
		    $table->checked = "checked='checked'";
		} 
		$secondary_cats[] = $table;
		
		if ($categories = $model->getList( ))
		{
		    foreach ( $categories as $category )
		    {
				$model_eventinstances = JModel::getInstance( 'Eventinstances', 'CalendarModel' );
				$model_eventinstances->setState( 'filter_secondary_category', $category->category_id );
				$model_eventinstances->setState( 'filter_enabled', '1' );
        		$model_eventinstances->setState( 'filter_datetype', $view );
        		$model_eventinstances->setState( 'filter_date_from', $date_from );
        		$model_eventinstances->setState( 'filter_date_to', $date_to );
				$model_eventinstances->setState( 'filter_primary_categories', $filter_primary_categories );
				$category->instancescount = $model_eventinstances->getTotal( true );

        		$category->checked = '';
        		if ($state['filter_secondary_category'] == $category->category_id )
        		{
        		    $category->checked = "checked='checked'";
        		} 
				
				$secondary_cats[] = $category;
		    }
		}
		return $secondary_cats;
	}

    function getState()
    {
        if (empty($this->state))
        {
            Calendar::load( 'CalendarHelperBase', 'helpers._base' );
            $helper = CalendarHelperBase::getInstance( 'event' );
            $this->state = $helper->getState();
        }
        
        return $this->state;
    }
}
?>