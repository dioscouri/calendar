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

class modCalendarEventinstancesHelper extends JObject
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
	   	JModel::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_calendar/models');    
	    $model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
	    $table = $model->getTable();
	    
	    $list = array();
	    
	    $return->start_date = date('Y-m-d');
	    $return->end_date = date( 'Y-m-d', strtotime('+7 days', strtotime( $return->start_date ) ) );
	    $model->setState('filter_date_to', $return->end_date );
	    
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
}

?>