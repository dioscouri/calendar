<?php
/**
 * @version	1.5
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class CalendarModelElementSeries extends DSCModelElement
{
    var $title_key = 'eventinstance_name';
    var $select_title_constant = 'COM_CALENDAR_SELECT_EVENTINSTANCE';
    var $select_constant = 'COM_CALENDAR_SELECT';
    var $clear_constant = 'COM_CALENDAR_CLEAR_SELECTION';

    public function getTable($name='EventInstances', $prefix='CalendarTable', $options = array())
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
        return parent::getTable($name, $prefix, $options);
    }
}
?>