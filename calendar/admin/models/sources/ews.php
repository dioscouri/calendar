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

JLoader::register( "EWS_Exception", JPATH_LIBRARIES . "/php-ews/EWS_Exception.php" );

Calendar::load( 'CalendarModelSources', 'models.sources' );

class CalendarModelSourcesEws extends CalendarModelSources
{
    public $host = 'mail.jazzatlincolncenter.org';
    public $location = '';
    public $username = '';
    public $domain = 'jazzatlinctr';
    public $password = '';
    public $mail = '';
    
    public function test() 
    {
        $host = 'mail.jazzatlincolncenter.org';
        $location = 'https://'. $host .'/EWS/Services.wsdl'; // EWS/Exchange.asmx
        $username = 'rdiaz';
        $domain = 'jazzatlinctr';
        //$username = 'rdiaz@jazzatlinctr';
        //$username = 'jazzatlinctr\rdiaz';
        //$username = 'rdiaz@jalc.org';
        $password = '49rx*dykw';
        //$username = "jalcweb";
        //$password = "3columbu&";
        //$host = urlencode($username) . ":" . urlencode($password) . "@" . $host;
        ////FB::log($host);
        
        $mail = 'rdiaz@jalc.org';
        //$mail = 'jalcweb@jalc.org';
        $startDateEvent = '2011-09-14T09:00:00'; //ie: 2010-09-14T09:00:00
        $endDateEvent = '2011-09-20T17:00:00'; //ie: 2010-09-20T17:00:00
        
        $ews = new ExchangeWebServices($host, $username, $password, $domain);
        
        $request = new EWSType_FindItemType();
        $request->Traversal = EWSType_FolderQueryTraversalType::SHALLOW;
        
        $request->CalendarView->StartDate = $startDateEvent;
        $request->CalendarView->EndDate = $endDateEvent;
        $request->CalendarView->MaxEntriesReturned = 100;
        $request->CalendarView->MaxEntriesReturnedSpecified = true;
        $request->ItemShape->BaseShape = EWSType_DefaultShapeNamesType::ALL_PROPERTIES;
        
        $request->ParentFolderIds->DistinguishedFolderId->Id = EWSType_DistinguishedFolderIdNameType::CALENDAR;
        $request->ParentFolderIds->DistinguishedFolderId->Mailbox->EmailAddress = $mail;
        $response = $ews->FindItem($request);
        
        FB::log($response);
        
        //echo '<pre>'.print_r($response, true).'</pre>';
    }
    
    public function __construct($config = array())
    {
        parent::__construct($config);
        
        spl_autoload_register( 'CalendarModelSourcesEws::__autoload' );
        
        $this->host = 'mail.jazzatlincolncenter.org';
        $this->location = 'https://'. $this->host .'/EWS/Services.wsdl'; // EWS/Exchange.asmx
        
        $this->username = 'rdiaz';
        $this->domain = 'jazzatlinctr';
        $this->password = '49rx*dykw';
        $this->mail = 'rdiaz@jalc.org';
        
        //$username = 'rdiaz@jazzatlinctr';
        //$username = 'jazzatlinctr\rdiaz';
        //$username = 'rdiaz@jalc.org';
        //$username = "jalcweb";
        //$password = "3columbu&";
        //$host = urlencode($username) . ":" . urlencode($password) . "@" . $host;
        ////FB::log($host);
        //$mail = 'jalcweb@jalc.org';
    }
    
    public static function __autoload( $class_name ) 
    {
        // Start from the base path and determine the location from the class name,
        $base_path = JPATH_LIBRARIES . '/php-ews';
        $include_file = $base_path . '/' . str_replace('_', '/', $class_name) . '.php';
        
        return (file_exists($include_file) ? require_once $include_file : false);
    }
    
    public function getPresetFilters() 
    {
        $return = array();
        
        $filter = $this->getPresetObject( array(
                'id' => "filter_ews_self",
                'title' => "Exchange Server Events",
                'value' => "ews.filter_self"
                ) );
        $return[] = $filter;

        return $return;
    }
    
    public function getList( $refresh=false )
    {
        $refresh = true;
        
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
        $list = array();

        $host = $this->host; 
        $username = $this->username;
        $password = $this->password;
        $domain = $this->domain;
        $mail = $this->mail;
        
        //$startDateEvent = '2011-09-14T09:00:00'; //ie: 2010-09-14T09:00:00
        //$endDateEvent = '2011-09-20T17:00:00'; //ie: 2010-09-20T17:00:00
        $startDateEvent = $this->getState('filter_date_from');
        $endDateEvent = $this->getState('filter_date_to');
        
        $ews = new ExchangeWebServices($host, $username, $password, $domain);
        
        $request = new EWSType_FindItemType();
        $request->Traversal = EWSType_FolderQueryTraversalType::SHALLOW;
        
        $request->CalendarView->StartDate = $startDateEvent;
        $request->CalendarView->EndDate = $endDateEvent;
        $request->CalendarView->MaxEntriesReturned = 100;
        $request->CalendarView->MaxEntriesReturnedSpecified = true;
        $request->ItemShape->BaseShape = EWSType_DefaultShapeNamesType::ALL_PROPERTIES;
        
        $request->ParentFolderIds->DistinguishedFolderId->Id = EWSType_DistinguishedFolderIdNameType::CALENDAR;
        $request->ParentFolderIds->DistinguishedFolderId->Mailbox->EmailAddress = $mail;
        $response = $ews->FindItem($request);
        
        FB::log($response);
        
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
        
        // TODO Convert from to a CalendarObjectInstance instance
    }

}
