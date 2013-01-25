<?php
/**
 * @version 1.5
 * @package Calendar
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Calendar::load( 'CalendarViewBase', 'views.base' );

class CalendarViewEventinstances extends CalendarViewBase
{
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
			case "form":
				JRequest::setVar( 'hidemainmenu', '1' );
				$this->_form( $tpl );
				break;
			case "default":
			default:
			    $this->set( 'leftMenu', 'leftmenu_events' );
				$this->_default( $tpl );
				break;
		}
	}

	/**
	 * The default toolbar for a list
	 * @return unknown_type
	 */
	function _defaultToolbar( )
	{
	    $this->addClearCacheToolbarButton('eventinstances');
		//JToolBarHelper::editList( );
		//JToolBarHelper::deleteList( JText::_( 'VALIDDELETEITEMS' ) );
	}
	
	function _formToolbar( $isNew = null )
	{
		JToolBarHelper::save( 'save' );
		JToolBarHelper::apply( 'apply' );
		
		if ( $isNew )
		{
			JToolBarHelper::cancel( );
		}
		else
		{
			JToolBarHelper::cancel( 'close', 'Close' );
		}
	}
}
