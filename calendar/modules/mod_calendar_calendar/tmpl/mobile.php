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
$base_url = 'index.php?option=com_calendar&reset=1&Itemid=' . $item_id;
?>

<div id="calendar">

    <div id="current-month" class="full">
        <div id="date-navigation" class="full">
            <ul class="controls controls-medium full table">
        		<li class="cell prev text-center"><a href="<?php echo JRoute::_( 'index.php?&date=' . $date->currentdate_month_prev ); ?>"><?php echo '&lt;&lt;'; ?></a></li>
        		<li class="cell text-center"><?php echo date( "F Y", strtotime( $date->currentdate_month ) ); ?></li>
        		<li class="cell next text-center"><a href="<?php echo JRoute::_( 'index.php?&date=' . $date->currentdate_month_next ); ?>"><?php echo '&gt;&gt;'; ?></a></li>
            </ul>    
        </div>
    </div>
    
    <ol class="days-of-week table full">
        <li class="cell">SU</li>
        <li class="cell">M</li>
        <li class="cell">T</li>
        <li class="cell">W</li>
        <li class="cell">T</li>
        <li class="cell">F</li>
        <li class="cell last">SA</li>
    </ol>
    
    <ol class="dates table full">
        <?php
            $n = 0;
            while ($n<$date->currentdate_month_start)
            {
                $counter++;
                ?>
                <li class="cell">&nbsp;</li>
                <?php
                $n++;                
            }
            
            $this_date = $date->currentdate_month;
            for ( $i = 0; $this_date < $date->currentdate_month_next; $i++, $this_date = date( 'Y-m-d', strtotime( $date->currentdate_month . ' +' . $i . ' days' ) ) )
            {
                $counter++;
                if ($counter > '7') {
                    ?>
                    </ol>
                    <ol class="dates table full">
                    <?php
                    $counter = 1;
                }
                $is_first = false;
                if ($this_date == $state['date']) {
                    $is_first = true;
                }
                ?>
                <li class="active cell <?php if ($is_first) { echo "today "; } if (!($counter % 7)) { echo "last "; } ?>">
                    <a href="<?php echo JRoute::_( $base_url . "&view=day&date=" . $this_date ); ?>">
                        <?php echo date( 'j', strtotime( $this_date ) ); ?>
                    </a>
                </li>
                <?php
            }
            
            while ($counter<7)
            {
                $counter++;
                ?>
                <li class="cell">&nbsp;</li>
                <?php
            }
            ?>
    </ol>
    
</div>


