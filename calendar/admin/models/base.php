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

class CalendarModelBase extends DSCModel
{
    public function getTable($name='', $prefix='CalendarTable', $options = array())
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
        return parent::getTable($name, $prefix, $options);
    }
    
    public function loadMQ()
    {
        static $loaded;
        
        if (!$loaded) 
        {
            require_once JPATH_SITE . '/libraries/doctrine/doctrine_bootstrap.php';
            jimport('jalc.ServiceLoader');
            jimport('jalc.ClassLoader');
            
            $classLoader = new \JALC\ClassLoader('JALC\Entities', JPATH_LIBRARIES.'/jalc', 'JALC');
            $classLoader->register();
            $classLoader = new \JALC\ClassLoader('JALC\Queries', JPATH_LIBRARIES.'/jalc', 'JALC');
            $classLoader->register();
            $classLoader = new JALC\ClassLoader('JALC\EventsArtists\Entities', JPATH_SITE . '/components/com_jalc_events/models', 'JALC\EventsArtists');
            $classLoader->register();
            $classLoader = new JALC\ClassLoader('JALC\EventsArtists\Queries', JPATH_SITE . '/components/com_jalc_events/models', 'JALC\EventsArtists');
            $classLoader->register();
            
            jimport('mappablequery.Autoloader');
            MappableQuery\MappableQuery::addMappingBasePath('JALC\EventsArtists', JPATH_SITE . '/components/com_jalc_events/models/mapping');
            MappableQuery\MappableQuery::addMappingBasePath('JALC', JPATH_LIBRARIES.'/jalc/mapping');
            
            $loaded = true;
        }
        
        return $loaded;
    }
}