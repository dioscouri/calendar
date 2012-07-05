<?php
/**
 * @version	1.5
 * @package	Calendar
 * @author 	Dioscouri
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2011 Dioscouri. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Calendar::load( 'CalendarHelperBase', 'helpers._base' );

class CalendarHelperImage extends CalendarHelperBase
{
	// Default Dimensions for the images
	var $product_img_height 		= 0;
	var $product_img_width 			= 0;
	var $category_img_height 		= 0;
	var $category_img_width			= 0;
	var $manufacturer_img_width		= 0;
	var $manufacturer_img_height	= 0;
	
	// Default Paths for the images
	var $product_img_path 			= '';
	var $category_img_path			= '';
	var $manufacturer_img_path 		= '';
	
	// Default Paths for the thumbs
	var $product_thumb_path 			= '';
	var $category_thumb_path			= '';
	var $manufacturer_thumb_path 		= '';
	
	
	/**
	 * Protected! Use the getInstance
	 */ 
	protected function CalendarHelperImage()
	{
		// Parent Helper Construction
		parent::__construct();
		
		$config = Calendar::getInstance();
		
		// Load default Parameters
		$this->series_img_height = $config->get( 'series_img_height' );
		$this->series_img_width = $config->get( 'series_img_width' );
		$this->category_img_height = $config->get( 'category_img_height' );
		$this->category_img_width = $config->get( 'category_img_width' );
		$this->event_img_width = $config->get( 'event_img_width' );
		$this->event_img_height = $config->get( 'event_img_height' );
		$this->dailyevent_img_width = $config->get( 'dailyevent_img_width' );
		$this->dailyevent_img_height = $config->get( 'dailyevent_img_height' );
		$this->eventinstance_img_width = $config->get( 'eventinstance_img_width' );
		$this->eventinstance_img_height = $config->get( 'eventinstance_img_height' );
		
		$this->series_img_path = Calendar::getPath( 'series_images' );
		$this->category_img_path = Calendar::getPath( 'categories_images' );
		$this->event_img_path = Calendar::getPath( 'events_images' );
		$this->dailyevent_img_path = Calendar::getPath( 'dailyevents_images' );
		$this->eventinstance_img_path = Calendar::getPath( 'eventinstances_images' );
		
		$this->series_thumb_path = Calendar::getPath( 'series_thumbs' );
		$this->category_thumb_path = Calendar::getPath( 'categories_thumbs' );
		$this->event_thumb_path = Calendar::getPath( 'events_thumbs' );
		$this->dailyevent_thumb_path = Calendar::getPath( 'dailyevents_thumbs' );
		$this->eventinstance_thumb_path = Calendar::getPath( 'eventinstances_thumbs' );
	}
	
	/**
	 * Resize Image
	 * 
	 * @param name	string	filename of the image
	 * @param type	string	what kind of image: product, category
	 * @param options	array	array of options: width, height, path, thumb_path
	 * @return thumb full path
	 */
	function resize($name, $type = 'product', $options = array()){
		
		// Check File presence
		if(!JFile::exists($name)){
			return false;
		}
		
		JImport( 'com_calendar.library.image', JPATH_ADMINISTRATOR . 'components' );
		$img = new CalendarImage($name);
		
		$types = array('product', 'category', 'manufacturer');
		if (!in_array($type, $types))
		{
		    $type = 'product';
		}

		return $this->resizeImage( $img, $type, $options );
	}
	
    /**
	 * Resize Image
	 * 
	 * @param image	string	filename of the image
	 * @param type	string	what kind of image: product, category
	 * @param options	array	array of options: width, height, thumb_path
	 * @return thumb full path
	 */
	function resizeImage( &$img, $type = 'product', $options = array() )
	{

		$types = array('product', 'category', 'manufacturer');
		if(!in_array($type, $types))
			$type = 'product';

		// Code less!
		$thumb_path = $img->getDirectory().DS.'thumbs';
		$img_width = $type.'_img_width';
		$img_height = $type.'_img_height';
		
		$img->load();
		
		// Default width or options width?
		if(!empty($options['width']) && is_numeric($options['width']))
			$width = $options['width'];
		else
			$width = $this->$img_width;
		
		// Default height or options height?
		if(!empty($options['height']) && is_numeric($options['height']))
			$height = $options['height'];
		else	
			$height= $this->$img_height;
		
		// Default thumb path or options thumb path?
		if(!empty($options['thumb_path']))
			$dest_dir = $options['thumb_path'];
		else	
			$dest_dir = $thumb_path;
			
		$this->checkDirectory($dest_dir);

		if($width >= $height)
			$img->resizeToWidth( $width );
		else
			$img->resizeToHeight( $height );
			
		$dest_path = $dest_dir.DS.$img->getPhysicalName();
		
		if (!$img->save( $dest_path ))
		{
		    $this->setError( $img->getError() );
		    return false;
		}
		
		return $dest_path;
	}
	
	/**
	 * getLocalizedname
	 * 
	 * get a localized version of an image name (addtocart_it-IT.png)
	 * if path is specified, checks also if that image exists, and if not, 
	 * returns the orginal name
	 * 
	 * @param string $image
	 * @param string $path
	 * @param string $lang (auto or language tag)
	 */
	
	public static function getLocalizedName($image, $path = '', $lang = 'auto')
	{
		if( $lang == 'auto' )
		{
			$lang = JFactory::getLanguage();
			$lang = $lang->getTag();			
		}
		
		$name = JFile::stripExt($image);
		$ext = JFile::getExt($image);
		
		// append language tag
		$new_image = $name.'_'.$lang.'.'.$ext;

		// checks image existance
		if($path)
		{
			if( !JFile::exists($path.DS.$new_image) )
			{
				$new_image = $image;
			}
		}
		
		return $new_image;
	}
	
	/**
	 * Gets an image
	 * 
	 * @param $image
	 * @param $alt
	 * @param $type
	 * @param $url
	 * @return unknown_type
	 */
	function getImage( $object_name, $image, $alt = '', $type = 'thumb', $url = false )
	{
		switch ( $type )
		{
			case "full":
				$path = $object_name . '_images';
				break;
			case "thumb":
			default:
				$path = $object_name . '_thumbs';
				break;
		}
		
		$tmpl = "";
		
		if ( !empty( $image ) )
		{
			jimport( 'joomla.filesystem.file' );
			$src = ( JFile::exists( Calendar::getPath( $path ) . DS . $image ) ) ? Calendar::getUrl( $path ) . $image : 'media/com_calendar/images/noimage.png';
			
			// if url is true, just return the url of the file and not the whole img tag
			$tmpl = ( $url ) ? $src : "<img src='" . $src . "' alt='" . JText::_( $alt ) . "' title='" . JText::_( $alt ) . "' align='middle' border='0' />";
		}
		
		return $tmpl;
	}
}
