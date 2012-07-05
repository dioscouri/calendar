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

JHTML::_( 'script', 'common.js', 'media/com_calendar/js/' );
?>

<?php if ( $v != 2) { ?>
<div id="event-filter">
<?php } ?>
    <form id="calendarCategories" name="calendarCategories" action="" method="post">
        <ul class="primary_categories">       
        	<?php foreach (@$primary_categories as $category):?>
        		<li class="cat <?php echo $category->category_class; ?>">
                    <input type="checkbox" <?php echo $category->checked; ?> name="primary_category[]" value="<?php echo $category->category_id; ?>" onclick="<?php echo $onclick_primary; ?>" /><?php echo $category->category_name; ?>
                </li>
        	<?php endforeach;?>
    	</ul>
    
        <ul class="secondary_categories">
        	<?php foreach (@$secondary_categories as $category):?>					
        		<li>
                    <input type="radio" <?php echo $category->checked; ?> name="secondary_category" value="<?php echo $category->category_id; ?>" onclick="<?php echo $onclick_secondary; ?>" /><?php echo $category->category_name . ' (' . $category->instancescount . ')'; ?>
                </li>		
        	<?php endforeach;?>
        </ul>
        <input type="hidden" name="Itemid" value="<?php echo $item_id; ?>" />
        <input type="hidden" name="calendar_id" value="<?php echo $calendar_id; ?>" />
    </form>
<?php if ( $v != 2) { ?>
</div>
<?php } ?>