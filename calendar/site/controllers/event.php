<?php
/**
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class CalendarControllerEvent extends CalendarController
{
	function __construct( )
	{
		parent::__construct( );
		$this->set( 'suffix', 'event' );
	}
	
	/**
	 * Default function for displaying an eventinstance
	 * 
	 * @param unknown_type $cachable
	 * @param unknown_type $urlparams
	 */
	function display($cachable=false, $urlparams = false)
	{
		$id = JRequest::getVar( 'id' );
		$request = JRequest::get('request');
		
		JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
		$model = JModel::getInstance( 'Event', 'CalendarModel' );
		$events_model = JModel::getInstance( 'Events', 'CalendarModel' );
		if ($model->pingTessituraWebAPI()) 
		{
		    // in the ghetto, we use the same url for different documents
		    $event = $events_model->getItem($id);
			if ( !is_object($event) || !$event->getDataSourceID() )
    		{
        		$model->setState( 'filter_eventinstance', $id );
        		$model->setState( 'get_event', true );
        		$model->setId( $id );
        		$item = $model->getItem();
        		
        		$ids = array( $item->getDataSourceID() );
        		$availability = $model->getAvailability( $ids );
        		$instances = $events_model->getInstances( $item->show->getDataSourceID() );
    		} 
    		    else 
    		{
    		    if ($instances = $events_model->getInstances( $event->getDataSourceID() )) {
    		        $item = $instances[0];
    		    }
    		    
    		    $ids = array( $item->getDataSourceID() );
    		    $availability = $model->getAvailability( $ids );
    		}

		} 
		    else 
		{
		    $item = null;
		    $this->setMessage( JText::_( "Unable to Load Event" ), 'notice' );
		    $this->setRedirect( JRoute::_( $this->getRedirectUrl() ) );
		    return; 
		}

		if ( !is_object($item) || !$item->getID() )
		{
		    $this->setMessage( JText::_( "Invalid Event" ), 'notice' );
		    $this->setRedirect( JRoute::_( $this->getRedirectUrl() ) );
		    return;
		}
		
		$view = $this->getView( $this->get( 'suffix' ), 'html' );
		$view->assign( 'item', $item );
		
		// push the event into the user state, where it stays until session expires or user views another event
		$app = JFactory::getApplication();
		$context = "com_calendar.view.event";
		$app->setUserState($context . '.item', serialize($item) );
		
        $event_helper = new CalendarHelperEvent();
        $previous_state = $event_helper->getState();
		$view->assign( 'previous_state', $previous_state );
		$back_url = $event_helper->getBackToCalendarURL( $previous_state );
		$view->assign( 'back_url', $back_url );

        $document = JFactory::getDocument();
        $page_title = $item->title;
        $page_title .= " | " . date( 'l, F j', strtotime( $item->eventinstance_date ) );
        $page_title .= ", " . date( 'g:iA', strtotime( $item->eventinstance_start_time ) );
        $page_title .= " | " . $item->getVenue_Name();
        $document->setTitle( strip_tags( $page_title ) );
        $document->setDescription( strip_tags( htmlspecialchars_decode( $item->event_description_short ) ) );
        
        $document->addCustomTag( "<meta property='og:image' content='" . JURI::root() . $item->event_full_image . "' />" );
        
        if (!JALC_ISMOBILE) 
        {
    		// change the template style
    		$db = JFactory::getDbo();
    		$query = $db->getQuery(true);
    		$query->select('*');
    		$query->from('#__template_styles as s');
    		$query->where('s.client_id = 0');
    		$query->where("s.template = 'default'");
    		$query->where("s.title = 'detail-page'");
    		$db->setQuery( (string) $query );
    		$template = $db->loadObject();
    		
    		$app = JFactory::getApplication();
    		$app->setTemplate('default', @$template->params);
        }
        
        $view->assign( 'availability', $availability );
        $view->assign( 'instances', $instances );
        
        // get the associated media item, incl handler html
        if ( !empty( $item->event->mediamanager_id ) )
        {
            $media_id = $item->event->mediamanager_id;
             
            $this->getItem( $media_id );
             
            $media_item = $this->media_item;
            $handler = $this->handler;
            $html = trim( $this->html );
             
            $view->assign( 'media_handler', $handler );
            $view->assign( 'media_handler_html', $html );
            $view->assign( 'media_item', $media_item );
        }
        
		parent::display( );
	}
	
	/**
	 * Function used to display an event on the mobile site.
	 * 
	 * @param unknown_type $cachable
	 * @param unknown_type $urlparams
	 */
	function show($cachable=false, $urlparams = false)
	{
	    $id = JRequest::getVar( 'id' );
	    $request = JRequest::get('request');
	
	    JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
	    $model = JModel::getInstance( 'Events', 'CalendarModel' );
	    $item = $model->getItem($id);
	
	    if ( !is_object($item) || !$item->getID() )
	    {
	        $this->setMessage( JText::_( "Invalid Event" ), 'notice' );
	        $this->setRedirect( JRoute::_( $this->getRedirectUrl() ) );
	        return;
	    }
	
	    $view = $this->getView( $this->get( 'suffix' ), 'html' );
	    $view->assign( 'item', $item );
	    JRequest::setVar('layout', 'show');
	
	    // push the event into the user state, where it stays until session expires or user views another event
	    $app = JFactory::getApplication();
	    $context = "com_calendar.view.event.show";
	    $app->setUserState($context . '.item' , serialize($item) );
	
	    $event_helper = new CalendarHelperEvent();
	    $previous_state = $event_helper->getState();
	    $view->assign( 'previous_state', $previous_state );
	    
	    //$back_url = $event_helper->getBackToCalendarURL( $previous_state );	    
	    $back_url = @$_SERVER['HTTP_REFERER'];
	    $view->assign( 'back_url', $back_url );
	    
	    $app = JFactory::getApplication();
	    $context = "com_calendar.view.event.show";
	    $app->setUserState($context . '.back_url' , serialize($back_url) );
	    
	    $date = date('Y-m-d');
	    if ( $date < 
	            $item->firstDate->format('Y-m-d')) {
	        $date = $item->firstDate->format('Y-m-d');
	    }

	    $next = $model->getNextInstance( $item->getDataSourceID(), $date );
	    $view->assign( 'next', $next );
	    
	    $instances = $model->getInstances( $item->getDataSourceID() );
	    $view->assign( 'instances', $instances );
	    
	    $times = array();
	    foreach ($instances as $instance) 
	    {
	    	$time = $instance->startDateTime->format( 'g:iA' );
	    	if (!in_array($time, $times)) 
	    	{
	    		$times[] = $time;
	    	}
	    }
	    //sort($times);
	    $view->assign( 'times', $times );
	    
	    if (!JALC_ISMOBILE)
	    {
	        // change the template style
	        $db = JFactory::getDbo();
	        $query = $db->getQuery(true);
	        $query->select('*');
	        $query->from('#__template_styles as s');
	        $query->where('s.client_id = 0');
	        $query->where("s.template = 'default'");
	        $query->where("s.title = 'detail-page'");
	        $db->setQuery( (string) $query );
	        $template = $db->loadObject();
	
	        $app = JFactory::getApplication();
	        $app->setTemplate('default', @$template->params);
	    }
	    
	    /*$ids = array( $item->getDataSourceID() );
	    $availability = $model->getAvailability( $ids );*/
	    $availability = array( $item->getDataSourceID() => true );
	    $view->assign( 'availability', $availability );
	
	    parent::display( );
	}
	
	private function getItem( $media_id, $item_only=false )
	{
	    $this->media_item = new JObject();
	    $this->handler = null;
	    $this->html = null;
	    
	    if (!JFile::exists( JPATH_ADMINISTRATOR.DS."components".DS."com_mediamanager".DS."defines.php" )) {
	        return;
	    }
	    
	    if ( !class_exists('MediaManager') ) {
	        JLoader::register( "MediaManager", JPATH_ADMINISTRATOR.DS."components".DS."com_mediamanager".DS."defines.php" );
	    }
	    
	    JModel::addIncludePath( JPATH_ADMINISTRATOR . "/components/com_mediamanager/models" );
	    $model = JModel::getInstance( 'Media', 'MediamanagerModel' );
	    $model->setId( $media_id );
	    $model->setState( 'get_categories', true );
	    $model->setState( 'get_files', true );
	    $media_item = $model->getItem( $media_id );

	    if (empty($media_item->media_id) || empty($media_item->media_type) || empty($media_item->media_enabled))
	    {
	        //JError::raiseNotice( '', 'MMCL1: Invalid Media' );
	        return;
	    }
	    
	    if ($item_only) 
	    {
	        $this->media_item = $media_item;
	        return;
	    }
	    
	    $hmodel = JModel::getInstance( 'Handlers', 'MediamanagerModel' );
	    $handler = $hmodel->getTable();
	    $handler->load( array( 'element'=>$media_item->media_type ) );
	    $key = $handler->getKeyName();
	    if (empty($handler->$key))
	    {
	        //JError::raiseNotice( '', 'MMCL: Invalid Media Type' );
	        return;
	    }
	    
	    if (empty($handler->published) && !empty($handler->id))
	    {
	        $handler->published = 1;
	        if ($handler->store())
	        {
	            // do we need to redirect?
	            $uri = JURI::getInstance();
	            $redirect = $uri->toString();
	            $redirect = JRoute::_( $redirect, false );
	            $this->setRedirect( $redirect );
	            return;
	        }
	    }
	     
	    $import = JPluginHelper::importPlugin( 'mediamanager', $handler->element );
	    
	    $html = '';
	    $dispatcher = JDispatcher::getInstance();
	    $results = $dispatcher->trigger( 'onDisplayMediaItem', array( $handler, $media_item ) );
	    for ($i=0; $i<count($results); $i++)
	    {
	        $html .= $results[$i];
	    }
	     
	    $media_item->categories = array();
	    foreach ($media_item->mediacategories as $mc)
	    {
	        if (empty($media_item->categories[$mc->categorytype_id]))
	        {
	            $media_item->categories[$mc->categorytype_id] = array();
	        }
	        $media_item->categories[$mc->categorytype_id][] = $mc;
	    }
	    
	    $this->media_item = $media_item;
	    $this->handler = $handler;
	    $this->html = $html;
	    
	    return;
	}
	
	public function downloadMedia() 
	{
	    $id = JRequest::getVar( 'id' );
	    
	    JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
	    $model = JModel::getInstance( 'Event', 'CalendarModel' );
	    $model->setState( 'filter_eventinstance', $id );
	    $model->setState( 'get_event', true );
	    $item = $model->getItem();
	    
	    if ( !is_object($item) || !$item->getID() )
	    {
	        $this->setMessage( JText::_( "Invalid Event" ), 'notice' );
	        $this->setRedirect( JRoute::_( $this->getRedirectUrl() ) );
	        return;
	    }
	    
	    if ( !empty( $item->event->mediamanager_id ) )
	    {
	        $media_id = $item->event->mediamanager_id;
	         
	        $this->getItem( $media_id, true );
	         
	        $media_item = $this->media_item;
	        
	        if (!empty($media_item->mediafiles[0])) {
	            $file_path = $media_item->mediafiles[0]->file_url;
	            $this->download( $file_path );
	        }
	    }

	    return;
	}
	
	/**
	 * Downloads file
	 *
	 * @param mixed A valid file object or a full path to a file
	 * @param mixed Boolean
	 * @return array
	 */
	private function download( $file )
	{
	    $success = false;
	
	    if (!is_object($file))
	    {
	        $file_path = $file;
	
	        $file = new JObject();
	        $file->name = JFile::getName( $file_path );
	        $file->path = JPath::clean( $file_path );
	        $file->extension = JFile::getExt( $file->name );
	    }
	
	    $file->path = JPath::clean($file->path);
	
	    // This will set the Content-Type to the appropriate setting for the file
	    switch( $file->extension ) {
	        case "pdf": $ctype="application/pdf"; break;
	        case "exe": $ctype="application/octet-stream"; break;
	        case "zip": $ctype="application/zip"; break;
	        case "doc": $ctype="application/msword"; break;
	        case "xls": $ctype="application/vnd.ms-excel"; break;
	        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
	        case "gif": $ctype="image/gif"; break;
	        case "png": $ctype="image/png"; break;
	        case "jpeg":
	        case "jpg": $ctype="image/jpg"; break;
	        /*case "mp3": $ctype="audio/mpeg"; break;*/
	        case "wav": $ctype="audio/x-wav"; break;
	        case "mpeg":
	        case "mpg":
	        case "mpe": $ctype="video/mpeg"; break;
	        case "mov": $ctype="video/quicktime"; break;
	        case "avi": $ctype="video/x-msvideo"; break;
	
	        // The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
	        case "php":
	        case "htm":
	        case "html": if ($file->path) die("<b>Cannot be used for ". $file->extension ." files!</b>");
	
	        default: $ctype="application/octet-stream";
	    }
	
	    // If requested file exists
	    if (JFile::exists($file->path) || !JURI::isInternal($file->path)) {
	
	        if (intval( ini_get('output_buffering')) > '1' )
	        {
	            ob_end_clean();
	        }
	
	        // Fix IE bugs
	        if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
	            $header_file = preg_replace('/\./', '%2e', $file->name, substr_count($file->name, '.') - 1);
	
	            if (ini_get('zlib.output_compression'))  {
	                ini_set('zlib.output_compression', 'Off');
	            }
	        }
	        else {
	            $header_file = $file->name;
	        }
	
	        // Prepare headers
	        header("Pragma: public");
	        header("Expires: 0");
	        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	        header("Cache-Control: public", false);
	
	        header("Content-Description: File Transfer");
	        header("Content-Type: $ctype" );
	        header("Accept-Ranges: bytes");
	        header("Content-Disposition: attachment; filename=\"" . $header_file . "\";");
	        header("Content-Transfer-Encoding: binary");
	        header("Content-Length: " . filesize($file->path));
	
	        error_reporting(0);
	        if ( ! ini_get('safe_mode') ) {
	            set_time_limit(0);
	        }
	
	        if (intval( ini_get('output_buffering')) > '1' )
	        {
	            ob_clean();
	            flush();
	            @readfile($file->path);
	            exit;
	        }
	        else
	        {
	            //Output file by chunks
	            $chunk = 1 * (1024 * 1024);
	            $total = filesize($file->path);
	            $sent = 0;
	            while ($sent < $total)
	            {
	                echo file_get_contents($file->path, false, null, $sent, $chunk );
	                $sent += $chunk;
	                @ob_flush();
	                @flush();
	            }
	            // fread doesn't seem to be working, using file_get_contents instead
	            //$this->readfileChunked($file->path, $chunk);
	            exit;
	        }
	
	        $success = true;
	        exit;
	    }
	
	    return $success;
	}
	
	/**
	 * For filtering calendars by primary categories.
	 * Is expected to be called via Ajax.
	 * 
	 * @param $categories array
	 * @param $type string primary or secondary type
	 * @return void
	 */
	function filterprimary()
	{	
		$this->_setModelState();
	    $model = $this->getModel( 'month' );
	    
		$state = array();
	    $app = JFactory::getApplication( );
	    $ns = $this->getNamespace( );
		$m = JRequest::getVar( 'month' );
		$y = JRequest::getVar( 'year' );
		if( empty( $m ) && empty( $y ) )
		{
			$state['month'] = date( 'm' );
			$state['year'] = date( 'Y' );
		}		
		else 
		{
			$state['month'] = $app->getUserStateFromRequest( $ns . 'month', 'month', '', '' );
			$state['year'] = $app->getUserStateFromRequest( $ns . 'year', 'year', '', '' );
		}
		$state['filter_date_from'] = $state['year'] . '-' . $state['month'] . '-01';
        $state['filter_datetype'] = 'month';
        
	    Calendar::load( 'CalendarHelperBase', 'helpers.base' );
	    $helper = CalendarHelperBase::getInstance();
	    $datevars = $helper->setDateVariables( $state['filter_date_from'], null, 'monthly' );
	    $state['filter_date_to'] = $datevars->nextdate;
        
	    $state['order'] = 'tbl.eventinstance_date';
	    $state['direction'] = 'ASC';
	    
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		
	    $state = $model->getState();
	    
		// get categories for filtering
		$elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
		
		$vars = new JObject();
		Calendar::load( 'CalendarHelperBase', 'helpers.base' );
		$helper = new CalendarHelperBase();
		$values = $helper->elementsToArray( $elements );
		$item_id = $values['Itemid'];
		$vars->item_id = $item_id;
		$vars->values = $values;
		
		$categories = array();
		foreach($elements as $element)
		{
			if($element->checked && strpos ( $element->name , 'primary_cat_' ) !== false )
			{
				$categories[] = $element->value;
			}
		}
		
		$model->setState( 'filter_primary_categories', $categories );
		$model->setState( 'order', 'tbl.eventinstance_date' );
		$model->setState( 'direction', 'ASC' );
		$query = $model->getQuery( );
		$query->order( 'tbl.eventinstance_start_time' );
		$model->setQuery( $query );
		
	    $list = $model->getList();
	    $vars->items = $list;
	   
		// make date and time variables
		$date = new JObject();
		$date->current = $state->filter_date_from;
		$date->month = $state->month;
		$date->year = $state->year;
		$date->month_name = date( 'F', strtotime( $date->year . '-' . $date->month . '-01' ) );
		
		$date->nextmonth = date( 'm', strtotime( $date->year . '-' . $date->month . ' +1 month' ) );
		$date->nextyear = date( 'Y', strtotime( $date->year . '-' . $date->month . ' +1 month' ) );
		$date->prevmonth = date( 'm', strtotime( $date->year . '-' . $date->month . ' -1 month' ) );
		$date->prevyear = date( 'Y', strtotime( $date->year . '-' . $date->month . ' -1 month' ) );
		
		$date->weekdays = array( 'Sunday' => 'SUN', 'Monday' => 'MON', 'Tuesday' => 'TUES', 'Wednesday' => 'WED', 'Thursday' => 'THU', 'Friday' => 'FRI', 'Saturday' => 'SAT' );
		$date->weekstart = 'SUN';
		$date->weekend = 'SAT';
		$date->numberofdays = date( 't', strtotime( $date->year . '-' . $date->month . '-01' ) );
		$date->monthstartday = date( 'l', strtotime( $date->year . '-' . $date->month . '-01' ) );
		
		if ( $date->monthstartday == 'Friday' || $date->monthstartday == 'Saturday' )
		{
			$date->numberofweeks = 6;
		}
		else
		{
			$date->numberofweeks = 5;
		}

		$instance = JTable::getInstance( 'EventInstances', 'CalendarTable' );
		
		$days = array();
		foreach ($list as $item)
		{
		    $day = $item->eventinstance_date;
		    if (empty($days[$day]))
		    {
		        $days[$day] = new JObject();
		        $days[$day]->dateTime = strtotime( $day );
		        $days[$day]->dateMySQL = $day;
		        $days[$day]->events = array();
		    }
		    
		    $instance->event_full_image = $item->event_full_image;
		    $item->image_src = $instance->getImage('src');
		    $days[$day]->events[] = $item;
		}
			
		$vars->date = $date;
		$vars->days = $days;
				
		$html = $this->getLayout( 'default', $vars, 'month' ); 		
		echo ( json_encode( array('msg'=>$html) ) );
	}
	
	/**
	 * For filtering calendars by secondary categories.
	 * Is expected to be called via Ajax.
	 * 
	 * @param $categories array
	 * @param $type string primary or secondary type
	 * @return void
	 */
	function filtersecondary()
	{	
		$this->_setModelState();
	    $model = $this->getModel( 'month' );

	    $state = array();
	    $app = JFactory::getApplication( );
	    $ns = $this->getNamespace( );
		$m = JRequest::getVar( 'month' );
		$y = JRequest::getVar( 'year' );
		if( empty( $m ) && empty( $y ) )
		{
			$state['month'] = date( 'm' );
			$state['year'] = date( 'Y' );
		}		
		else 
		{
			$state['month'] = $app->getUserStateFromRequest( $ns . 'month', 'month', '', '' );
			$state['year'] = $app->getUserStateFromRequest( $ns . 'year', 'year', '', '' );
		}
		$state['filter_date_from'] = $state['year'] . '-' . $state['month'] . '-01';
        $state['filter_datetype'] = 'month';
        
	    Calendar::load( 'CalendarHelperBase', 'helpers.base' );
	    $helper = CalendarHelperBase::getInstance();
	    $datevars = $helper->setDateVariables( $state['filter_date_from'], null, 'monthly' );
	    $state['filter_date_to'] = $datevars->nextdate;
        
	    $state['order'] = 'tbl.eventinstance_date';
	    $state['direction'] = 'ASC';
	    
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		
        $state = $model->getState();	    
		// get categories for filtering		
		// take filter categories and do filtering
		$elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
		
		$vars = new JObject();
		Calendar::load( 'CalendarHelperBase', 'helpers.base' );
		$helper = new CalendarHelperBase();
		$values = $helper->elementsToArray( $elements );
		$item_id = $values['Itemid'];
		$vars->item_id = $item_id;
		$vars->values = $values;
		
		foreach($elements as $element)
		{
			if($element->checked && $element->name == 'secondary_category' )
			{
				$filter_category = $element->value;
			}
		}
		$model->setState( 'filter_secondary_category', $filter_category );
		
		$model->setState( 'order', 'tbl.eventinstance_date' );
		$model->setState( 'direction', 'ASC' );
		$query = $model->getQuery( );
		$query->order( 'tbl.eventinstance_start_time' );
		$model->setQuery( $query );
		
	    $list = $model->getList();
	    $vars->items = $list;
	    
		// make date and time variables
		$date = new JObject();
		$date->current = $state->filter_date_from;
		$date->month = $state->month;
		$date->year = $state->year;
		$date->month_name = date( 'F', strtotime( $date->year . '-' . $date->month . '-01' ) );
		
		$date->nextmonth = date( 'm', strtotime( $date->year . '-' . $date->month . ' +1 month' ) );
		$date->nextyear = date( 'Y', strtotime( $date->year . '-' . $date->month . ' +1 month' ) );
		$date->prevmonth = date( 'm', strtotime( $date->year . '-' . $date->month . ' -1 month' ) );
		$date->prevyear = date( 'Y', strtotime( $date->year . '-' . $date->month . ' -1 month' ) );
		
		$date->weekdays = array( 'Sunday' => 'SUN', 'Monday' => 'MON', 'Tuesday' => 'TUES', 'Wednesday' => 'WED', 'Thursday' => 'THU', 'Friday' => 'FRI', 'Saturday' => 'SAT' );
		$date->weekstart = 'SUN';
		$date->weekend = 'SAT';
		$date->numberofdays = date( 't', strtotime( $date->year . '-' . $date->month . '-01' ) );
		$date->monthstartday = date( 'l', strtotime( $date->year . '-' . $date->month . '-01' ) );
		
		if ( $date->monthstartday == 'Friday' || $date->monthstartday == 'Saturday' )
		{
			$date->numberofweeks = 6;
		}
		else
		{
			$date->numberofweeks = 5;
		}

		$instance = JTable::getInstance( 'EventInstances', 'CalendarTable' );
		
		$days = array();
		foreach ($list as $item)
		{
		    $day = $item->eventinstance_date;
		    if (empty($days[$day]))
		    {
		        $days[$day] = new JObject();
		        $days[$day]->dateTime = strtotime( $day );
		        $days[$day]->dateMySQL = $day;
		        $days[$day]->events = array();
		    }
		    
		    $instance->event_full_image = $item->event_full_image;
		    $item->image_src = $instance->getImage('src');
		    $days[$day]->events[] = $item;
		}
		
		$vars->date = $date;
		$vars->days = $days;
		
		$html = $this->getLayout( 'default', $vars, 'month' ); 
		echo ( json_encode( array('msg'=>$html) ) );
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return return_type
	 */
	public function ical()
	{
	    $id = JRequest::getVar( 'id' );
	    
		JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
		$model = JModel::getInstance( 'Event', 'CalendarModel' );
		$model->setState( 'filter_eventinstance', $id );
		$item = $model->getItem();
		
        if ($ical = $model->getICal( $item )) 
        {
            $this->ical = new JObject();
            $this->ical->path = JPATH_BASE . '/' . $ical;
            $this->ical->extension = JFile::getExt($this->ical->path);
            $this->ical->name = JFile::getName($this->ical->path);
            	
            Calendar::load( 'CalendarFile', 'library.file' );
            $file = new CalendarFile();
            $file->download( $this->ical );            
        } 
            else 
        {
            echo $model->getError();
        }
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return return_type
	 */
	function getEvents()
	{
	    $launch_date = '2011-08-03';
	    $day_after_launch_date = '2011-08-04';
	    
	    JRequest::setVar('format', 'json');
	    JLoader::import( 'com_calendar.library.json', JPATH_ADMINISTRATOR . DS . 'components' );
	    
	    JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
	    $model = JModel::getInstance( 'EventInstances', 'CalendarModel' );
	    $model->setState('filter_enabled', '1' );
	    
	    $date = JRequest::getVar('date');
	    switch ($date)
	    {
	        case "featured":
	            // startdate == today + 2
	            $jdate = JFactory::getDate( strtotime('today +2 days') );
	            $day = $jdate->toFormat( '%Y-%m-%d' );
	            $model->setState('filter_date_from', $day );
	            $model->setState('filter_datetype', 'date' );
	            
	            $model->setState('filter_digital_signage', '1' );
	            $limit = JRequest::getInt('limit', 3);
	            $model->setState('limit', $limit );
	            $obj_name = "var objFeatured";
	            break;
	        case "tomorrow":
	            $jdate = JFactory::getDate( strtotime('tomorrow') );
	            $day = $jdate->toFormat( '%Y-%m-%d' );
	    	    if ($day < $day_after_launch_date)
	            {
	                //$day = $day_after_launch_date;
	            }
        	    $model->setState('filter_date_from', $day );
        	    $model->setState('filter_date_to', $day );
        	    $model->setState('filter_datetype', 'date' );
        	    $obj_name = "var objTomorrow";
	            break;
	        case "today":
	            $jdate = JFactory::getDate( strtotime('today') );
	            $day = $jdate->toFormat( '%Y-%m-%d' );
	            if ($day < $launch_date)
	            {
	                //$day = $launch_date;
	            }
        	    $model->setState('filter_date_from', $day );
        	    $model->setState('filter_date_to', $day );
        	    $model->setState('filter_datetype', 'date' );
        	    $obj_name = "var objToday";
	            break;
	        default:
	            if (empty($date))
	            {
	                $jdate = JFactory::getDate( strtotime('today') );
	            } 
	                else
	            {
	                $jdate = JFactory::getDate( strtotime( $date ) );
	            }
	            $day = $jdate->toFormat( '%Y-%m-%d');
        	    $model->setState('filter_date_from', $day );
        	    $model->setState('filter_date_to', $day );
        	    $model->setState('filter_datetype', 'date' );
        	    $obj_name = "var objDate";
	            break;
	    }
		$query = $model->getQuery( );
		$query->order( 'tbl.eventinstance_date' );
		$query->order( 'tbl.eventinstance_start_time' );
		$query->group( 'tbl.event_id' );
		$model->setQuery( $query );
		
		if (!$list = $model->getList())
		{
		    $list = array();
		    $object = new stdClass();
		    $object->error = JText::_( "No Events Scheduled" );
		    $list[] = $object;
		} 
		    else
		{
		    $keys = array( 'eventinstance_date', 'eventinstance_start_time', 'event_short_title', 'event_full_image', 'event_id', 'eventinstance_id' );
		    foreach ($list as $item)
		    {
		        $props = get_object_vars( $item );
		        foreach ($props as $key=>$prop)
		        {
		            if (!in_array($key, $keys))
		            {
		                unset($item->$key);
		            }
		        }
		    }
		}
	    
		$response = new stdClass();
		$response->data = $list;
		$string = json_encode( $response );
		$string = $obj_name . " = " . $string;
		echo $string;
	}
}
