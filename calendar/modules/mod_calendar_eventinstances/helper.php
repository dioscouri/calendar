<?php
/**
 * @package Featured Items
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted Access' );

jimport( 'joomla.application.component.model' );

class modCalendarUpcomingHelper extends JObject
{
	/**
	 * Sets the modules params as a property of the object
	 * @param unknown_type $params
	 * @return unknown_type
	 */
	function __construct( $params )
	{
		$this->params = $params;
	}
	
	/**
	 * Gets the various db information to sucessfully display item
	 * @return unknown_type
	 */
	function getItems()
	{
	    $list = array();
	    
	    $ids = explode(',', $this->params->get('ids') );
	    foreach( $ids as $id )
	    {
	        $id = trim($id);
	    }
	    
        JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_featureditems/models/' );
        $model = JModel::getInstance( 'Items', 'FeaturedItemsModel' );
        $model->setState( 'limit', $this->params->get('limit') );
        if (!empty($ids))
        {
            $model->setState( 'filter_ids', $ids );
        }
        
        if ($list = $model->getList())
        {
            foreach ($list as $list_item)
            {
                $item = new JObject();
                $item->label = $list_item->item_label;
                $item->short_title = $list_item->item_short_title;
                $item->long_title = $list_item->item_long_title;
                $item->image_src = $list_item->item_image_url;
                $item->url = $list_item->item_url;
                $item->url_target = $list_item->item_url_target;
                $item->publish_up = $list_item->publish_up;
                $item->publish_down = $list_item->publish_down;
                $item->item_type = $list_item->item_type;
                $item->content = ''; 
                $this->setItemProperties( $item, $list_item );
                
                $list[] = $item;
            }
        }
        		
		return $list;
	}
    
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $item
	 * @return return_type
	 */
	function setItemProperties( &$item, $list_item )
	{
        switch ($item->item_type)
        {
            case "mediamanager":
                $this->setMediamanagerProperties( $item, $list_item );
                break;
            case "calendar":
                $this->setCalendarProperties( $item, $list_item );
                break;
            default:
                $item->content = "<p>". $item->short_title . "</p>";
                break;
        }
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $item
	 * @return return_type
	 */
	function setMediamanagerProperties( &$item, $list_item )
	{
        if ( !class_exists('MediaManager') ) {
            JLoader::register( "MediaManager", JPATH_ADMINISTRATOR.DS."components".DS."com_mediamanager".DS."defines.php" );
        }
        JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_mediamanager/models' );
        $model = JModel::getInstance( 'Media', 'MediaManagerModel' );
        $model->setId( $list_item->fk_id );
        $media = $model->getItem();
        $plugin_content = $this->getPlugin( $media );
        
        if (empty($item->short_title))
        {
            $item->short_title = $media->media_title;
        }
        
	    if (empty($item->long_title))
        {
            $item->long_title = $media->media_title;
        }
        
		if (empty($item->image_src))
        {
            $item->image_src = $media->media_image_remote;
        }
        
		if (empty($item->url))
        {
            $item->url = "index.php?option=com_com_mediamanager&view=media&task=view&id=" . $media->media_id;
            $item->url = JRoute::_( $item->url ); 
        }
        
		if (empty($item->content))
        {
            $content = !empty($media->media_description) ? $media->media_description : $media->media_title;
            $item->content = "<p>". $content . "</p>";
        }
        
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $item
	 * @return return_type
	 */
	function setCalendarProperties( &$item, $list_item )
	{
	    if ( !class_exists( 'Calendar' ) ) {
	        JLoader::register( "Calendar", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_calendar" . DS . "defines.php" );
	    }
	    Calendar::load( 'Calendar', 'defines' );
	    JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
        JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );

        $row = JTable::getInstance( 'EventInstances', 'CalendarTable' );
		$row->load( $list_item->fk_id );
		$row->bindObjects();
        
	    if (empty($item->short_title))
        {
            $item->short_title = $row->event_short_title;
        }
        
	    if (empty($item->long_title))
        {
            $item->long_title = $row->event_long_title;
        }
        
		if (empty($item->image_src))
        {
            $item->image_src = $row->image_src;
        }
        
		if (empty($item->url))
        {
            $item->url = $row->link_view;
            $item_id = Calendar::getInstance()->get('item_id');
            $item->url .= "&Itemid=" . $item_id;
            $item->url = JRoute::_( $item->url ); 
        }
        
		if (empty($item->content))
        {
            $html = '<p class="date cat ' . $row->primary_category_class . '">';
                $html .= date('M j', strtotime($row->eventinstance_date) ) . ' ' . JText::_( "at" ) . ' ';  
                $html .= (date('i', strtotime( $row->eventinstance_start_time ) ) == '00') ? date( 'g a', strtotime( $row->eventinstance_start_time ) ) : date( 'g:i a', strtotime( $row->eventinstance_start_time ) );
            $html .= '</p>
            <p>'. $row->event_short_title . '</p>
            ';
            $item->content = $html;
        }
	}
	
	/**
	 * Get the neccesary logic for the specific plugin ...
	 * @param unknown_type $item
	 * @return unknown_type
	 */
	function getPlugin( $media_item, $params = array( ) )
	{
		// if empty media_item->media_id or media_item->media_type, fail
		if ( empty( $media_item->media_id ) || empty( $media_item->media_type ) )
		{
			echo JText::_( 'NOT VALID MEDIA ITEM' );
			return;
		}
		//get the model 
		$hmodel = JModel::getInstance( 'handlers', 'MediaManagerModel' );
		
		$handler = $hmodel->getTable( );
		$handler->load( array( 'element' => $media_item->media_type ) );
		
		if ( empty( $handler->id ) )
		{
			echo JText::_( 'NOT VALID HANDLER' );
			return;
		}
		
		if ( empty( $handler->published ) && !empty( $handler->id ) )
		{
			$handler->published = 1;
			if ( $handler->store( ) )
			{
				// do we need to redirect?
				$uri = JURI::getInstance( );
				$redirect = $uri->toString( );
				$redirect = JRoute::_( $redirect, false );
				$this->setRedirect( $redirect );
				return;
			}
		}
		
		$import = JPluginHelper::importPlugin( 'mediamanager', $handler->element );
		
		$html = '';
		$dispatcher = JDispatcher::getInstance( );
		$results = $dispatcher->trigger( 'onDisplayMediaItem', array( $handler, $media_item ) );
		for ( $i = 0; $i < count( $results ); $i++ )
		{
			$html .= $results[$i];
		}
		
		$vars = new JObject( );
		$vars->row = $media_item;
		$vars->params = $params;
		$vars->handler_html = $html;
		
		return $vars;
	}
}
?>