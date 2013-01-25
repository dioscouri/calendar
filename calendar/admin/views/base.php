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
	
	protected function addClearCacheToolbarButton( $view=null )
	{
		if (JFactory::getUser()->authorise('core.admin', 'com_calendar'))
	    {
	        $link = 'index.php?option=com_calendar&view=events&task=clearAllCache';
	        $bar = JToolBar::getInstance('toolbar');
	        if (!empty($view)) {
	            $return = base64_encode( JRoute::_("index.php?option=com_calendar&view=" . $view ) );
	            $link .= '&return=' . $return;
	        }
	        
	        $bar->prependButton( 'Link', 'default', 'Clear All Cache', $link );
	    }
	}

}