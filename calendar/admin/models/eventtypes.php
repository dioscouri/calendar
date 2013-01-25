<?php
/**
 * @version	1.5
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this type is being included by a parent type */
defined('_JEXEC')or die('Restricted Access'); 

Calendar::load('CalendarModelBase','models._base'); 

class CalendarModelEventTypes extends CalendarModelBase
{
    protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
        $filter_id_from = $this->getState('filter_id_from');
        $filter_id_to   = $this->getState('filter_id_to');
        $filter_title    = $this->getState('filter_title');
        $filter_enabled = $this->getState('filter_enabled');
        $filter_type = $this->getState('filter_type');
        $filter_event = $this->getState('filter_event');
        $filter_typetitle = $this->getState('filter_typetitle'); 

        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

            $where = array();
            $where[] = 'LOWER(tbl.eventtype_id) LIKE '.$key;
            $query->where('('.implode(' OR ', $where).')');
        }
        
        if (strlen($filter_id_from))
        {
            if (strlen($filter_id_to))
            {
                $query->where('tbl.eventtype_id >= '.(int) $filter_id_from);  
            }
                else
            {
                $query->where('tbl.eventtype_id = '.(int) $filter_id_from);
            }
        }
        
        if (strlen($filter_id_to))
        {
            $query->where('tbl.eventtype_id <= '.(int) $filter_id_to);
        }
        
        if (strlen($filter_typetitle))
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_typetitle ) ) ).'%');
            $query->where('LOWER(type.type_title) LIKE '.$key);
        }

        if (strlen($filter_type))
        {
            $query->where('tbl.type_id = '.$this->_db->Quote($filter_type));
        }

        if (strlen($filter_event))
        {
            $query->where('tbl.event_id = '.$this->_db->Quote($filter_event));
        }
    }
    
    protected function _buildQueryJoins(&$query)
    {
        $query->join('LEFT', '#__calendar_events AS event ON tbl.event_id = event.event_id');
        $query->join('LEFT', '#__calendar_types AS type ON tbl.type_id = type.type_id');
    }
    
    protected function _buildQueryFields(&$query)
    {
        $fields = array();
        $fields[] = " type.* ";
        $fields[] = " event.event_short_title ";
        
        $query->select( $this->getState( 'select', 'tbl.*' ) );     
        $query->select( $fields );
    }
}