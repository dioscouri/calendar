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
jimport('joomla.application.component.model');

class modCalendarFiltersHelper
{
	/**
	 * Sets the modules params as a property of the object
	 * @param unknown_type $params
	 * @return unknown_type
	 */
	function __construct( $params=null )
	{
		$this->params = $params;
		
		Calendar::load( 'CalendarHelperBase', 'helpers.base' );
		$event_helper = CalendarHelperBase::getInstance( 'event' );
		$this->state = $event_helper->getState(); 
	}
	
	function getVenues()
	{
	    JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
	    $model = JModel::getInstance( 'Venues', 'CalendarModel' );
	    $model->setState( 'filter_admin_only', '0' );
	    $model->setState( 'order', 'tbl.ordering' );
	    if ($items = $model->getList()) {
	        foreach ($items as $item) {
	            $item->filter_selected = false;
	            if (in_array($item->getDataSourceID(), $this->state['filter_venues'])) {
	                $item->filter_selected = true;
	            }
	        }
	    }
	    
	    return $items;
	}
	
	function getTypes()
	{
	    JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
	    $model = JModel::getInstance( 'Types', 'CalendarModel' );
	    $model->setState( 'filter_site', '1' );
	 	$model->setState( 'filter_admin_only', '0' );
	    $model->setState( 'order', 'tbl.ordering' );
		if ($items = $model->getList()) {
	        foreach ($items as $item) {
	            $item->filter_selected = false;
	            if (in_array($item->type_id, $this->state['filter_types'])) {
	                $item->filter_selected = true;
	            }
	        }
	    }
	     
	    return $items;
	}
	
	function getPopularSearches()
	{
	    // TODO Does this need to be dependant on the calendar_id being displayed?
	    
	    return array();
	    
	    JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
	    $model = JModel::getInstance( 'Types', 'CalendarModel' );
	    $model->setState( 'filter_site', '1' );
	    $model->setState( 'order', 'tbl.ordering' );
	    if ($items = $model->getList()) {
	        foreach ($items as $item) {
	            $item->filter_selected = false;
	            if (in_array($item->type_id, $this->state['filter_types'])) {
	                $item->filter_selected = true;
	            }
	        }
	    }
	
	    return $items;
	}
}