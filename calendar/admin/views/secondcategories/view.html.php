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

Calendar::load( 'CalendarViewBase', 'views.base' );

class CalendarViewSecondCategories extends CalendarViewBase
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
			case "form":
				JRequest::setVar( 'hidemainmenu', '1' );
				$this->_form( $tpl );
				break;
			case "default":
			default:
			    $this->set( 'leftMenu', 'leftmenu_configuration' );
				$this->_default( $tpl );
				break;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see calendar/admin/views/CalendarViewBase#_defaultToolbar()
	 */
	function _defaultToolbar( )
	{
		JToolBarHelper::publishList( 'category_enabled.enable' );
		JToolBarHelper::unpublishList( 'category_enabled.disable' );
		JToolBarHelper::divider( );
		parent::_defaultToolbar( );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see calendar/admin/views/CalendarViewBase#_formToolbar($isNew)
	 */
	function _formToolbar( $isNew = null )
	{
		if ( !$isNew )
		{
			JToolBarHelper::custom( 'save_as', 'refresh', 'refresh', JText::_( 'Save As' ), false );
		}
		parent::_formToolbar( $isNew );
	}
	
}
