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

jimport( 'joomla.application.component.model' );

class CalendarHelperDiagnostics extends DSCHelperDiagnostics 
{
    /**
     * Performs basic checks on your installation to ensure it is OK
     * @return unknown_type
     */
    function checkInstallation()
    {
        $functions = array();
        $functions[] = 'checkEventinstanceTitle';
        $functions[] = 'checkEventtypeAdmin_only';
        $functions[] = 'checkEventtypeURL';
        $functions[] = 'addActionbuttonOverrideFields';
        $functions[] = 'addActionbuttonNoteFields';
        $functions[] = 'addEventActionbuttonOverrideFields';
        $functions[] = 'addEventActionbuttonOverrideTextField';
        $functions[] = 'checkEventinstancePrices';
        
        foreach ($functions as $function)
        {
            if (!$this->{$function}())
            {
                return $this->redirect( JText::_("COM_CALENDAR_".$function."_FAILED") .' :: '. $this->getError(), 'error' );
            }
        }
    }
    
    /**
     *
     * @param unknown_type $fieldname
     * @param unknown_type $value
     */
    protected function setCompleted( $fieldname, $value='1' )
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
        $config = JTable::getInstance( 'Config', 'CalendarTable' );
        $config->load( array( 'config_name'=>$fieldname ) );
        $config->config_name = $fieldname;
        $config->value = '1';
        $config->save();
    }
    
    /**
     *
     * @return boolean
     */
    private function checkEventinstanceTitle()
    {
        if (Calendar::getInstance()->get( __FUNCTION__, '0' ))
        {
            return true;
        }
    
        $table = '#__calendar_eventinstances';
        $definitions = array();
        $fields = array();
    
        $fields[] = "eventinstance_title";
        $definitions["eventinstance_title"] = "text NULL";
    
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            $this->setCompleted( __FUNCTION__ );
            return true;
        }
        return false;
    }
    
    /**
     *
     * @return boolean
     */
    private function checkEventtypeAdmin_only()
    {
    	if (Calendar::getInstance()->get( __FUNCTION__, '0' ))
    	{
    		return true;
    	}
    
    	$table = '#__calendar_types';
    	$definitions = array();
    	$fields = array();
    
    	$fields[] = "admin_only";
    	$definitions["admin_only"] = "tinyint(1) NOT NULL";
    
    	if ($this->insertTableFields( $table, $fields, $definitions ))
    	{
    		$this->setCompleted( __FUNCTION__ );
    		return true;
    	}
    	return false;
    }
    
    /**
     *
     * @return boolean
     */
    private function checkEventtypeURL()
    {
        if (Calendar::getInstance()->get( __FUNCTION__, '0' ))
        {
            return true;
        }
    
        $table = '#__calendar_types';
        $definitions = array();
        $fields = array();
    
        $fields[] = "type_url";
        $definitions["type_url"] = "text NOT NULL";
    
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            $this->setCompleted( __FUNCTION__ );
            return true;
        }
        return false;
    }
    
    /**
     *
     * @return boolean
     */
    private function addActionbuttonOverrideFields()
    {
        if (Calendar::getInstance()->get( __FUNCTION__, '0' ))
        {
            return true;
        }
    
        $table = '#__calendar_actionbuttons';
        $definitions = array();
        $fields = array();
    
        $fields[] = "actionbutton_override_main_site";
        $definitions["actionbutton_override_main_site"] = "tinyint(1) DEFAULT 0";
    
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            $this->setCompleted( __FUNCTION__ );
            return true;
        }
        return false;
    }
    
    /**
     *
     * @return boolean
     */
    private function addActionbuttonNoteFields()
    {
        if (Calendar::getInstance()->get( __FUNCTION__, '0' ))
        {
            return true;
        }
    
        $table = '#__calendar_actionbuttons';
        $definitions = array();
        $fields = array();
    
        $fields[] = "actionbutton_notes";
        $definitions["actionbutton_notes"] = "text";
    
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            $this->setCompleted( __FUNCTION__ );
            return true;
        }
        return false;
    }
    
    /**
    *
    * @return boolean
    */
    private function addEventActionbuttonOverrideFields()
    {
        if (Calendar::getInstance()->get( __FUNCTION__, '0' ))
        {
            return true;
        }
    
        $table = '#__calendar_events';
        $definitions = array();
        $fields = array();
    
        $fields[] = "event_actionbutton_url";
        $definitions["event_actionbutton_url"] = "text";
    
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            $this->setCompleted( __FUNCTION__ );
            return true;
        }
        return false;
    }
    
    /**
    *
    * @return boolean
    */
    private function addEventActionbuttonOverrideTextField()
    {
        if (Calendar::getInstance()->get( __FUNCTION__, '0' ))
        {
            return true;
        }
    
        $table = '#__calendar_events';
        $definitions = array();
        $fields = array();
    
        $fields[] = "event_actionbutton_label";
        $definitions["event_actionbutton_label"] = "text";
    
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            $this->setCompleted( __FUNCTION__ );
            return true;
        }
        return false;
    }
    
    /**
     *
     * @return boolean
     */
    private function checkEventinstancePrices()
    {
        if (Calendar::getInstance()->get( __FUNCTION__, '0' ))
        {
            return true;
        }
    
        $table = '#__calendar_eventinstances';
        $definitions = array();
        $fields = array();
    
        $fields[] = "eventinstance_prices";
        $definitions["eventinstance_prices"] = "text NULL";
    
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            $this->setCompleted( __FUNCTION__ );
            return true;
        }
        return false;
    }
}