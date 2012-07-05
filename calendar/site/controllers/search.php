<?php
/**
 * @package	Calendar
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class CalendarControllerSearch extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		$this->set( 'suffix', 'search' );
	}
	
	function _setModelState( )
	{
		$state = parent::_setModelState( );
		$app = JFactory::getApplication( );
		$model = $this->getModel( $this->get( 'suffix' ) );
		$ns = $this->getNamespace( );
		
		//$state['limit'] = $app->getUserStateFromRequest( $ns . 'limit', 'limit', '', '' );
		$limit = 5; 
		$state['limit'] = $limit; 
		$limitstart = JRequest::getVar( 'limitstart', '0', 'request', 'int' );
		$state['limitstart'] = ( $limit != 0 ? ( floor( $limitstart / $limit ) * $limit ) : 0 );
		
		$state['search_type'] =  $app->getUserStateFromRequest( $ns . 'search_type', 'search_type', '', '' );
		switch ( $state['search_type'] )
		{
				case "0":
					$state['filter_search'] = $app->getUserStateFromRequest( $ns . 'filter_search', 'filter_search', '', '' );
					break;
				case "1":
					$state['filter_description'] = $app->getUserStateFromRequest( $ns . 'filter_description', 'filter_search', '', '' );
					break;
				case "2":
					$state['filter_title'] = $app->getUserStateFromRequest( $ns . 'filter_title', 'filter_search', '', '' );
					break;
				default:
					break;
		}	
			
		$state['filter_enabled'] = 1;
        
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		return $state;
	}
	
	/**
	 *
	 * @return void
	 */
	function search( )
	{
		JRequest::setVar( 'view', $this->get( 'suffix' ) );
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'search', true );
		
	    $model = $this->getModel( $this->get( 'suffix' ) );
	    $this->_setModelState();
	    $state = $model->getState();
	    
        $jdate = JFactory::getDate();
        $date = $jdate->toFormat('%Y-%m-%d');
	    $model->setState( 'filter_date_from', $date );
        $model->setState('order', 'tbl.eventinstance_date');
        $model->setState('direction', 'ASC');
		$items = $model->getList( );
		if ($items)
		{
		    foreach ($items as $item)
		    {
		        $this->truncateDescription( $item->event_short_description );
		    }
		}
			
		JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
		$series_model = JModel::getInstance( 'Series', 'CalendarModel' );		
		switch ( $state->search_type )
		{
				case "0":
					$series_model->setState('filter_search',$state->filter_search);
					break;
				case "1":
					$series_model->setState('filter_description',$state->filter_description);
					break;
				case "2":
					$series_model->setState('filter_title',$state->filter_title);
					break;
				default:
					break;
		}				
		$series_model->setState('filter_enabled',1);
		$series = $series_model->getList( );
		
		$view = $this->getView( $this->get( 'suffix' ), 'html' );
		$view->assign( 'items', $items );
		$view->assign( 'series', $series );
	    				
		parent::display( );
	}
	
    /**
     * 
     * Enter description here ...
     * @param unknown_type $text
     * @return unknown_type
     */
    function truncateDescription( &$text, $length='200' )
    {
        if (empty($text))
        {
            return $text;
        }
        
        $allowed_tags = ""; // '<p><i><em>';
        
        $text = $this->stripArgumentFromTags( $text );
        $text = strip_tags( $text, $allowed_tags );
        
        $strlen = strlen($text);
        if ($length >= $strlen) { $length = $strlen; }
        
        $int = strpos( $text, ' ', $length );
        if ($int < $length) { $int = $length; }
        $text = substr( $text, 0, $int );
        if (!empty($text)) { $text .= "..."; }
    
        //$this->closeTags( $text, "<p>", "</p>" );
        //$this->closeTags( $text, "<em>", "</em>" );
    
        return $text;
    }
    
    /**
     * 
     * Enter description here ...
     * @param $text
     * @param $tag_open
     * @param $tag_close
     * @return unknown_type
     */
    function closeTags( &$text, $tag_open, $tag_close )
    {
        $p = substr_count( $text, $tag_open );
        $p_close = substr_count( $text, $tag_close );
    
        if ($p > $p_close) {
            $diff = $p - $p_close;
            $text .= str_repeat( $tag_close, $diff );
        }
    
        if ($p < $p_close) {
            $diff = $p_close - $p;
            $text = str_repeat( $tag_open, $diff ) . $text;
        }
    
        return $text;
    }
    
    function stripArgumentFromTags( $htmlString ) 
    {
        $regEx = '/([^<]*<\s*[a-z](?:[0-9]|[a-z]{0,9}))(?:(?:\s*[a-z\-]{2,14}\s*=\s*(?:"[^"]*"|\'[^\']*\'))*)(\s*\/?>[^<]*)/i'; // match any start tag
    
        $chunks = preg_split($regEx, $htmlString, -1,  PREG_SPLIT_DELIM_CAPTURE);
        $chunkCount = count($chunks);
    
        $strippedString = '';
        for ($n = 0; $n < $chunkCount; $n++) {
            $strippedString .= $chunks[$n];
        }
    
        return $strippedString;
    }
}
