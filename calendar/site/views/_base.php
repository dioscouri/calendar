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

jimport( 'joomla.filter.filteroutput' );
jimport( 'joomla.application.component.view' );

class CalendarViewBase extends JView
{
	/**
	 * The valid task set by the controller
	 * @var str
	 */
	protected $_doTask;
	
	/**
	 * First displays the submenu, then displays the output
	 * but only if a valid _doTask is set in the view object
	 *
	 * @param $tpl
	 * @return unknown_type
	 */
	function display( $tpl = null )
	{
		// display() will return null if 'doTask' is not set by the controller
		// This prevents unauthorized access by bypassing the controllers
		if ( empty( $this->_doTask ) )
		{
			return null;
		}
		
		$this->getLayoutVars( $tpl );
		
		Calendar::load( 'CalendarMenu', 'library.menu' );
		
		if ( !JRequest::getInt( 'hidemainmenu' ) && empty( $this->hidemenu ) )
		{
			$this->displaySubmenu( );
		}
		
		parent::display( $tpl );
	}
	
	/**
	 * Displays a submenu if there is one and if hidemainmenu is not set
	 *
	 * @param $selected
	 * @return unknown_type
	 */
	function displaySubmenu( $selected = '' )
	{
		if ( !JRequest::getInt( 'hidemainmenu' ) && empty( $this->hidemenu ) )
		{
			jimport( 'joomla.html.toolbar' );
			require_once ( JPATH_ADMINISTRATOR . DS . 'includes' . DS . 'toolbar.php' );
			$view = strtolower( JRequest::getVar( 'view' ) );
			
			$menu = &CalendarMenu::getInstance( );
			if ( !empty( JFactory::getUser( )->id ) )
			{
				$menu->addEntry( JText::_( 'Dashboard' ), 'index.php?option=com_calendar&view=dashboard', $view == 'dashboard' ? true : false );
				$menu->addEntry( JText::_( 'Profile' ), 'index.php?option=com_calendar&view=accounts', $view == 'accounts' ? true : false );
				$menu->addEntry( JText::_( 'Order History' ), 'index.php?option=com_calendar&view=orders', $view == 'orders' ? true : false );
				$menu->addEntry( JText::_( 'Subscriptions' ), 'index.php?option=com_calendar&view=subscriptions', $view == 'subscriptions' ? true : false );
			}
			$menu->addEntry( JText::_( 'Shopping Cart' ), 'index.php?option=com_calendar&view=carts', $view == 'carts' ? true : false );
			
		}
	}
	
	/**
	 * Sets the task to something valid
	 *
	 * @access   public
	 * @param    string $task The task name.
	 * @return   string Previous value
	 * @since    1.5
	 */
	function setTask( $task )
	{
		$previous = $this->_doTask;
		$this->_doTask = $task;
		return $previous;
	}
	
	/**
	 * Gets layout vars for the view
	 *
	 * @return unknown_type
	 */
	function getLayoutVars( $tpl = null )
	{
		$layout = $this->getLayout( );
		switch ( strtolower( $layout ) )
		{
			case "view":
				$this->_form( $tpl );
				break;
			case "form":
				JRequest::setVar( 'hidemainmenu', '1' );
				$this->_form( $tpl );
				break;
			case "default":
			default:
				$this->_default( $tpl );
				break;
		}
	}
	
	/**
	 * Basic commands for displaying a list
	 *
	 * @param $tpl
	 * @return unknown_type
	 */
	function _default( $tpl = '' )
	{
		Calendar::load( 'CalendarSelect', 'library.select' );
		Calendar::load( 'CalendarGrid', 'library.grid' );
		$model = $this->getModel( );
		
		// set the model state
		$state = $model->getState( );
		JFilterOutput::objectHTMLSafe( $state );
		$this->assign( 'state', $state );
		
		// page-navigation
		$this->assign( 'pagination', $model->getPagination( ) );
		
		// list of items
		$this->assign( 'items', $model->getList( ) );
		
		// form
		$validate = JUtility::getToken( );
		$form = array( );
		$view = strtolower( JRequest::getVar( 'view' ) );
		$form['action'] = "index.php?option=com_calendar&controller={$view}&view={$view}";
		$form['validate'] = "<input type='hidden' name='{$validate}' value='1' />";
		$this->assign( 'form', $form );
	}
	
	/**
	 * Basic methods for a form
	 * @param $tpl
	 * @return unknown_type
	 */
	function _form( $tpl = '' )
	{
		Calendar::load( 'CalendarSelect', 'library.select' );
		$model = $this->getModel( );
		
		// get the data
		$row = $model->getItem( );
		JFilterOutput::objectHTMLSafe( $row );
		$this->assign( 'row', $row );
		
		// form
		$form = array( );
		$controller = strtolower( $this->get( '_controller', JRequest::getVar( 'controller', JRequest::getVar( 'view' ) ) ) );
		$view = strtolower( $this->get( '_view', JRequest::getVar( 'view' ) ) );
		$task = strtolower( $this->get( '_task', 'edit' ) );
		$form['action'] = $this->get( '_action', "index.php?option=com_calendar&controller={$controller}&view={$view}&task={$task}&id=" . $model->getId( ) );
		$form['validation'] = $this->get( '_validation', "index.php?option=com_calendar&controller={$controller}&view={$view}&task=validate&format=raw" );
		$form['validate'] = "<input type='hidden' name='" . JUtility::getToken( ) . "' value='1' />";
		$form['id'] = $model->getId( );
		$this->assign( 'form', $form );
		
		// set the required image
		// TODO Fix this
		$required = new stdClass( );
		$required->text = JText::_( 'Required' );
		$required->image = "<img src='" . JURI::root( ) . "/media/com_calendar/images/required_16.png' alt='{$required->text}'>";
		$this->assign( 'required', $required );
	}
	
}
