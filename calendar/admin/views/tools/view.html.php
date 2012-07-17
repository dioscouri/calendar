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

class CalendarViewTools extends CalendarViewBase
{
	
	
	function _form( $tpl = null )
	{
		parent::_form( $tpl );
		
		// load the plugin
		$row = $this->getModel( )->getItem( );
		$import = JPluginHelper::importPlugin( 'calendar', $row->element );
	}
	
	function _defaultToolbar( )
	{}
	
	function _viewToolbar( )
	{
		JToolBarHelper::custom( 'view', 'forward', 'forward', JText::_( 'Submit' ), false );
		JToolBarHelper::cancel( 'close', JText::_( 'Close' ) );
	}
}
