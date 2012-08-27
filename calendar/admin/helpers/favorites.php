<?php
/**
 * @package Calendar
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class CalendarHelperFavorites extends JObject 
{
    /**
     * Checks if extension is installed
     *
     * @return boolean
     */
    public function isInstalled()
    {
        $success = false;
    
        jimport('joomla.filesystem.file');
        if (JFile::exists( JPATH_ADMINISTRATOR . '/components/com_favorites/defines.php' ))
        {
            JLoader::register( "Favorites", JPATH_ADMINISTRATOR . "/components/com_favorites/defines.php" );
            
            $parentPath = JPATH_ADMINISTRATOR . '/components/com_favorites/helpers';
            DSCLoader::discover('FavoritesHelper', $parentPath, true);
            
            $parentPath = JPATH_ADMINISTRATOR . '/components/com_favorites/library';
            DSCLoader::discover('Favorites', $parentPath, true);
            
            if ($this->getScope()) 
            {
                $success = true;
            }
        }
        return $success;
    }
    
    /**
     * 
     * @param unknown_type $scope
     */
    public function getScope( $scope='com_calendar.event' )
    {
        // TODO cache this
        
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_favorites/tables' );
        
        $table = JTable::getInstance( 'Scopes', 'FavoritesTable' );
        $table->load( array( 'scope_identifier'=>$scope ) );
        
        if (empty($table->scope_id)) 
        {
            switch ($scope) 
            {
                case "com_calendar.event":
                    $table->scope_name			   = 'Calendar Event';
                    $table->scope_url              = 'index.php?option=com_calendar&view=events&task=view&id=';
                    $table->scope_table            = '#__calendar_events';
                    $table->scope_table_field      = 'event_id';
                    $table->scope_table_name_field = 'event_short_title';
                    break;
            }
            
            $table->scope_identifier       = $scope;
            $table->save();
        }
        
        return $table;
    }
    
    /**
     * Gets the standard html form for adding tags to an item
     *  
     * @param unknown_type $identifier
     * @param unknown_type $scope
     * @return string
     */
    public function getForm( $identifier, $title, $scope='com_calendar.event' )
    {
        $html = '';
        
        if (!$this->isInstalled())
        {
            return $html;
        }
        
        $scope_object = $this->getScope( $scope );
        
        $helper = new FavoritesHelperFavorites();
        $html = $helper->favButton( $identifier, $scope_object->scope_id, $title );
        
        return $html;
    }
}
