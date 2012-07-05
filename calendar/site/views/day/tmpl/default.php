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
//Calendar::load( 'DisqusAPI', 'library.disqus.disqusapi' );
//$config = Calendar::getInstance();
//$disqus = new DisqusAPI( $config->get( 'disqus_api_key' ) );
?>

<div id="calendar_content">
    <?php
    if (@$state->filter_primary_categories == array('-1'))
    {
        ?>
        <div class="error">
        <?php echo JText::_( "Please select at least one category of events to display"); ?>
        </div>
        <?php
    }
    ?>
	<div id="event-nav" class="wrap">
        <div>
            <form name="calendarForm" id="calendarForm" method="post" action="<?php echo JRoute::_( "index.php?option=com_calendar" . $itemid_string ) ?>" >
                <?php $attribs = array( 'class' => 'inputbox', 'size' => '1', 'onchange' => 'document.calendarForm.submit();' ); ?>
                <?php Calendar::load( 'CalendarSelect', 'library.select' ); ?>
                <?php echo CalendarSelect::view( 'day', 'view', $attribs ); ?>
                <input name="current_date" value="<?php echo $date->current; ?>" type="hidden" />
                <input name="month" value="<?php echo $date->month; ?>" type="hidden" />
                <input name="year" value="<?php echo $date->year; ?>" type="hidden" />
                <input name="reset" value="0" type="hidden" />
            </form>
        </div>
        
        <div style="clear: both;"></div>

	</div>

    <div id="page-title" class="narrow">
        <ul class="horiz event-nav">
    		<li><a href="<?php echo JRoute::_( 'index.php?option=com_calendar&view=day&current_date=' . $date->prevdaydate . '&month=' . $date->prevmonth . '&year=' . $date->prevyear . $itemid_string ); ?>"><?php echo JText::_( "Previous" ); ?></a></li>
    		<li><a class="next" href="<?php echo JRoute::_( 'index.php?option=com_calendar&view=day&current_date=' . $date->nextdaydate . '&month=' . $date->nextmonth . '&year=' . $date->nextyear . $itemid_string ); ?>"><?php echo JText::_( "Next" ); ?></a></li>
        </ul>
    
        <h1><?php echo JText::_( $date->month_name ) . " " . date('j', strtotime($date->current) ); ?>, <?php echo $date->year; ?></h1>
    </div>
	
	<div class="calendar_dates">
		<?php if (!empty($this->days)) { ?>
            <ol class="events wrap">
	        <?php foreach ( $this->days as $day ) : ?>
                <?php if (!empty($day->isClosed)) { ?>
                    <li>
                        <div class="closed">
                            <?php echo $day->text; ?>
                        </div>                        
                    </li>
                <?php } else { ?>
                
                    <?php if (!empty($this->workingday)) { ?>
                        <li class="working_day">
                            <div>
                                <?php echo JText::_( $this->workingday->text ); ?>
                                
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
                    
    	            <?php foreach ( $day->events as $event ) : ?>
    	            <?php $eventCategory = CalendarHelperCategory::getCategoryName( $event->event_primary_category_id ); ?>
                    
            		<li>
            			<div class="events-image">
            				<a href="<?php echo JRoute::_( $event->link_view . $itemid_string ); ?>">
            					<img class="event_image" src="<?php echo $event->image_src; ?>" width="239" />
            				</a>
            			</div>
            			<div class="events-info">
            				<p class="date cat <?php echo $event->primary_category_class; ?>"><?php echo date('M j', strtotime($date->current) ); ?> at <?php echo (date('i', strtotime( $event->eventinstance_start_time ) ) == '00') ? date( 'g a', strtotime( $event->eventinstance_start_time ) ) : date( 'g:i a', strtotime( $event->eventinstance_start_time ) ); ?></p>
            				<p> 
            					<a href="<?php echo JRoute::_( $event->link_view . $itemid_string ); ?>">
                                <?php echo $event->event_short_title; ?>
                                </a>
                                    <?php 
                                    /*$disqus_thread_details = $disqus->threads->details( array( 'thread:ident'=>'eventinstance_' . $event->eventinstance_id, 'forum'=>$config->get( 'disqus_forum_id' ) ) );
                                    if (!empty($disqus_thread_details->posts)) { 
                                    ?>
                                    <a href="<?php echo JRoute::_( $event->link_view . $itemid_string ); ?>#disqus" class="disqus_count"><span><?php echo $disqus_thread_details->posts; ?></span></a>
                                    <?php } */ ?>
                            </p>
            			</div>
            			<?php if (!empty($event->actionbutton_url)) { ?>
                            <?php 
                            $handler = "{handler: 'iframe', size: {x: 740, y: 510} }";
                            ?>
            				<div class="events-action actionbutton_<?php echo $event->actionbutton_id; ?>">
            					<a href="<?php echo $event->actionbutton_url; ?>" class='modal' rel="<?php echo $handler; ?>">
            						<?php echo $event->actionbutton_name; ?>
            					</a>
                                <?php if (!empty($event->actionbutton_string)) { ?>
                                <p><?php echo $event->actionbutton_string; ?></p>
                                <?php } ?>
            				</div>
            			<?php } ?>
            		</li>
    	            
    	            <?php endforeach; ?>
	           <?php } ?>
	        <?php endforeach; ?>
            </ol>
            
        <?php } else { ?>
            <ol class="events wrap">
            
                <?php if (!empty($this->workingday)) { ?>
                    <li class="working_day">
                        <div>
                            <?php echo JText::_( $this->workingday->text ); ?>
                            
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
                            
                <li>
                    <div class="closed">
                        <?php echo JText::_( "No events scheduled for this day" ); ?>
                    </div>
                </li>
            </ol>
        <?php } ?>
        
        <div style="clear: both;"></div>
	</div>
    
    <div style="clear: both;"></div>
</div>