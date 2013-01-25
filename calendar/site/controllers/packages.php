<?php
/**
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class CalendarControllerPackages extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		$this->set( 'suffix', 'packages' );
	}
	
	function display()
	{
	    $this->view();
	}
	
	public function view() 
	{
	    $model = $this->getModel( $this->get('suffix') );
	    $id = $model->getId();
	    $groups = $model->getGroups();
	    
	    if (empty($groups[$id])) 
	    {
	        // fail
	    }
	    
	    $item = $groups[$id];
	    $model->_item = $item;

	    $view = $this->getView( $this->get( 'suffix' ), 'html' );
	    $view->assign( 'item', $item );
	    
		$active = JFactory::getApplication()->getMenu()->getActive();
		$params = new JRegistry();
		if (is_object( $active )) 
		{
		    $params = $active->params;
		}
		$view->assign( 'menu_params', $params );
		
	    JRequest::setVar('layout', 'view');
	    parent::display();
	}
}