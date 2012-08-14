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

class CalendarViewBase extends DSCViewAdmin
{
	/**
	 * Displays a layout file 
	 * 
	 * @param unknown_type $tpl
	 * @return unknown_type
	 */
	function display($tpl=null)
	{
	    DSC::loadBootstrap();
	    
	    JHTML::_('stylesheet', 'common.css', 'media/dioscouri/css/');	    
		JHTML::_('stylesheet', 'admin.css', 'media/com_calendar/css/');
        
        parent::display($tpl);
	}

}