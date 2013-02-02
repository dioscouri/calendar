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

if ( !class_exists('Artsvision') ) {
    JLoader::register( "Artsvision", JPATH_ADMINISTRATOR . "/components/com_artsvision/defines.php" );
}
Artsvision::load( 'ArtsvisionTableSchedule', 'tables.schedule' );

Calendar::load( 'CalendarModelSources', 'models.sources' );

class CalendarModelSourcesArtsvision extends CalendarModelSources
{
    public function getPresetFilters() 
    {
        $return = array();
        
        $filter = $this->getPresetObject( array(
                'id' => "filter_artsvision_dizzys",
                'title' => "Dizzy's Events",
                'value' => "artsvision.filter_dizzys"
                ) );
        $return[] = $filter;

        return $return;
    }
    
    public function getList( $refresh=false )
    {
        if (empty( $this->_list ) || $refresh)
        {
            $cache_key = base64_encode(serialize($this->getState())) . '.list';
             
            $classname = strtolower( get_class($this) );
            $cache = JFactory::getCache( $classname . '.list', '' );
            $cache->setCaching($this->cache_enabled);
            $cache->setLifeTime($this->cache_lifetime);
            $list = $cache->get($cache_key);
            if(!version_compare(JVERSION,'1.6.0','ge'))
            {
                $list = unserialize( trim( $list ) );
            }
            if (!$list || $refresh)
            {
                $list = $this->_getList( $refresh );
        
                if ( empty( $list ) )
                {
                    $list = array( );
                }
        
                foreach ( $list as $key=>&$item )
                {
                    $this->prepareItem( $item, $key, $refresh );
                }
        
                if(version_compare(JVERSION,'1.6.0','ge'))
                {
                    // joomla! 1.6+ code here
                    $cache->store($list, $cache_key);
                }
                else
                {
                    // Joomla! 1.5 code here
                    $cache->store(  serialize( $list ), $cache_key);
                }
            }
             
            $this->_list = $list;
        
        }
        
        return $this->_list;        
    }
    
    /**
     * Gets an array of objects from the results of database query.
     *
     * @param   string   $query       The query.
     * @param   integer  $limitstart  Offset.
     * @param   integer  $limit       The number of records.
     *
     * @return  array  An array of results.
     *
     * @since   11.1
     */
    protected function _getList($refresh=false, $limitstart = 0, $limit = 0)
    {
        $model = Artsvision::getClass( 'ArtsvisionModelSchedule', 'models.schedule' );
        
        $state = $this->getState();
        foreach ($state as $key=>$value)
        {
            $model->setState( $key, $value );
        }
        FB::log( 'modelsourceartsvision.state-from-artsvision model' );
        FB::log( $model->getState() );
                
        $list = $model->getList( $refresh );
        
        return $list;
    }
    
    /**
     * Set basic properties for the item, whether in a list or a singleton
     *
     * @param unknown_type $item
     * @param unknown_type $key
     * @param unknown_type $refresh
     */
    protected function prepareItem( &$item, $key=0, $refresh=false )
    {
        parent::prepareItem($item, $key, $refresh);
        
        // Convert from a ArtsvisionTableSchedule object to a CalendarObjectInstance instance
        if (!is_a($item, 'CalendarObjectInstance')) {
            $clone = $item;
            $item = $this->getInstanceObject( array(
                    'datasource_id' => "artsvision." . $clone->id,
                    'title' => $clone->project_name,
                    'title_short' => $clone->project_name,
                    'start_time' => $clone->starttime,
                    'end_time' => $clone->endtime
                    ) );
            foreach (get_object_vars($clone) as $prop=>$def)
            {
                $item->$prop = $clone->$prop;
            }
        }
    }
    
    /**
     * Gets a property from the model's state, or the entire state if no property specified
     * @param $property
     * @param $default
     * @param string The variable type {@see JFilterInput::clean()}.
     *
     * @return unknown_type
     */
    public function getState( $property=null, $default=null, $return_type='default' )
    {
        $state = parent::getState( $property, $default, $return_type );
        foreach ($state as $key=>$value)
        {
            switch ( $key ) 
            {
                case "order":
                    switch ($value) 
                    {
                        case "date":
                        case "tbl.date":                        
                        case "tbl.eventinstance_date":
                        case "eventinstance_date":
                            $state->set( $key, 'tbl.date_' );
                            break;
                    }
                    break;
            }
        }
        
        return $state;
    }
}
