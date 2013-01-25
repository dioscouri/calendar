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

class CalendarViewEvents extends CalendarViewBase
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
			case "selectcategories":
				parent::_default( $tpl );
				break;
			case "view":
				$this->_form( $tpl );
				break;
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
	
	function _default( $tpl = '' )
	{
		parent::_default( $tpl );
		$model = $this->getModel( );
		
		// list of items
		$items = $model->getList( );
		foreach ( $items as $item )
		{
			$item->categories_list = '';
			/*
			$model = JModel::getInstance( 'Events', 'CalendarModel' );
			$model->setState( 'filter_eventcategories', $item->event_id );
			if ( $categories = $model->getList( ) )
			{
				$cats = array( );
				foreach ( $categories as $category )
				{
					//$cats[] = JText::_( $category->secondarycat_name );
				}
				$item->categories_list = implode( ', ', $cats );
			}*/
		}
		
		$this->assign( 'items', $items );
	}
	
	/**
	 * The default toolbar for a list
	 * @return unknown_type
	 */
	function _defaultToolbar()
	{
        $this->addClearCacheToolbarButton();
        	    
	    JToolBarHelper::editList();
	    //JToolBarHelper::deleteList(JText::_('VALIDDELETEITEMS'));
	    //JToolBarHelper::addnew();
	}
	
	/**
	 * The default toolbar for editing an item
	 * @param $isNew
	 * @return unknown_type
	 */
	function _formToolbar($isNew = null)
	{
	    $divider = false;
	    $surrounding = (!empty($this->surrounding)) ? $this->surrounding : array();
	    if (!empty($surrounding['prev'])) {
	        $divider = true;
	        JToolBarHelper::custom('saveprev', "saveprev", "saveprev", 'Save and Prev', false);
	    }
	    if (!empty($surrounding['next'])) {
	        $divider = true;
	        JToolBarHelper::custom('savenext', "savenext", "savenext", 'Save and Next', false);
	    }
	    if ($divider) {
	        JToolBarHelper::divider();
	    }
	
	    //JToolBarHelper::custom('savenew', "savenew", "savenew", 'Save and New', false);
	    JToolBarHelper::save('save');
	    JToolBarHelper::apply('apply');
	
	    if ($isNew) {
	        JToolBarHelper::cancel();
	    } else {
	        JToolBarHelper::cancel('close', 'Close');
	    }
	}
}
