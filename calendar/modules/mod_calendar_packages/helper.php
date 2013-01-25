<?php
/**
 * @package		Calendar Packages
 * @subpackage	mod_calendar_packages
 * @copyright	Copyright (C) 2012 Dioscouri Design. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class modCalendarPackagesHelper extends JObject
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
		
		$model = JModel::getInstance( 'Packages', 'CalendarModel' );
		if ($list = $model->getGroups()) 
		{
		    $router = new CalendarHelperRoute();
		    foreach ($list as $item) 
		    {
		        $item->itemid = $router->findItemid( array('view'=>'packages', 'task'=>'view', 'id'=>$item->alias) );
		    }
		    $return = $list;
		}
				
        return $return;
	}
}
