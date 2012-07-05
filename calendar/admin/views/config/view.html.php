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

Calendar::load( 'CalendarViewBase', 'views._base' );

class CalendarViewConfig extends CalendarViewBase
{
	/**
	 * 
	 * @param $tpl
	 * @return unknown_type
	 */
	function getLayoutVars( $tpl = null )
	{
		$layout = $this->getLayout( );
		switch ( strtolower( $layout ) )
		{
			case "default":
			default:
				$this->set( 'leftMenu', 'leftmenu_configuration' );
				$this->_default( $tpl );
				break;
		}
	}
	
	/**
	 * 
	 * @return void
	 **/
	function _default( $tpl = null )
	{
		Calendar::load( 'CalendarSelect', 'library.select' );
		Calendar::load( 'CalendarGrid', 'library.grid' );
		Calendar::load( 'CalendarTools', 'library.tools' );
		
		// check config
		$row = Calendar::getInstance( );
		$this->assign( 'row', $row );
		
		// add toolbar buttons
		JToolBarHelper::save( 'save' );
		JToolBarHelper::cancel( 'close', JText::_( 'Close' ) );
		
		// plugins
		$filtered = array( );
		$items = CalendarTools::getPlugins( );
		for ( $i = 0; $i < count( $items ); $i++ )
		{
			$item = &$items[$i];
			// Check if they have an event
			if ( $hasEvent = CalendarTools::hasEvent( $item, 'onListConfigCalendar' ) )
			{
				// add item to filtered array
				$filtered[] = $item;
			}
		}
		$items = $filtered;
		$this->assign( 'items_sliders', $items );
		
		// Add pane
		jimport( 'joomla.html.pane' );
		$sliders = JPane::getInstance( 'sliders' );
		$this->assign( 'sliders', $sliders );
		
		// form
		$validate = JUtility::getToken( );
		$form = array( );
		$view = strtolower( JRequest::getVar( 'view' ) );
		$form['action'] = "index.php?option=com_calendar&controller={$view}&view={$view}";
		$form['validate'] = "<input type='hidden' name='{$validate}' value='1' />";
		$this->assign( 'form', $form );
		
		// set the required image
		// TODO Fix this to use defines
		$required = new stdClass( );
		$required->text = JText::_( 'Required' );
		$required->image = "<img src='" . JURI::root( ) . "/media/com_calendar/images/required_16.png' alt='{$required->text}'>";
		$this->assign( 'required', $required );
	}
	
}
