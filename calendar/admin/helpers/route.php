<?php
/**
 * @package Calendar
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

if ( !class_exists('Calendar') ) { 
    JLoader::register( "Calendar", JPATH_ADMINISTRATOR.DS."components".DS."com_calendar".DS."defines.php" );
}

class CalendarHelperRoute extends DSCHelperRoute 
{
    static $itemids = null;
    
    public static function getItems( $option='com_calendar' )
    {
        return parent::getItems($option);        
    }
    
    /**
     * Build the route
     *
     * @param   array   An array of URL arguments
     * @return  array   The URL arguments to use to assemble the URL
     */
    public static function build( &$query )
    {
        $segments = array();
    
        // get a menu item based on the Itemid or the currently active item
        $menu = JFactory::getApplication()->getMenu();
    
        if (empty($query['Itemid']))
        {
            $item = $menu->getActive();
            $menuItemGiven = false;
        }
        else
        {
            $item = $menu->getItem( $query['Itemid'] );
            $menuItemGiven = true;
        }
    
        $menuView = (empty($item->query['view'])) ? null : $item->query['view'];
        $menuTask = (empty($item->query['task'])) ? null : $item->query['task'];
        $menuId = (empty($item->query['id'])) ? null : $item->query['id'];
    
        // if the menu item and the query match...
        if ($menuView == @$query['view'] &&
                $menuTask == @$query['task'] &&
                $menuId == @$query['id']
        ) {
            // unset all variables and use the menu item's alias set by user
            unset ($query['view']);
            unset ($query['task']);
            unset ($query['id']);
        }
    
        // otherwise, create the sef url from the query
        if ( !empty ($query['view'])) {
            $view = $query['view'];
            $segments[] = $view;
            unset ($query['view']);
        }
    
        if ( !empty ($query['task'])) {
            $task = $query['task'];
            $segments[] = $task;
            unset ($query['task']);
        }
    
        if ( !empty ($query['id'])) {
            $id = $query['id'];
            $segments[] = $id;
            unset ($query['id']);
        }
        
        return $segments;
    }
    
    /**
     * Parses the segments of a URL
     *
     * @param   array   The segments of the URL to parse
     * @return  array   The URL attributes
     */
    public static function parse( $segments )
    {
        //echo "segments:<br /><pre>";
        //print_r($segments);
        //echo "</pre>";
    
        $vars = array();
        $count = count($segments);
    
        $vars['view'] = $segments[0];
        switch ($segments[0])
        {
            default:
                if ($count == '2')
                {
                    switch($segments[0]) {
                        case "event":
                        case "eventinstance":
                            // is $segments[1] an integer, task, alias, or datasource:id string?
                            Calendar::load( 'CalendarModelBase', 'models.base' );
                            $model = new CalendarModelBase();
                            if ($datasource = $model->getDatasource( $segments[1] ) ) {
                                $vars['id'] = $segments[1];
                            } elseif (is_numeric($segments[1])) {
                                $vars['id'] = $segments[1];
                            } else {
                                $vars['task'] = $segments[1];
                            }
                            break;
                        default:
                            $vars['task'] = $segments[1];
                            break;
                    }
                }
    
                if ($count == '3')
                {
                    $vars['task'] = $segments[1];
                    $vars['id'] = $segments[2];
                }
                
                break;
        }
    
    
        //echo "vars:<br /><pre>";
        //print_r($vars);
        //echo "</pre>";
    
        return $vars;
    }
    
    public static function findItemid($needles=array('view'=>'events', 'task'=>'', 'id'=>'')) 
    {
        // populate the array of menu items for the extension
        if (empty(self::$itemids))
        {
            self::$itemids = array();

            // method=upgrade KILLS all of the useful properties in the __menus table,
            // so we need to do this manually
            // $menus      = &JApplication::getMenu('site', array());
            // $component  = &JComponentHelper::getComponent('com_sample');
            // $items      = $menus->getItems('componentid', $component->id);
            $items = self::getItems();
            
            if (empty( $items ))
            {
                return null;
            }

            foreach ($items as $item)
            {
                if (!empty($item->query) && !empty($item->query['view']))
                {
                    // reconstruct each url query, in case admin has created custom URLs
                    $query = "";
                    
                    $view = $item->query['view'];
                    $query .= "&view=$view";
                    
                    if (!empty($item->query['task']))
                    {
                        $task = $item->query['task'];
                        $query .= "&task=$task";                        
                    }

                    if (!empty($item->query['id']))
                    {
                        $id = $item->query['id'];
                        $query .= "&id=$id";                        
                    }
                    
                    // set the itemid in the cache array
                    if (empty(self::$itemids[$query])) 
                    {
                        self::$itemids[$query] = $item->id;
                    }
                }
            }
        }

        
        // Make this search the array of self::$itemids, matching with the properties of the $needles array
        // return null if nothing found
        
        // reconstruct query based on needle
        $query = "";
        
        if (!empty($needles['view']))
        {
            $view = $needles['view'];
            $query .= "&view=$view";                        
        }
        
        if (!empty($needles['task']))
        {
            $task = $needles['task'];
            $query .= "&task=$task";                        
        }

        if (!empty($needles['id']))
        {
            $id = $needles['id'];
            $query .= "&id=$id";                        
        }
        
        // if the query exists in the itemid cache, return it
        if (!empty(self::$itemids[$query])) 
        {
            return self::$itemids[$query];
        }

        return null;
    }
}