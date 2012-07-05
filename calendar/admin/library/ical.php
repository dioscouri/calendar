<?php
/**
 * @version 1.5
 * @package Calendar
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class CalendarICal extends JObject
{
    var $version = '1.0';
    var $dtstart = '';
    var $dtend = '';
    var $location = '';
    var $description = '';
    var $summary = '';
    var $priority = '3';
    
    /**
     * 
     * @return string
     */
    function getContents()
    {
        // TODO Make this better, but for now just supports v1.0 ics files for Outlook 2003 support
        $ics_contents  = "BEGIN:VCALENDAR\r\n";
        $ics_contents .= "VERSION:1.0\r\n";
        $ics_contents .= "BEGIN:VEVENT\r\n";
        $ics_contents .= "DTSTART:".$this->dtstart."\r\n";
        $ics_contents .= "DTEND:".$this->dtend."\r\n";
        $ics_contents .= "LOCATION:".$this->location."\r\n";
        $ics_contents .= "DESCRIPTION:".$this->description."\r\n";
        $ics_contents .= "SUMMARY:".$this->summary."\r\n";
        $ics_contents .= "PRIORITY:".$this->priority."\r\n";
        $ics_contents .= "END:VEVENT\r\n";
        $ics_contents .= "END:VCALENDAR\r\n";
        return $ics_contents;
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $date
     * @param unknown_type $time
     * @return return_type
     */
    function setDTStart( $date, $time )
    {
        $date   = str_replace("-", "", $date);
        $time   = str_replace(":", "", $time);
        
        $this->dtstart = $date . "T" . $time . "Z";
        return $this->dtstart;
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $date
     * @param unknown_type $time
     * @return return_type
     */
    function setDTEnd( $date, $time )
    {
        $date   = str_replace("-", "", $date);
        $time   = str_replace(":", "", $time);
        
        $this->dtend = $date . "T" . $time . "Z";
        return $this->dtend;        
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $property
     * @param unknown_type $html
     * @return return_type
     */
    function setHtmlProperty( $property, $html )
    {
        $html      = str_replace(array("\r\n", "\r", "\n"), "\\n",   $html);
        $html      = str_replace("<br>", "\\n",   $html);
        $html      = str_replace("&amp;", "&",    $html);
        $html      = str_replace("&rarr;", "-->", $html);
        $html      = str_replace("&larr;", "<--", $html);
        $html      = str_replace(",", "\\,",      $html);
        $html      = str_replace(";", "\\;",      $html);
        
        $this->$property = $html;
    }
}