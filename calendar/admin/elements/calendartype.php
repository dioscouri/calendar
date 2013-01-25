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
    JLoader::register( "Calendar", JPATH_ADMINISTRATOR."/components/com_calendar/defines.php" );

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

class JFakeElementCalendarType extends JFakeElementBase
{
	var	$_name = 'CalendarType';

	public function getInput() 
	{
		return JFakeElementCalendarType::fetchElement($this->name, $this->value, $this->element, $this->options['control']);

	}
	
	public static function fetchElement($name, $value, &$node, $control_name)
	{
		
		JModel::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_calendar/models');
		$model = JModel::getInstance('Types', 'CalendarModel');
        $items = $model->getList( );
		$mitems = array();

		foreach ( $items as $item ) {
			$mitems[] = JHTML::_('select.option',  $item->type_id, $item->type_name );
		}
		
		$doc =  JFactory::getDocument();
		
		$output= JHTML::_('select.genericlist',  $mitems, ''.$control_name.$name, 'class="inputbox" style="width:90%;"', 'value', 'text', $value );
		return $output;
		
		
		
		
	}
}

if(version_compare(JVERSION,'1.6.0','ge')) {
	class JFormFieldCalendarType extends JFakeElementCalendarType {}
} else {
	class JElementCalendarType extends JFakeElementCalendarType {}
}


?>