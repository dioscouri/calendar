<?php
/**
 * @version	1.5
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
jimport('joomla.plugin.plugin');

class plgSearchCalendar extends JPlugin 
{   
    function plgSearchCalendar(& $subject, $config)
    {
        parent::__construct($subject, $config);
    }
    
    /**
     * Checks the extension is installed
     * 
     * @return boolean
     */
    function _isInstalled()
    {
        $success = false;
        
        jimport('joomla.filesystem.file');
        if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_calendar'.DS.'defines.php')) 
        {
            $success = true;
            if ( !class_exists('Calendar') ) {
                JLoader::register( "Calendar", JPATH_ADMINISTRATOR.DS."components".DS."com_calendar".DS."defines.php" );
                JLoader::register( "Calendar", JPATH_ADMINISTRATOR.DS."components".DS."com_calendar".DS."defines.php" );
            }
        }
        return $success;
    }
    
    /**
     * Tells the seach component what extentions are being searched
     * 
     * @return unknown_type
     */
    function onSearchAreas()
    {
        if (!$this->_isInstalled())
        {
            // TODO Find out if this should return null or array
            return array();
        }
        
        $areas = 
            array(
                'calendar' => $this->params->get('title', "Calendar")
            );
        return $areas;
    }
    
    /**
     * Performs the search
     * 
     * @param string $keyword
     * @param string $match
     * @param unknown_type $ordering
     * @param unknown_type $areas
     * @return unknown_type
     */    
    function onSearch( $keyword='', $match='', $ordering='', $areas=null )
    {
        if (!$this->_isInstalled())
        {
            return array();
        }
        
        if ( is_array( $areas ) ) 
        {
            if ( !array_intersect( $areas, array_keys( $this->onSearch() ) ) ) 
            {
                return array();
            }
        }
        
        $keyword = trim( $keyword );
        if (empty($keyword)) 
        {
            return array();
        }
        
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_calendar'.DS.'tables' );
        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_calendar'.DS.'models' );
        $model = JModel::getInstance( 'Events', 'CalendarModel' );
        $model->setState('filter_enabled', '1');
        $match = strtolower($match);
        switch ($match)
        {
            case 'exact':
                $model->setState('filter', $match);
            case 'all':
            case 'any':
            default:
                $words = explode( ' ', $keyword );
                $wheres = array();
                foreach ($words as $word)
                {
                    $model->setState('filter', $word);
                }
                break;
        }
        
        // order the items according to the ordering selected in com_search
        switch ( $ordering ) 
        {
            case 'newest':
                $model->setState('order', 'tbl.event_created_date');
                $model->setState('direction', 'DESC');
                break;
            case 'oldest':
                $model->setState('order', 'tbl.event_created_date');
                $model->setState('direction', 'ASC');
                break;
            case 'popular':
                //$model->setState('order', 'tbl.numViewed');
                //break;
            case 'alpha':
            default:
                $model->setState('order', 'tbl.event_name');
                break;
        }

        $items = $model->getList();
        if (empty($items)) { return array(); }
 
        //Calendar::load( 'CalendarHelperEvent', 'helpers.event' );
        //$helper = new CalendarHelperEvent();
        
        $config = Calendar::getInstance( );
        $jdate = JFactory::getDate();
        $date = $jdate->toFormat('%Y-%m-%d');
        // format the items array according to what com_search expects
        foreach ($items as $key => $item)
        {
            $instance = $model->getNextInstance( $item->event_id, $date );
            $table = $model->getTable();
            $table->load( $item->event_id );
            
            $item->href         = "index.php?option=com_calendar&view=events&task=view&id=" . $item->event_id . "&instance_id=" . $instance->eventinstance_id . "&Itemid=" . $config->get( 'item_id' );
            $item->title        = $item->event_short_title;
            $item->created      = $item->event_created_date;
            $item->section      = JText::_( $this->params->get('title', "Calendar") );
            $item->text         = $item->event_short_description;
            $item->image        = $table->getImage( 'full' );
            $item->browsernav   = "1";                
        }

        return $items;
    }
}
?>