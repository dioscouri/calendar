<?php
/**
 * @version	1.5
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

// Check the registry to see if our Calendar class has been overridden
if ( !class_exists( 'Calendar' ) ) JLoader::register( "Calendar", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_calendar" . DS . "defines.php" );

// include lang files
$element = strtolower( 'com_calendar' );
$lang = &JFactory::getLanguage( );
$lang->load( $element, JPATH_BASE );
$lang->load( $element, JPATH_ADMINISTRATOR );

$large_width = '720';
$large_height = '405';
$medium_width = '359';
$medium_height = '203';
$small_width = '239';
$small_height = '134';

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

JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );

$vars = new JObject();
$vars->item_id = $item_id;
$rows = array();
$ids = explode(',', $params->get('ids') );

$use_upcoming = $params->get('use_upcoming');
if ($use_upcoming)
{
    $date = JFactory::getDate();
    $ids = array();
    // $ids = array of ids from the queue
    
    $model = JModel::getInstance( 'Eventinstances', 'CalendarModel' );
    $model->setState( 'limit', $params->get('limit') );
    $model->setState( 'filter_upcoming_enabled', '1' );
    $model->setState( 'filter_date_from', $date->toFormat( '%Y-%m-%d' ) );
    $model->setState( 'order', 'tbl.eventinstance_date' );
    $model->setState( 'direction', 'ASC' );
	$query = $model->getQuery( );
	$query->order( 'tbl.eventinstance_start_time' );
	$model->setQuery( $query );
		
    if ($list = $model->getList())
    {
        foreach ($list as $li)
        {
            $ids[] = $li->eventinstance_id;
        } 
    }
}

if (count($ids) > '1')
{
    $rows = array();
    foreach ($ids as $id)
    {
		$row = JTable::getInstance( 'EventInstances', 'CalendarTable' );
		$row->load( $id );
		$row->bindObjects();
                
        if (empty($row->event_published) || empty($row->eventinstance_id))
        {
            continue;
        }
        
        $rows[] = $row;                        
    }
    
    $vars->rows = $rows;
    
    if ($params->get('slideshow') == '1')
    {
        require ( JModuleHelper::getLayoutPath( 'mod_calendar_eventinstances', "slideshow_". $params->get('layout', 'large') ) );
    }
    else
    {
        require ( JModuleHelper::getLayoutPath( 'mod_calendar_eventinstances', "many_". $params->get('layout', 'large') ) );
    }
}
    else
{

	$row = JTable::getInstance( 'EventInstances', 'CalendarTable' );
	$row->load( $ids[0] );
	$row->bindObjects();
            
    if (!empty($row->event_published) && !empty($row->eventinstance_id))
    {
        $vars->row = $row;
        require ( JModuleHelper::getLayoutPath( 'mod_calendar_eventinstances', $params->get('layout', 'large') ) );        
    } 
}



