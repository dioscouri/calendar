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

if ( !class_exists('Calendar') ) 
    JLoader::register( "Calendar", JPATH_ADMINISTRATOR.DS."components".DS."com_calendar".DS."defines.php" );

if(!class_exists('JFakeElementBase')) {
	if(version_compare(JVERSION,'1.6.0','ge')) {
		class JFakeElementBase extends JFormField {
			// This line is required to keep Joomla! 1.6/1.7 from complaining
			public function getInput() {
			}
		}
	} else {
		class JFakeElementBase extends JElement {}
	}
}

class JFakeElementCalendarSeries extends JFakeElementBase
{
	var	$_name = 'CalendarSeries';

	public function getInput() 
	{
		return JFakeElementCalendarSeries::fetchElement($this->name, $this->value, $this->element, $this->options['control']);
	}
	
	public function fetchElement($name, $value, &$node, $control_name)
	{
		$html = "";
		$doc 		= JFactory::getDocument();
		$fieldName	= $control_name ? $control_name.'['.$name.']' : $name;
		$title = JText::_('Select Series');
		if ($value) {
			JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_calendar'.DS.'tables');
			$table = JTable::getInstance('Series', 'CalendarTable');
			$table->load($value);
			$title = $table->series_name;
		}
		else
		{
			$title=JText::_('Select a Series');
		}

 		$js = "
		function jSelectSeries(id, title, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
			document.getElementById('sbox-window').close();
		}";
		
		$doc->addScriptDeclaration($js);

		$link = 'index.php?option=com_calendar&task=elementSeries&tmpl=component&object='.$name;

		JHTML::_('behavior.modal', 'a.modal');
		$html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$name.'_name" value="'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('Select a Series').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">'.JText::_('Select').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.(int)$value.'" />';
		
		return $html;
	}
}

if(version_compare(JVERSION,'1.6.0','ge')) {
	class JFormFieldCalendarSeries extends JFakeElementCalendarSeries {}
} else {
	class JElementCalendarSeries extends JFakeElementCalendarSeries {}
}

/*


if ( !class_exists('Calendar') ) 
    JLoader::register( "Calendar", JPATH_ADMINISTRATOR.DS."components".DS."com_calendar".DS."defines.php" );

class JElementCalendarSeries extends JElement
{
	var	$_name = 'CalendarSeries';

	function fetchElement($name, $value, &$node, $control_name)
	{
	    
		$html = "";
		$doc 		= JFactory::getDocument();
		$fieldName	= $control_name ? $control_name.'['.$name.']' : $name;
		$title = JText::_('Select Series');
		if ($value) {
			JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_calendar'.DS.'tables');
			$table = JTable::getInstance('Series', 'CalendarTable');
			$table->load($value);
			$title = $table->series_name;
		}
		else
		{
			$title=JText::_('Select a Series');
		}

 		$js = "
		function jSelectSeries(id, title, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
			document.getElementById('sbox-window').close();
		}";
		
		$doc->addScriptDeclaration($js);

		$link = 'index.php?option=com_calendar&task=elementSeries&tmpl=component&object='.$name;

		JHTML::_('behavior.modal', 'a.modal');
		$html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$name.'_name" value="'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('Select a Series').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">'.JText::_('Select').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.(int)$value.'" />';
		
		return $html;
	}
}*/
?>