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

JLoader::import( 'com_calendar.tables._basexref', JPATH_ADMINISTRATOR . DS . 'components' );

class CalendarTableEventTypes extends CalendarTableXref
{
	function CalendarTableEventTypes( &$db )
	{
		$keynames = array( );
		$keynames['event_id'] = 'event_id';
		$keynames['type_id'] = 'type_id';
		$this->setKeyNames( $keynames );
		
		$tbl_key = 'event_id';
		$tbl_suffix = 'eventtypes';
		$name = 'calendar';
		
		$this->set( '_tbl_key', $tbl_key );
		$this->set( '_suffix', $tbl_suffix );
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );
	}
	
	function check( )
	{
		if ( empty( $this->type_id ) )
		{
			$this->setError( JText::_( "Type Required" ) );
		}
		
		if ( empty( $this->event_id ) )
		{
			$this->setError( JText::_( "Event Required" ) );
		}
		
		return parent::check();
	}
}
