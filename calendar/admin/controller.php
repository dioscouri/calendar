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
	
	/**
	 * Note: This only clears the events cache; the packages cache can be cleared from CalendarControllerPackages#clearAllCache
	 */
	public function clearAllCache()
	{
	    $app = JFactory::getApplication();
	    
		$model = $this->getModel( 'Types' );
		$model->clearCache();
		$app->enqueueMessage( "Cleared 'Event Types' Cache" );
		
		$model = $this->getModel( 'Events' );
		$model->clearCache();
		$app->enqueueMessage( "Cleared 'Events' Cache" );
		
		$model = $this->getModel( 'EventInstances' );
		$model->clearCache();
		$app->enqueueMessage( "Cleared 'Event Instances' Cache" );
		
		$app->enqueueMessage( "Please take a second to visit <a href='" . JURI::root() . "calendar?reset=1' target='_blank'>" . JURI::root() . "calendar?reset=1</a> in order to refill the cache of events and ensure speedy operation of the website.  Thank you!", 'warning');
		
		$this->clearTessWebAPICache();
		
		// check for a $return value in the request, base64_encoded, and if present, setRedirect there
		// otherwise setRedirect to the events list to force at least a partial repopulation of the cache
		$return = JRequest::getVar('return', '', 'base64');
		if (!empty($return)) {
		    $return = base64_decode($return);
		} else {
		    $return = "index.php?option=com_calendar&view=events";
		}
		$this->setRedirect($return);
	}
	
	public function clearTessWebAPICache()
	{
	    $app = JFactory::getApplication();
	    
	    jimport('jalc.ServiceLoader');
	    jimport('tessitura.SoapAPI');
	    $api = Jalc\ServiceLoader::getTessituraApi(null, 'soap');
	    
	    $api->connect();
	    
	    if (!$client = $api->getClient()) {
	        $app->enqueueMessage( "Failed to clear 'TessWebAPI' Cache - could not get soap client", 'warning' );
	        return false;
	    }
	    
	    try {
	        $destroyCache = $client->destroyCache();
	        $app->enqueueMessage( "Cleared 'TessWebAPI' Cache" );
	    }
	    catch(Exception $e) {
	        $app->enqueueMessage( "Failed to clear 'TessWebAPI' Cache", 'warning' );
	    }
	}
}

?>