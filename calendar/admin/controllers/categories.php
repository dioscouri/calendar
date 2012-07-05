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

class CalendarControllerCategories extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		
		$this->set( 'suffix', 'categories' );
		$this->registerTask( 'category_enabled.enable', 'boolean' );
		$this->registerTask( 'category_enabled.disable', 'boolean' );
		$this->registerTask( 'selected_enable', 'selected_switch' );
		$this->registerTask( 'selected_disable', 'selected_switch' );
		$this->registerTask( 'saveprev', 'save' );
		$this->registerTask( 'savenext', 'save' );
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
		
		$state['order'] = $app->getUserStateFromRequest( $ns . '.filter_order', 'filter_order', 'tbl.ordering', 'cmd' );
		$state['filter_id_from'] = $app->getUserStateFromRequest( $ns . 'id_from', 'filter_id_from', '', '' );
		$state['filter_id_to'] = $app->getUserStateFromRequest( $ns . 'id_to', 'filter_id_to', '', '' );
		$state['filter_name'] = $app->getUserStateFromRequest( $ns . 'name', 'filter_name', '', '' );
		$state['filter_parentid'] = $app->getUserStateFromRequest( $ns . 'parentid', 'filter_parentid', '', '' );
		$state['filter_enabled'] = $app->getUserStateFromRequest( $ns . 'enabled', 'filter_enabled', '', '' );
		
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		return $state;
	}
	
	/**
	 * Reorders multiple items (based on form input from list) and redirects to default layout
	 * @return void
	 */
	function ordering( )
	{
		$error = false;
		$this->messagetype = '';
		$this->message = '';
		$redirect = 'index.php?option=com_calendar&view=' . $this->get( 'suffix' );
		$redirect = JRoute::_( $redirect, false );
		
		$model = $this->getModel( $this->get( 'suffix' ) );
		$row = $model->getTable( );
		
		$ordering = JRequest::getVar( 'ordering', array( 0 ), 'post', 'array' );
		$cids = JRequest::getVar( 'cid', array( 0 ), 'post', 'array' );
		foreach ( @$cids as $cid )
		{
			$row->load( $cid );
			$row->ordering = @$ordering[$cid];
			
			if ( !$row->store( ) )
			{
				$this->message .= $row->getError( );
				$this->messagetype = 'notice';
				$error = true;
			}
		}
		
		if ( $error )
		{
			$this->message = JText::_( 'Error' ) . " - " . $this->message;
		}
		else
		{
			$this->message = JText::_( 'Items Ordered' );
		}
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
		
		//$this->rebuild( );
		$row->reorder( );
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
		$row->category_description = JRequest::getVar( 'category_description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		$fieldname = 'category_full_image_new';
		$userfile = JRequest::getVar( $fieldname, '', 'files', 'array' );
		if ( !empty( $userfile['size'] ) )
		{
			if ( $upload = $this->addfile( $fieldname ) )
			{
				$row->category_full_image = $upload->getPhysicalName( );
			}
			else
			{
				$error = true;
			}
		}
		
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
	 * Deletes record(s) and redirects to default layout
	 */
	function delete( )
	{
		$error = false;
		$this->messagetype = '';
		$this->message = '';
		if ( !isset( $this->redirect ) )
		{
			$this->redirect = JRequest::getVar( 'return' ) ? base64_decode( JRequest::getVar( 'return' ) ) : 'index.php?option=com_calendar&view=' . $this->get( 'suffix' );
			$this->redirect = JRoute::_( $this->redirect, false );
		}
		
		$model = $this->getModel( $this->get( 'suffix' ) );
		
		$cids = JRequest::getVar( 'cid', array( 0 ), 'request', 'array' );
		foreach ( @$cids as $cid )
		{
			$row = $model->getTable( );
			if ( !$row->delete( $cid ) )
			{
				$this->message .= $row->getError( );
				$this->messagetype = 'notice';
				$error = true;
			}
		}
		
		if ( $error )
		{
			$this->message = JText::_( 'Error' ) . " - " . $this->message;
		}
		else
		{
			$this->message = JText::_( 'Items Deleted' );
		}
		
		$this->setRedirect( $this->redirect, $this->message, $this->messagetype );
	}
	
	/**
	 * Adds a thumbnail image to item
	 * @return unknown_type
	 */
	function addfile( $fieldname = 'category_full_image_new' )
	{
		Calendar::load( 'CalendarImage', 'library.image' );
		$upload = new CalendarImage( );
		// handle upload creates upload object properties
		$upload->handleUpload( $fieldname );
		// then save image to appropriate folder
		$upload->setDirectory( Calendar::getPath( 'categories_images' ) );
		
		// do upload!
		$upload->upload( );
		
		// Thumb
		Calendar::load( 'CalendarHelperImage', 'helpers.image' );
		$imgHelper = CalendarHelperBase::getInstance( 'Image', 'CalendarHelper' );
		if ( !$imgHelper->resizeImage( $upload, 'category' ) )
		{
			JFactory::getApplication( )->enqueueMessage( $imgHelper->getError( ), 'notice' );
		}
		
		return $upload;
	}
	
	/**
	 * Loads view for assigning events to categories
	 *
	 * @return unknown_type
	 */
	function selectevents( )
	{
		$this->set( 'suffix', 'events' );
		$state = parent::_setModelState( );
		$app = JFactory::getApplication( );
		$model = $this->getModel( $this->get( 'suffix' ) );
		$ns = $this->getNamespace( );
		
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		
		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$row = $model->getTable( 'categories' );
		$row->load( $id );
		
		$view = $this->getView( 'categories', 'html' );
		$view->set( '_controller', 'categories' );
		$view->set( '_view', 'categories' );
		$view->set( '_action', "index.php?option=com_calendar&controller=categories&task=selectevents&tmpl=component&id=" . $model->getId( ) );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState( ) );
		$view->assign( 'row', $row );
		$view->setLayout( 'selectevents' );
		$view->display( );
	}
	
	/**
	 *
	 * @return unknown_type
	 */
	function selected_switch( )
	{
		$error = false;
		$this->messagetype = '';
		$this->message = '';
		
		$model = $this->getModel( $this->get( 'suffix' ) );
		$row = $model->getTable( );
		
		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$cids = JRequest::getVar( 'cid', array( 0 ), 'request', 'array' );
		$task = JRequest::getVar( 'task' );
		$vals = explode( '_', $task );
		
		$field = $vals['0'];
		$action = $vals['1'];
		
		switch ( strtolower( $action ) )
		{
			case "switch":
				$switch = '1';
				break;
			case "disable":
				$enable = '0';
				$switch = '0';
				break;
			case "enable":
				$enable = '1';
				$switch = '0';
				break;
			default:
				$this->messagetype = 'notice';
				$this->message = JText::_( "Invalid Task" );
				$this->setRedirect( $redirect, $this->message, $this->messagetype );
				return;
				break;
		}
		
		$keynames = array( );
		foreach ( @$cids as $cid )
		{
			$table = JTable::getInstance( 'Categories', 'CalendarTable' );
			$keynames["category_id"] = $id;
			$keynames["event_id"] = $cid;
			$table->load( $keynames );
			if ( $switch )
			{
				if ( isset( $table->event_id ) )
				{
					if ( !$table->delete( ) )
					{
						$this->message .= $cid . ': ' . $table->getError( ) . '<br/>';
						$this->messagetype = 'notice';
						$error = true;
					}
				}
				else
				{
					$table->event_id = $cid;
					$table->category_id = $id;
					if ( !$table->save( ) )
					{
						$this->message .= $cid . ': ' . $table->getError( ) . '<br/>';
						$this->messagetype = 'notice';
						$error = true;
					}
				}
			}
			else
			{
				switch ( $enable )
				{
					case "1":
						$table->event_id = $cid;
						$table->category_id = $id;
						if ( !$table->save( ) )
						{
							$this->message .= $cid . ': ' . $table->getError( ) . '<br/>';
							$this->messagetype = 'notice';
							$error = true;
						}
						break;
					case "0":
					default:
						if ( !$table->delete( ) )
						{
							$this->message .= $cid . ': ' . $table->getError( ) . '<br/>';
							$this->messagetype = 'notice';
							$error = true;
						}
						break;
				}
			}
		}
		
		if ( $error )
		{
			$this->message = JText::_( 'Error' ) . ": " . $this->message;
		}
		else
		{
			$this->message = "";
		}
		
		$redirect = JRequest::getVar( 'return' ) ? base64_decode( JRequest::getVar( 'return' ) ) : "index.php?option=com_calendar&controller=categories&task=selectevents&tmpl=component&id=" . $id;
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	/**
	 * Batch resize of thumbs
	 * @author Skullbock
	 */
	function recreateThumbs( )
	{
		
		$per_step = 100;
		$from_id = JRequest::getInt( 'from_id', 0 );
		$to = $from_id + $per_step;
		
		Calendar::load( 'CalendarHelperCategory', 'helpers.category' );
		Calendar::load( 'CalendarImage', 'library.image' );
		$width = Calendar::getInstance( )->get( 'category_img_width', '0' );
		$height = Calendar::getInstance( )->get( 'category_img_height', '0' );
		
		$model = $this->getModel( 'Categories', 'CalendarModel' );
		$model->setState( 'limistart', $from_id );
		$model->setState( 'limit', $to );
		
		$row = $model->getTable( );
		
		$count = $model->getTotal( );
		
		$categories = $model->getList( );
		
		$i = 0;
		$last_id = $from_id;
		foreach ( $categories as $p )
		{
			$i++;
			$image = $p->category_full_image;
			$path = Calendar::getPath( 'categories_images' );
			
			if ( $image != '' )
			{
				
				$img = new CalendarImage( $path . '/' . $image);
				$img->setDirectory( Calendar::getPath( 'categories_images' ) );
				
				// Thumb
				Calendar::load( 'CalendarHelperImage', 'helpers.image' );
				$imgHelper = CalendarHelperBase::getInstance( 'Image', 'CalendarHelper' );
				$imgHelper->resizeImage( $img, 'category' );
			}
			
			$last_id = $p->category_id;
		}
		
		if ( $i < $count ) $redirect = "index.php?option=com_calendar&controller=categories&task=recreateThumbs&from_id=" . ( $last_id + 1 );
		else $redirect = "index.php?option=com_calendar&view=config";
		
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, JText::_( 'Done' ), 'notice' );
		return;
	}
}

?>