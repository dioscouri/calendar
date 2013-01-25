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
<div id="calendar-view-filters" class="wrap full tab-wrapper">
    <div class="right h4 purple">
        <a class="bare" href="<?php echo JRoute::_( "index.php?option=com_calendar" . $itemid_string . "&date=" . $state['date'] . "&reset=1" ); ?>">
            <?php echo JText::_( "Reset" ); ?>
        </a>
    </div>
    <ul class="table tabs">
        <li class="tab cell open">
            <div class="inner">
                <?php echo JText::_( "Filter" ); ?>
            </div>
        </li>
    </ul>
</div>

<?php if (!empty($types)) { ?>
    <div class="filter event-types wrap">
        <div class="header h4">
            <?php echo JText::_( "Event Type" ); ?>
        </div>
        <ul class="filters">
        <?php foreach ($types as $item) { ?>
            <li class="<?php echo $item->filter_selected ? 'selected' : 'unselected'; ?>">
                <a href="<?php echo JRoute::_( "index.php?option=com_calendar&view=" . $state['view'] . "&filter_toggle[type]=" . $item->type_id . $itemid_string ); ?>">
                    <?php echo $item->type_name; ?>
                </a>
            </li>
        <?php } ?>
        </ul>
    </div>
<?php } ?>

<?php if (!empty($venues)) { ?>
    <div class="filter event-venues wrap">
        <div class="header h4">
            <?php echo JText::_( "Venue" ); ?>
        </div>
        <ul class="filters">
        <?php foreach ($venues as $item) { ?>
            <li class="<?php echo $item->filter_selected ? 'selected' : 'unselected'; ?>">
                <a href="<?php echo JRoute::_( "index.php?option=com_calendar&view=" . $state['view'] . "&filter_toggle[venue]=" . $item->getDataSourceID() . $itemid_string ); ?>">
                    <?php echo (!empty($item->venue_name) && $item->venue_name != $item->name) ? $item->venue_name : $item->name; ?>
                </a>
            </li>
        <?php } ?>
        </ul>
    </div>
<?php } ?>

<?php if (!empty($popular_searches)) { ?>
    <div class="filter popular-searches wrap">
        <div class="header h4">
            <?php echo JText::_( "Popular Searches" ); ?>
        </div>
        <ul class="filters">
        <?php foreach ($popular_searches as $item) { ?>
            <li class="">
                <a href="#">
                    <?php echo $item->title; ?>
                </a>
            </li>
        <?php } ?>
        </ul>
    </div>
<?php } ?>

<?php //echo DSC::dump($types); ?>
<?php //echo DSC::dump($venues); ?>