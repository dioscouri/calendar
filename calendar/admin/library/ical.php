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
    var $url = '';
    
    /**
     * 
     * @return string
     */
    function getContents()
    {
        /*
        BEGIN:VCALENDAR
        X-ORIGINAL-URL:http://www.guggenheim.org/new-york/calendar-and-events/2012/09/05/cobra-a-revolutionary-european-avant-garde-movement/i/11999
        VERSION:2.0
        METHOD:PUBLISH
        BEGIN:VEVENT
        DESCRIPTION;CHARSET=UTF-8:  Karel Appel, The Crying Crocodile Tries to Catch the Sun, 1956. Oil on canvas, 145.5 x 113.1 cm. The Solomon R. Guggenheim Foundation, Peggy Guggenheim Collection, Venice, 1976. © 2012 Karel Appel Foundation/Artists Rights Society (ARS), New York  $10, $7 members, FREE for students with a valid&nbsp;ID. Reserve a student ticket.  Dutch art historian Willemijn Stokvis discusses the radical postwar Cobra movement, which was part of the international tendency toward un art autre (art of another kind). Inspired by the art of so-called primitives, children, and the mentally ill, the group, which included Pierre Alechinsky, Karel Appel, and Asger Jorn, fostered idealistic, Marxist-inspired plans for a new folk art.
        SUMMARY;CHARSET=UTF-8:Cobra: A Revolutionary European Avant-Garde Movement
        DTSTART;VALUE=DATE-TIME:20120905T183000
        DTEND;VALUE=DATE-TIME:20120905T193000
        END:VEVENT
        END:VCALENDAR
        */
        $ics_contents  = "BEGIN:VCALENDAR\r\n";
        $ics_contents .= "X-ORIGINAL-URL:".$this->url."\r\n";
        $ics_contents .= "VERSION:2.0\r\n";
        $ics_contents .= "METHOD:PUBLISH\r\n";
        $ics_contents .= "BEGIN:VEVENT\r\n";
        $ics_contents .= "DESCRIPTION;CHARSET=UTF-8:".$this->description."\r\n";
        $ics_contents .= "SUMMARY;CHARSET=UTF-8:".$this->summary."\r\n";
        $ics_contents .= "DTSTART;VALUE=DATE-TIME:".$this->dtstart."\r\n";
        $ics_contents .= "DTEND;VALUE=DATE-TIME:".$this->dtend."\r\n";
        $ics_contents .= "LOCATION:".$this->location."\r\n";
        $ics_contents .= "END:VEVENT\r\n";
        $ics_contents .= "END:VCALENDAR\r\n";
        
        /*
         * Outlook 2003-compatible ICS (version 1.0)
         * Maybe enable this with a param?
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
        */
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
        
        //$this->dtstart = $date . "T" . $time . "Z";
        $this->dtstart = $date . "T" . $time;
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
        
        //$this->dtend = $date . "T" . $time . "Z";
        $this->dtend = $date . "T" . $time;
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
        $html      = str_replace("&ldquo\;", '"',      $html);
        $html      = str_replace("&rdquo\;", '"',      $html);
        
        $this->$property = $html;
    }
}