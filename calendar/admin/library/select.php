<?php
/**
* @package		Calendar
* @copyright	Copyright (C) 2011 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'html'.DS.'html'.DS.'select.php' );

class CalendarSelect extends JHTMLSelect
{
	/**
	* Generates a yes/no radio list
	*
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the radio list
	*/
	public static function booleans( $selected, $name = 'filter_enabled', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title='Select State', $yes = 'Enabled', $no = 'Disabled' )
	{
	    $list = array();
		if($allowAny) {
			$list[] =  self::option('', "- ".JText::_( $title )." -" );
		}

		$list[] = JHTML::_('select.option',  '0', JText::_( $no ) );
		$list[] = JHTML::_('select.option',  '1', JText::_( $yes ) );

		return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
	}

	/**
	* Generates range list
	*
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the radio list
	*/
	public static function range( $selected, $name = 'filter_range', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title = 'Select Range' )
	{
	    $list = array();
		if($allowAny) {
			$list[] =  self::option('', "- ".JText::_( $title )." -" );
		}

		$list[] = JHTML::_('select.option',  'today', JText::_( "Today" ) );
		$list[] = JHTML::_('select.option',  'yesterday', JText::_( "Yesterday" ) );
		$list[] = JHTML::_('select.option',  'last_seven', JText::_( "Last Seven Days" ) );
		$list[] = JHTML::_('select.option',  'last_thirty', JText::_( "Last Thirty Days" ) );
		$list[] = JHTML::_('select.option',  'ytd', JText::_( "Year to Date" ) );

		return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
	}
	
    /**
    * Generates range list
    *
    * @param string The value of the HTML name attribute
    * @param string Additional HTML attributes for the <select> tag
    * @param mixed The key that is selected
    * @returns string HTML for the radio list
    */
    public static function reportrange( $selected, $name = 'filter_range', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title = 'Select Range' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -" );
        }

        $list[] = JHTML::_('select.option',  'custom', JText::_( "Custom" ) );
        $list[] = JHTML::_('select.option',  'yesterday', JText::_( "Yesterday" ) );
        $list[] = JHTML::_('select.option',  'last_week', JText::_( "Last Week" ) );
        $list[] = JHTML::_('select.option',  'last_month', JText::_( "Last Month" ) );
        $list[] = JHTML::_('select.option',  'ytd', JText::_( "Year to Date" ) );
        $list[] = JHTML::_('select.option',  'all', JText::_( "All Time" ) );
        
        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }

    /**
    * Generates a created/modified select list
    *
    * @param string The value of the HTML name attribute
    * @param string Additional HTML attributes for the <select> tag
    * @param mixed The key that is selected
    * @returns string HTML for the radio list
    */
    public static function datetype( $selected, $name = 'filter_datetype', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title='Select Type' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -" );
        }

        $list[] = JHTML::_('select.option',  'created', JText::_( "Created" ) );
        $list[] = JHTML::_('select.option',  'modified', JText::_( "Modified" ) );
        $list[] = JHTML::_('select.option',  'shipped', JText::_( "Shipped" ) );
        
        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    /**
    * Generates a Period Unit Select List for recurring payments
    *
    * @param string The value of the HTML name attribute
    * @param string Additional HTML attributes for the <select> tag
    * @param mixed The key that is selected
    * @returns string HTML for the radio list
    */
    public static function periodUnit( $selected, $name = 'filter_periodunit', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title='Select Period Unit' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -" );
        }

        $list[] = JHTML::_('select.option',  'D', JText::_( "Day" ) );
        $list[] = JHTML::_('select.option',  'W', JText::_( "Week" ) );
        $list[] = JHTML::_('select.option',  'M', JText::_( "Month" ) );
        $list[] = JHTML::_('select.option',  'Y', JText::_( "Year" ) );
        
        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    /**
    * Generates a Display Type List for events
    *
    * @param string The value of the HTML name attribute
    * @param string Additional HTML attributes for the <select> tag
    * @param mixed The key that is selected
    * @returns string HTML for the radio list
    */
    public static function displaytype( $selected, $name = 'event_display_type', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowAny = false, $title = 'Select Display Type' )
    {
    	$list = array( );
    	if ( $allowAny )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title ) . " -" );
    	}
    
    	$list[] = JHTML::_( 'select.option', 'date', JText::_( "Display each date" ) );
    	$list[] = JHTML::_( 'select.option', 'range', JText::_( "Display events as a range" ) );
    
    	return self::genericlist( $list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    /**
     *
     * @param unknown_type $selected
     * @param unknown_type $name
     * @return unknown_type
     */
    public static function article_element( $selected, $name = 'article_id' )
    {
    	$return = array( );
    	$model = JModel::getInstance( 'ElementArticle', 'CalendarModel' );
    	$return['select'] = $model->fetchElement( $name, $selected );
    	$return['clear'] = $model->clearElement( $name, '0' );
    	return $return;
    }
    
    /**
     *
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @return unknown_type
     */
    public static function category( $selected, $name = 'filter_parentid', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowAny = false, $allowNone = true, $title = 'Select Category', $title_none = 'No Parent', $enabled = null )
    {
    	$list = array( );
    
    	if ( $allowAny )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title ) . " -" );
    	}
    
    	if ( $allowNone )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title_none ) . " -" );
    	}
    
    	JTable::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'tables' );
    	JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
    	$model = JModel::getInstance( 'Categories', 'CalendarModel' );
    	$model->setState( 'order', 'tbl.ordering' );
    	if ( intval( $enabled ) == '1' )
    	{
    		$model->setState( 'filter_enabled', '1' );
    	}
    	else
    	{
    		$items = $model->getList( );
    	}
    
    	foreach ( @$items as $item )
    	{
    		$list[] = self::option( $item->category_id, JText::_( $item->category_name ) );
    	}
    	return self::genericlist( $list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    /**
     *
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @return unknown_type
     */
    public static function secondcategory( $selected, $name = 'filter_parentid', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowAny = false, $allowNone = true, $title = 'Select Category', $title_none = 'No Parent', $enabled = null )
    {
    	$list = array( );
    
    	if ( $allowAny )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title ) . " -" );
    	}
    
    	if ( $allowNone )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title_none ) . " -" );
    	}
    
    	JTable::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'tables' );
    	JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
    	$model = JModel::getInstance( 'SecondCategories', 'CalendarModel' );
    	$model->setState( 'order', 'tbl.ordering' );
    	if ( intval( $enabled ) == '1' )
    	{
    		$model->setState( 'filter_enabled', '1' );
    	}
    	else
    	{
    		$items = $model->getList( );
    	}
    
    	foreach ( @$items as $item )
    	{
    		$list[] = self::option( $item->category_id, JText::_( $item->category_name ) );
    	}
    	return self::genericlist( $list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    /**
     *
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @return unknown_type
     */
    public static function series( $selected, $name = 'filter_parentid', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowNone = true, $title_none = 'Select Series', $enabled = null )
    {
    	// Build list
    	$list = array( );
    
    	if ( $allowNone )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title_none ) . " -", 'series_id', 'series_name' );
    	}
    
    	$model = JModel::getInstance( 'Series', 'CalendarModel' );
    	$items = $model->getList( );
    
    	foreach ( @$items as $item )
    	{
    		$list[] = self::option( $item->series_id, JText::_( $item->series_name ), 'series_id', 'series_name' );
    	}
    
    	return self::genericlist( $list, $name, $attribs, 'series_id', 'series_name', $selected, $idtag );
    }
    
    /**
     *
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @return unknown_type
     */
    public static function event( $selected, $name = 'event_id', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowNone = true, $title_none = 'Select Event', $enabled = null )
    {
    	// Build list
    	$list = array( );
    
    	if ( $allowNone )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title_none ) . " -", 'event_id', 'event_short_title' );
    	}
    
    	$model = JModel::getInstance( 'Events', 'CalendarModel' );
    	$items = $model->getList( );
    
    	foreach ( @$items as $item )
    	{
    		$list[] = self::option( $item->event_id, JText::_( $item->event_short_title ), 'event_id', 'event_short_title' );
    	}
    
    	return self::genericlist( $list, $name, $attribs, 'event_id', 'event_short_title', $selected, $idtag );
    }
    
    /**
     *
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @return unknown_type
     */
    public static function venue( $selected, $name = 'venue_id', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowNone = true, $title_none = 'Select Venue', $enabled = null )
    {
    	// Build list
    	$list = array( );
    
    	if ( $allowNone )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title_none ) . " -", 'venue_id', 'venue_name' );
    	}
    
    	$model = JModel::getInstance( 'Venues', 'CalendarModel' );
    	$items = $model->getList( );
    
    	foreach ( @$items as $item )
    	{
    		$list[] = self::option( $item->venue_id, JText::_( $item->venue_name ), 'venue_id', 'venue_name' );
    	}
    
    	return self::genericlist( $list, $name, $attribs, 'venue_id', 'venue_name', $selected, $idtag );
    }
    
    /**
     *
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @return unknown_type
     */
    public static function actionbutton( $selected, $name = 'actionbutton_id', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowNone = true, $title_none = 'Select Action Button', $enabled = null )
    {
    	// Build list
    	$list = array( );
    
    	if ( $allowNone )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title_none ) . " -", 'actionbutton_id', 'actionbutton_name' );
    	}
    
    	$model = JModel::getInstance( 'Actionbuttons', 'CalendarModel' );
    	$items = $model->getList( );
    
    	foreach ( @$items as $item )
    	{
    		$list[] = self::option( $item->actionbutton_id, JText::_( $item->actionbutton_name ), 'actionbutton_id', 'actionbutton_name' );
    	}
    
    	return self::genericlist( $list, $name, $attribs, 'actionbutton_id', 'actionbutton_name', $selected, $idtag );
    }
    
    /**
     *
     * Enter description here ...
     * @param unknown_type $selected
     * @param unknown_type $name
     * @param unknown_type $attribs
     * @param unknown_type $idtag
     * @param unknown_type $allowAny
     * @param unknown_type $title
     * @return return_type
     */
    public static function repeats( $selected, $name = 'filter_repeats', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowAny = false, $title = 'Select Repeat Period' )
    {
    	$list = array( );
    	if ( $allowAny )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title ) . " -" );
    	}
    
    	$list[] = JHTML::_( 'select.option', 'daily', JText::_( "Daily" ) );
    	//$list[] = JHTML::_( 'select.option', 'weekdays', JText::_( "Weekdays" ) );
    	//$list[] = JHTML::_( 'select.option', 'mon_wed_fri', JText::_( "mon_wed_fri" ) );
    	//$list[] = JHTML::_( 'select.option', 'tue_thur', JText::_( "tue_thur" ) );
    	$list[] = JHTML::_( 'select.option', 'weekly', JText::_( "Weekly" ) );
    	$list[] = JHTML::_( 'select.option', 'monthly', JText::_( "Monthly" ) );
    	$list[] = JHTML::_( 'select.option', 'yearly', JText::_( "Yearly" ) );
    
    	return self::genericlist( $list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    /**
     *
     * Enter description here ...
     * @param unknown_type $selected
     * @param unknown_type $name
     * @param unknown_type $attribs
     * @param unknown_type $idtag
     * @param unknown_type $allowAny
     * @param unknown_type $title
     * @return return_type
     */
    public static function defaultview( $selected, $name = 'default_view', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowAny = false, $title = 'Select Default View' )
    {
    	$list = array( );
    	if ( $allowAny )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title ) . " -" );
    	}
    
    	$list[] = JHTML::_( 'select.option', 'day', JText::_( "Day" ) );
    	$list[] = JHTML::_( 'select.option', 'three', JText::_( "Three Days" ) );
    	$list[] = JHTML::_( 'select.option', 'week', JText::_( "Week" ) );
    	$list[] = JHTML::_( 'select.option', 'month', JText::_( "Month" ) );
		$list[] = JHTML::_( 'select.option', 'calendar', JText::_( "Calendar Month" ) );
    
    	return self::genericlist( $list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    /**
     * Generates a yes/no radio list
     *
     * @param string The value of the HTML name attribute
     * @param string Additional HTML attributes for the <select> tag
     * @param mixed The key that is selected
     * @returns string HTML for the radio list
     */
    public static function categorytype( $selected, $name = 'filter_categorytype', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowAny = false, $title = 'Select Category Type' )
    {
    	$list = array( );
    	if ( $allowAny )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title ) . " -" );
    	}
    
    	$list[] = JHTML::_( 'select.option', 'primary', JText::_( "Primary" ) );
    	$list[] = JHTML::_( 'select.option', 'secondary', JText::_( "Secondary" ) );
    
    	return self::genericlist( $list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    /**
     *
     * Enter description here ...
     * @param unknown_type $selected
     * @param unknown_type $name
     * @param unknown_type $attribs
     * @param unknown_type $idtag
     * @param unknown_type $allowAny
     * @param unknown_type $title
     * @return return_type
     */
    public static function view( $selected, $name = 'current_view', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowAny = false, $title = 'Select Default View' )
    {
    	$list = array( );
    	if ( $allowAny )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title ) . " -" );
    	}
    
    	$list[] = JHTML::_( 'select.option', 'month', JText::_( "Month view" ) );
    	$list[] = JHTML::_( 'select.option', 'week', JText::_( "Week view" ) );
    	$list[] = JHTML::_( 'select.option', 'day', JText::_( "Day view" ) );
    
    	return self::genericlist( $list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    public static function categoryclass( $selected, $name = 'filter_class', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowAny = false, $title = 'Select Class' )
    {
    	$list = array( );
    	if ( $allowAny )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title ) . " -" );
    	}
    
    	$list[] = JHTML::_( 'select.option', 'cat1', "Cat1 - Magenta" );
    	$list[] = JHTML::_( 'select.option', 'cat2', "Cat1 - Green" );
    	$list[] = JHTML::_( 'select.option', 'cat3', "Cat1 - Yellow" );
    	$list[] = JHTML::_( 'select.option', 'cat4', "Cat1 - Red" );
    	$list[] = JHTML::_( 'select.option', 'cat5', "Cat1 - Blue" );
    	$list[] = JHTML::_( 'select.option', 'cat6', "Cat1 - Purple" );
    
    	return self::genericlist( $list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }

    /**
    *
    * @param $selected
    * @param $name
    * @param $attribs
    * @param $idtag
    * @param $allowAny
    * @return unknown_type
    */
    public static function type( $selected, $name = 'type_id', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowNone = false, $title_none = 'Select Type', $enabled = null )
    {
        // Build list
        $list = array( );
    
        if ( $allowNone )
        {
            $list[] = self::option( '', "- " . JText::_( $title_none ) . " -", 'venue_id', 'venue_name' );
        }
    
        $model = JModel::getInstance( 'Types', 'CalendarModel' );
        $items = $model->getList( );
    
        foreach ( @$items as $item )
        {
            $list[] = self::option( $item->type_id, JText::_( $item->type_name ) );
        }
    
        return self::genericlist( $list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
}
