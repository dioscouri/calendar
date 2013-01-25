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

class CalendarControllerEventinstances extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		
		$this->set( 'suffix', 'eventinstances' );
		
		$this->registerTask( 'eventinstance_published.enable', 'boolean' );
		$this->registerTask( 'eventinstance_published.disable', 'boolean' );
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
		
		$state['order'] = $app->getUserStateFromRequest( $ns . '.filter_order', 'filter_order', 'tbl.eventinstance_date', 'cmd' );
		$state['direction'] = $app->getUserStateFromRequest( $ns . '.filter_direction', 'filter_direction', 'DESC', 'word' );
		$state['filter_id_from'] = $app->getUserStateFromRequest( $ns . 'filter_id_from', 'filter_id_from', '', '' );
		$state['filter_id_to'] = $app->getUserStateFromRequest( $ns . 'filter_id_to', 'filter_id_to', '', '' );
		$state['filter_name'] = $app->getUserStateFromRequest( $ns . 'filter_name', 'filter_name', '', '' );
		$state['filter_enabled'] = $app->getUserStateFromRequest( $ns . 'filter_enabled', 'filter_enabled', '', '' );
		$state['filter_event'] = $app->getUserStateFromRequest( $ns . 'filter_event', 'filter_event', '', '' );
    	$state['filter_date_from'] = $app->getUserStateFromRequest($ns.'filter_date_from', 'filter_date_from', '', '');
    	$state['filter_date_to'] = $app->getUserStateFromRequest($ns.'filter_date_to', 'filter_date_to', '', '');
    	$state['filter_venue_id'] = $app->getUserStateFromRequest($ns.'filter_venue_id', 'filter_venue_id', '', '');
    			
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		return $state;
	}
	
	function edit($cachable=false, $urlparams = false)
	{
		$model = $this->getModel( $this->get( 'suffix' ) );
		$item = $model->getItem(null, true);
		 
		if (!$model->getID())
		{
			$redirect = $this->list_url;
			$this->message = 'No Datasource ID exists for this event, so you cannot edit it.';
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		 
		if (is_object($item) && empty($item->eventinstance_id))
		{
			$table = $model->getTable();
			$table->load( array('datasource_id'=>$item->getDataSourceID() ) );
			$table->bind($item);
			$table->datasource_id = $item->getDataSourceID();
			$table->check();
			$table->save();
			 
			// clear cache
			$item = $model->getItem( null, true );
		}
	
		parent::edit($cachable, $urlparams);
	}
	
	/**
	 * Saves an item and redirects based on task
	 * @return void
	 */
	function save( )
	{
		$post = JRequest::get( 'post', '4' );
		$task = JRequest::getVar( 'task' );
		$model = $this->getModel( $this->get( 'suffix' ) );
		$item = $model->getItem( $model->getId(), true );
		$row = $model->getTable();
		
		$row->load( array( 'datasource_id'=>$item->getDataSourceID() ) );
		$row->bind( $post );
		
		if ( $row->save( ) )
		{
			$model->clearCache();
			
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
	
	/**
	 * Adds a thumbnail image to item
	 * @return unknown_type
	 */
	function addfile( $fieldname = 'eventinstance_full_image_new' )
	{
		Calendar::load( 'CalendarImage', 'library.image' );
		$upload = new CalendarImage( );
		// handle upload creates upload object properties
		$upload->handleUpload( $fieldname );
		// then save image to appropriate folder
		$upload->setDirectory( Calendar::getPath( 'eventinstances_images' ) );
		
		// do upload!
		$upload->upload( );
		
		// Thumb
		Calendar::load( 'CalendarHelperImage', 'helpers.image' );
		$imgHelper = CalendarHelperBase::getInstance( 'Image', 'CalendarHelper' );
		if ( !$imgHelper->resizeImage( $upload, 'eventinstance' ) )
		{
			JFactory::getApplication( )->enqueueMessage( $imgHelper->getError( ), 'notice' );
		}
		
		return $upload;
	}
}

?>