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

class CalendarViewBase extends DSCViewSite
{
    public function __construct($config = array())
    {
        parent::__construct($config);
    
        $this->defines = Calendar::getInstance();
    }
    
    public function display($tpl=null)
    {
        DSC::loadJQuery('latest', true, 'calendarJQ');
        DSC::loadBootstrap();
        
        if ($this->defines->get('include_site_css'))
        {
            JHTML::_('stylesheet', 'site.css', 'media/com_calendar/css/');
        }
        JHTML::_('script', 'common.js', 'media/com_calendar/js/');
        JHTML::_('stylesheet', 'common.css', 'media/dioscouri/css/');
    
        parent::display($tpl);
    }
}
