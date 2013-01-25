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

class CalendarControllerPackages extends CalendarController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		
		$this->set( 'suffix', 'packages' );
		$this->registerTask( 'admin_only.enable', 'boolean' );
		$this->registerTask( 'admin_only.disable', 'boolean' );
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
		$state['filter_admin_only'] = $app->getUserStateFromRequest( $ns . 'filter_admin_only', 'filter_admin_only', '', '' );
		$state['order']     = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.ordering', 'cmd');
		
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		return $state;
	}
	
	function display($cachable=false, $urlparams = false)
	{
	    $refresh = false;
	    $model = $this->getModel( $this->get( 'suffix' ) );
	    $state = $this->_setModelState();
	    $items = $model->getList();
	    foreach ($items as $item) 
	    {
	        if (empty($item->package_id))
	        {
	            $table = $model->getTable();
	            $table->load( array('datasource_id'=>$item->getDataSourceID() ) );
	            $table->bind($item);
	            $table->datasource_id = $item->getDataSourceID();
	            $table->package_name = $item->title;
	            if (!$table->save()) {
	                JFactory::getApplication()->enqueueMessage( $table->getError() );
	            } else {
	                $refresh = true;
	            }
	        }	        
	    }
	    
	    if ($refresh) {
	        $model->clearCache();
	        $items = $model->getList( true );
	    }

	    if ($state['order'] == 'tbl.ordering') {
	        $dir = ($state['direction'] == 'desc') ? '-1' : 1;
    	    jimport( 'joomla.utilities.arrayhelper' );
    	    JArrayHelper::sortObjects($items, 'ordering', $dir );
    	    $model->_list = $items;
	    }
	    	    
	    parent::display($cachable, $urlparams);
		
	    $groups = $model->getGroups();
	}
	
	function edit($cachable=false, $urlparams = false)
	{
	    $model = $this->getModel( $this->get( 'suffix' ) );
	    $item = $model->getItem();
	    if (empty($item->package_id))
	    {
	        $table = $model->getTable();
	        $table->load( array('datasource_id'=>$item->getDataSourceID() ) );
	        $table->bind($item);
	        $table->datasource_id = $item->getDataSourceID();
	        $table->package_name = $item->title;
	        if (!$table->save()) {
	            JFactory::getApplication()->enqueueMessage( $table->getError() );
	        }
	    }
	
	    parent::edit($cachable, $urlparams);
	}
	
	/**
	 * Saves an item and redirects based on task
	 * @return void
	 */
	protected function doSave( )
	{
		$post = JRequest::get( 'post', '4' );
		$model = $this->getModel( $this->get( 'suffix' ) );
		$item = $model->getItem();
		$row = $model->getTable();
		
		$row->load( array( 'datasource_id'=>$item->getDataSourceID() ) );
		$row->bind( $post );
		
		if ( $row->save( ) )
		{
			$this->messagetype = 'message';
			$this->message = JText::_( 'Saved' );
			$this->row = $row;
			
			$model->clearCache();
		}
    		else
		{
		    $this->messagetype = 'warning';

		    $app = JFactory::getApplication();
		    $app->enqueueMessage( JText::_( 'Save Failed' ), 'warning');
		    
		    foreach ($row->getErrors() as $error)
		    {
		        $error = trim($error);
		        if (!empty($error)) {
		            $app->enqueueMessage($error, 'warning');
		        }
		    }
		}
		
		$task = JRequest::getVar( 'task' );
		$redirect = "index.php?option=com_calendar";
		switch ( $task )
		{
		    case "saveprev":
		        $redirect .= '&view=' . $this->get( 'suffix' );
		        // get prev in list
		        $surrounding = $model->getSurrounding( $model->getId( ) );
		        if ( !empty( $surrounding['prev'] ) )
		        {
		            $redirect .= '&task=edit&id=' . $surrounding['prev'];
		        }
		        break;
		    case "savenext":
		        $redirect .= '&view=' . $this->get( 'suffix' );
		        // get next in list
		        $surrounding = $model->getSurrounding( $model->getId( ) );
		        if ( !empty( $surrounding['next'] ) )
		        {
		            $redirect .= '&task=edit&id=' . $surrounding['next'];
		        }
		        break;
		    case "savenew":
		        $redirect .= '&view=' . $this->get( 'suffix' ) . '&task=add';
		        break;
		    case "apply":
		        $redirect .= '&view=' . $this->get( 'suffix' ) . '&task=edit&id=' . $model->getId( );
		        break;
		    case "save":
		    default:
		        $redirect .= "&view=" . $this->get( 'suffix' );
		        break;
		}
		
		$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	protected function doBoolean()
	{
	    $error = false;
	    $this->messagetype	= '';
	    $this->message 		= '';
	    $redirect = 'index.php?option='.$this->get('com').'&view='.$this->get('suffix');
	    $redirect = JRoute::_( $redirect, false );
	
	    $model = $this->getModel($this->get('suffix'));
	    $row = $model->getTable();
	
	    $cids = JRequest::getVar('cid', array (0), 'post', 'array');
	    $task = JRequest::getVar( 'task' );
	    $vals = explode('.', $task);
	
	    $field = $vals['0'];
	    $action = $vals['1'];
	
	    switch (strtolower($action))
	    {
	        case "switch":
	            $switch = '1';
	            break;
	        case "disable":
	            $enable = '0';
	            $switch = '0';
	            break;
	        case "enable":
	            $enable = '1';
	            $switch = '0';
	            break;
	        default:
	            $this->messagetype 	= 'notice';
	            $this->message 		= JText::_( "Invalid Task" );
	            $this->setRedirect( $redirect, $this->message, $this->messagetype );
	            return;
	            break;
	    }
	
	    if ( !in_array( $field, array_keys( $row->getProperties() ) ) )
	    {
	        $this->messagetype 	= 'notice';
	        $this->message 		= JText::_( "Invalid Field" ).": {$field}";
	        $this->setRedirect( $redirect, $this->message, $this->messagetype );
	        return;
	    }
	
	    foreach (@$cids as $cid)
	    {
	        unset($row);
	        $row = $model->getTable();
	        $item = $model->getItem( $cid );
	        $row->load( array('datasource_id'=>$cid ) );
	        $row->datasource_id = $cid;
	        $row->package_name = $item->name;
	
	        switch ($switch)
	        {
	            case "1":
	                $row->$field = $row->$field ? '0' : '1';
	                break;
	            case "0":
	            default:
	                $row->$field = $enable;
	                break;
	        }
	
	        if ( !$row->save() )
	        {
	            $this->message .= $row->getError();
	            $this->messagetype = 'notice';
	            $error = true;
	        }
	    }
	
	    if ($error)
	    {
	        $this->message = JText::_('Error') . ": " . $this->message;
	        $return = false;
	    }
	    else
	    {
	        $this->message = JText::_('Status Changed');
	        $return = true;
	    }

	    $model->clearCache();
	    
	    $this->setRedirect( $redirect, $this->message, $this->messagetype );
	    return $return;
	}
	
	/**
	 * Reorders multiple items (based on form input from list) and redirects to default layout
	 * Should be called from a public function in your controller.
	 *
	 * It is the responsibility of each child controller to check the validity of the request using
	 * (j1.6+) JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
	 * or
	 * (j1.5) JRequest::checkToken() or die( 'Invalid Token' );
	 *
	 * @return boolean
	 */
	protected function doOrdering()
	{
	    $error = false;
	    $this->messagetype	= '';
	    $this->message 		= '';
	    $redirect = 'index.php?option='.$this->get('com').'&view='.$this->get('suffix');
	    $redirect = JRoute::_( $redirect, false );
	
	    $model = $this->getModel($this->get('suffix'));
	    $row = $model->getTable();
	
	    $ordering = JRequest::getVar('ordering', array(0), 'post', 'array');
	    $cids = JRequest::getVar('cid', array (0), 'post', 'array');
	    foreach (@$cids as $cid)
	    {
	        $row->load( array('datasource_id'=>$cid ) );
	        $row->ordering = @$ordering[$cid];
	
	        if (!$row->store())
	        {
	            $this->message .= $row->getError();
	            $this->messagetype = 'notice';
	            $error = true;
	        }
	    }
	
	    $row->reorder();
	
	    if ($error)
	    {
	        $this->message = JText::_('Error') . " - " . $this->message;
	        $return = false;
	    }
	    else
	    {
	        $this->message = JText::_('Items Ordered');
	        $return = true;
	    }
	
	    $model->clearCache();
	    $this->setRedirect( $redirect, $this->message, $this->messagetype );
	    return $return;
	}
	
	public function clearAllCache()
	{
		$model = $this->getModel($this->get('suffix'));
		$model->clearCache();
		
		$app = JFactory::getApplication();
		$app->enqueueMessage( "Cleared packages cache" );
		
		//We don't check for a 'return' request parameter like we did in CalendarController#clearAllCache
		//because by forcing redirection to the packages listing, we force immediate repopulation of the cache
		
		$return = "index.php?option=com_calendar&view=packages";
		$this->setRedirect($return);
	}
	
}

?>