<?php
/**
 * @version	1.5
 * @package	Calendar
 * @category 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Calendar::load( 'CalendarModelBase', 'models._base' );

class CalendarModelEventCategories extends CalendarModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
       	$filter     = $this->getState('filter');
        $filter_name	= $this->getState('filter_name');
        $filter_event   = $this->getState('filter_event');
        $filter_category   = $this->getState('filter_category');
        $filter_type   = $this->getState('filter_type');
        $filter_eventfilter = $this->getState('filter_eventfilter');
        $filter_eventalpha = $this->getState('filter_eventalpha');
        $filter_eventenabled = $this->getState('filter_eventenabled');
        $filter_eventorder = $this->getState('filter_eventorder');
        
       	if ($filter) 
       	{
			$key	= $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

			$where = array();
			$where[] = 'LOWER(tbl.event_id) LIKE '.$key;
			$query->where('('.implode(' OR ', $where).')');
       	}
       	
		if (strlen($filter_event))
        {
        	$query->where('tbl.event_id = '.(int) $filter_event);
       	}
       	
        if (strlen($filter_category))
        {
            $query->where('tbl.category_id = '.(int) $filter_category);
        }
        
        if (strlen($filter_type))
        {
            $query->where("event.event_type = '".$filter_type."'");
        }
        
        if (strlen($filter_eventenabled))
        {
            $query->where("event.event_enabled = '".$filter_eventenabled."'");
        }
        
    }
    
    protected function _buildQueryJoins(&$query)
    {
        $query->join('LEFT', '#__calendar_events AS event ON tbl.event_id = event.event_id');
        $query->join('LEFT', '#__calendar_secondcategories AS category ON tbl.category_id = category.category_id');
    }
    
    protected function _buildQueryFields(&$query)
    {
        $fields = array();
        $fields[] = " event.* ";
        $fields[] = " category.* ";
        
        $query->select( $this->getState( 'select', 'tbl.*' ) );     
        $query->select( $fields );
    }


}
