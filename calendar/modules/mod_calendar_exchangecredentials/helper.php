<?php
/**
 * @version    1.5
 * @package    Calendar
 * @author     Dioscouri Design
 * @link     http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.model' );

class modCalendarExchangeCredentialsHelper
{
	/**
	 * Sets the modules params as a property of the object
	 * @param unknown_type $params
	 * @return unknown_type
	 */
	function __construct( $params )
	{
		$this->params = $params;
	}

}
?>