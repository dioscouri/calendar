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
	
	/**
	 * Gets the various db information to sucessfully display items
	 * @return unknown_type
	 */
	function getItems()
	{
	    $return = new modCalendarUpcomingDataSet( $this->params );
	    
	    $model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
	    $table = $model->getTable();
	    
	    $list = array();
	    
	    $list[] = $table;
	    $list[] = $table;
	    $list[] = $table;
	    $list[] = $table;
	    $list[] = $table;
	    $list[] = $table;
	    
	    $return->today_items = $list;
	    
	    $list[] = $table;
	    $list[] = $table;
	    $list[] = $table;
	    $list[] = $table;
	    $list[] = $table;
	    $list[] = $table;
	    $list[] = $table;
	    $list[] = $table;
	    $list[] = $table;
	    $list[] = $table;
	    
	    $return->this_week_items = $list;
	    
	    return $return;
	    
        $model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
        
        if ($list = $model->getList())
        {
            foreach ($list as $list_item)
            {
                
            }
        }
        		
		return $list;
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