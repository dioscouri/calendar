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

class CalendarControllerSeries extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		
		$this->set( 'suffix', 'series' );
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
		$error = false;
		$row = $model->getTable( );
		$row->load( $model->getId( ) );
		$row->bind( JRequest::get( 'POST' ) );
		
		$row->series_description = JRequest::getVar( 'series_description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		$fieldname = 'series_full_image_new';
		$userfile = JRequest::getVar( $fieldname, '', 'files', 'array' );
		if ( !empty( $userfile['size'] ) )
		{
			if ( $upload = $this->addfile( $fieldname ) )
			{
				$row->series_full_image = $upload->getPhysicalName( );
			}
			else
			{
				$error = true;
			}
		}
		
		$row->series_multimedia = JRequest::getVar( 'series_multimedia', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		if ( $row->save( ) )
		{
			$model->setId( $row->id );
			$this->messagetype = 'message';
			$this->message = JText::_( 'Saved' );
			if ( $error )
			{
				$this->messagetype = 'notice';
				$this->message .= " :: " . $this->getError( );
			}
			
			$dispatcher = JDispatcher::getInstance( );
			$dispatcher->trigger( 'onAfterSave' . $this->get( 'suffix' ), array( $row ) );
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
	
	/**
	 * Adds a thumbnail image to item
	 * @return unknown_type
	 */
	function addfile( $fieldname = 'series_full_image_new' )
	{
		Calendar::load( 'CalendarImage', 'library.image' );
		$upload = new CalendarImage( );
		// handle upload creates upload object properties
		$upload->handleUpload( $fieldname );
		// then save image to appropriate folder
		$upload->setDirectory( Calendar::getPath( 'series_images' ) );
		
		// do upload!
		$upload->upload( );
		
		// Thumb
		Calendar::load( 'CalendarHelperImage', 'helpers.image' );
		$imgHelper = CalendarHelperBase::getInstance( 'Image', 'CalendarHelper' );
		if ( !$imgHelper->resizeImage( $upload, 'series' ) )
		{
			JFactory::getApplication( )->enqueueMessage( $imgHelper->getError( ), 'notice' );
		}
		
		return $upload;
	}
}

?>