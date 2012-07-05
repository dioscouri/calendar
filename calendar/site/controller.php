<?php
/**
 * @version	0.1
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class CalendarController extends JController 
{	
	/**
	* default view
	*/
	public $default_view = 'dashboard';
	
	var $_models = array();
	var $message = "";
	var $messagetype = "";
		
	/**
	 * constructor
	 */
	function __construct( $config=array() ) 
	{
		parent::__construct( $config );
		$this->set('suffix', $this->get('default_view') );
		
		// Set a base path for use by the controller
		if (array_key_exists('base_path', $config)) {
			$this->_basePath	= $config['base_path'];
		} else {
			$this->_basePath	= JPATH_COMPONENT;
		}
		
		// Register Extra tasks
		$this->registerTask( 'list', 'display' );
		$this->registerTask( 'close', 'cancel' );
		$this->registerTask( 'add', 'edit' );
		$this->registerTask( 'new', 'edit' );
		$this->registerTask( 'apply', 'save' );
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
    function _setModelState()
    {
		$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
		$ns = $this->getNamespace();

		$state = array();
		
    	// limitstart isn't working for some reason when using getUserStateFromRequest -- cannot go back to page 1
		$limit  = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', '0', 'request', 'int');
		// If limit has been changed, adjust offset accordingly
		$state['limitstart'] = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        $state['limit']  	= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $state['filter_enabled'] = 1;
        $state['order']     = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.'.$model->getTable()->getKeyName(), 'cmd');
        $state['direction'] = $app->getUserStateFromRequest($ns.'.filter_direction', 'filter_direction', 'ASC', 'word');
        $state['filter']    = $app->getUserStateFromRequest($ns.'.filter', 'filter', '', 'string');
        $state['id']        = JRequest::getVar('id', JRequest::getVar('id', '', 'get', 'int'), 'post', 'int');

        // TODO santize the filter
        // $state['filter']   	= 

    	foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );	
		}
  		return $state;
    }

    /**
     * 
     * @return unknown_type
     */
    function getNamespace()
    {
    	$app = JFactory::getApplication();
    	$model = $this->getModel( $this->get('suffix') );
		$ns = $app->getName().'::'.'com.calendar.model.'.$model->getTable()->get('_suffix');
    	return $ns;
    }
    
    /**
     * We override parent::getModel because parent::getModel was always creating a new Model instance
     *
     */
	function getModel( $name = '', $prefix = '', $config = array() )
	{
		if ( empty( $name ) ) {
			$name = $this->getName();
		}

		if ( empty( $prefix ) ) {
			$prefix = $this->getName() . 'Model';
		}
		
		$fullname = strtolower( $prefix.$name ); 
		if (empty($this->_models[$fullname]))
		{
			if ( $model = & $this->_createModel( $name, $prefix, $config ) )
			{
				// task is a reserved state
				$model->setState( 'task', @$this->_task );
	
				// Lets get the application object and set menu information if its available
				$app	= &JFactory::getApplication();
				$menu	= &$app->getMenu();
				if (is_object( $menu ))
				{
					if ($item = $menu->getActive())
					{
						$params	=& $menu->getParams($item->id);
						// Set Default State Data
						$model->setState( 'parameters.menu', $params );
					}
				}
			}
				else 
			{
				$model = new JModel();
			}
			$this->_models[$fullname] = $model;
		}

		return $this->_models[$fullname];
	}
	
	/**
	 * Method to load and return a model object.
	 *
	 * @access	private
	 * @param	string  The name of the model.
	 * @param	string	Optional model prefix.
	 * @param	array	Configuration array for the model. Optional.
	 * @return	mixed	Model object on success; otherwise null
	 * failure.
	 * @since	1.5
	 */
	function _createModel($name, $prefix = '', $config = array())
	{
		// Clean the model name
		$modelName		= preg_replace('/[^A-Z0-9_]/i', '', $name);
		$classPrefix	= preg_replace('/[^A-Z0-9_]/i', '', $prefix);

		$result = &JModel::getInstance($modelName, $classPrefix, $config);
		return $result;
	}
	
	/**
	 * Gets the available tasks in the controller.
	 *
	 * @return  array  Array[i] of task names.
	 * @since   11.1
	 */
	public function getTaskMap()
	{
    	if(version_compare(JVERSION,'1.6.0','ge')) {
            // Joomla! 1.6+ code here
            return $this->taskMap;
        } else {
            // Joomla! 1.5 code here
            return $this->_taskMap;
        }
	}
	
	/**
	 * Gets the available tasks in the controller.
	 *
	 * @return  array  Array[i] of task names.
	 * @since   11.1
	 */
	public function getDoTask()
	{
    	if(version_compare(JVERSION,'1.6.0','ge')) {
            // Joomla! 1.6+ code here
            return $this->doTask;
        } else {
            // Joomla! 1.5 code here
            return $this->_doTask;
        }
	}

	/**
	 * Sets the tasks in the controller.
	 *
	 */
	public function setDoTask( $task )
	{
    	if(version_compare(JVERSION,'1.6.0','ge')) {
            // Joomla! 1.6+ code here
            $this->doTask = $task;
        } else {
            // Joomla! 1.5 code here
            $this->_doTask = $task;
        }
	}
	
	/**
	* 	display the view
	*/
	function display($cachable=false)
	{
		$this->setDoTask( JRequest::getCmd( 'task', 'display' ) );
		// this sets the default view
		JRequest::setVar( 'view', JRequest::getVar( 'view', 'items' ) );
		
		$document =& JFactory::getDocument();

		$viewType	= $document->getType();
		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$viewLayout	= JRequest::getCmd( 'layout', 'default' );

		$view = & $this->getView( $viewName, $viewType, '', array( 'base_path'=>$this->_basePath));

		// Get/Create the model
		if ($model = & $this->getModel($viewName)) 
		{
			// controller sets the model's state - this is why we override parent::display()
			$this->_setModelState();
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}

		// Set the layout
		$view->setLayout($viewLayout);
		
        // Set the task in the view, so the view knows it is a valid task 
        if (in_array($this->getTask(), array_keys($this->getTaskMap()) ))
        {
          $view->setTask($this->getDoTask());	
        }
		
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onBeforeDisplayComponentCalendar', array() );
		
		// Display the view
		if ($cachable && $viewType != 'feed') {
			global $option;
			$cache =& JFactory::getCache($option, 'view');
			$cache->get($view, 'display');
		} else {
			$view->display();
		}

		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onAfterDisplayComponentCalendar', array() );
		
        $this->footer();		
	}

	/**
	 * @return void
	 */
	function view() 
	{		
		parent::display();
	}
	
	/**
	 * @return void
	 */
	function edit() 
	{
		parent::display();
	}

	/**
	 * cancel and redirect to main page
	 * @return void
	 */
	function cancel() 
	{
		$link = 'index.php?option=com_calendar&view='.$this->get('suffix');
		
		$task = JRequest::getVar( 'task' );
		switch (strtolower($task))
		{
			case "cancel":
				$msg = JText::_( 'Operation Cancelled' );
				$type = "notice";
			  break;
			case "close":
			default:
				$model 	= $this->getModel( $this->get('suffix') );
			    $row = $model->getTable();
			    $row->load( $model->getId() );
				if (isset($row->checked_out) && !JTable::isCheckedOut( JFactory::getUser()->id, $row->checked_out) )
				{
					$row->checkin();
				}
				$msg = "";
				$type = "";				
			  break;
		}
	    
	    $this->setRedirect( $link, $msg, $type );		
	}

    /**
     * Verifies the fields in a submitted form.  Uses the table's check() method.
     * Will often be overridden. Is expected to be called via Ajax 
     * 
     * @return unknown_type
     */
    function validate()
    {
        Calendar::load( 'CalendarHelperBase', 'helpers._base' );
        $helper = new CalendarHelperBase();
            
        $response = array();
        $response['msg'] = '';
        $response['error'] = '';
            
        // get elements from post
            $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );

            // validate it using table's ->check() method
            if (empty($elements))
            {
                // if it fails check, return message
                $response['error'] = '1';
                $response['msg'] = $helper->generateMessage(JText::_("Could not process form"));
                echo ( json_encode( $response ) );
                return;
            }
            
        // convert elements to array that can be binded             
            $values = $helper->elementsToArray( $elements );
            

        // get table object
            $table = $this->getModel( $this->get('suffix') )->getTable();
        
        // bind to values
            $table->bind( $values );
        
        // validate it using table's ->check() method
            if (!$table->check())
            {
                // if it fails check, return message
                $response['error'] = '1';
                $response['msg'] = $helper->generateMessage($table->getError());
            }

        echo ( json_encode( $response ) );
        return;
    }

    /**
     * Displays the footer
     * 
     * @return unknown_type
     */
    function footer()
    {
        // show a generous linkback, TIA
        $show_linkback = Calendar::getInstance()->get('show_linkback', '1');
        $format = JRequest::getVar('format');
        if ($show_linkback == '1' && $format != 'raw') 
        {
            $model  = $this->getModel( 'dashboard' );
            $view   = $this->getView( 'dashboard', 'html' );
            $view->hidemenu = true;
            $view->setTask('footer');
            $view->setModel( $model, true );
            $view->setLayout('footer');
            $view->assign( 'style', '');
            $view->display();
        } 
            elseif ($format != 'raw')
        {
            $model  = $this->getModel( 'dashboard' );
            $view   = $this->getView( 'dashboard', 'html' );
            $view->hidemenu = true;
            $view->setTask('footer');
            $view->setModel( $model, true );
            $view->setLayout('footer');
            $view->assign( 'style', 'style="display: none;"');
            $view->display();
        }

        return;
    }
    
	/**
	 * 
	 * @return 
	 */
	function doTask()
	{
		$success = true;
		$msg = new stdClass();
		$msg->message = '';
		$msg->error = '';
				
		// expects $element in URL and $elementTask
		$element = JRequest::getVar( 'element', '', 'request', 'string' );
		$elementTask = JRequest::getVar( 'elementTask', '', 'request', 'string' );

		$msg->error = '1';
		// $msg->message = "element: $element, elementTask: $elementTask";
		
		// gets the plugin named $element
		$import 	= JPluginHelper::importPlugin( 'calendar', $element );
		$dispatcher	=& JDispatcher::getInstance();
		// executes the event $elementTask for the $element plugin
		// returns the html from the plugin
		// passing the element name allows the plugin to check if it's being called (protects against same-task-name issues)
		$result 	= $dispatcher->trigger( $elementTask, array( $element ) );
		// This should be a concatenated string of all the results, 
			// in case there are many plugins with this eventname 
			// that return null b/c their filename != element) 
		$msg->message = implode( '', $result );
			// $msg->message = @$result['0'];
						
		// encode and echo (need to echo to send back to browser)		
		echo $msg->message;
		$success = $msg->message;

		return $success;
	}
	
	/**
	 * 
	 * @return 
	 */
	function doTaskAjax()
	{
		$success = true;
		$msg = new stdClass();
		$msg->message = '';
				
		// get elements $element and $elementTask in URL 
			$element = JRequest::getVar( 'element', '', 'request', 'string' );
			$elementTask = JRequest::getVar( 'elementTask', '', 'request', 'string' );
			
		// get elements from post
			// $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
			
		// for debugging
			// $msg->message = "element: $element, elementTask: $elementTask";

		// gets the plugin named $element
			$import 	= JPluginHelper::importPlugin( 'calendar', $element );
			$dispatcher	=& JDispatcher::getInstance();
			
		// executes the event $elementTask for the $element plugin
		// returns the html from the plugin
		// passing the element name allows the plugin to check if it's being called (protects against same-task-name issues)
			$result 	= $dispatcher->trigger( $elementTask, array( $element ) );
		// This should be a concatenated string of all the results, 
			// in case there are many plugins with this eventname 
			// that return null b/c their filename != element)
			$msg->message = implode( '', $result );
			// $msg->message = @$result['0'];

			// set response array
			$response = array();
			$response['msg'] = $msg->message;
				
			// encode and echo (need to echo to send back to browser)
			echo ( json_encode( $response ) );

			return $success;
	}


	/**
	 * Loads a module based on ID
	 *
	 * @param int $id
	 * @return string
	 */
	function loadModule( $id=null )
	{
		$success = true;
		$msg = new stdClass( );
		$msg->message = '';
		$msg->error = '';

		$element = $id;
		if (empty($element))
		{
			$element = JRequest::getVar( 'element', '', 'request', 'string' );
		}

		$module = JTable::getInstance('Module');
		$module->load( (int) $element );

		if (!empty($module->id))
		{
			$file					= $module->module;
			$custom 				= substr( $file, 0, 4 ) == 'mod_' ?  0 : 1;
			$module->user  	        = $custom;
			// CHECK: custom module name is given by the title field, otherwise it's just 'om' ??
			$module->name		= $custom ? $module->title : substr( $file, 4 );
			$module->style		= null;
			$module->position	= strtolower($module->position);

			jimport('joomla.application.module.helper');
			
			$msg->message = JModuleHelper::renderModule( $module );
		}
			
		return $msg->message;
	}

	/**
		* Loads a module
		* expects to be called via ajax
		*
		* @return return_type
		*/
	function loadModuleAjax()
	{
		$success = true;
		$msg = new stdClass( );
		$msg->message = '';
		$msg->error = '';

		$element = JRequest::getVar( 'element', '', 'request', 'string' );

		$module = JTable::getInstance('Module');
		$module->load( (int) $element );

		if (!empty($module->id))
		{
			$file					= $module->module;
			$custom 				= substr( $file, 0, 4 ) == 'mod_' ?  0 : 1;
			$module->user  	        = $custom;
			// CHECK: custom module name is given by the title field, otherwise it's just 'om' ??
			$module->name		= $custom ? $module->title : substr( $file, 4 );
			$module->style		= null;
			$module->position	= strtolower($module->position);

			$msg->message = JModuleHelper::renderModule( $module );
		}

		$response = array( );
		$response['msg'] = $msg->message;
		echo ( json_encode( $response ) );

		return $success;
	}

	/**
	 * Gets the parsed layout file
	 *
	 * @param string $layout The name of  the layout file
	 * @param object $vars Variables to assign to
	 * @param string $plugin The name of the plugin
	 * @param string $group The plugin's group
	 * @return string
	 * @access protected
	 */
	function getLayout($layout, $vars = false, $view )
	{
		ob_start();
		$layout = $this->getLayoutPath( $view, $layout );
		include($layout);
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}


	/**
	 * Get the path to a layout file
	 *
	 * @param   string  $plugin The name of the plugin file
	 * @param   string  $group The plugin's group
	 * @param   string  $layout The name of the plugin layout file
	 * @return  string  The path to the plugin layout file
	 * @access protected
	 */
	function getLayoutPath( $view, $layout = 'default')
	{
		$app = JFactory::getApplication();

		// get the template and default paths for the layout
		$templatePath = JPATH_SITE.DS.'templates'.DS.$app->getTemplate().DS.'html'.DS.'com_calendar'.DS.$view.DS.$layout.'.php';
		//$defaultPath = JPATH_SITE.DS.'plugins'.DS.$group.DS.$plugin.DS.'tmpl'.DS.$layout.'.php';
		$defaultPath = JPATH_SITE.DS.'components'.DS.'com_calendar'.DS.'views'.DS.$view.DS.'tmpl'.DS.$layout.'.php';
		 
		// if the site template has a layout override, use it
		jimport('joomla.filesystem.file');
		if (JFile::exists( $templatePath ))
		{
			return $templatePath;
		}
		else
		{
			return $defaultPath;
		}
	}

	/**
	 * Returns array of dates
	 * given date + 6 days
	 *
	 * @param string $date
	 * @return array of dates
	 */
	function getDayDates( $date )
	{
		$weekdays = array( );

		$weekdays[0] = date( 'Y-m-d', strtotime( $date ) );
		for ( $i = 1; $i < 7; $i++ )
		{
			$weekdays[$i] = date( 'Y-m-d', strtotime( $date . ' +' . $i . 'days' ) );
		}

		return $weekdays;
	}

	/**
		* Returns array of week dates for given date
		* Considering: end and start of the year, start day for weeks is Sunday
		*
		* @param string $date
		* @return array of dates
		*/
	function getSundayWeekDates( $date )
	{
		$day_position = date( 'w', strtotime( $date ) );

		// forming array of dates
		// first add dates preciding start date
		$weekdays = array( );
		$counter = $day_position;
		for ( $i = 0; $i < $day_position; $i++ )
		{
			$weekdays[$i] = date( 'Y-m-d', strtotime( $date . ' -' . $counter . 'days' ) );
			$counter--;
		}
		$weekdays[$day_position] = date( 'Y-m-d', strtotime( $date ) );
		$counter = 0;
		for ( $i = $day_position; $i < 7; $i++ )
		{
			$weekdays[$i] = date( 'Y-m-d', strtotime( $date . ' +' . $counter . 'days' ) );
			$counter++;
		}

		return $weekdays;
	}

	/**
	 * Returns array of non-working days
	 *
	 * @param void
	 * @return array non working days
	 */
	function getNonWorkingDays( )
	{
		$config = Calendar::getInstance( );
			
		$str_days = $config->get( 'non_working_days' );

		$non_working_days = array( );
		$str_days = @preg_replace( '/\s/', '', $str_days );
		$non_working_days = explode( ',', $str_days );

		return $non_working_days;
	}

	/**
	 * Makes hours array
	 *
	 * @param void
	 * @return array of strings with hours (am/pm)
	 */
	function getHours( )
	{
		$hours = array( );

		$hours[0] = '12am';
		for ( $i = 1; $i < 12; $i++ )
		{
			$hours[] = $i . 'am';
		}

		$hours[12] = '12pm';
		$counter = 1;
		for ( $i = 13; $i < 24; $i++ )
		{
			$hours[] = $counter . 'pm';
			$counter++;
		}
			
		return $hours;
	}

	/**
	 * For filtering calendars by primary categories.
	 * Is expected to be called via Ajax.
	 *
	 * @param $categories array
	 * @param $type string primary or secondary type
	 * @return void
	 */
	function filtercategories()
	{
		$this->_setModelState();
		$model = $this->getModel( $this->get( 'suffix' ) );

		// get categories for filtering
		$elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );

		$vars = new JObject();
		Calendar::load( 'CalendarHelperBase', 'helpers._base' );
		$helper = new CalendarHelperBase();
		$values = $helper->elementsToArray( $elements );
		$item_id = $values['Itemid'];
		$vars->item_id = $item_id;
		$vars->values = $values;

		$event_helper = CalendarHelperBase::getInstance( 'event' );
		$state = $event_helper->getState();
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		$state = $model->getState();
		$vars->state = $state;

		$view = JRequest::getVar('view');

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

		// affix the Closed Days to the end of the list array
	    Calendar::load( 'CalendarHelperCalendar', 'helpers.calendar' );
	    $helper = CalendarHelperBase::getInstance( 'calendar' );
		$config = Calendar::getInstance();
		$non_working_days = $config->get('non_working_days');
		$closed_days = explode(',', $non_working_days);
		$closed_days_array = array();
		foreach( $closed_days as $day_of_week )
		{
			$closed_days_array[] = $helper->getDaysOfMonth($date->month, $date->year, trim( $day_of_week ) );
		}

		switch ($view)
		{
			case "day":
				// used by day view
				$date->nextdaydate = date( 'Y-m-d', strtotime( $date->current . ' +1 day' ) );
				$date->nextmonth   = date( 'm',     strtotime( $date->current . ' +1 day' ) );
				$date->nextyear    = date( 'Y',     strtotime( $date->current . ' +1 day' ) );
				$date->prevdaydate = date( 'Y-m-d', strtotime( $date->current . ' -1 day' ) );
				$date->prevmonth   = date( 'm',     strtotime( $date->current . ' -1 day' ) );
				$date->prevyear    = date( 'Y',     strtotime( $date->current . ' -1 day' ) );
				$date->hours = $this->getHours( );
				$date->nonworkingdays = $this->getNonWorkingDays( );
				foreach ($closed_days_of_month as $closed_day)
				{
					if ($closed_day == $date->current)
					{
						$instance = JTable::getInstance( 'EventInstances', 'CalendarTable' );
						$instance->eventinstance_date = $closed_day;
						$instance->isClosedDay = true;
						$list[] = $instance;
					}
				}
				break;
			case "week":
				// used by week view
				$date->weekdays = $this->getDayDates( $date->current );
				$date->nextweekdate = date( 'Y-m-d', strtotime( $date->current . ' +7 days' ) );
				$date->nextmonth    = date( 'm',     strtotime( $date->current . ' +7 days' ) );
				$date->nextyear     = date( 'Y',     strtotime( $date->current . ' +7 days' ) );
				$date->prevweekdate = date( 'Y-m-d', strtotime( $date->current . ' -7 days' ) );
				$date->prevmonth    = date( 'm',     strtotime( $date->current . ' -7 days' ) );
				$date->prevyear     = date( 'Y',     strtotime( $date->current . ' -7 days' ) );
				$date->weekstartday = date( 'j', strtotime( $date->weekdays[0] ) );
				$date->weekstartmonth = date( 'm', strtotime( $date->weekdays[0] ) );
				$date->weekstartmonthname = date( 'F', strtotime( $date->weekdays[0] ) );
				$date->weekstartyear = date( 'Y', strtotime( $date->weekdays[0] ) );
				$date->weekendday = date( 'j', strtotime( $date->weekdays[6] ) );
				$date->weekendmonth = date( 'm', strtotime( $date->weekdays[6] ) );
				$date->weekendmonthname = date( 'F', strtotime( $date->weekdays[6] ) );
				$date->weekendyear = date( 'Y', strtotime( $date->weekdays[6] ) );
				$date->nonworkingdays = $this->getNonWorkingDays( );
				foreach ($closed_days_array as $closed_days_of_month)
				{
					foreach ($closed_days_of_month as $closed_day)
					{
						if ($closed_day < $date->nextweekdate && $closed_day >= $date->current)
						{
							$instance = JTable::getInstance( 'EventInstances', 'CalendarTable' );
							$instance->eventinstance_date = $closed_day;
							$instance->isClosedDay = true;
							$list[] = $instance;
						}
					}
				}
				break;
			case "month":
			default:
				// used by month view
				$date->nextmonth = date( 'm', strtotime( $date->year . '-' . $date->month . ' +1 month' ) );
				$date->nextyear = date( 'Y', strtotime( $date->year . '-' . $date->month . ' +1 month' ) );
				$date->prevmonth = date( 'm', strtotime( $date->year . '-' . $date->month . ' -1 month' ) );
				$date->prevyear = date( 'Y', strtotime( $date->year . '-' . $date->month . ' -1 month' ) );
				$date->weekdays = array( 'Sunday' => 'SUN', 'Monday' => 'MON', 'Tuesday' => 'TUES', 'Wednesday' => 'WED', 'Thursday' => 'THU', 'Friday' => 'FRI', 'Saturday' => 'SAT' );
				$date->weekstart = 'SUN';
				$date->weekend = 'SAT';
				$date->numberofdays = date( 't', strtotime( $date->year . '-' . $date->month . '-01' ) );
				$date->monthstartday = date( 'l', strtotime( $date->year . '-' . $date->month . '-01' ) );
				$date->numberofweeks = 5;
				if ( $date->monthstartday == 'Friday' || $date->monthstartday == 'Saturday' )
				{
					$date->numberofweeks = 6;
				}
				 
				foreach ($closed_days_array as $closed_days_of_month)
				{
					foreach ($closed_days_of_month as $closed_day)
					{
						$instance = JTable::getInstance( 'EventInstances', 'CalendarTable' );
						$instance->eventinstance_date = $closed_day;
						$instance->isClosedDay = true;
						$list[] = $instance;
					}
				}
				break;
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
			 
			if (!empty($item->isClosedDay))
			{
				$days[$day]->isClosed = true;
				$days[$day]->text = JText::_( $config->get( 'non_working_day_text', 'Lab closed' ) );
			}
			else
			{
				$instance->event_full_image = $item->event_full_image;
				$item->image_src = $instance->getImage('src');
				$days[$day]->events[] = $item;
			}
		}

		ksort($days);
		$vars->date = $date;
		$vars->days = $days;

		$workingday = new JObject();
		$workday_text = $config->get( 'working_day_text' );
		$workday_url = $config->get( 'working_day_link' );
		$workday_url_label = $config->get( 'working_day_link_text' );
		if (!empty($workday_text))
		{
			$workingday->text = $workday_text;
			$workingday->url = $workday_url;
			$workingday->url_label = $workday_url_label;
		}
		$vars->workingday = $workingday;

		$module_html = $this->loadModule( JRequest::getVar('module_id') );
		$html = $this->getLayout( 'default', $vars, $view );

		$return = array();
		$return['content'] = $html;
		$return['module'] = $module_html;
		echo ( json_encode( $return ) );
	}
	
}

?>