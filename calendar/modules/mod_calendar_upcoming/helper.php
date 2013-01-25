<?php
/**
 * @package Calendar
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted Access' );

jimport( 'joomla.application.component.model' );

class modCalendarUpcomingHelper extends JObject
{
    public $models = array();
    
	/**
	 * Sets the modules params as a property of the object
	 * @param unknown_type $params
	 * @return unknown_type
	 */
	function __construct( $params )
	{
		$this->params = $params;
		
		JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
				
		$item_id = $params->get('item_id');
		if (empty($item_id))
		{
		    $active = JFactory::getApplication( )->getMenu( )->getActive( );
		    if ( !empty( $active ) )
		    {
		        $item_id = $active->id;
		    }
		    else
		    {
		        $item_id = JRequest::getInt( 'Itemid' );
		    }
		}
		
		$this->itemid = $item_id;
		
	}
	
	public function getModel( $name='EventInstances' )
	{
        if (empty($this->models[$name])) 
        {
            $this->models[$name] = JModel::getInstance( $name, 'CalendarModel' );
        }
        
        return $this->models[$name];	    
	}
	
	/**
	 * Gets the various db information to sucessfully display items
	 * @return unknown_type
	 */
	function getItems()
	{
	    $return = new modCalendarUpcomingDataSet( $this->params );
	    
	    $model = $this->getModel();
	    $table = $model->getTable();
	    
	    $list = array();
	    
	    $return->start_date = date('Y-m-d');
	    $return->end_date = date( 'Y-m-d', strtotime('+7 days', strtotime( $return->start_date ) ) );
	    $model->setState('filter_date_to', $return->end_date );
	    $model->setState('filter_enabled', 1);
	    
	    $types = array();
	    $param_types = explode(',', $this->params->get('types') );
	    foreach( $param_types as $type )
	    {
	        $type = trim($type);
	        if (!empty($type))
	        {
	            $types[] = $type;
	        }
	    }
	    $model->setState('filter_types', $types);
	    
	    $venues = array();
	    $param_venues = explode(',', $this->params->get('venues') );
	    foreach( $param_venues as $venue )
	    {
	        $venue = trim($venue);
	        if (!empty($venue))
	        {
	            $venues[] = $venue;
	        }
	    }
	    $model->setState('filter_venues', $venues);
	    
	    $list = $model->getList();

	    $today_items = array();
	    $this_week_items = array();
	    
	    foreach ($list as $item) 
	    {
	        if ($item->eventinstance_date == $return->start_date) {
	            $today_items[] = $item;
	        } else {
	            $this_week_items[] = $item;
	        }
	    }
	    
	    $return->today_items = $today_items;
	    $return->this_week_items = $list;
	    
	    return $return;
	}
	
	public function getAvailability( $items ) 
	{
	    $model = $this->getModel();
	    
	    $ids = DSCHelper::getColumn( $items, 'dataSourceID' );
	    $availability = $model->getAvailability( $ids );
        return $availability;
	}
}

class modCalendarUpcomingDataSet extends JObject 
{
    public $today;
    public $end_of_week;
    public $today_items = array();
    public $this_week_items = array();
    
    function __construct( $params )
    {
        $this->params = $params;
        
        // TODO use params for these
        $this->today = date( 'Y-m-d' );
        $this->end_of_week = date( 'Y-m-d', strtotime('now +7 day') );
    }
}
?>