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

$counter = 0;
$base_url = 'index.php?option=com_calendar&Itemid=' . $item_id;
?>

<div id="calendar">

    <div id="calendar-view-navigation" class="wrap full tab-wrapper">
        <ul class="full table tabs">
    		<li class="tab cell <?php if ($state['view'] == 'month') { echo "open"; } ?>">
    		    <a class="" href="<?php if ($state['view'] == 'month') { echo "javascript:void(0);"; } else { echo JRoute::_( $base_url . "&view=month&date=" . $state['date'] ); } ?>">
    		        <?php echo JText::_( "Month" ); ?>
    		    </a>
    		</li>
    		<li class="tab cell <?php if ($state['view'] == 'week') { echo "open"; } ?>">
    		    <a class="" href="<?php if ($state['view'] == 'week') { echo "javascript:void(0);"; } else { echo JRoute::_( $base_url . "&view=week&date=" . $state['date'] ); } ?>">
    		        <?php echo JText::_( "Week" ); ?>
    		    </a>
    		</li>
    		<li class="tab cell <?php if ($state['view'] == 'day') { echo "open"; } ?>">
    		    <a class="" href="<?php if ($state['view'] == 'day') { echo "javascript:void(0);"; } else { echo JRoute::_( $base_url . "&view=day&date=" . $state['date'] ); } ?>">
    		        <?php echo JText::_( "Day" ); ?>
    		    </a>
    		</li>
        </ul>    
    </div>

    <div id="current-month" class="wrap">
        <?php echo date( 'M j', strtotime( $state['date'] ) );
        if ($state['date'] == $date->currentdate_end) {
            echo date( ', Y', strtotime(  $state['date'] ) );
        } else {
            echo "-" . date( 'M j, Y', strtotime(  $date->currentdate_end ) );
        } ?>
        <div id="date-navigation" class="wrap right">
            <ul class="controls controls-medium flat">
        		<li class="prev"><a href="<?php echo JRoute::_( 'index.php?option=com_calendar&view='.$date->handler.'&date=' . $date->navigation->prev . $itemid_string ); ?>"><?php echo JText::_( "Previous" ); ?></a></li>
        		<li class="next"><a href="<?php echo JRoute::_( 'index.php?option=com_calendar&view='.$date->handler.'&date=' . $date->navigation->next . $itemid_string ); ?>"><?php echo JText::_( "Next" ); ?></a></li>
            </ul>    
        </div>
    </div>
    
    <ol class="days-of-week flat">
        <li>SU</li>
        <li>M</li>
        <li>T</li>
        <li>W</li>
        <li>T</li>
        <li>F</li>
        <li class="last">SA</li>
    </ol>
    
    <ol class="dates flat">
        <?php
            $n = ($date->currentdate_dayofweek_start == 0) ? 7 : $date->currentdate_dayofweek_start;
            while ($n>0)
            {
                $this_date = date( 'Y-m-d', strtotime( $state['date'] . ' -' . $n . ' days' ) );
                $counter++;
                ?>
                <li class="inactive <?php if (!($counter % 7)) { echo "last "; } ?>">
                    <a href="<?php echo JRoute::_( $base_url . "&view=" . $state['view'] . "&date=" . $this_date ); ?>">
                        <?php echo date( 'j', strtotime( $this_date ) ); ?>
                    </a>
                </li>
                <?php
                $n--;
            } 
            
            $this_date = $state['date'];
            for ( $i = 0; $this_date <= $date->currentdate_end; $i++, $this_date = date( 'Y-m-d', strtotime( $state['date'] . ' +' . $i . ' days' ) ) )
            {
                $counter++;
                $is_first = false;
                if ($this_date == $state['date']) {
                    $is_first = true;
                }
                ?>
                <li class="active <?php if ($is_first) { echo "today "; } if (!($counter % 7)) { echo "last "; } ?>">
                    <a href="<?php echo JRoute::_( $base_url . "&view=" . $state['view'] . "&date=" . $this_date ); ?>">
                        <?php echo date( 'j', strtotime( $this_date ) ); ?>
                    </a>
                </li>
                <?php
            }

            $n = 1;
            $remainder = (6  - $date->currentdate_dayofweek_end == 0) ? 7 : 6  - $date->currentdate_dayofweek_end;
            if ($counter + $remainder < $date->minimum_number_of_weeks * 7) {
                $remainder = $date->minimum_number_of_weeks * 7 - $counter;
            }
            
            while ($n<=$remainder)
            {
                $this_date = date( 'Y-m-d', strtotime( $date->currentdate_end . ' +' . $n . ' days' ) );
                $counter++;
                ?>
                <li class="inactive <?php if (!($counter % 7)) { echo "last "; } ?>">
                    <a href="<?php echo JRoute::_( $base_url . "&view=" . $state['view'] . "&date=" . $this_date ); ?>">
                        <?php echo date( 'j', strtotime( $this_date ) ); ?>
                    </a>
                    
                </li>
                <?php
                $n++;
            } 
            ?>
    </ol>

    <div class="pick-a-date-wrapper wrap">
        <ul class="pick-a-date table full">
            <li class="cell"><?php echo JText::_( "Pick a Date" ); ?></li>
            <li class="cell date-input"><input type="text" name="date" id="datepicker" value="<?php echo $state['date']; ?>" size="10"></li>
        </ul>
    </div>
    
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery( "#datepicker" ).datepicker({ minDate: 0, dateFormat: "yy-mm-dd", dayNamesMin: ["Su", "M", "T", "W", "T", "F", "Sa"], onSelect: function(dateText, inst) { window.location = '<?php echo JRoute::_( $base_url . "&view=" . $state['view'] . "&date=" ); ?>' + dateText; } });
});
</script>

