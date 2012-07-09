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
        
		$h = $values['eventinstance_start_time_hours'];
		$m = $values['eventinstance_start_time_minutes'];
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
		
		$table->eventinstance_start_time = $h . ':' . $m;
        
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
		$post = JRequest::get( 'post', '4' );
		$row->bind( $post );
		
		$row->eventinstance_description = JRequest::getVar( 'eventinstance_description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		$h = JRequest::getVar( 'eventinstance_start_time_hours' );
		$m = JRequest::getVar( 'eventinstance_start_time_minutes' );
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
		
		$row->eventinstance_start_time = $h . ':' . $m;
		
		$fieldname = 'eventinstance_full_image_new';
		$userfile = JRequest::getVar( $fieldname, '', 'files', 'array' );
		if ( !empty( $userfile['size'] ) )
		{
			if ( $upload = $this->addfile( $fieldname ) )
			{
				$row->eventinstance_full_image = $upload->getPhysicalName( );
			}
			else
			{
				$error = true;
			}
		}
		
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
		
		$row->_isNew = empty( $row->eventinstance_id );
		
		if ( $row->save( ) )
		{
			$row->eventinstance_id = $row->id;
			$model->setId( $row->eventinstance_id );
			$this->messagetype = 'message';
			$this->message = JText::_( 'Saved' );
			
			if ($row->_isNew && $row->eventinstance_recurring)
		    {
        		// Also save the recurring params
        		$recurring = JTable::getInstance( 'Recurring', 'CalendarTable' );
        		$recurring->bind( $post );
        		$recurring->recurring_name = $row->eventinstance_name;
        		$recurring->recurring_alias = $row->eventinstance_alias;
        		$recurring->recurring_description = $row->eventinstance_description;
        		$recurring->recurring_published = $row->eventinstance_published;
        		$recurring->recurring_start_date = $row->eventinstance_date; 
        		$recurring->recurring_start_time = $row->eventinstance_start_time;
        		$recurring->recurring_end_time = $row->eventinstance_end_time;
	            $recurring->event_id = $row->event_id;
	            $recurring->venue_id = $row->venue_id;
	            $recurring->recurring_end_type = JRequest::getVar( 'recurring_end_type' );
	            $recurring->recurring_finishes = ($recurring->recurring_end_type != 'never');
                $recurring->save();
                
                $row->recurring_id = $recurring->recurring_id;
                $row->store();

                // Save each eventinstance of the recurrance
                $recurring->createEventInstances( $recurring->getNextDate() );
		    }
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