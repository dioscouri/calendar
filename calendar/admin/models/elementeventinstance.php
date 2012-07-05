<?php
/**
 * @version 1.5
 * @package MediaManager
 * @media  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

MediaManager::load( 'MediaManagerModelMedia', 'models.media' );

class MediaManagerModelElementMedia extends MediaManagerModelMedia
{
    function getTable()
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_mediamanager/tables' );
        $table = parent::getTable( 'Media' );
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
	function fetchElement($name, $value='', $control_name='', $js_extra='')
	{
		$doc 		=& JFactory::getDocument();

		$fieldName	= $control_name ? $control_name.'['.$name.']' : $name;
		if ($value) 
		{
		    $table    = $this->getTable();
			$table->load($value);
			$title = $table->media_title;
		} 
            else 
		{
			$title = JText::_('Select a Media Item');
		}
		
		$js = "
		function mediamanagerSelectMedia(id, title, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
			document.getElementById('sbox-window').close();
			$js_extra
		}";
		$doc->addScriptDeclaration($js);

		$link = 'index.php?option=com_mediamanager&view=elementmedia&tmpl=component&object='.$name;

		JHTML::_('behavior.modal', 'a.modal');
		$html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$name.'_name" value="'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('Select a Media Item').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">'.JText::_('Select').'</a></div></div>'."\n";
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
	function clearElement($name, $value='', $control_name='')
	{
		$doc 		=& JFactory::getDocument();
		$fieldName	= $control_name ? $control_name.'['.$name.']' : $name;
		
		$js = "
		function resetElementMedia(id, title, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
		}";
		$doc->addScriptDeclaration($js);
		
		$html = '<div class="button2-left">
		<div class="blank">
		
		<a href="javascript::void();" onclick="resetElementMedia( \''.$value.'\', \''.JText::_( 'Select a Media Item' ).'\', \''.$name.'\' )">'.JText::_( 'Clear Selection' ).'</span>
		</div></div>'."\n";

		return $html;
	}
	
}
?>
