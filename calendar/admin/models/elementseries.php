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

jimport( 'joomla.application.component.helper');
jimport( 'joomla.application.component.model');
Calendar::load( 'CalendarModelSeries', 'models.series' );

class CalendarModelElementSeries extends CalendarModelSeries
{
	/*
	 * Required the Table of event instances it will return eventinstances table object
	 *  
	 */ 
	function &getTable( $name = 'eventinstances', $prefix = 'CalendarTable', $options = array( ) )
	{
		if ( empty( $name ) )
		{
			$name = $this->getName( );
		}
		
		JTable::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'tables' );
		if ( $table = $this->_createTable( $name, $prefix, $options ) )
		{
			return $table;
		}
		
		JError::raiseError( 0, 'Table ' . $name . ' not supported. File not found.' );
		$null = null;
		return $null;
	}
	
	/**
	 *
	 * @return
	 * @param object $name
	 * @param object $value[optional]
	 * @param object $node[optional]
	 * @param object $control_name[optional]
	 */
	function _fetchElement($name, $value='', $node='', $control_name='')
	{
		
		$html = "";
		$doc 		=& JFactory::getDocument();
		$fieldName	= $control_name ? $control_name.'['.$name.']' : $name;
		$title = JText::_('Select Series');
		if ($value) {
			$title =$value;
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
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('Select a User').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">'.JText::_('Select').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.(int)$value.'" />';

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
	function _clearElement($name, $value='', $node='', $control_name='')
	{
		
		global $mainframe;

		$db			=& JFactory::getDBO();
		$doc 		=& JFactory::getDocument();
		$template 	= $mainframe->getTemplate();
		$fieldName	= $control_name ? $control_name.'['.$name.']' : $name;
		
		$js = "
		function resetElement(id, title, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
		}";
		$doc->addScriptDeclaration($js);
		
		$html = '<div class="button2-left">
		<div class="blank">
		
		<a href="javascript::void();" onclick="resetElement( \''.$value.'\', \''.JText::_( 'select seriess' ).'\', \''.$name.'\' )">'.JText::_( 'Clear selection' ).'</span>
		</div></div>'."\n";

		return $html;
	}
	
}
?>