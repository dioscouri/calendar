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

class CalendarControllerVenues extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		
		$this->set( 'suffix', 'venues' );
	}
	
	/**
	 * Sets the model's state
	 * 
	 * @return array()
	 */
	function _setModelState( )
	{
		$state = parent::_setModelState( );
		$app = JFactory::getApplication( );
		$model = $this->getModel( $this->get( 'suffix' ) );
		$ns = $this->getNamespace( );
		
		$state['filter_id_from'] = $app->getUserStateFromRequest( $ns . 'id_from', 'filter_id_from', '', '' );
		$state['filter_id_to'] = $app->getUserStateFromRequest( $ns . 'id_to', 'filter_id_to', '', '' );
		$state['filter_name'] = $app->getUserStateFromRequest( $ns . 'name', 'filter_name', '', '' );
		
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		return $state;
	}
	
	/**
	 * Saves an item and redirects based on task
	 * @return void
	 */
	function save( )
	{
		parent::save( );
		
		$task = JRequest::getVar( 'task' );
		$model = $this->getModel( $this->get( 'suffix' ) );
		
		$row = $model->getTable( );
		$row->load( $model->getId( ) );
		
		$row->venue_description = JRequest::getVar( 'venue_description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		if ( $row->save( ) )
		{
			$row->event_id = $row->id;
			$model->setId( $row->event_id );
			$this->messagetype = 'message';
			$this->message = JText::_( 'Saved' );
		}
		else
		{
			$this->messagetype = 'notice';
			$this->message = JText::_( 'Save Failed' ) . " - " . $row->getError( );
		}
	}
}

?>