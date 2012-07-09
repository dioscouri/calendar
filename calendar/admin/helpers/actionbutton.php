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
defined( '_JEXEC' ) or die( 'Restricted access' );

Calendar::load( 'CalendarHelperBase', 'helpers.base' );
jimport( 'joomla.filesystem.file' );

class CalendarHelperActionbutton extends CalendarHelperBase
{
	/**
	 * Returns html for creating action button
	 * from given actionbutton id
	 * 
	 * @param int actionbutton id
	 * @return string actionbutton html
	 */
	function getActionbuttonHTML( $actionbutton_id )
	{
		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
		$model = JModel::getInstance( 'Actionbuttons', 'CalendarModel' );
		$model->setId( $actionbutton_id );
		$actionbutton = $model->getItem( );
		
		$html = '';
		
		if (!empty($actionbutton->actionbutton_url_default))
		{
    		$html .= '<span class="actionbutton">';
    		$html .= '<a class="actionbutton" href="' . $actionbutton->actionbutton_url_default . '">';
    		$html .= $actionbutton->actionbutton_name;
    		$html .= '</a>';
    		$html .= '</span>';
		}
		
		return $html;
	}
}
