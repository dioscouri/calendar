<?php defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_('behavior.modal');
JHTML::_( 'script', 'common.js', 'media/com_calendar/js/' );
$state = @$this->state;
$form = @$this->form;
$items = @$this->items;
$date = @$this->date;
Calendar::load( 'CalendarHelperCategory', 'helpers.category' );
if( empty($state) )
	$state = @$vars->state;
	
if( empty($items) )
	$items = @$vars->items;

if( empty($date) )
	$date = @$vars->date;
	
if( empty($this->days) )
	$this->days = @$vars->days;

if( empty($this->workingday) )
	$this->workingday = @$vars->workingday;
	
$itemid_string = "";
if (!empty($vars->item_id))
{
    $itemid_string = "&Itemid=" . $vars->item_id;
}
?>

<div id="calendar-content" class="wrap">
    <?php
    if (@$state->filter_primary_categories == array('-1'))
    {
    	/*
        ?>
        <div class="error">
        <?php echo JText::_( "Please select at least one category of events to display"); ?>
        </div>
        <?php
        */
    }
    ?>
    
    <div id="date-navigation" class="wrap">
        <ul class="horiz">
    		<li><a class="prev" href="<?php echo JRoute::_( 'index.php?option=com_calendar&view=month&reset=0&month=' . $date->prevmonth . '&year=' . $date->prevyear . $itemid_string ); ?>"><?php echo JText::_( "Previous" ); ?></a></li>
            <li class="date-range"><?php echo JText::_( $date->month_name ) . " 1-" . $date->numberofdays; ?></li>
    		<li><a class="next" href="<?php echo JRoute::_( 'index.php?option=com_calendar&view=month&reset=0&month=' . $date->nextmonth . '&year=' . $date->nextyear . $itemid_string ); ?>"><?php echo JText::_( "Next" ); ?></a></li>
        </ul>    
    </div>

    <div id="event-views" class="wrap">
        <ul class="horiz">
            <li class="week-view">
	            <form name="calendarForm" id="calendarForm" method="post" action="<?php echo JRoute::_( "index.php?option=com_calendar" . $itemid_string ) ?>" >
	            <input name="submit" type="submit" value="<?php echo JText::_( "Week" ); ?>" class="week-button" />
                <input name="view" value="week" type="hidden" />
				<input name="current_date" value="<?php echo $date->current; ?>" type="hidden" />
				<input name="month" value="<?php echo $date->month; ?>" type="hidden" />
				<input name="year" value="<?php echo $date->year; ?>" type="hidden" />
				<input name="reset" value="0" type="hidden" />
	            </form>	            
            </li>
    		<li class="month-view">
    			<span class="month-button"><?php echo JText::_( "Month" ); ?></span>
            </li>
        </ul>
    </div>

    <div id="event-types" class="wrap">
        <ul class="horiz">
            <li class="events-view"><?php echo JText::_( "Events" ); ?></li>
            <?php foreach ( $this->tabbed_types as $type ) { ?>
                <li class="<?php echo $type->type_name; ?>-view"><a href="<?php echo JRoute::_( "index.php?option=com_calendar&view=month&reset=0&layout=tab&type=" . $type->type_id . "&current_date=" . $date->current . $itemid_string ); ?>"><?php echo JText::_( $type->type_name ); ?></a></li>
            <?php } ?>
        </ul>
    </div>
	
	<div class="event-dates wrap">
		<?php if ( !empty($this->days) ) { ?>
            <ol class="events-month wrap">
            <?php if (!empty($this->workingday->text) || !empty($this->workingday->url)) { ?>
                <li class="working-day wrap">
                    <div>
                        <?php if (!empty($this->workingday->text)) { echo JText::_( $this->workingday->text ); } ?>
                        
                        <?php if (!empty($this->workingday->url)) { ?>
                            <span>
                            <a href="<?php echo $this->workingday->url; ?>">
                                <?php echo $this->workingday->url_label; ?>
                            </a>
                            </span>
                        <?php } ?>
                        
                    </div>
                </li>
            <?php } ?>
            
	        <?php foreach ( @$this->days as $day ) : ?>
                <?php if (!empty($day->isClosed)) { ?>
                    <li class="closed wrap">
                        <h3>
                           <?php echo date('j', $day->dateTime); ?><span class="end"><?php echo date('S', $day->dateTime); ?></span>
                           <span class="day"> <?php echo date('D', $day->dateTime); ?></span>
                        </h3>
                        <p>
                            <?php echo $day->text; ?>
                        </p>                        
                    </li>
                <?php } elseif (!empty($day->events)) { ?>
        	        <li class="wrap">
                        <h3>
        	               <?php echo date('j', $day->dateTime); ?><span class="end"><?php echo date('S', $day->dateTime); ?></span>
                           <span class="day"> <?php echo date('D', $day->dateTime); ?></span>
                        </h3>
                        
                        <ul class="horiz">
        	                <?php foreach ( $day->events as $event ) : ?>
        	                <?php $eventCategory = CalendarHelperCategory::getCategoryName( $event->event_primary_category_id ); ?>
                            <li>
            					<div class="image">
            						<a href="<?php echo JRoute::_( $event->link_view . $itemid_string ); ?>">
            						<img src="<?php echo $event->image_src; ?>" alt="event" width="159" height="89">
            						</a>
            					</div>
                                <p class="date cat <?php echo $event->primary_category_class; ?>"><?php echo (date('i', strtotime( $event->eventinstance_start_time ) ) == '00') ? date( 'g a', strtotime( $event->eventinstance_start_time ) ) : date( 'g:i a', strtotime( $event->eventinstance_start_time ) ); ?></p>
            					<p>
            						<a href="<?php echo JRoute::_( $event->link_view . $itemid_string ); ?>">
                                	<?php echo $event->event_short_title; ?>
                                	</a>
                                </p>
                                <?php if (!empty($event->actionbutton_url)) { ?>
                                    <?php 
                                    $handler = "{handler: 'iframe', size: {x: 740, y: 510} }";
                                    ?>
                                	<a class="action modal" href="<?php echo $event->actionbutton_url; ?>" rel="<?php echo $handler; ?>">
                                    	<?php echo $event->actionbutton_name; ?>
                                	</a>
                                <?php } ?>
            				</li>
        	            <?php endforeach; ?>
                        </ul>
        	        </li>
                <?php } ?>
	        <?php endforeach; ?>
            </ol>
        <?php } else { ?>
        
            <div class="month_event">
                <?php echo JText::_( "No events scheduled for this month" ); ?>
            </div>
            
        <?php } ?>
	</div>

</div>
