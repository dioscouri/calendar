<?php
/**
 * @version	0.1
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class CalendarController extends DSCControllerAdmin
{
    /**
     * default view
     */
    public $default_view = 'events';
    
	/**
	 * @var array() instances of Models to be used by the controller
	 */
	public $_models = array();

	/**
	 * string url to perform a redirect with. Useful for child classes.
	 */
	protected $redirect;
	
	/**
	* For displaying a searchable list of series in a lightbox
	* Usage:
	*/
	function elementSeries( )
	{
		$model = $this->getModel( 'elementseries' );
		$view = $this->getView( 'elementseries' );
		$view->setModel( $model, true );
		$view->display( );
	}
	
	/**
	* For displaying a searchable list of series in a lightbox
	* Usage:
	*/
	function elementCalendar( )
	{
		$model = $this->getModel( 'elementcalendar' );
		$view = $this->getView( 'elementcalendar' );
		$view->setModel( $model, true );
		$view->display( );
	}
}

?>