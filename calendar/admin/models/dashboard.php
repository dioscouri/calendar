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

Calendar::load( 'CalendarModelBase', 'models._base' );

class CalendarModelDashboard extends CalendarModelBase 
{
	function getTable($name='', $prefix=null, $options = array())
	{
		return parent::getTable('Config');
	}
}