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
<div id="calendar">
<?php } ?>
    <form name='mini_calendar' id='mini_calendar' action='' method='post'>
    <div id="calendar-nav" class="wrap">
        <ul class="horiz">
    		<li><a href="<?php echo JRoute::_( 'index.php?option=com_calendar&view=' . $view . '&reset=0&current_date=' . $date->prevdate . '&month=' . $date->prevmonth . '&year=' . $date->prevyear . '&Itemid=' . $item_id ); ?>"><?php echo JText::_( "Previous" ); ?></a></li>
            <li><?php echo $header_title; ?></li>
    		<li><a class="next" href="<?php echo JRoute::_( 'index.php?option=com_calendar&view=' . $view . '&reset=0&current_date=' . $date->nextdate . '&month=' . $date->nextmonth . '&year=' . $date->nextyear . '&Itemid=' . $item_id ); ?>"><?php echo JText::_( "Next" ); ?></a></li>
        </ul>
    </div>
    
    <ol class="days horiz">
        <li class="wide">S</li>
        <li>M</li>
        <li>T</li>
        <li>W</li>
        <li>T</li>
        <li>F</li>
        <li class="wide">S</li>
    </ol>
    <ol class="horiz">
        <?php
            $n = 0;
            while ($n<$date->monthstartdayofweek)
            {
                ?>
                <li></li>
                <?php
                $n++;
            } 
            
            $daycounter = 1;
            for ( $i = 1; $i <= $date->numberofweeks; $i++ )
            {
                foreach ( $date->weekdays as $key => $value )
                {
                    $loop_date = date('Y-m-d', strtotime($date->year . '-' . $date->month . '-' . $daycounter));                    
                    
                    switch ( $date->handler )
                    {
                        case 'month':
                            $day_state = 'active';                               
                            break;
                        case 'week':
                        case 'three':
                            if( in_array( $loop_date, $date->range ) )
                            {
                                $day_state = 'active';
                            }
                            else 
                            {
                                $day_state = '';
                            }
                            break;
                        default:
                            $day_state = '';
                            break;
                    }

                    if( date('Y-m-d') == $loop_date ) $day_state = 'current';
                    if( $loop_date == date('Y-m-d', strtotime($date->current)) ) $day_state = 'active';
                
                    if ( $daycounter <= $date->numberofdays )
                    {
                        if ( $key == $date->weekstart ){
                            ?><li class="wide <?php echo $day_state; ?>"><?php
                        }else{
                            ?><li class="<?php echo $day_state; ?>"><?php
                        }
                            $link = 'index.php?option=com_calendar&view=' . $link_handler;
                            $link .= '&Itemid=' . $item_id;
                            $link .= '&year=' . $date->year;
                            $link .= '&month=' . $date->month;
                            $link .= '&current_date=' . $date->year . '-' . $date->month . '-' . $daycounter;
                            
                            $day = $date->year . '-' . $date->month . '-' . $daycounter;
                            if ($helper->dateHasEvent( $day )) {
                            ?>
                            <a href="<?php echo $link; ?>"><?php echo $daycounter; ?></a>
                            <?php
                            } else {
                                echo "<span class='inactive'>$daycounter</span>";
                            }
                            $daycounter++;
                            ?>
                        </li>
                        <?php
                    }
                }
            }
            
            $n = $date->monthenddayofweek;
            while ($n<6)
            {
                ?>
                <li></li>
                <?php
                $n++;
            } 
            ?>
    </ol>
    
    <input type="hidden" name="option" value="com_calendar" />
    <input type="hidden" name="view" value="<?php echo $date->handler; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="Itemid" value="<?php echo $item_id; ?>" />
    <input type="hidden" name="default_handler" value="<?php echo $default_handler; ?>" />
    
    </form>
<?php if ( $v != 2) { ?>
</div>
<?php } ?>
