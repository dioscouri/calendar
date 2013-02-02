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
    
    <div id="date-navigation" class="wrap right">
        <ul class="controls controls-medium flat">
    		<li class="prev"><a href="<?php echo JRoute::_( 'index.php?option=com_calendar&view=month&date=' . $this->date_navigation->prev . $itemid_string ); ?>"><?php echo JText::_( "Previous" ); ?></a></li>
    		<li class="next"><a href="<?php echo JRoute::_( 'index.php?option=com_calendar&view=month&date=' . $this->date_navigation->next . $itemid_string ); ?>"><?php echo JText::_( "Next" ); ?></a></li>
        </ul>    
    </div>

    <div id="event-views" class="wrap">
        <ul class="dsc-flat">
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

	<div class="event-dates wrap">
		<?php if ( !empty($this->days) ) { ?>
            <ul class="events-list month-list wrap">
            <?php if (!empty($this->workingday->text) || !empty($this->workingday->url)) { ?>
                <li class="day-working wrap">
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
                    <li class="day-closed wrap">
                        <h3>
                           <?php echo date('j', $day->dateTime); ?><span class="end"><?php echo date('S', $day->dateTime); ?></span>
                           <span class="day"> <?php echo date('D', $day->dateTime); ?></span>
                        </h3>
                        <p>
                            <?php echo $day->text; ?>
                        </p>                        
                    </li>
                <?php } elseif (!empty($day->events)) { FB::log($day->dateMySQL); FB::log($day->events); ?>
        	        <li class="day wrap" data-date="<?php echo $day->dateMySQL; ?>">
                        <h3>
        	               <?php echo date('j', $day->dateTime); ?><span class="end"><?php echo date('S', $day->dateTime); ?></span>
                           <span class="day"> <?php echo date('D', $day->dateTime); ?></span>
                        </h3>
                        
                        <ul class="day-events dsc-flat">
        	                <?php foreach ( $day->events as $event ) : ?>
                            <li class="day-event" data-id="<?php echo $event->datasource_id; ?>">
            					<div class="image">
            						<a href="<?php echo JRoute::_( $event->link_view . $itemid_string ); ?>">
            						<img src="<?php echo $event->image_src; ?>" class="event-image">
            						</a>
            					</div>
                                <p class="time"><?php echo (date('i', strtotime( $event->start_time ) ) == '00') ? date( 'g a', strtotime( $event->start_time ) ) : date( 'g:i a', strtotime( $event->start_time ) ); ?></p>
            					<p>
            						<a href="<?php echo JRoute::_( $event->link_view . $itemid_string ); ?>">
                                	<?php echo $event->title_short; ?>
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
            </ul>
        <?php } else { ?>
        
            <div class="month_event">
                <?php echo JText::_( "No events scheduled for this month" ); ?>
            </div>
            
        <?php } ?>
	</div>

</div>
