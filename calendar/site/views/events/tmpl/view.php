<?php defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_('behavior.modal');
JHTML::_( 'script', 'common.js', 'media/com_calendar/js/' );
$item = @$this->row;
$state = @$this->state;
$eventCategory = CalendarHelperCategory::getCategoryName( $this->instance->event_primary_category_id );
$categories_list = CalendarHelperCategory::getSecondaryCategories( $this->instance->event_id );
$more_dates = count($this->instance->more_dates);
$back_url = $this->back_url;
$uri = JURI::getInstance();
$config = CalendarConfig::getInstance();

$handler = "{handler: 'iframe', size: {x: 740, y: 510} }";
?>
<div id="calendar-content">

    <?php if (!empty($back_url)) { ?>
    	<a class="back" href="<?php echo JRoute::_( $back_url ); ?>"><?php echo JText::_( "Back to calendar" ); ?></a>
	<?php } ?>
            
	<div id="event-info" class="wrap">
		<div class="col-right">
            <p class="date cat <?php echo $this->instance->primary_category_class; ?>"><?php echo $eventCategory; ?><?php  if( !empty($categories_list)) echo ", " . $categories_list; ?></p>
            <span class="share">Share</span>
            
            <h1><?php echo $this->instance->event_long_title; ?></h1>

			<h2>
    			<?php echo date('l, F j, Y,', strtotime( $this->instance->eventinstance_date ) ) . " @ "; 
                echo (date('i', strtotime( $this->instance->eventinstance_start_time ) ) == '00') ? date( 'g a', strtotime( $this->instance->eventinstance_start_time ) ) : date( 'g:i a', strtotime( $this->instance->eventinstance_start_time ) ); ?>
            </h2>
			
            <?php if (!empty($this->instance->actionbutton_url)) { ?>
	            <div class="action-button">
	                <a href="<?php echo JRoute::_( $this->instance->actionbutton_url ); ?>" class='modal' rel="<?php echo $handler; ?>"><?php echo JText::_( $this->instance->actionbutton_name ); ?></a>
	                <p><?php echo JText::_( $this->instance->actionbutton_string ); ?></p>
	            </div>
            <?php } ?>
            
            <?php if (!empty($this->instance->venue_name)) { ?>
    		    <div><?php echo $this->instance->venue_name; ?></div>
            <?php } ?>
                
			<?php if( !empty( $this->instance->series_id )){	?>
			    <div><?php echo JText::_( "This is a" ) . " " . $this->instance->series_name . " " . JText::_( "event"); ?>.
                <a href="<?php echo JRoute::_( 'index.php?option=com_calendar&view=series&id=' . (int) $this->instance->series_id ); ?>"><?php echo JText::_( "Go to series page" ); ?></a></div>
			<?php } ?>
            
            <div class="add-to-cal">
	            <a href="<?php echo JRoute::_( "index.php?option=com_calendar&view=events&task=downloadical&format=raw&instance_id=" . $this->instance->eventinstance_id ); ?>">
	                <?php echo JText::_( 'Add to calendar' ); ?>
	            </a>
            </div>
		</div>
        
        <div class="col-left">
		    <div id="event-image">
		        <img src="<?php echo $this->instance->image_src; ?>" >
                <?php if (!empty($this->instance->event_image_caption)) { ?>
                <div id="event-image-caption">
                    <?php echo $this->instance->event_image_caption; ?>
                </div>
                <?php } ?>
		    </div>
        </div>
	</div>

    <div class="event-content">
        <h4 class="panel-trigger arrow-off panel first" id="panel-description"><?php echo JText::_( "Description" ); ?></h4>
        <div class="panel-content" id="panel-container-description">
        
            <?php if ($more_dates) { ?>
            <p id="more-dates">
                <?php echo JText::sprintf( 'There are x dates for this event', $more_dates ); ?>
            </p>
            
            <ul class="more_dates">
                <?php foreach ($this->instance->more_dates as $mdate) { ?>
                    <li>
                        <a href="<?php echo JRoute::_( $mdate->link_view ); ?>">
                            <?php echo date('l, F j, Y,', strtotime( $mdate->eventinstance_date ) ) . " "; 
                            echo (date('i', strtotime( $mdate->eventinstance_start_time ) ) == '00') ? date( 'g', strtotime( $mdate->eventinstance_start_time ) ) : date( 'g:i', strtotime( $mdate->eventinstance_start_time ) ); ?>-<?php echo (date('i', strtotime( $mdate->eventinstance_end_time ) ) == '00') ? date( 'g a', strtotime( $mdate->eventinstance_end_time ) ) : date( 'g:i a', strtotime( $mdate->eventinstance_end_time ) ); ?>
                        </a>
                        <?php if (!empty($mdate->actionbutton_url)) { ?>
                        <a class="action" href="<?php echo JRoute::_( $mdate->actionbutton_url ); ?>" class='modal' rel="<?php echo $handler; ?>"><?php echo JText::_( $mdate->actionbutton_name ); ?></a>
                        <?php } ?>                     
                    </li>
                <?php } ?>
            </ul>
            <?php } ?>
            
            <div class="event-description">
                <div class="event-description-main">
                    <?php echo htmlspecialchars_decode( $this->instance->event_short_description ); ?>
                </div>
            </div>
            
        </div>

	    <?php if (!empty($this->instance->event_multimedia)) { ?>
	    <h4 class="panel-trigger arrow-off panel" id="panel-multimedia"><?php echo JText::_( "Multimedia" ); ?></h4>
        <div class="panel-content" id="panel-container-multimedia">
            <?php echo $this->instance->event_multimedia; ?>
        </div>
	    <?php } ?>
                        
        <h4 class="panel-trigger arrow-off panel last" id="panel-related_events"><?php echo JText::_( "Related Events" ); ?></h4>
        <div class="panel-content" id="panel-container-related_events">
		    <?php $related_events = $this->getModel()->getRelated( $this->instance->eventinstance_id, 5 ); ?>
		    
		    <?php if (!empty($related_events)) { ?>
		        <ul class="horiz features small">
		            <?php foreach ($related_events as $related_event) { ?>
		            <li>
		                <a href="<?php echo JRoute::_( $related_event->link_view ); ?>">
                            <img src="<?php echo $related_event->image_src; ?>">
		                    <div class="feature-info">
		                        <p class="cat <?php echo $related_event->primary_category_class; ?>">
		                            <?php echo $related_event->primary_category_title; ?>
		                        </p>
		                        <h4>
		                            <?php echo $related_event->event_short_title; ?>
		                        </h4>
                                <span class="share">Share</span>
                                <p class="date">
                                    <?php echo date('M j', strtotime( $related_event->eventinstance_date ) ) . " " . JText::_( "at" ) . " "; echo (date('i', strtotime( $related_event->eventinstance_start_time ) ) == '00') ? date( 'g a', strtotime( $related_event->eventinstance_start_time ) ) : date( 'g:i a', strtotime( $related_event->eventinstance_start_time ) ); ?>
                                </p>
                                <p class="description">
                                    <?php echo $related_event->event_short_description; ?>
                                </p>
                                
                                <?php if (!empty($related_event->actionbutton_url)) { ?>
                                    <div class="action-button">
                                        <a href="<?php echo JRoute::_( $related_event->actionbutton_url ); ?>" class='modal' rel="<?php echo $handler; ?>"><?php echo JText::_( $related_event->actionbutton_name ); ?></a>
                                        <p><?php echo JText::_( $related_event->actionbutton_string ); ?></p>
                                    </div>
                                <?php } ?>
                                                                
                                <div class="add-to-cal">
                    	            <a href="<?php echo JRoute::_( "index.php?option=com_calendar&view=events&task=downloadical&format=raw&instance_id=" . $related_event->eventinstance_id ); ?>">
                    	                <?php echo JText::_( 'Add to calendar' ); ?>
                    	            </a>
                                </div>
		                    </div>
		                </a>
		            </li>
		            <?php } ?>
		        </ul>
		    
		    <?php } ?>
        </div>
        
    </div>
</div>    
