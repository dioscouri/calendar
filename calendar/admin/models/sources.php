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

class CalendarModelSources extends CalendarModelBase
{
    public function getTable($name='Config', $prefix='CalendarTable', $options = array())
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
        return parent::getTable($name, $prefix, $options);
    }
    
    public function getInstanceObject( $properties=array(), $options=array() )
    {
        $object = Calendar::getClass( 'CalendarObjectInstance', 'library.object.instance' );
        $object->setProperties($properties);
    
        return $object;
    }
    
    public function getPresetObject( $properties=array(), $options=array() ) 
    {
        $object = Calendar::getClass( 'CalendarObjectPreset', 'library.object.preset' );
        $object->setProperties($properties);
        
        return $object;
    }
    
    public function getSourceObject( $properties=array(), $options=array() )
    {
        $object = Calendar::getClass( 'CalendarObjectSource', 'library.object.source' );
        $object->setProperties($properties);
    
        return $object;
    }
    
    /**
     * Returns a list of known data sources
     * 
     * (non-PHPdoc)
     * @see DSCModel::getList()
     */
    public function getSources( $refresh=false )
    {
        $list = array();
        
        jimport('joomla.filesystem.file');
        $items = array();
        $exclusions = array();
        $folder = JPATH_ADMINISTRATOR . '/components/com_calendar/models/sources';
        if (JFolder::exists( $folder ))
        {
            $extensions = array( 'php' );
        
            $files = JFolder::files( $folder );
            foreach ($files as $file)
            {
                $namebits = explode('.', $file);
                $extension = $namebits[count($namebits)-1];
                if (in_array($extension, $extensions))
                {
                    if (!in_array($file, $exclusions) && !in_array($file, $items))
                    {
                        $items[$file] = $this->getSourceObject( array(
                                    'id' => $file,
                                    'title' => $namebits[0],
                                    'value' => 'CalendarModelSources' . $namebits[0]  
                                    ) );
                    }
                }
            }
        }
        ksort( $items );
        
        foreach ( $items as $item )
        {
            $list[] = $item;
        }
        
        return $list;
    }
    
    public function getSourcePresets( $refresh=false )
    {
        $sourcepresets = array();
        
        if ($sources = $this->getSources())
        {
            foreach ($sources as $source)
            {
                $model = Calendar::getClass( $source->value, 'models.sources.' . $source->title );
                if ($presets = $model->getPresetFilters()) 
                {
                    $sourcepresets = array_merge( $sourcepresets, $presets );
                }
            }
        }
        
        return $sourcepresets;
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
	protected function _getSourceList($refresh=false, $limitstart = 0, $limit = 0)
	{
		$result = array();
        $state = $this->getState();
        
		$activemodels = array();
		// get the models that will be used from the $state->filter_sourcepresets
		if ($filter_sourcepresets = $state->get('filter_sourcepresets')) {
		    foreach ($filter_sourcepresets as $filter_preset) {
		        $parts = explode('.', $filter_preset);
		        $modelname = $parts[0];
		        $filter = $parts[1];
		        if (!array_key_exists($modelname, $activemodels)) {
		            $activemodels[$modelname] = Calendar::getClass( 'CalendarModelSources' . $modelname , 'models.sources.' . $modelname );
		            $activemodels[$modelname]->setState($filter, 1);
		            foreach ($state as $key=>$value)
		            {
		                $activemodels[$modelname]->setState( $key, $value );
		            }
		            $result = array_merge( $result, $activemodels[$modelname]->getList( $refresh ) );
		        }
		    }
		} 
        
		return $result;
	}
	
	/**
	 * Retrieves the data for a paginated list
	 * @return array Array of objects containing the data from the database
	 */
	public function getSourceItems($refresh = false)
	{
	    if (empty( $this->_sourceitems ) || $refresh)
	    {
	        $cache_key = base64_encode(serialize($this->getState())) . '.sourceitems';
	         
	        $classname = strtolower( get_class($this) );
	        $cache = JFactory::getCache( $classname . '.sourceitems', '' );
	        $cache->setCaching($this->cache_enabled);
	        $cache->setLifeTime($this->cache_lifetime);
	        $list = $cache->get($cache_key);
	        if(!version_compare(JVERSION,'1.6.0','ge'))
	        {
	            $list = unserialize( trim( $list ) );
	        }
	        
	        if (!$list || $refresh)
	        {
	            $state = $this->getState();
	            $list = $this->_getSourceList( $refresh, $this->getState('limitstart'), $this->getState('limit') );
	        
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
	        
	        $this->_sourceitems = $list;
	    }
	    
	    return $this->_sourceitems;
	}
}