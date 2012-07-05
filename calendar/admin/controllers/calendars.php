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

class CalendarControllerCalendars extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		
		$this->set( 'suffix', 'calendars' );
	}
	
	/**
	 * Sets the model's state
	 * 
	 * @return array()
	 */
	function _setModelState( )
	{
		$state = parent::_setModelState( );
		$app = JFactory::getApplication( );
		$model = $this->getModel( $this->get( 'suffix' ) );
		$ns = $this->getNamespace( );
		
		$state['filter_id_from'] = $app->getUserStateFromRequest( $ns . 'id_from', 'filter_id_from', '', '' );
		$state['filter_id_to'] = $app->getUserStateFromRequest( $ns . 'id_to', 'filter_id_to', '', '' );
		$state['filter_name'] = $app->getUserStateFromRequest( $ns . 'name', 'filter_name', '', '' );
		
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		return $state;
	}
	
	/**
	 * Saves an item and redirects based on task
	 * @return void
	 */
	function save( )
	{
		$task = JRequest::getVar( 'task' );
		$model = $this->getModel( $this->get( 'suffix' ) );
		
		$row = $model->getTable( );
		$row->load( $model->getId( ) );
		$row->bind( JRequest::get( 'POST' ) );
		
		$row->_isNew = empty( $row->calendar_id );
		
		$primary_categories = JRequest::getVar( 'calendar_filter_primary_categories', array(), 'post', 'array' );
		$row->calendar_filter_primary_categories = implode(',', $primary_categories);
		
		$secondary_categories = JRequest::getVar( 'calendar_filter_secondary_categories', array(), 'post', 'array' );
		$row->calendar_filter_secondary_categories = implode(',', $secondary_categories);
		
		$filter_types = JRequest::getVar( 'calendar_filter_types', array(), 'post', 'array' );
		$row->calendar_filter_types = implode(',', $filter_types);
		
		$tabbed_types = JRequest::getVar( 'calendar_tabbed_types', array(), 'post', 'array' );
		$row->calendar_tabbed_types = implode(',', $tabbed_types);
		
		if ( $row->save( ) )
		{
			$row->calendar_id = $row->id;
			$model->setId( $row->calendar_id );
			$this->messagetype = 'message';
			$this->message = JText::_( 'Saved' );			
		}
		else
		{
			$this->messagetype = 'notice';
			$this->message = JText::_( 'Save Failed' ) . " - " . $row->getError( );
		}
		
		$redirect = "index.php?option=com_calendar";
		switch ( $task )
		{
			case "saveprev":
				$redirect .= '&view=' . $this->get( 'suffix' );
				// get prev in list				
				$surrounding = $model->getSurrounding( $model->getId( ) );
				if ( !empty( $surrounding['prev'] ) )
				{
					$redirect .= '&task=edit&id=' . $surrounding['prev'];
				}
				break;
			case "savenext":
				$redirect .= '&view=' . $this->get( 'suffix' );
				// get next in list				
				$surrounding = $model->getSurrounding( $model->getId( ) );
				if ( !empty( $surrounding['next'] ) )
				{
					$redirect .= '&task=edit&id=' . $surrounding['next'];
				}
				break;
			case "savenew":
				$redirect .= '&view=' . $this->get( 'suffix' ) . '&task=add';
				break;
			case "apply":
				$redirect .= '&view=' . $this->get( 'suffix' ) . '&task=edit&id=' . $model->getId( );
				break;
			case "save":
			default:
				$redirect .= "&view=" . $this->get( 'suffix' );
				break;
		}
		
		$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	function edit($cachable=false, $urlparams = false)
	{
		$model = $this->getModel( $this->get( 'suffix' ) );
		$calendar = $model->getItem( $model->getId() );
		
		$filter_primary_categories = array();		
		if ( !empty( $calendar->calendar_filter_primary_categories ) )
		{
			$primary_categories_str = $calendar->calendar_filter_primary_categories;			
			$filter_primary_categories = explode( ',', $primary_categories_str );
			foreach ($filter_primary_categories as &$cat) {
			    $cat = trim( $cat );
			}
		}

		$filter_secondary_categories = array();
		if ( !empty( $calendar->calendar_filter_secondary_categories ) )
		{
			$secondary_categories_str = $calendar->calendar_filter_secondary_categories;			
			$filter_secondary_categories = explode( ',', $secondary_categories_str );
			foreach ($filter_secondary_categories as &$cat) {
			    $cat = trim( $cat );
			}
		}
		
		$filter_types = array();
		if ( !empty( $calendar->calendar_filter_types ) )
		{
		    $types_str = $calendar->calendar_filter_types;
		    $filter_types = explode( ',', $types_str );
		    foreach ($filter_types as &$cat) {
		        $cat = trim( $cat );
		    }
		}
		
		$tabbed_types = array();
		if ( !empty( $calendar->calendar_tabbed_types ) )
		{
		    $types_str = $calendar->calendar_tabbed_types;
		    $tabbed_types = explode( ',', $types_str );
		    foreach ($tabbed_types as &$cat) {
		        $cat = trim( $cat );
		    }
		}
		
        $view = $this->getView( 'calendars', 'html' );
		$view->assign( 'filter_primary_categories', $filter_primary_categories );
		$view->assign( 'filter_secondary_categories', $filter_secondary_categories );
		$view->assign( 'filter_types', $filter_types );
		$view->assign( 'tabbed_types', $tabbed_types );
        
	    parent::edit($cachable, $urlparams);
	}
}

?>