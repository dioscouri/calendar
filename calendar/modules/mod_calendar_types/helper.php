<?php
/**
 * @package		Calendar Types
 * @subpackage	mod_calendar_types
 * @copyright	Copyright (C) 2012 Dioscouri Design. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class modCalendarTypesHelper extends JObject
{
    public function __construct( $params )
    {
        parent::__construct();
        
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
	 * 
	 * @return unknown_type
	 */
	public function getItems()
	{
		$return = array();
		
		$parentPath = JPATH_ADMINISTRATOR . '/components/com_calendar/helpers';
		DSCLoader::discover('CalendarHelper', $parentPath, true);
		
		$parentPath = JPATH_ADMINISTRATOR . '/components/com_calendar/library';
		DSCLoader::discover('Calendar', $parentPath, true);
		
		$model = JModel::getInstance( 'Types', 'CalendarModel' );
		$model->setState('filter_class', $this->params->get('filter_class') );
		if ($list = $model->getList()) 
		{
		    $router = new CalendarHelperRoute();
		    foreach ($list as $item) 
		    {
		        $item->itemid = $router->findItemid( array('view'=>'types', 'task'=>'view', 'id'=>$item->type_id) );
		    }
		    $return = $list;
		}
				
        return $return;
	}
}
