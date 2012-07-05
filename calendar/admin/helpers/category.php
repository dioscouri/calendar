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

Calendar::load( 'CalendarHelperBase', 'helpers._base' );
jimport( 'joomla.filesystem.file' );

class CalendarHelperCategory extends CalendarHelperBase
{
	static $categories = array( );
	
	/**
	 * Gets the list of available category layout files
	 * from the template's override folder
	 * and the calendar events view folder
	 * 
	 * Returns array of filenames
	 * Array
	 * (
	 *     [0] => view.php
	 *     [1] => camera.php
	 *     [2] => cameras.php
	 *     [3] => computers.php
	 *     [4] => laptop.php
	 * )
	 *  
	 * @param array $options
	 * @return array
	 */
	public static function getLayouts( $options = array( ) )
	{
		$layouts = array( );
		// set the default exclusions array
		$exclusions = array( 'default.php', 'event_buy.php', 'event_children.php', 'event_comments.php', 'event_files.php', 'event_relations.php', 'event_requirements.php', 'event_reviews.php', 'quickadd.php', 'search.php' );
		// TODO merge $exclusions with $options['exclude']
		
		jimport( 'joomla.filesystem.file' );
		$app = JFactory::getApplication( );
		if ( $app->isAdmin( ) )
		{
			// TODO This doesn't account for when templates are assigned to menu items.  Make it do so
			$db = JFactory::getDBO( );
			$db->setQuery( "SELECT `template` FROM #__templates_menu WHERE `menuid` = '0' AND `client_id` = '0';" );
			$template = $db->loadResult( );
		}
		else
		{
			$template = $app->getTemplate( );
		}
		$folder = JPATH_SITE . DS . 'templates' . DS . $template . DS . 'html' . DS . 'com_calendar' . DS . 'events';
		
		if ( JFolder::exists( $folder ) )
		{
			$extensions = array( 'php' );
			
			$files = JFolder::files( $folder );
			foreach ( $files as $file )
			{
				$namebits = explode( '.', $file );
				$extension = $namebits[count( $namebits ) - 1];
				if ( in_array( $extension, $extensions ) )
				{
					if ( !in_array( $file, $exclusions ) )
					{
						$layouts[] = $file;
					}
				}
			}
		}
		
		// now do the media templates folder
		$folder = Calendar::getPath( 'categories_templates' );
		
		if ( JFolder::exists( $folder ) )
		{
			$extensions = array( 'php' );
			
			$files = JFolder::files( $folder );
			foreach ( $files as $file )
			{
				$namebits = explode( '.', $file );
				$extension = $namebits[count( $namebits ) - 1];
				if ( in_array( $extension, $extensions ) )
				{
					if ( !in_array( $file, $exclusions ) && !in_array( $file, $layouts ) )
					{
						$layouts[] = $file;
					}
				}
			}
		}
		
		sort( $layouts );
		
		return $layouts;
	}
	
	/**
	 * Determines a category's layout 
	 * 
	 * @param int $category_id
	 * @return unknown_type
	 */
	public static function getLayout( $category_id )
	{
		static $template;
		
		$layout = 'default';
		
		jimport( 'joomla.filesystem.file' );
		$app = JFactory::getApplication( );
		if ( empty( $template ) )
		{
			if ( $app->isAdmin( ) )
			{
				$template = $app->getTemplate( );
				$db = JFactory::getDBO( );
				$db->setQuery( "SELECT `template` FROM #__templates_menu WHERE `menuid` = '0' AND `client_id` = '0';" );
				//$template = $db->loadResult( );
			}
			else
			{
				$template = $app->getTemplate( );
			}
		}
		$templatePath = JPATH_SITE . DS . 'templates' . DS . $template . DS . 'html' . DS . 'com_calendar' . DS . 'events' . DS . '%s' . '.php';
		$mediaPath = Calendar::getPath( 'categories_templates' ) . DS . '%s' . '.php';
		
		if ( isset( $this ) && is_a( $this, 'CalendarHelperCategory' ) )
		{
			$helper = &$this;
		}
		else
		{
			$helper = &CalendarHelperBase::getInstance( 'Category' );
		}
		
		if ( empty( $helper->categories[$category_id] ) )
		{
			JTable::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'tables' );
			$helper->categories[$category_id] = JTable::getInstance( 'Categories', 'CalendarTable' );
			$helper->categories[$category_id]->load( $category_id );
		}
		$category = $helper->categories[$category_id];
		
		// if the $category->category_layout file exists in the template, use it
		if ( !empty( $category->category_layout ) && JFile::exists( sprintf( $templatePath, $category->category_layout ) ) )
		{
			return $category->category_layout;
		}
		
		// if the $category->category_layout file exists in the media folder, use it
		if ( !empty( $category->category_layout ) && JFile::exists( sprintf( $mediaPath, $category->category_layout ) ) )
		{
			return $category->category_layout;
		}
		
		// if all else fails, use the default!
		return $layout;
	}
	
	/**
	 * Gets a category's image
	 * 
	 * @param $id
	 * @param $by
	 * @param $alt
	 * @param $type
	 * @param $url
	 * @return unknown_type
	 */
	public static function getImage( $id, $by = 'id', $alt = '', $type = 'thumb', $url = false )
	{
		switch ( $type )
		{
			case "full":
				$path = 'categories_images';
				break;
			case "thumb":
			default:
				$path = 'categories_thumbs';
				break;
		}
		
		$tmpl = "";
		if ( strpos( $id, '.' ) )
		{
			// then this is a filename, return the full img tag if file exists, otherwise use a default image
			$src = ( JFile::exists( Calendar::getPath( $path ) . DS . $id ) ) ? Calendar::getUrl( $path ) . $id : 'media/com_calendar/images/noimage.png';
			
			// if url is true, just return the url of the file and not the whole img tag
			$tmpl = ( $url ) ? $src : "<img src='" . $src . "' alt='" . JText::_( $alt ) . "' title='" . JText::_( $alt ) . "' align='middle' border='0' />";
			
		}
		else
		{
			if ( !empty( $id ) )
			{
				// load the item, get the filename, create tmpl
				JTable::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'tables' );
				$row = JTable::getInstance( 'Categories', 'CalendarTable' );
				$row->load( ( int ) $id );
				$id = $row->category_full_image;
				
				$src = ( JFile::exists( Calendar::getPath( $path ) . DS . $row->category_full_image ) ) ? Calendar::getUrl( $path ) . $id : 'media/com_calendar/images/noimage.png';
				
				// if url is true, just return the url of the file and not the whole img tag
				$tmpl = ( $url ) ? $src : "<img src='" . $src . "' alt='" . JText::_( $alt ) . "' title='" . JText::_( $alt ) . "' align='middle' border='0' />";
			}
		}
		return $tmpl;
	}
	
	/**
	 * Returns a formatted path for the category
	 * @param $id
	 * @param $format
	 * @return unknown_type
	 */
	public static function getPathName( $id, $format = 'flat', $linkSelf = false )
	{
		$name = '';
		if ( empty( $id ) )
		{
			return $name;
		}
		
		if ( isset( $this ) && is_a( $this, 'CalendarHelperCategory' ) )
		{
			$helper = &$this;
		}
		else
		{
			$helper = &CalendarHelperBase::getInstance( 'Category' );
		}
		
		if ( empty( $helper->categories[$id] ) )
		{
			JTable::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'tables' );
			$helper->categories[$id] = JTable::getInstance( 'Categories', 'CalendarTable' );
			$helper->categories[$id]->load( $id );
		}
		$item = $helper->categories[$id];
		
		if ( empty( $item->category_id ) )
		{
			return $name;
		}
		$path = $item->getPath( );
		
		switch ( $format )
		{
			case "array":
				$name = array( );
				foreach ( @$path as $cat )
				{
					$include_root = Calendar::getInstance( )->get( 'include_root_pathway', false );
					if ( !$cat->isroot || $include_root )
					{
						$pathway_object = new JObject( );
						$pathway_object->name = $cat->category_name;
						$slug = $cat->category_alias ? ":$cat->category_alias" : "";
						$link = "index.php?option=com_calendar&view=events&filter_category=" . $cat->category_id . $slug;
						$pathway_object->link = $link;
						$pathway_object->id = $cat->category_id;
						$name[] = $pathway_object;
					}
				}
				
				// add the item
				$pathway_object = new JObject( );
				$pathway_object->name = $item->category_name;
				$slug = $item->category_alias ? ":$item->category_alias" : "";
				$link = "index.php?option=com_calendar&view=events&filter_category=" . $item->category_id . $slug;
				$pathway_object->link = $link;
				$pathway_object->id = $item->category_id;
				$name[] = $pathway_object;
				break;
			case "bullet":
				foreach ( @$path as $cat )
				{
					if ( !$cat->isroot )
					{
						$name .= '&bull;&nbsp;&nbsp;';
						$name .= JText::_( $cat->category_name );
						$name .= "<br/>";
					}
				}
				$name .= '&bull;&nbsp;&nbsp;';
				$name .= JText::_( $item->category_name );
				break;
			case 'links':
			// get the root category
				JTable::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'tables' );
				$root = JTable::getInstance( 'Categories', 'CalendarTable' )->getRoot( );
				$root_itemid = Calendar::getClass( "CalendarHelperRoute", 'helpers.route' )->category( $root->category_id, true );
				
				$include_root = Calendar::getInstance( )->get( 'include_root_pathway', false );
				if ( $include_root )
				{
					$link = JRoute::_( "index.php?option=com_calendar&view=events&filter_category=" . $root->category_id . "&Itemid=" . $root_itemid, false );
					$name .= " <a href='$link'>" . JText::_( 'All Categories' ) . '</a> ';
				}
				
				foreach ( @$path as $cat )
				{
					if ( !$cat->isroot )
					{
						if ( !$itemid = Calendar::getClass( "CalendarHelperRoute", 'helpers.route' )->category( $cat->category_id, true ) )
						{
							$itemid = $root_itemid;
						}
						$slug = $cat->category_alias ? ":$cat->category_alias" : "";
						$link = JRoute::_( "index.php?option=com_calendar&view=events&filter_category=" . $cat->category_id . $slug . "&Itemid=" . $itemid, false );
						if ( !empty( $name ) )
						{
							$name .= " > ";
						}
						$name .= " <a href='$link'>" . JText::_( $cat->category_name ) . '</a> ';
					}
				}
				
				if ( !empty( $name ) )
				{
					$name .= " > ";
				}
				
				if ( $linkSelf )
				{
					if ( !$itemid = Calendar::getClass( "CalendarHelperRoute", 'helpers.route' )->category( $item->category_id, true ) )
					{
						$itemid = $root_itemid;
					}
					$slug = $item->category_alias ? ":$item->category_alias" : "";
					$link = JRoute::_( "index.php?option=com_calendar&view=events&filter_category=" . $item->category_id . $slug . "&Itemid=" . $itemid, false );
					$name .= " <a href='$link'>" . JText::_( $item->category_name ) . '</a> ';
				}
				else
				{
					$name .= JText::_( $item->category_name );
				}
				
				break;
			default:
				foreach ( @$path as $cat )
				{
					if ( !$cat->isroot )
					{
						$name .= " / ";
						$name .= JText::_( $cat->category_name );
					}
				}
				$name .= " / ";
				$name .= JText::_( $item->category_name );
				break;
		}
		
		return $name;
	}
	
	/**
	 * Finds the prev & next items in the list 
	 *  
	 * @param $id   event id
	 * @return array( 'prev', 'next' )
	 */
	public static function getSurrounding( $id )
	{
		$return = array( );
		
		$prev = intval( JRequest::getVar( "prev" ) );
		$next = intval( JRequest::getVar( "next" ) );
		if ( $prev || $next )
		{
			$return["prev"] = $prev;
			$return["next"] = $next;
			return $return;
		}
		
		$app = JFactory::getApplication( );
		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
		$model = JModel::getInstance( 'Categories', 'CalendarModel' );
		$ns = $app->getName( ) . '::' . 'com.calendar.model.' . $model->getTable( )->get( '_suffix' );
		$state = array( );
		
		$state['limit'] = $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg( 'list_limit' ), 'int' );
		$state['limitstart'] = $app->getUserStateFromRequest( $ns . 'limitstart', 'limitstart', 0, 'int' );
		$state['filter'] = $app->getUserStateFromRequest( $ns . '.filter', 'filter', '', 'string' );
		$state['direction'] = $app->getUserStateFromRequest( $ns . '.filter_direction', 'filter_direction', 'ASC', 'word' );
		
		$state['order'] = $app->getUserStateFromRequest( $ns . '.filter_order', 'filter_order', 'tbl.lft', 'cmd' );
		$state['filter_id_from'] = $app->getUserStateFromRequest( $ns . 'id_from', 'filter_id_from', '', '' );
		$state['filter_id_to'] = $app->getUserStateFromRequest( $ns . 'id_to', 'filter_id_to', '', '' );
		$state['filter_name'] = $app->getUserStateFromRequest( $ns . 'name', 'filter_name', '', '' );
		$state['filter_parentid'] = $app->getUserStateFromRequest( $ns . 'parentid', 'filter_parentid', '', '' );
		$state['filter_enabled'] = $app->getUserStateFromRequest( $ns . 'enabled', 'filter_enabled', '', '' );
		
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		$rowset = $model->getList( );
		
		$found = false;
		$prev_id = '';
		$next_id = '';
		
		for ( $i = 0; $i < count( $rowset ) && empty( $found ); $i++ )
		{
			$row = $rowset[$i];
			if ( $row->category_id == $id )
			{
				$found = true;
				$prev_num = $i - 1;
				$next_num = $i + 1;
				if ( isset( $rowset[$prev_num]->category_id ) )
				{
					$prev_id = $rowset[$prev_num]->category_id;
				}
				if ( isset( $rowset[$next_num]->category_id ) )
				{
					$next_id = $rowset[$next_num]->category_id;
				}
				
			}
		}
		
		$return["prev"] = $prev_id;
		$return["next"] = $next_id;
		return $return;
	}
	
	/**
	 * Returns category name 
	 * 
	 * @param int category id
	 * @return string category name
	 * 
	 */
	public static function getCategoryName( $category_id )
	{
		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
		$model = JModel::getInstance( 'Categories', 'CalendarModel' );
		$model->setId( $category_id );
		$category = $model->getItem( );
		
		$return = '';
		if (!empty($category->category_name))
		{
		    $return = $category->category_name;
		}
		return $return;
	}
	
	/**
	 * Returns categories names 
	 * 
	 * @param  string categories ids
	 * @return string $categories names
	 * 
	 */
	public static function getCategoriesNames( $category_ids, $type='Categories' )
	{
		$category_ids = explode( ',', $category_ids );
		$categories_array = array();
		
		JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
		foreach ($category_ids as $category_id)
		{
		    $category_id = trim( $category_id );
		    
			$table = JTable::getInstance( $type, 'CalendarTable' );
			$table->load( $category_id );
			if (!empty($table->category_name)) {
    			$categories_array[] = $table->category_name;
			}
		}
		$categories = implode(', ', $categories_array);
		
		return $categories;
	}
	
	/**
	 * Returns secondary categories list
	 * 
	 * @param $event_id;
	 * @return array;
	 */
	public static function getSecondaryCategories( $event_id )
	{
		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
		$model = JModel::getInstance( 'Events', 'CalendarModel' );
		return $model->getSecondaryCategoriesString( $event_id );
	}
}
