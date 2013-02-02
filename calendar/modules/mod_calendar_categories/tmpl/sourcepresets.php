<?php
/**
 * @version    1.5
 * @package    Calendar
 * @author     Dioscouri Design
 * @link     http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<form class="" action="" method="post">
    <ul>       
    	<?php foreach ($sourcepresets as $sourcepreset) { ?>
    		<li class="">
                <input type="checkbox" name="filter_sourcepresets[]" value="<?php echo $sourcepreset->value; ?>" <?php if (in_array($sourcepreset->value, $state["filter_sourcepresets"])) { echo "checked"; } ?>/><?php echo $sourcepreset->title; ?>
            </li>
    	<?php } ?>
	</ul>

    <input type="hidden" name="Itemid" value="<?php echo $item_id; ?>" />
    <input type="hidden" name="calendar_id" value="<?php echo $calendar_id; ?>" />
</form>
