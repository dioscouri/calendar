<?php
/**
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_calendar'.DS.'defines.php')) 
{
    // Check the registry to see if our Calendar class has been overridden
    if ( !class_exists('Calendar') ) 
        JLoader::register( "Calendar", JPATH_ADMINISTRATOR.DS."components".DS."com_calendar".DS."defines.php" );
	
     Calendar::load( 'CalendarPluginBase', 'library.plugins._base' );

    class plgContentCalendar_EventInstance extends CalendarPluginBase
    {
    	/**
    	 * @var $_element  string  Should always correspond with the plugin's filename, 
    	 *                         forcing it to be unique 
    	 */
        var $_element    = 'calendar_eventinstance';
        
    	function plgContentCalendar_EventInstance(& $subject, $config) 
    	{    		 
    		parent::__construct($subject, $config);
    		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
    		$this->loadLanguage('com_calendar');
    	}
    	
     	/**
         * Checks the extension is installed
         * 
         * @return boolean
         */
        function isInstalled()
        {
            $success = false;
            
            jimport('joomla.filesystem.file');
            if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_calendar'.DS.'defines.php')) 
            {
                $success = true;
                // Check the registry to see if our Calendar class has been overridden
                if ( !class_exists('Calendar') ) 
                    JLoader::register( "Calendar", JPATH_ADMINISTRATOR.DS."components".DS."com_calendar".DS."defines.php" );
            }
            return $success;
        }
    	
    	/**
         * Search for the tag and replace it with the media view {calendar}
         * 
         * @param $article
         * @param $params
         * @param $limitstart
         */
       	function onPrepareContent( &$row, &$params, $page=0 )
       	{
       		if( !$this->isInstalled() )
       			return true;
      		
    	   	// simple performance check to determine whether bot should process further
    		if ( JString::strpos( $row->text, 'calendar_eventinstance' ) === false ) {
    			return true;
    		}
    	
    		// Get plugin info
    		$plugin =& JPluginHelper::getPlugin('content', 'calendar_eventinstance');
    	
    	 	// expression to search for
    	 	$regex = '/{calendar_eventinstance\s*.*?}/i';
    	
    	 	$pluginParams = new JParameter( $plugin->params );
            $this->pluginParams = $pluginParams;
            
    		// check whether plugin has been unpublished
    		if ( !$pluginParams->get( 'enabled', 1 ) ) {
    			$row->text = preg_replace( $regex, '', $row->text );
    			return true;
    		}
    	
    	 	// find all instances of plugin and put in $matches
    		preg_match_all( $regex, $row->text, $matches );
   
    		// Number of plugin instances
    	 	$count = count( $matches[0] );
    	
    	 	// plugin only processes if there are any instances of the plugin in the text
    	 	if ( $count ) {
    	 		foreach($matches as $match)
    	 		{
    	 			$this->processTags( $row, $matches, $count, $regex );
    	 		}
    		}
       	}
        
        /**
         * Processes the various calendar plugin instances
         * 
         * @param $row
         * @param $matches
         * @param $count
         * @param $regex
         * @return unknown_type
         */
        function processTags( $row, $matches, $count, $regex )
        {
            // TODO This would load the appropriate plugin for the media item,
            // or, if possible, just load the front-end Media view

            for ( $i=0; $i < $count; $i++ )
            {
                $load = str_replace( 'calendar_eventinstance', '', $matches[0][$i] );
                $load = str_replace( '{', '', $load );
                $load = str_replace( '}', '', $load );
                $load = trim( $load );
        
                $item    = $this->processTag( $load );
                $row->text  = str_replace($matches[0][$i], $item, $row->text );
            }
        
            // removes tags without matching
            $row->text = preg_replace( $regex, '', $row->text );
        }
        
        /**
         * Shows a single media item based on params (if present)
         * @param $load
         * @return unknown_type
         */
        function processTag( $load )
        {
            $inline_params = explode(" ", $load);
            
            $params = $this->get('params');
            $params = $params->toArray();
            
            $params['attributes'] = array();
            // Merge plugin parameters with tag parameters, overwriting wherever necessary
            foreach( $inline_params as $p )
            {
                $data = explode("=", $p);
                $k = $data[0];
                $v = $data[1];
                
                $params[$k] = $v;
            }
            
            if ( !array_key_exists('id', $params) && !array_key_exists('ids', $params) && !array_key_exists('use_upcoming', $params))
            {
                return;
            }

            JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
            JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
            
            if (!empty($params['id']))
            {
        		$row = JTable::getInstance( 'EventInstances', 'CalendarTable' );
        		$row->load( $params['id'] );
        		$row->bindObjects();
                        
                if (empty($row->event_published) || empty($row->eventinstance_id))
                {
                    return;
                }
                
                if (empty($params['layout']))
                {
                    $params['layout'] = 'large';
                }
                
                return $this->view( $row, $params );
            }
            
            if (!empty($params['ids']))
            {
                $rows = array();
                $ids = explode(',', $params['ids']);
                foreach ($ids as $id)
                {
            		$row = JTable::getInstance( 'EventInstances', 'CalendarTable' );
            		$row->load( $id );
            		$row->bindObjects();
                            
                    if (empty($row->event_published) || empty($row->eventinstance_id))
                    {
                        continue;
                    }
                    
                    $rows[] = $row;                        
                }
                
                if (empty($params['layout']))
                {
                    $params['layout'] = 'small';
                }
                
                return $this->viewMany( $rows, $params );
            }
            
            if (!empty($params['use_upcoming']))
            {
                $date = JFactory::getDate();
                $ids = array();
                // $ids = array of ids from the queue
                
                $model = JModel::getInstance( 'Eventinstances', 'CalendarModel' );
                $model->setState( 'limit', $params['limit'] );
                if (!empty($params['start']))
                {
                    $model->setState( 'limitstart', $params['start'] );
                }                
                $model->setState( 'filter_upcoming_enabled', '1' );
                $model->setState( 'filter_date_from', $date->toFormat( '%Y-%m-%d' ) );
                $model->setState( 'order', 'tbl.eventinstance_date' );
                $model->setState( 'direction', 'ASC' );
            	$query = $model->getQuery( );
            	$query->order( 'tbl.eventinstance_start_time' );
            	$model->setQuery( $query );
            		
                if ($list = $model->getList())
                {
                    foreach ($list as $li)
                    {
                        $ids[] = $li->eventinstance_id;
                    } 
                }
                $rows = array();

                foreach ($ids as $id)
                {
            		$row = JTable::getInstance( 'EventInstances', 'CalendarTable' );
            		$row->load( $id );
            		$row->bindObjects();
                            
                    if (empty($row->event_published) || empty($row->eventinstance_id))
                    {
                        continue;
                    }
                    
                    $rows[] = $row;                        
                }
                
                if (empty($params['layout']))
                {
                    $params['layout'] = 'small';
                }
                
                if (count($rows) > 1)
                {
                    return $this->viewMany( $rows, $params );
                }
                return $this->view( $rows[0], $params );
            }
           
        }
        
        /**
         * (non-PHPdoc)
         * @see calendar/calendar/admin/library/plugins/CalendarPluginBase::_getLayout()
         */
        function _getLayout($layout, $vars = false, $plugin = '', $group = 'content')
        {
            return parent::_getLayout($layout, $vars, $plugin, $group);
        }

        /**
         * 
         * Enter description here ...
         * @param unknown_type $name
         * @return unknown_type
         */
        function getModel( $name )
        {
            JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
            $model = JModel::getInstance( $name, 'CalendarModel' );
            return $model;
        }
        
        /**
         * 
         * Enter description here ...
         * @param $row
         * @param $params
         * @return unknown_type
         */
        function view( $row, $params=array() )
        {
            $vars = new JObject();
            $vars->large_width = '720';
            $vars->large_height = '405';
            $vars->medium_width = '359';
            $vars->medium_height = '203';
            $vars->small_width = '239';
            $vars->small_height = '134';
            $vars->row = $row;
            $vars->item_id = $this->pluginParams->get('item_id');
            return $this->_getLayout( $params['layout'], $vars );
            
        }
        
        function viewMany( $rows, $params=array() )
        {
            $vars = new JObject();
            $vars->large_width = '720';
            $vars->large_height = '405';
            $vars->medium_width = '359';
            $vars->medium_height = '203';
            $vars->small_width = '239';
            $vars->small_height = '134';
            $vars->rows = $rows;
            $vars->item_id = $this->pluginParams->get('item_id');
            return $this->_getLayout( "many_".$params['layout'], $vars );
            
        }
    }
}