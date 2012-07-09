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

class CalendarController extends DSCControllerSite 
{	
	public $default_view = 'month';
	public $message = "";
	public $messagetype = "";

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
		Calendar::load( 'CalendarHelperBase', 'helpers.base' );
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