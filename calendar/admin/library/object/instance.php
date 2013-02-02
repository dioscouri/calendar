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

class CalendarObjectInstance extends JObject
{
    public $datasource_id;
    public $id;
    public $title;
    public $title_short;
    public $subtitle;
    public $description;
    public $description_short;
    public $primary_image;
    public $date;
    public $start_time;
    public $end_time;
    public $location_id;
    public $location_name;
    public $link_view;
}