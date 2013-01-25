<?php
/**
 * @package		Calendar Types
 * @subpackage	mod_calendar_types
 * @copyright	Copyright (C) 2012 Dioscouri Design. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once dirname(__FILE__).'/helper.php';

$helper = new modCalendarTypesHelper( $params );
$items = $helper->getItems();

$itemid_string = (!empty($helper->itemid)) ? "&Itemid=" . $helper->itemid : '';

if (!empty($items)) {
    require JModuleHelper::getLayoutPath('mod_calendar_types', $params->get('layout', 'default'));
}
