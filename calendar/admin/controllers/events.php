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

class CalendarControllerEvents extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		
		$this->set( 'suffix', 'events' );
		
		$this->registerTask( 'event_published.enable', 'boolean' );
		$this->registerTask( 'event_published.disable', 'boolean' );
		$this->registerTask( 'selected.enable', 'selected_switch' );
		$this->registerTask( 'selected.disable', 'selected_switch' );
		$this->registerTask( 'event_upcoming_enabled.enable', 'boolean' );
		$this->registerTask( 'event_upcoming_enabled.disable', 'boolean' );
		$this->registerTask( 'digital_signage.enable', 'boolean' );
		$this->registerTask( 'digital_signage.disable', 'boolean' );
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
		
		$state['filter_category'] = $app->getUserStateFromRequest( $ns . 'filter_category', 'filter_category', '', '' );
		$state['filter_series'] = $app->getUserStateFromRequest( $ns . 'filter_series', 'filter_series', '', '' );
		$state['filter_eventcategories'] = $app->getUserStateFromRequest( $ns . 'filter_eventcategories', 'filter_eventcategories', '', '' );
		$state['filter_venue_name'] = $app->getUserStateFromRequest( $ns . 'filter_venue_name', 'filter_venue_name', '', '' );
		$state['filter_venue_id'] = $app->getUserStateFromRequest( $ns . 'filter_venue_id', 'filter_venue_id', '', '' );
		$state['filter_upcoming_enabled'] = $app->getUserStateFromRequest( $ns . 'filter_upcoming_enabled', 'filter_upcoming_enabled', '', '' );
		$state['filter_digital_signage'] = $app->getUserStateFromRequest( $ns . 'filter_digital_signage', 'filter_digital_signage', '', '' );
		
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		return $state;
	}
	
	/**
	 * Loads view for adding new Event Instance
	 * 
	 * @return unknown_type
	 */
	function neweventinstance( )
	{
		$this->set( 'suffix', 'eventinstances' );
		$state = parent::_setModelState( );
		$app = JFactory::getApplication( );
		$model = $this->getModel( $this->get( 'suffix' ) );
		$ns = $this->getNamespace( );
		
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		
		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$row = $model->getTable( 'events' );
		$row->load( $id );
		
		$view = $this->getView( 'events', 'html' );
		$view->set( '_controller', 'events' );
		$view->set( '_view', 'events' );
		$view->set( '_action', "index.php?option=com_calendar&controller=events&task=neweventinstance&tmpl=component&id=" . $model->getId( ) );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState( ) );
		$view->assign( 'row', $row );
		$view->setLayout( 'neweventinstance' );
		$view->display( );
	}
	
	/**
	 * Loads view for assigning to secondary categories
	 * 
	 * @return unknown_type
	 */
	function selectcategories( )
	{
		$this->set( 'suffix', 'categories' );
		$state = parent::_setModelState( );
		$app = JFactory::getApplication( );
		$model = $this->getModel( $this->get( 'suffix' ) );
		$ns = $this->getNamespace( );
		
		$state['filter_parentid'] = $app->getUserStateFromRequest( $ns . 'parentid', 'filter_parentid', '', '' );
		$state['order'] = $app->getUserStateFromRequest( $ns . '.filter_order', 'filter_order', 'tbl.ordering', 'cmd' );
		
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		
		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$row = $model->getTable( 'events' );
		$row->load( $id );
		
		$view = $this->getView( 'events', 'html' );
		$view->set( '_controller', 'events' );
		$view->set( '_view', 'events' );
		$view->set( '_action', "index.php?option=com_calendar&controller=events&task=selectcategories&tmpl=component&id=" . $model->getId( ) );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState( ) );
		$view->assign( 'row', $row );
		$view->setLayout( 'selectcategories' );
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
		
		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$cids = JRequest::getVar( 'cid', array( 0 ), 'request', 'array' );
		$task = JRequest::getVar( 'task' );
		$vals = explode( '.', $task );
		
		if ( count( $cids ) > 1 )
		{
			$keynames = array( );
			foreach ( @$cids as $cid )
			{
				$table = JTable::getInstance( 'EventCategories', 'CalendarTable' );
				$keynames['event_id'] = $id;
				$keynames['category_id'] = $cid;
				$table->load( $keynames );
				
				if ( isset( $table->category_id ) )
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
					$table->event_id = $id;
					$table->category_id = $cid;
					if ( !$table->save( ) )
					{
						$this->message .= $cid . ': ' . $table->getError( ) . '<br/>';
						$this->messagetype = 'notice';
						$error = true;
					}
				}
			}
		}
		else
		{
			$vals = explode( '.', $task );
			
			$field = $vals['0'];
			$action = $vals['1'];
			
			switch ( strtolower( $action ) )
			{
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
					$redirect = "index.php?option=com_calendar&view=events&task=selectcategories&tmpl=component&id=" . $id;
					$this->setRedirect( $redirect, $this->message, $this->messagetype );
					return;
					break;
			}
			
			$keynames = array( );
			foreach ( @$cids as $cid )
			{
				$table = JTable::getInstance( 'EventCategories', 'CalendarTable' );
				$keynames['event_id'] = $id;
				$keynames['category_id'] = $cid;
				$table->load( $keynames );
				
				switch ( $enable )
				{
					case "1":
						$table->event_id = $id;
						$table->category_id = $cid;
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
		
		$redirect = JRequest::getVar( 'return' ) ? base64_decode( JRequest::getVar( 'return' ) ) : "index.php?option=com_calendar&view=events&task=selectcategories&tmpl=component&id=" . $id;
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
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
		Calendar::load( 'CalendarHelperBase', 'helpers._base' );
		$helper = new CalendarHelperBase(); 			
        $values = $helper->elementsToArray( $elements );

        /*
		$response['error'] = '1';
		$response['msg'] = $helper->generateMessage( Calendar::dump( $values ) ); 
		echo ( json_encode( $response ) );
		return;
        */
        
		// get table object
		$table = $this->getModel( $this->get('suffix') )->getTable();
		
		// bind to values
        $table->bind( $values );

        $row->_isNew = empty( $row->event_id );
        
	    // check if new image being uploaded
        if (!empty($values['event_full_image_new']))
        {
            $table->event_full_image = true;
        }
        
        // check if new categories being created
        if (!empty($values['new_primary_category_name']))
        {
            $table->event_primary_category_id = '-1';
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
		$post = JRequest::get( 'post', '4' );
		$row->bind( $post );
		
		$row->event_short_description = JRequest::getVar( 'event_short_description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$row->event_long_description = JRequest::getVar( 'event_long_description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		$row->_isNew = empty( $row->event_id );
		
		$fieldname = 'event_full_image_new';
		$userfile = JRequest::getVar( $fieldname, '', 'files', 'array' );
		if ( !empty( $userfile['size'] ) )
		{
			if ( $upload = $this->addfile( $fieldname ) )
			{
				$row->event_full_image = $upload->getPhysicalName( );
			}
			else
			{
				$error = true;
			}
		}
		
		$newseries = JRequest::getVar( 'new_series_name' );
		if ( !empty( $newseries ) )
		{
			Calendar::load( 'CalendarHelperSeries', 'helpers.series' );
			$row->series_id = CalendarHelperSeries::createSeriesFromName( $newseries );
		}
		
		$newpcat = JRequest::getVar( 'new_primary_category_name' );
		if ( !empty( $newpcat ) )
		{
		    JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		    $pcat = JTable::getInstance( 'Categories', 'CalendarTable' );
		    //$pcat->getRoot();
		    $pcat->category_enabled = '1';
			$pcat->category_name = $newpcat;
			if ($pcat->save())
			{
			    $row->event_primary_category_id = $pcat->category_id;
			} 
    			else
			{
			    JFactory::getApplication()->enqueueMessage( $pcat->getError(), 'notice' );
			}
		}
		
		// save secondary categories
		// no secondary cat can be == the primary
		$secondary_categories = JRequest::getVar( 'secondary_categories', array(), 'post', 'array' );
		/*foreach ($secondary_categories as $key=>$secondary_category)
		{
		    if ($secondary_category == $row->event_primary_category_id)
		    {
		        unset($secondary_categories[$key]);
		    }
		}*/
		
		$newscat = JRequest::getVar( 'new_secondary_category_name' );
		if ( !empty( $newscat ) )
		{
		    JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		    $scat = JTable::getInstance( 'Categories', 'CalendarTable' );
			$scat->category_name = $newscat;
			$scat->category_enabled = '1';
			if ($scat->save())
			{
			    $secondary_categories[] = $scat->category_id;
			} 
    			else
			{
			    JFactory::getApplication()->enqueueMessage( $scat->getError(), 'notice' );
			}
		}
		
		$row->event_multimedia = JRequest::getVar( 'event_multimedia', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		if ( $row->save( ) )
		{
			$row->event_id = $row->id;
			$model->setId( $row->event_id );
			$this->messagetype = 'message';
			$this->message = JText::_( 'Saved' );
			
			$row->storeSecondaryCategories( $secondary_categories );
			
			$this->row = $row;
			$this->saveEventInstances();
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
	function addfile( $fieldname = 'event_full_image_new' )
	{
		Calendar::load( 'CalendarImage', 'library.image' );
		$upload = new CalendarImage( );
		// handle upload creates upload object properties
		$upload->handleUpload( $fieldname );
		// then save image to appropriate folder
		$upload->setDirectory( Calendar::getPath( 'events_images' ) );
		
		// do upload!
		$upload->upload( );
		
		// Thumb
		Calendar::load( 'CalendarHelperImage', 'helpers.image' );
		$imgHelper = CalendarHelperBase::getInstance( 'Image', 'CalendarHelper' );
		if ( !$imgHelper->resizeImage( $upload, 'event' ) )
		{
			JFactory::getApplication( )->enqueueMessage( $imgHelper->getError( ), 'notice' );
		}
		
		return $upload;
	}
	
	function edit()
	{
        $model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
        $model->setState( 'filter_event', $model->getId() );
        $model->setState( 'order', 'tbl.eventinstance_date' );
        $model->setState( 'direction', 'ASC' );
        $query = $model->getQuery();
        $query->order( 'tbl.eventinstance_start_time' );
        $model->setQuery( $query );
        $items = $model->getList();

		$model = $this->getModel( $this->get( 'suffix' ) );
		$event = $model->getItem( $model->getId() );
		$secondary_categories = array();
		$categories_list = '';
		if ( !empty( $event->event_id ) )
		{
			$model = JModel::getInstance( 'EventCategories', 'CalendarModel' );
			$model->setState( 'filter_event', $event->event_id );
			if ( $categories = $model->getList( ) )
			{
				$cats = array( );
				foreach ( $categories as $category )
				{
				    $secondary_categories[] = $category->category_id; 
					$cats[] = JText::_( @$category->category_name );
				}
				$categories_list = implode( ', ', $cats );
			}
		}
        
        $view   = $this->getView( 'events', 'html' );
        $view->set('items', $items);
		$view->assign( 'secondary_categories', $secondary_categories );
		$view->assign( 'categories_list', $categories_list );
        
	    parent::edit();
	}
	
    /**
     * 
     * Enter description here ...
     * @param $pick_id
     * @return unknown_type
     */
    function getInstancesHtml( $item_id )
    {
        $html = '';

        JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
        $model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
        $model->setState('filter_event', $item_id);
        $model->setState( 'order', 'tbl.eventinstance_date' );
        $model->setState( 'direction', 'ASC' );
        $query = $model->getQuery();
        $query->order( 'tbl.eventinstance_start_time' );
        $model->setQuery( $query );

        if ($items = $model->getList())
        {
            $events_model = JModel::getInstance('Events', 'CalendarModel');
            $events_model->setId( $item_id );
            $item = $events_model->getItem();
            
            $view   = $this->getView( 'events', 'html' );
            $view->set( '_doTask', true);
            $view->set( 'hidemenu', true);
            $view->setModel( $model, true );
            $view->setLayout( 'form_instances' );
            $view->set('items', $items);
            $view->set('row', $item);

            ob_start();
            $view->display();
            $html = ob_get_contents();
            ob_end_clean();
        }

        return $html;
    }
    
    /**
     *
     * Adds a relationship
     */
    function addInstance()
    {
        $response = array();
        $response['msg'] = '';
        $response['error'] = '';

        Calendar::load( 'CalendarHelperBase', 'helpers._base' );
        $helper = CalendarHelperBase::getInstance();

        // get elements from post
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );

        // convert elements to array that can be binded
        Calendar::load( 'CalendarHelperBase', 'helpers._base' );
        $helper = CalendarHelperBase::getInstance();
        $values = $helper->elementsToArray( $elements );

        $event_id = $values['id'];

        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
        $table = JTable::getInstance('EventInstances', 'CalendarTable');

        $table->bind( $values );

	    // check if new venue being uploaded
	    $new_venue = '';
        if (!empty($values['new_venue_name']))
        {
            $table->venue_id = true;
            $new_venue = $values['new_venue_name'];
        }    
		else 
		{
			$table->venue_id = $values['venue_id_insert'];
		}
		
		$table->actionbutton_id = $values['actionbutton_id_insert'];
		$table->actionbutton_url = $values['actionbutton_url_insert'];
		$table->actionbutton_string = $values['actionbutton_string_insert'];
		
        $table->eventinstance_date = $values['eventinstance_date_insert'];
        
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
		
		$h_end = $values['eventinstance_end_time_hours'];
		$m_end = $values['eventinstance_end_time_minutes'];
		if ( empty( $m_end ) )
		{
			$m_end = '0';
		}
		
		if ($m_end < '10')
		{
		    $m_end = '0'.$m_end;
		}
		
		if ($h_end < '10')
		{
		    $h_end = '0'.$h_end;
		}
		
		$table->eventinstance_end_time = $h_end . ':' . $m_end;
		
		$table->eventinstance_recurring = $values['_checked']['eventinstance_recurring'];
		$table->event_id = $event_id;
		
		// validate it using table's ->check() method
		if (!$table->check())
		{
			// if it fails check, return message
			$response['error'] = '1';
			$response['msg'] = $helper->generateMessage( $table->getError() );
			$response['msg'] .= $this->getInstancesHtml( $event_id );
            echo ( json_encode( $response ) );
            return;
		}

		if ( !empty( $new_venue ) )
		{
		    $venue = JTable::getInstance( 'Venues', 'CalendarTable' );
			$venue->venue_name = $new_venue;
			if ($venue->save())
			{
			    $table->venue_id = $venue->venue_id;
			} 
    			else
			{
    			$response['error'] = '1';
    			$response['msg'] = $helper->generateMessage( $venue->getError() );
    			$response['msg'] .= $this->getInstancesHtml( $event_id );
                echo ( json_encode( $response ) );
                return;
			}
		}		
        
        if ($table->save())
        {
            if ($table->eventinstance_recurring)
    		{
        		// Also save the recurring params
        		$recurring = JTable::getInstance( 'Recurring', 'CalendarTable' );
        		$recurring->bind( $values );
        		$recurring->recurring_name = $table->eventinstance_name;
        		$recurring->recurring_alias = $table->eventinstance_alias;
        		$recurring->recurring_description = $table->eventinstance_description;
        		$recurring->recurring_published = $table->eventinstance_published;
        		$recurring->recurring_start_date = $table->eventinstance_date; 
        		$recurring->recurring_start_time = $table->eventinstance_start_time;
        		$recurring->recurring_end_time = $table->eventinstance_end_time;
        		$recurring->actionbutton_id = $table->actionbutton_id;
        		$recurring->recurring_actionbutton_url = $table->actionbutton_url;
        		$recurring->recurring_actionbutton_string = $table->actionbutton_string;
	            $recurring->event_id = $table->event_id;
	            $recurring->venue_id = $table->venue_id;
	            $recurring->recurring_end_type = $values['_checked']['recurring_end_type'];
	            $recurring->recurring_finishes = ($recurring->recurring_end_type != 'never');
                $recurring->save();
                
                $table->recurring_id = $recurring->recurring_id;
                $table->store();

                // Save each eventinstance of the recurrance
                $recurring->createEventInstances( $recurring->getNextDate() );
    		}
        }

        $response['error'] = '0';
        $response['msg'] = $this->getInstancesHtml( $event_id );

        echo ( json_encode( $response ) );
        return;
    }
    
    /**
     * Removes relationship
     */
    function removeInstance()
    {
        $response = array();
        $response['msg'] = '';
        $response['error'] = '';

        Calendar::load( 'CalendarHelperBase', 'helpers._base' );
        $helper = CalendarHelperBase::getInstance();

        // get elements from post
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );

        // convert elements to array that can be binded
        Calendar::load( 'CalendarHelperBase', 'helpers._base' );
        $helper = CalendarHelperBase::getInstance();
        $values = $helper->elementsToArray( $elements );

        $item_id = $values['id'];
        $eventinstance_id = JRequest::getInt('eventinstance_id');

        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
        $table = JTable::getInstance('EventInstances', 'CalendarTable');
        $table->delete( $eventinstance_id );

        $response['error'] = '0';
        $response['msg'] = $this->getInstancesHtml( $item_id );

        echo ( json_encode( $response ) );
    }
    
    /**
     *
     */
    function refreshVenues()
    {
        Calendar::load( 'CalendarSelect', 'library.select' );
        
        $db = JFactory::getDBO();
        $db->setQuery( 'SELECT MAX(venue_id) FROM #__calendar_venues' );
        $result = $db->loadResult();
        
        $response = array();
        $response['error'] = '0';
        $response['msg'] = CalendarSelect::venue( $result, 'venue_id', '', 'venue_id' );
        echo ( json_encode( $response ) );        
    }
    
    function saveEventInstances()
    {
        $row = $this->row;
        JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
        $model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
        $model->setState('filter_event', $item_id);
        $model->setState( 'order', 'tbl.eventinstance_date' );
        $model->setState( 'direction', 'ASC' );
        $query = $model->getQuery();
        $query->order( 'tbl.eventinstance_start_time' );
        $model->setQuery( $query );

		$eventinstance_date = JRequest::getVar( 'eventinstance_date', array( 0 ), 'post', 'array' );
		$eventinstance_start_time = JRequest::getVar( 'eventinstance_start_time', array( 0 ), 'post', 'array' );
		$eventinstance_end_time = JRequest::getVar( 'eventinstance_end_time', array( 0 ), 'post', 'array' );
        $venue_id = JRequest::getVar( 'venue_id', array( 0 ), 'post', 'array' );
        $actionbutton_id = JRequest::getVar( 'actionbutton_id', array( 0 ), 'post', 'array' );
        $actionbutton_string = JRequest::getVar( 'actionbutton_string', array( 0 ), 'post', 'array' );
        $actionbutton_url = JRequest::getVar( 'actionbutton_url', array( 0 ), 'post', 'array' );
        
        if ($items = $model->getList())
        {
            foreach ($items as $item)
            {
                $table = JTable::getInstance( 'EventInstances', 'CalendarTable' );
                $table->load( $item->eventinstance_id );
                $table->eventinstance_date = $eventinstance_date[$item->eventinstance_id];
                $table->eventinstance_start_time = $eventinstance_start_time[$item->eventinstance_id];
                $table->eventinstance_end_time = $eventinstance_end_time[$item->eventinstance_id];
                $table->venue_id = $venue_id[$item->eventinstance_id];
                $table->actionbutton_id = $actionbutton_id[$item->eventinstance_id];
                $table->actionbutton_string = $actionbutton_string[$item->eventinstance_id];
                $table->actionbutton_url = $actionbutton_url[$item->eventinstance_id];
                $table->store();
            }
        }
    }
}

?>