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

class CalendarControllerTypes extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		$this->set( 'suffix', 'types' );
	}
	
	function display()
	{
	    $this->view();
	}
	
	public function view() 
	{
	    $model = $this->getModel( $this->get('suffix') );
	    $id = $model->getId();
	    $item = $model->getItem( $id );

	    $view = $this->getView( $this->get( 'suffix' ), 'html' );
	    $view->assign( 'item', $item );
	    
	    $filter_date_from = date('Y-m-d');
	    $filter_date_to = date('Y-m-d', strtotime( $filter_date_from . " +8 months" ) );
	    
	    $emodel = $this->getModel('events');
	    $emodel->setState('filter_type', $id );
	    $emodel->setState('filter_date_to', $filter_date_to );
	    $shows = $emodel->getList();
	    $view->assign( 'shows', $shows );
	    
	    JRequest::setVar('layout', 'view');
	    parent::display();
	}
}