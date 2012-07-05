<?php
/**
* @version		0.1.0
* @package		Calendar
* @copyright	Copyright (C) 2011 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class Calendar extends DSC
{
    protected $_version 		= '1.0';
    protected $_build          = 'r100';
    protected $_versiontype    = 'community';
    protected $_copyrightyear 	= '2011';
    protected $_name 			= 'calendar';
    protected $_min_php		= '5.3';
	var $show_linkback = '1';
	var $amigosid = '';
	var $page_tooltip_dashboard_disabled = '0';
	var $page_tooltip_config_disabled = '0';
	var $page_tooltip_tools_disabled = '0';
	var $page_tooltip_accounts_disabled = '0';
	var $page_tooltip_payouts_disabled = '0';
	var $page_tooltip_logs_disabled = '0';
	var $page_tooltip_payments_disabled = '0';
	var $page_tooltip_commissions_disabled = '0';
	var $page_tooltip_users_view_disabled = '0';
	var $non_working_days = "Thursday";
	var $non_working_day_text = "Museum Closed";
	var $working_day_text = "Daily Museum Tours (accept Thurs) 11 am and 1 pm ";
	var $working_day_link_text = "learn more";
	var $working_day_link = "http://dioscouri.com";
	var $series_article_title = '1';
	var $enable_add_new = '0';
	var $item_id = '';
	//social bookmarking integration
    var $display_facebook_like				= '1';
    var $display_tweet						= '1';
    var $display_tweet_message				= 'Check this out!';
    var $disqus_api_key                     = '';
    var $disqus_forum_id                    = '';
    var $default_date                       = '';
    
	
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
		$query = "SELECT * FROM #__calendar_config";
		return $query;
	}

        /**
     * Get component config
     *
     * @acces   public
     * @return  object
     */
  public static function getInstance() {
        static $instance;

        if (!is_object($instance)) {
            $instance = new Calendar();
        }

        return $instance;
    }

	/**
	 * Get the URL to the folder containing all media assets
	 *
	 * @param string	$type	The type of URL to return, default 'media'
	 * @return 	string	URL
	 */
	public static function getURL( $type = 'media', $com='' )
	{
		$url = '';
		
		switch ( $type )
		{
			case 'media':
				$url = JURI::root( true ) . '/media/com_calendar/';
				break;
			case 'css':
				$url = JURI::root( true ) . '/media/com_calendar/css/';
				break;
			case 'images':
				$url = JURI::root( true ) . '/media/com_calendar/images/';
				break;
			case 'js':
				$url = JURI::root( true ) . '/media/com_calendar/js/';
				break;
			case 'categories_images':
				$url = JURI::root( true ) . '/images/com_calendar/categories/';
				break;
			case 'categories_thumbs':
				$url = JURI::root( true ) . '/images/com_calendar/categories/thumbs/';
				break;
			case 'events_images':
				$url = JURI::root( true ) . '/images/com_calendar/events/';
				break;
			case 'events_thumbs':
				$url = JURI::root( true ) . '/images/com_calendar/events/thumbs/';
				break;
			case 'eventinstances_images':
				$url = JURI::root( true ) . '/images/com_calendar/eventinstances/';
				break;
			case 'eventinstances_thumbs':
				$url = JURI::root( true ) . '/images/com_calendar/eventinstances/thumbs/';
				break;
			case 'series_images':
				$url = JURI::root( true ) . '/images/com_calendar/series/';
				break;
			case 'series_thumbs':
				$url = JURI::root( true ) . '/images/com_calendar/series/thumbs/';
				break;
		}
		
		return $url;
	}

	/**
	 * Get the path to the folder containing all media assets
	 *
	 * @param 	string	$type	The type of path to return, default 'media'
	 * @return 	string	Path
	 */
	public static function getPath( $type = 'media', $com='' )
	{
		$path = '';
		
		switch ( $type )
		{
			case 'media':
				$path = JPATH_SITE . DS . 'media' . DS . 'com_calendar';
				break;
			case 'css':
				$path = JPATH_SITE . DS . 'media' . DS . 'com_calendar' . DS . 'css';
				break;
			case 'images':
				$path = JPATH_SITE . DS . 'media' . DS . 'com_calendar' . DS . 'images';
				break;
			case 'js':
				$path = JPATH_SITE . DS . 'media' . DS . 'com_calendar' . DS . 'js';
				break;
			case 'categories_images':
				$path = JPATH_SITE . DS . 'images' . DS . 'com_calendar' . DS . 'categories';
				break;
			case 'categories_thumbs':
				$path = JPATH_SITE . DS . 'images' . DS . 'com_calendar' . DS . 'categories' . DS . 'thumbs';
				break;
			case 'events_images':
				$path = JPATH_SITE . DS . 'images' . DS . 'com_calendar' . DS . 'events';
				break;
			case 'events_thumbs':
				$path = JPATH_SITE . DS . 'images' . DS . 'com_calendar' . DS . 'events' . DS . 'thumbs';
				break;
			case 'eventinstances_images':
				$path = JPATH_SITE . DS . 'images' . DS . 'com_calendar' . DS . 'eventinstances';
				break;
			case 'eventinstances_thumbs':
				$path = JPATH_SITE . DS . 'images' . DS . 'com_calendar' . DS . 'eventinstances' . DS . 'thumbs';
				break;
			case 'series_images':
				$path = JPATH_SITE . DS . 'images' . DS . 'com_calendar' . DS . 'series';
				break;
			case 'series_thumbs':
				$path = JPATH_SITE . DS . 'images' . DS . 'com_calendar' . DS . 'series' . DS . 'thumbs';
				break;
		}
		
		return $path;
	}



/**
	 * Intelligently loads instances of classes in framework
	 *
	 * Usage: $object = Calendar::getClass( 'CalendarHelperCarts', 'helpers.carts' );
	 * Usage: $suffix = Calendar::getClass( 'CalendarHelperCarts', 'helpers.carts' )->getSuffix();
	 * Usage: $categories = Calendar::getClass( 'CalendarSelect', 'select' )->category( $selected );
	 *
	 * @param string $classname   The class name
	 * @param string $filepath    The filepath ( dot notation )
	 * @param array  $options
	 * @return object of requested class (if possible), else a new JObject
	 */
	public static function getClass( $classname, $filepath='controller', $options=array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_calendar' )  )
	{
	    return parent::getClass( $classname, $filepath, $options  );
	}
	
	/**
	* Method to intelligently load class files in the framework
	*
	* @param string $classname   The class name
	* @param string $filepath    The filepath ( dot notation )
	* @param array  $options
	* @return boolean
	*/
	public static function load( $classname, $filepath='controller', $options=array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_calendar' ) )
	{
	    return parent::load( $classname, $filepath, $options  );
	}
	
}
?>
