<?php
/**
 * @version 1.5
 * @package Calendar
 * @user  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class CalendarModelElementType extends DSCModelElement
{
    var $title_key = 'type_name';
    var $select_title_constant = 'COM_CALENDAR_SELECT_CALENDAR';
    var $select_constant = 'COM_CALENDAR_SELECT';
    var $clear_constant = 'COM_CALENDAR_CLEAR_SELECTION';

    public function getTable($name='Types', $prefix='CalendarTable', $options = array())
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
        return parent::getTable($name, $prefix, $options);
    }
}
?>
