<?php
/**
 * @package		Calendar Packages
 * @subpackage	mod_calendar_packages
 * @copyright	Copyright (C) 2012 Dioscouri Design. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once dirname(__FILE__).'/helper.php';

$helper = new modCalendarPackagesHelper( $params );
$items = $helper->getItems();

$itemid_string = (!empty($helper->itemid)) ? "&Itemid=" . $helper->itemid : '';

if (!empty($items)) {
    require JModuleHelper::getLayoutPath('mod_calendar_packages', $params->get('layout', 'default'));
}
