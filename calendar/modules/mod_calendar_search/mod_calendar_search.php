<?php
/**
 * @version	1.5
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

// Check the registry to see if our Calendar class has been overridden
if ( !class_exists( 'Calendar' ) ) JLoader::register( "Calendar", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_calendar" . DS . "defines.php" );

// include lang files
$element = strtolower( 'com_calendar' );
$lang = &JFactory::getLanguage( );
$lang->load( $element, JPATH_BASE );
$lang->load( $element, JPATH_ADMINISTRATOR );

$original = $params->get( 'filter_text', 'Search Events...' );
$filter_text = JRequest::getVar( 'filter_search', $original );

$item_id = $params->get('item_id');
if (empty($item_id))
{
    $active = JFactory::getApplication( )->getMenu( )->getActive( );
    if ( !empty( $active ) )
    {
    	$item_id = $active->id;
    }
    else
    {
    	$item_id = JRequest::getInt( 'Itemid' );
    }    
}

require ( JModuleHelper::getLayoutPath( 'mod_calendar_search' ) );
