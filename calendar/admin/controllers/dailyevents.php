<?php 
/**
 * @version	1.5
 * @package	Calendar
 * @instance 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class CalendarControllerDailyevents extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		
		$this->set( 'suffix', 'dailyevents' );
		
		$this->registerTask( 'dailyevent_published.enable', 'boolean' );
		$this->registerTask( 'dailyevent_published.disable', 'boolean' );
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
		$state['filter_venue_id'] = $app->getUserStateFromRequest( $ns . 'filter_venue_id', 'filter_venue_id', '', '' );
		
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		return $state;
	}
	
	/**
	 * Verifies the fields in a submitted form.  Uses the table's check() method.
	 * Will often be overridden. Is expected to be called via Ajax 
	 * 
	 * @return unknown_type
	 */
	function validate()
	{
		$response = array();
		$response['msg'] = '';
		$response['error'] = '';
		
		// get elements from post
		$elements_post = JRequest::getVar( 'elements', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$elements = json_decode( preg_replace('/[\n\r]+/', '\n', $elements_post ) );
		        
		// convert elements to array that can be binded
		Calendar::load( 'CalendarHelperBase', 'helpers.base' );
		$helper = new CalendarHelperBase(); 			
        $values = $helper->elementsToArray( $elements );
       
		// get table object
		$table = $this->getModel( $this->get('suffix') )->getTable();
		
		// bind to values
        $table->bind( $values );

	    // check if new venue being uploaded
        if (!empty($values['new_venue_name']))
        {
            $table->venue_id = true;
        }
		
		$h = $values['dailyevent_start_time_hours'];
		$m = $values['dailyevent_start_time_minutes'];
		if ( empty( $m ) )
		{
			$m = '0';
		}		
		if ($m < '10')
		{
		    $m = '0'.$m;
		}		
		if ($h < '10')
		{
		    $h = '0'.$h;
		}		
		$table->dailyevent_start_time = $h . ':' . $m;
		
		$h = $values['dailyevent_end_time_hours'];
		$m = $values['dailyevent_end_time_minutes'];
		if ( empty( $m ) )
		{
			$m = '0';
		}		
		if ($m < '10')
		{
		    $m = '0'.$m;
		}		
		if ($h < '10')
		{
		    $h = '0'.$h;
		}		
		$table->dailyevent_end_time = $h . ':' . $m;
		
	 	// check if new image being uploaded
        if (!empty($values['dailyevent_full_image_new']))
        {
            $table->dailyevent_full_image = true;
        }
        
		// validate it using table's ->check() method
		if (!$table->check())
		{
			// if it fails check, return message
			$response['error'] = '1';
			$response['msg'] = $helper->generateMessage( $table->getError() ); 
		}
			
		echo ( json_encode( $response ) );
		return;
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
		
		$row->dailyevent_short_description = JRequest::getVar( 'dailyevent_short_description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$row->dailyevent_long_description = JRequest::getVar( 'dailyevent_long_description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		$row->_isNew = empty( $row->dailyevent_id );
		
		$h = JRequest::getVar( 'dailyevent_start_time_hours' );
		$m = JRequest::getVar( 'dailyevent_start_time_minutes' );
		if ( empty( $m ) )
		{
			$m = '0';
		}		
		if ($m < '10')
		{
		    $m = '0'.$m;
		}		
		if ($h < '10')
		{
		    $h = '0'.$h;
		}		
		$row->dailyevent_start_time = $h . ':' . $m;
		
		$h = JRequest::getVar( 'dailyevent_end_time_hours' );
		$m = JRequest::getVar( 'dailyevent_end_time_minutes' );
		if ( empty( $m ) )
		{
			$m = '0';
		}		
		if ($m < '10')
		{
		    $m = '0'.$m;
		}		
		if ($h < '10')
		{
		    $h = '0'.$h;
		}		
		$row->dailyevent_end_time = $h . ':' . $m;
		
		$new_venue = JRequest::getVar( 'new_venue_name' );
		if ( !empty( $new_venue ) )
		{
		    JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		    $venue = JTable::getInstance( 'Venues', 'CalendarTable' );
			$venue->venue_name = $new_venue;
			if ($venue->save())
			{
			    $row->venue_id = $venue->venue_id;
			} 
    			else
			{
			    JFactory::getApplication()->enqueueMessage( $venue->getError(), 'notice' );
			}
		}
		
		$fieldname = 'dailyevent_full_image_new';
		$userfile = JRequest::getVar( $fieldname, '', 'files', 'array' );
		if ( !empty( $userfile['size'] ) )
		{
			if ( $upload = $this->addfile( $fieldname ) )
			{
				$row->dailyevent_full_image = $upload->getPhysicalName( );
			}
			else
			{
				$error = true;
			}
		}
				
		$row->dailyevent_multimedia = JRequest::getVar( 'dailyevent_multimedia', '', 'post', 'string', JREQUEST_ALLOWRAW );
	
		if ( $row->save( ) )
		{
			$row->dailyevent_id = $row->id;
			$model->setId( $row->dailyevent_id );
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
	function addfile( $fieldname = 'dailyevent_full_image' )
	{
		Calendar::load( 'CalendarImage', 'library.image' );
		$upload = new CalendarImage( );
		// handle upload creates upload object properties
		$upload->handleUpload( $fieldname );
		// then save image to appropriate folder
		$upload->setDirectory( Calendar::getPath( 'dailyevents_images' ) );
		
		// do upload!
		$upload->upload( );
		
		// Thumb
		Calendar::load( 'CalendarHelperImage', 'helpers.image' );
		$imgHelper = CalendarHelperBase::getInstance( 'Image', 'CalendarHelper' );
		if ( !$imgHelper->resizeImage( $upload, 'dailyevent' ) )
		{
			JFactory::getApplication( )->enqueueMessage( $imgHelper->getError( ), 'notice' );
		}
		
		return $upload;
	}
}
?>