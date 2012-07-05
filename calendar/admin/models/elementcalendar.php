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

Calendar::load( 'CalendarModelCalendars', 'models.calendars' );

class CalendarModelElementCalendar extends CalendarModelCalendars
{
    function getTable()
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
        $table = parent::getTable( 'Calendars' );
        return $table;
    }
    
    /**
     *
     * @return
     * @param object $name
     * @param object $value[optional]
     * @param object $node[optional]
     * @param object $control_name[optional]
     */
    function fetchElement($name, $value='', $control_name='', $js_extra='', $fieldName='' )
    {
        $doc        =& JFactory::getDocument();

        if (empty($fieldName)) {
            $fieldName = $control_name ? $control_name.'['.$name.']' : $name;
        }            
        
        if ($value) 
        {
            $table    = $this->getTable();
            $table->load($value);
            $title = $table->calendar_name;
        } 
            else 
        {
            $title = JText::_('Select a Calendar');
        }
        
        $close_window = '';
        if(version_compare(JVERSION,'1.6.0','ge')) {
        	$close_window = "window.parent.SqueezeBox.close();";
        } else {
        	$close_window = "document.getElementById('sbox-window').close();";
        }
        
        
        $js = "
        function calendarSelectCalendar(id, title, object) {
            document.getElementById(object + '_id').value = id;
            document.getElementById(object + '_name').value = title;
            document.getElementById(object + '_name_hidden').value = title;
            $close_window
            $js_extra
        }";
        $doc->addScriptDeclaration($js);

        $link = 'index.php?option=com_calendar&view=elementcalendar&tmpl=component&object='.$name;

        JHTML::_('behavior.modal', 'a.modal');
        $html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$name.'_name" value="'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
        $html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('Select a Calendar').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">'.JText::_('Select').'</a></div></div>'."\n";
        $html .= "\n".'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.$value.'" />';
        $html .= "\n".'<input type="hidden" id="'.$name.'_name_hidden" name="'.$name.'_name_hidden" value="'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'" />';
        
        return $html;
    }

    /**
     *
     * @return
     * @param object $name
     * @param object $value[optional]
     * @param object $node[optional]
     * @param object $control_name[optional]
     */
    function clearElement($name, $value='', $control_name='')
    {
        $doc        =& JFactory::getDocument();
        $fieldName  = $control_name ? $control_name.'['.$name.']' : $name;
        
        $js = "
        function calendarResetElementCalendar(id, title, object) {
            document.getElementById(object + '_id').value = id;
            document.getElementById(object + '_name').value = title;
        }";
        $doc->addScriptDeclaration($js);
        
        $html = '
        <div class="button2-left">
            <div class="blank">
                <a href="javascript::void();" onclick="calendarResetElementCalendar( \''.$value.'\', \''.JText::_( 'Select a Calendar' ).'\', \''.$name.'\' )">' . 
                JText::_( 'Clear Selection' ) . '
                </a>
            </div>
        </div>
        ';

        return $html;
    }
    
}
?>
