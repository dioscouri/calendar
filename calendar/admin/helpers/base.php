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
defined('_JEXEC') or die('Restricted access');

if ( !class_exists('Calendar') ) { 
    JLoader::register( "Calendar", JPATH_ADMINISTRATOR.DS."components".DS."com_calendar".DS."defines.php" );
}

class CalendarHelperBase extends JObject
{   
	/**
	 * Returns a reference to the a Helper object, only creating it if it doesn't already exist
	 *
	 * @param type 		$type 	 The helper type to instantiate
	 * @param string 	$prefix	 A prefix for the helper class name. Optional.
	 * @return helper The Helper Object	 
	*/
	public static function getInstance( $type = 'Base', $prefix = 'CalendarHelper' )
	{
		static $instances;

		if (!isset( $instances )) {
			$instances = array();
		}

		$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
		
		// The Base helper is in _base.php, but it's named CalendarHelperBase
		if(strtolower($type) == 'Base'){
			$helperClass = $prefix.ucfirst($type);
			$type = '_Base';
		}
		
		$helperClass = $prefix.ucfirst($type);

		if (empty($instances[$helperClass]))
		{

			if (!class_exists( $helperClass ))
			{
				jimport('joomla.filesystem.path');
				if($path = JPath::find(CalendarHelperBase::addIncludePath(), strtolower($type).'.php'))
				{
					require_once $path;
	
					if (!class_exists( $helperClass ))
					{
						JError::raiseWarning( 0, 'Helper class ' . $helperClass . ' not found in file.' );
						return false;
					}
				}
				else
				{
					JError::raiseWarning( 0, 'Helper ' . $type . ' not supported. File not found.' );
					return false;
				}
			}

			$instance = new $helperClass();
			
			$instances[$helperClass] = & $instance;
		}

		return $instances[$helperClass];
	}
	
	/**
	 * Check if the path exists, and if not, tries to create it
	 * @param string $dir
	 * @param bool $create
	 */
	function checkDirectory($dir, $create = true)
	{
		$return = true;
		if (!$exists = &JFolder::exists( $dir ) ) 
		{
			if ($create)
			{
			    if (!$return = &JFolder::create( $dir ))
			    {
			        $this->setError( "Attempted to Create Dir But Failed" );
			    }
			}
                else
			{
			    $return = false;
			    $this->setError( "Dir Does Not Exist and Did Not Attempt to Create" );
			}
		}
		
        if (!is_writable($dir)) 
        {
            if (!$change = &JPath::setPermissions( $dir ))
            {
                $this->setError( "Changing Permissions on Dir Failed" );
            }
        }
        
		return $return;
	}
	
	/**
	 * Determines whether/not a user can view a record
	 *
	 * @param $id					id of commission
	 * @param $userid [optional] 	If absent, current logged-in user is used
	 * @return boolean
	 */
	function canView( $id, $userid=null )
	{
		$result = false;

		$user = JFactory::getUser( $userid );
		$userid = intval($user->id);

		// if the user is super admin, yes
			if ($user->gid == '25') { return true; }

		return $result;
	}
	
	/**
	 * Add a directory where CalendarHelper should search for helper types. You may
	 * either pass a string or an array of directories.
	 *
	 * @access	public
	 * @param	string	A path to search.
	 * @return	array	An array with directory elements
	 * @since 1.5
	 */
	function addIncludePath( $path=null )
	{
		static $calendarHelperPaths;

		if (!isset($calendarHelperPaths)) {
			$calendarHelperPaths = array( dirname( __FILE__ ) );
		}

		// just force path to array
		settype($calendarHelperPath, 'array');

		if (!empty( $calendarHelperPath ) && !in_array( $calendarHelperPath, $calendarHelperPaths ))
		{
			// loop through the path directories
			foreach ($calendarHelperPath as $dir)
			{
				// no surrounding spaces allowed!
				$dir = trim($dir);

				// add to the top of the search dirs
				// so that custom paths are searched before core paths
				array_unshift($calendarHelperPaths, $dir);
			}
		}
		return $calendarHelperPaths;
	}

    /**
     * Formats and converts a number according to currency rules
     * As of v0.5.0 is a wrapper
     * 
     * @param unknown_type $amount
     * @param unknown_type $currency
     * @return unknown_type
     */
    function currency($amount, $currency='', $options='')
    {
        Calendar::load('CalendarHelperCurrency', 'helpers.currency');
        $amount = CalendarHelperCurrency::_($amount, $currency, $options);
        return $amount;
    }
	
	/**
	 * Return a mesure with its unit
	 * @param float $amount
	 * @param string $type could be dimension or weight
	 */
	function measure($amount, $type='dimension')
	{
        // default to whatever is in config
            
        $config = Calendar::getInstance();
        $dim_unit = $config->get('dimensions_unit', 'cm');
        $weight_unit = $config->get('weight_unit', 'kg');
            
        if(strtolower($type) == 'dimension'){
        	return $amount.$dim_unit;
        } else{
        	return $amount.$weight_unit;
        }
		
	}

	/**
	 * Nicely format a number
	 * 
	 * @param $number
	 * @return unknown_type
	 */
    function number($number, $options='' )
	{
		$config = Calendar::getInstance();
        $options = (array) $options;
        
        $thousands = isset($options['thousands']) ? $options['thousands'] : $config->get('currency_thousands', ',');
        $decimal = isset($options['decimal']) ? $options['decimal'] : $config->get('currency_decimal', '.');
        $num_decimals = isset($options['num_decimals']) ? $options['num_decimals'] : $config->get('currency_num_decimals', '2');
		
		$return = number_format($number, $num_decimals, $decimal, $thousands);
		return $return;
	}

	/**
	 * Extracts a column from an array of arrays or objects
	 *
	 * @static
	 * @param	array	$array	The source array
	 * @param	string	$index	The index of the column or name of object property
	 * @return	array	Column of values from the source array
	 * @since	1.5
	 */
	function getColumn(&$array, $index)
	{
		$result = array();

		if (is_array($array))
		{
			foreach (@$array as $item)
			{
				if (is_array($item) && isset($item[$index]))
				{
					$result[] = $item[$index];
				}
					elseif (is_object($item) && isset($item->$index))
				{
					$result[] = $item->$index;
				}
			}
		}
		return $result;
	}

	/**
	 * Takes an elements object and converts it to an array that can be binded to a JTable object
	 *
	 * @param $elements is an array of objects with ->name and ->value properties, all posted from a form
	 * @return array[name] = value
	 */
	function elementsToArray( $elements )
	{
		$return = array();
        $names = array();
        $checked_items = array();
        if (empty($elements))
        {
            $elements = array();
        }
        
		foreach (@$elements as $element)
		{
			$isarray = false;
			$name = @$element->name;
			$value = @$element->value;
            $checked = @$element->checked;
            
			// if the name is an array,
			// attempt to recreate it 
			// using the array's name
			if (strpos($name, '['))
			{
				$isarray = true;
				$search = array( '[', ']' );
				$exploded = explode( '[', $name, '2' );
				$index = str_replace( $search, '', $exploded[0]);
				$name = str_replace( $search, '', $exploded[1]);
				if (!empty($index))
				{
                    // track the name of the array
	                if (!in_array($index, $names))
	                {
                        $names[] = $index;	
	                }

	                if (empty(${$index}))
	                {
	                    ${$index} = array(); 
	                }
	                
	                if (!empty($name))
	                {
	                	${$index}[$name] = $value;
	                }
	                else
	                {
                        ${$index}[] = $value;	
	                }
	                
				    if ($checked)
                    {
                    	if (empty($checked_items[$index]))
                    	{
                    		$checked_items[$index] = array();
                    	}
                        $checked_items[$index][] = $value; 
                    }
				}
			}
            elseif (!empty($name))
			{
				$return[$name] = $value;
			    if ($checked)
                {
                    if (empty($checked_items[$name]))
                    {
                        $checked_items[$name] = array();
                    }
                    $checked_items[$name] = $value; 
                }
			}
		}
		
		foreach ($names as $extra)
		{
			$return[$extra] = ${$extra};
		}
		
        $return['_checked'] = $checked_items;
        
		return $return;
	}

	/**
	 *
	 * @return unknown_type
	 */
	function setDateVariables( $curdate, $enddate=null, $period='daily', $interval_override='1' )
	{
		$database = JFactory::getDBO( );
		
		$return = new stdClass( );
		$return->thisdate = '';
		$return->nextdate = '';
		
		switch ( $period )
		{
		    case "day":
			case "daily":
				$thisdate = $curdate;
				$query = " SELECT DATE_ADD('" . $curdate . "', INTERVAL $interval_override DAY) ";
				$database->setQuery( $query );
				$nextdate = $database->loadResult( );
				$return->thisdate = $thisdate;
				$return->nextdate = $nextdate;
				break;
		    case "week":
			case "weekly":
				$thisdate = $curdate;
				$query = " SELECT DATE_ADD('" . $curdate . "', INTERVAL $interval_override WEEK) ";
				$database->setQuery( $query );
				$nextdate = $database->loadResult( );
				$return->thisdate = $thisdate;
				$return->nextdate = $nextdate;
				break;
			case "calendar":
				$start = getdate( strtotime( $curdate ) );
				
				// First period should be days between x day and the immediate Sunday
				if ( $start['wday'] < '1' )
				{
					$thisdate = $curdate;
					$query = " SELECT DATE_ADD( '" . $thisdate . "', INTERVAL $interval_override DAY ) ";
					$database->setQuery( $query );
					$nextdate = $database->loadResult( );
				}
				elseif ( $start['wday'] > '1' )
				{
					$interval = 8 - $start['wday'];
					$thisdate = $curdate;
					$query = " SELECT DATE_ADD( '" . $thisdate . "', INTERVAL {$interval} DAY ) ";
					$database->setQuery( $query );
					$nextdate = $database->loadResult( );
				}
				else
				{
					// then every period following should be Mon-Sun
					$thisdate = $curdate;
					$query = " SELECT DATE_ADD( '" . $thisdate . "', INTERVAL 7 DAY ) ";
					$database->setQuery( $query );
					$nextdate = $database->loadResult( );
				}
				
				if ( $nextdate > $enddate )
				{
					$query = " SELECT DATE_ADD( '" . $nextdate . "', INTERVAL $interval_override DAY ) ";
					$database->setQuery( $query );
					$nextdate = $database->loadResult( );
				}
				$return->thisdate = $thisdate;
				$return->nextdate = $nextdate;
				break;
			case "month":
			case "monthly":
				$start = getdate( strtotime( $curdate ) );
				$start_datetime = date( "Y-m-d", strtotime( $start['year'] . "-" . $start['mon'] . "-01" ) );
				$thisdate = $start_datetime;
				$query = " SELECT DATE_ADD( '" . $thisdate . "', INTERVAL $interval_override MONTH ) ";
				$database->setQuery( $query );
				$nextdate = $database->loadResult( );
				
				$return->thisdate = $thisdate;
				$return->nextdate = $nextdate;
				break;
			case "year":
			case "yearly":
				$start = getdate( strtotime( $curdate ) );
				$start_datetime = date( "Y-m-d", strtotime( $start['year'] . "-" . $start['mon'] . "-" . $start['mday'] ) );
				$thisdate = $start_datetime;
				$next_year = $start['year'] + $interval_override;
				$nextdate = date( "Y-m-d", strtotime( $next_year . "-" . $start['mon'] . "-" . $start['mday'] ) );
				
				$return->thisdate = $thisdate;
				$return->nextdate = $nextdate;
				break;
			default:
				break;
		}
		
		return $return;
	}

	/**
	 *
	 * @return unknown_type
	 */
	function getToday()
	{
		static $today;

		if (empty($today))
		{
			$config = JFactory::getConfig();
			$offset = $config->getValue('config.offset');
			$date = JFactory::getDate();
			$today = $date->toFormat( "%Y-%m-%d 00:00:00" );

			if ($offset > 0) {
				$command = 'DATE_ADD';
			} elseif ($offset < 0) {
				$command = 'DATE_SUB';
			} else {
				return $today;
			}

			$database = JFactory::getDBO();
			$query = "
				SELECT
					{$command}( '{$today}', INTERVAL {$offset} HOUR )
				";

			$database->setQuery( $query );
			$today = $database->loadResult();
		}
		return $today;
	}

	/**
	 *
	 * @param $date
	 * @return unknown_type
	 */
	function getOffsetDate( $date )
	{
		$config = JFactory::getConfig();
		$offset = $config->getValue('config.offset');
		if ($offset > 0) {
			$command = 'DATE_ADD';
		} elseif ($offset < 0) {
			$command = 'DATE_SUB';
		} else {
			$command = '';
		}
		if ($command)
		{
			$database = JFactory::getDBO();
			$query = "
				SELECT
					{$command}( '{$date}', INTERVAL {$offset} HOUR )
				";
			$database->setQuery( $query );
			$date = $database->loadResult();
		}
		return $date;
	}

	function getPeriodData( $start_datetime, $end_datetime, $period='daily', $select="tbl.*", $type='list' )
	{
		static $items;

		if (empty($items[$start_datetime][$end_datetime][$period][$select]))
		{
			$runningtotal = 0;
			$return = new stdClass();
			$database = JFactory::getDBO();

			// the following would be used if there were an additional filter in the Inputs
			$filter_where 	= "";
			$filter_select 	= "";
			$filter_join 	= "";
			$filter_typeid 	= "";
			if ($filter_typeid) {
				$filter_where 	= "";
				$filter_select 	= "";
				$filter_join 	= "";
			}

			$start_datetime = strval( htmlspecialchars( $start_datetime ) );
			$end_datetime = strval( htmlspecialchars( $end_datetime ) );

			$start 	= getdate( strtotime($start_datetime) );

			// start with first day of the period, corrected for offset
			$mainframe = JFactory::getApplication();
			$offset = $mainframe->getCfg( 'offset' );
			if ($offset > 0) {
				$command = 'DATE_ADD';
			} elseif ($offset < 0) {
				$command = 'DATE_SUB';
			} else {
				$command = '';
			}
			if ($command)
			{
				$database = JFactory::getDBO();
				$query = "
					SELECT
						{$command}( '{$start_datetime}', INTERVAL {$offset} HOUR )
					";

				$database->setQuery( $query );
				$curdate = $database->loadResult();

				$query = "
					SELECT
						{$command}( '{$end_datetime}', INTERVAL {$offset} HOUR )
					";

				$database->setQuery( $query );
				$enddate = $database->loadResult();
			}
				else
			{
				$curdate = $start_datetime;
				$enddate = $end_datetime;
			}

			// while the current date <= end_date
			// grab data for the period
			$num = 0;
			$result = array();
			while ($curdate <= $enddate)
			{
				// set working variables
					$variables = CalendarHelperBase::setDateVariables( $curdate, $enddate, $period );
					$thisdate = $variables->thisdate;
					$nextdate = $variables->nextdate;

				// grab all records
				// TODO Set the query here
					$query = new CalendarQuery();
					$query->select( $select );
					$rows = $this->selectPeriodData( $thisdate, $nextdate, $select, $type );
					$total = $this->selectPeriodData( $thisdate, $nextdate, "COUNT(*)", "result" );

				//store the value in an array
				$result[$num]['rows']		= $rows;
				$result[$num]['datedata'] 	= getdate( strtotime($thisdate) );
				$result[$num]['countdata']	= $total;
				$runningtotal 				= $runningtotal + $total;

				// increase curdate to the next value
				$curdate = $nextdate;
				$num++;

			} // end of the while loop

			$return->rows 		= $result;
			$return->total 		= $runningtotal;
			$items[$start_datetime][$end_datetime][$period][$select] = $return;
		}

		return $items[$start_datetime][$end_datetime][$period][$select];
	}
	
	/**
	 * includeJQueryUI function.
	 * 
	 * @access public
	 * @return void
	 */
	function includeJQueryUI()
	{
        self::includeJQuery();
	    JHTML::_('script', 'jquery-ui-1.7.2.min.js', 'media/com_calendar/js/');
        JHTML::_('stylesheet', 'jquery-ui.css', 'media/com_calendar/css/');
	}

	/**
	 * includeJQuery function.
	 * 
	 * @access public
	 * @return void
	 */
	function includeJQuery()
	{
	    JHTML::_('script', 'jquery-1.3.2.min.js', 'media/com_calendar/js/');
	}
	
	/**
	 * Include JQueryMultiFile script
	 */
	function includeMultiFile()
	{
		JHTML::_('script', 'Stickman.MultiUpload.js', 'media/com_calendar/js/');
		JHTML::_('stylesheet', 'Stickman.MultiUpload.css', 'media/com_calendar/css/');
	}
	
	/**
	 * Generate an html message for the checkout page
	 * used for validation errors
	 * 
	 * @param string message
	 * @return html message
	 */
	function generateMessage($msg, $include_li=true )
	{
		$html = '
		<dl id="system-message">
            <dt class="notice">'.JText::_( "Notice" ).'</dt>
            <dd class="notice message fade">
                <ul>';
				
                if ($include_li) {
                    $html .= "<li>".$msg."</li>";
                } else {
                    $html .= $msg;
                }
                
                $html .= "
                </ul>
            </dd>
        </dl>";
		
		return $html;
	}
	
    /**
     * Sets a json_encoded session variable to value
     * 
     * @param unknown_type $key
     * @param unknown_type $value
     * @return void
     */
    function setSessionVariable($key, $value)
    {
        $session =& JFactory::getSession();
        $session->set($key, json_encode($value));
    }
    
    /**
     * Gets json_encoded session variable
     * 
     * @param str $key
     * @return mixed
     */
    function getSessionVariable($key, $default=null)
    {
        $session =& JFactory::getSession();
        $sessionvalue = $default;
        if ($session->has($key))
        {
            $sessionvalue = $session->get($key);
            if (!empty($sessionvalue))
            {
                $sessionvalue = json_decode($sessionvalue); 
            }
        }
        return $sessionvalue;
    }
}