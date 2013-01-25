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
	public $cache_enabled = true;
	public $cache_lifetime = '86400';
	public $tessitura_web_api_status = null;
	public $av_status = null;
	
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->defines = Calendar::getInstance();
	}
	
    public function getTable($name='', $prefix='CalendarTable', $options = array())
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
        return parent::getTable($name, $prefix, $options);
    }
    
    protected function getLimit($default=null) 
    {
        $limit = $this->getState('limit', null);
        if (empty($limit)) 
        {
            $limit = $default;
        }
        return $limit;
    }
    
    public function loadMQ()
    {
        static $loaded;
        
        if (!$loaded) 
        {
            require_once JPATH_SITE . '/libraries/firephp/fb.php';
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
            
            if (defined('APPLICATION_ENV') && APPLICATION_ENV=='dev') MappableQuery\MappableQuery::$DEBUG = true;
				
            $loaded = true;
        }
        
        return $loaded;
    }
    
	/**
	 * Gets the identifier, setting it if it doesn't exist
	 * @return unknown_type
	 */
	public function getId()
	{
		if (empty($this->_id))
		{
			$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post' ), 'get' );
			$array = JRequest::getVar('cid', array( $id ), 'post', 'array');
			$id = $this->_filterinput->clean( $array[0], 'cmd' );
			$this->setId( $id );
		}

		return $this->_id;
	}
    
    public function getIdForQuery($id=null)
    {
        if (empty($id))
        {
            $id = $this->getState('filter_event');
            if (empty($id)) 
            {
                $this->setError( 'Invalid Event Filter' );
                return false;
            }
        }
    
        $parts = explode( '-', str_replace( ':', '-', $id ), 2);
    
        $return = new JObject();
        $return->data_source = $this->getDatasource( $parts[0] );
        $return->id = $parts[1];
        
        return $return;
    }
    
    public function getDatasource( $string )
    {
        $return = false;
        
        if (strpos($string, ':') !== false) {
            $parts = explode(':', $string, 2);
            $string = $parts[0];
        }
        
        switch ($string)
        {
        	case "tp":
        		$return = "tp";
        		break;
            case "t":
            case "tess":
                $return = "t";
                break;
            case "a":
            case "av":
                $return = "av";
                break;
            default:
                break;
        }
        
        return $return;
    }
    
    /**
     * Finds the items in a list that immediately precede and follow the selected item
     * @param unknown_type $id
     */
    public function getSurrounding( $pk )
    {
        $return = array();
        $return["prev"] = '';
        $return["next"] = '';
        
        return $return;
    }
    
    /**
     * Gets an array of MQ IDs based on a set of filters being stored in the Joomla DB,
     * such as event.type_id, tag.tag_id, etc
     * 
     * $key_values = array of arrays.
     * $key_values[$key] = array() 
     * 
     * Function will find all items where $key matches any of the values in $key_values[$key] 
     * 
     * @param unknown_type $key_values
     * @return multitype:
     */
	public function getIDsFromJoomlaFilter( $key_values=array() )
	{
	    $return = array();

	    $db = $this->getDBO();
	     
	    $query = new DSCQuery();
	    $query->select( array("tbl.datasource_id") );
	    $db_table = $this->getTable()->getTableName();
	    $query->from( $db_table . ' AS tbl' );
	    foreach ( $key_values as $key=>$values )
	    {
	        $where = array();
	        $values = (array) $values;
	        foreach ($values as $value) {
	            $v = $db->Quote($value);
	            $where[] = $key . "=" . $v;
	        }
	        if (!empty($where)) {
    	        $query->where( '(' . implode( ' OR ', $where ) . ')' );
	        }
	    }
	     
	    $db->setQuery( (string) $query );
	    if ($items = $db->loadObjectList()) {
	        foreach ($items as $item) {
	            if (!empty($item->datasource_id)) {
	                $return[] = $item->datasource_id;
	            }
	        }
	    }

	    return $return;
	}
	
	/**
	 * Clean the cache
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public function clearCache()
	{
	    parent::clearCache();
	     
	    $classname = strtolower( get_class($this) );
	    parent::cleanCache($classname . '.joomla-properties');
	}
    
    /**
     * 
     * @param unknown_type $item
     * @return CalendarModelBase
     */
    protected function cacheItem( $item, $cache_key=null )
    {
        if (!empty($item))
        {
            if (empty($cache_key)) {
                $cache_key = $item->getDatasourceID();
            }
    
            $classname = (!empty($this->classname)) ? $this->classname : strtolower( get_class($this) );
            $cache = JFactory::getCache( $classname . '.item', '' );
            $cache->setCaching(true);
            $cache->setLifeTime($this->cache_lifetime);
            $cache->store($item, $cache_key);
        }
    
        return $this;
    }
    
    public function pingTessituraWebAPI()
    {
        if (is_null($this->tessitura_web_api_status)) 
        {
            // https://tnew.jalc.org/SoapAPI/Tessitura.asmx
            $domain = 'tnew.jalc.org';
            $response_time = $this->pingDomain($domain, 1, 443);
            //$this->setError( 'response time: ' . $response_time );
            
            if ($response_time > 0)
            {
                $this->tessitura_web_api_status = true;
            } 
                else 
            {
                $this->tessitura_web_api_status = false;
            } 
        }

        if (is_null($this->av_status))
        {
        	$domain = 'av.jazzatlincolncenter.org';
        	$response_time = $this->pingDomain($domain, 1, 443);
        	//$this->setError( 'response time: ' . $response_time );
        
        	if ($response_time > 0)
        	{
        		$this->av_status = true;
        	}
        	else
        	{
        		$this->av_status = false;
        	}
        }

        if (!$this->av_status || !$this->tessitura_web_api_status) {
        	return false;
        }
        
        return true;
    }
    
    public function pingDomain( $domain, $timeout=1, $port=80 )
    {
        $starttime = microtime(true);
        $file      = @fsockopen($domain, $port, $errno, $errstr, $timeout);
        $stoptime  = microtime(true);
        $status    = 0;
    
        if (!$file) 
        { 
            $status = -1;
        }
            else 
        {
            fclose($file);
            $status = ($stoptime - $starttime) * 1000;
            $status = floor($status);
        }
        
        return $status;
    }
    
    public function getPurchaseUrl( $item, $options=array() )
    {
        if (defined('APPLICATION_ENV')) {
            $baseUrl = (APPLICATION_ENV=='dev' ? 'http://ticketing.jalc.org/_qa_/': 'https://ticketing.jalc.org/');
        }
        else {
            $baseUrl = 'https://ticketing.jalc.org/';
        }
        
        $return = '';
        
        $ds = $this->getDatasource( str_replace('-', ':', $item->getDataSourceID()));
        switch ($ds) 
        {
            case "tp":
                if ($item->isCourseSession && $item->onSale) {
                    if (!empty($item->educationPackageID)) {
                        $return = $baseUrl.'auxpkg/detail.aspx?pkg='. $item->educationPackageID. '&flex=N&nfs=N';
                    }
                }
                break;
            case "t":
            case "tess":
                if ($item->onSale && empty($item->isCourseSession)) {
                    $return = $baseUrl.'single/SelectSeating.aspx?p='.$item->tessituraID;
                }
                
                if (!empty($item->isCourseSession) && $item->onSale) {
                    $return = '';
                    if (!empty($item->educationPackageID)) {
                        $return = $baseUrl.'auxpkg/detail.aspx?pkg='. $item->educationPackageID. '&flex=N&nfs=N';
                    }
                }
                break;
            case "a":
            case "av":
                $return = "http://dizzys.jalc.org/index.php?option=com_dizzyclub&view=performances&event_id=".$item->show->oldWebID;
                break;
            default:
                break;            
        }
        
        return $return;
    }
    
    public function getActionButtonLabel($item, $options=array())
    {
        $return = 'COM_CALENDAR_ACTIONBUTTON_LABEL_BUY';
        
        $ds = $this->getDatasource( str_replace('-', ':', $item->getDataSourceID()));
        switch ($ds) 
        {
            case "tp":
                $return = 'COM_CALENDAR_ACTIONBUTTON_LABEL_ENROLL';
                break;
            case "t":
            case "tess":
                $return = 'COM_CALENDAR_ACTIONBUTTON_LABEL_BUY';
                break;
            case "a":
            case "av":
                $return = 'COM_CALENDAR_ACTIONBUTTON_LABEL_RESERVE';
                break;
            default:
                break;            
        }
        
        return $return;
    }
    
}