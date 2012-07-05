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

Calendar::load( 'CalendarTable', 'tables._base' );

class CalendarTableTypes extends CalendarTable
{
	/**
	 * Could this be abstracted into the base?
	 * 
	 * @param $db
	 * @return unknown_type
	 */
	function CalendarTableTypes( &$db )
	{
		$tbl_key = 'type_id';
		$tbl_suffix = 'types';
		$this->set( '_suffix', $tbl_suffix );
		$name = "calendar";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );
	}
	
	function check( )
	{
		if ( empty( $this->type_name ) )
		{
			$this->setError( JText::_( "Type Name Required" ) );
			return false;
		}
		
		if ( !empty( $this->type_name ) && empty($this->type_id))
		{
		    $key = strtolower( $this->type_name );
		    $query = "SELECT * FROM #__calendar_types WHERE LOWER( type_name ) = '$key';";
		    $db = $this->getDBO();
		    $db->setQuery( $query );
		    $result = $db->loadResult();
		    if ($result)
		    {
    			$this->setError( JText::_( "Type Name Must Be Unique" ) );
    			return false;		        
		    }
		}
		
		return true;
	}

}
