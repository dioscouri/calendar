<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?> 
<?php JHTML::_( 'script', 'common.js', 'media/com_calendar/js/' ); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>
<?php jimport('joomla.html.pane'); ?>
<?php $tabs = &JPane::getInstance( 'tabs' ); ?>
<?php $date = @$this->date; ?>
<?php $events = @$this->events; ?>
<?php $config = CalendarConfig::getInstance( ); ?>
<?php 
Calendar::load( 'CalendarHelperCategory', 'helpers.category' );
$eventCategory = CalendarHelperCategory::getCategoryName( $row->series_primary_category_id );
$itemid_string = "";
if (!empty($vars->item_id))
{
    $itemid_string = "&Itemid=" . $vars->item_id;
}
?>

<div id="calendar-content">

    <div id="series-info" class="wrap">
        <div class="col-right">
            <p class="date cat <?php echo $row->primary_category_class; ?>"><?php echo $eventCategory; ?><?php  if( !empty($categories_list)) echo ", " . $categories_list; ?></p>
            <span class="share">Share</span>
            
            <h1><?php echo $row->series_title; ?></h1>

          	<?php echo htmlspecialchars_decode($row->series_description); ?>
            
            <?php if (!empty($row->actionbutton_url)) { ?>
                <div class="action-button">
                    <a href="<?php echo JRoute::_( $row->actionbutton_url ); ?>" class='modal' rel="<?php echo $handler; ?>"><?php echo JText::_( $row->actionbutton_name ); ?></a>
                    <p><?php echo JText::_( $row->actionbutton_string ); ?></p>
                </div>
            <?php } ?>
            
            <div class="add-to-cal">
                <a href="<?php echo JRoute::_( "index.php?option=com_calendar&view=events&task=downloadical&format=raw&instance_id=" . $row->eventinstance_id ); ?>">
                    <?php echo JText::_( 'Add to calendar' ); ?>
                </a>
            </div>
        </div>
        
        <div class="col-left">
            <div id="series-image">
                <img src="<?php echo $row->image_src; ?>" >
                <?php if (!empty($row->event_image_caption)) { ?>
                <div id="series-image-caption">
                    <?php echo $row->event_image_caption; ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="event-content">
        <?php if (!empty($events)) { ?>    
            <h4 class="panel-trigger arrow-off panel" id="panel-events"><?php echo JText::_( "Events" ); ?></h4>
            <div class="panel-content" id="panel-container-events">
            
			    <ol class="events wrap">
			        <?php foreach ($events as $event) { ?>
			        <?php $eventCategory = CalendarHelperCategory::getCategoryName( $event->event_primary_category_id ); ?>
			        
			        <li>
			            <div class="events-image">
			                <a href="<?php echo JRoute::_( $event->link_view . $itemid_string ); ?>">
			                    <img class="event_image" src="<?php echo $event->image_src; ?>" width="239" />
			                </a>
			            </div>
			            <div class="events-info">
			                <p class="date cat <?php echo $event->primary_category_class; ?>"><?php echo date('M j', strtotime($event->eventinstance_date) ); ?> at <?php echo (date('i', strtotime( $event->eventinstance_start_time ) ) == '00') ? date( 'g a', strtotime( $event->eventinstance_start_time ) ) : date( 'g:i a', strtotime( $event->eventinstance_start_time ) ); ?></p>
			                <p>
			    				<a href="<?php echo JRoute::_( $event->link_view . $itemid_string ); ?>">
			                    <?php echo $event->event_short_title; ?>
			                    </a>
			                </p>
			            </div>
			            
						<?php if (!empty($event->actionbutton_url)) { ?>
							<div class="events-action actionbutton_<?php echo $event->actionbutton_id; ?>">
								<a href="<?php echo $event->actionbutton_url; ?>">
									<?php echo $event->actionbutton_name; ?>
								</a>
			                    <?php if (!empty($event->actionbutton_string)) { ?>
			                    <p><?php echo $event->actionbutton_string; ?></p>
			                    <?php } ?>
							</div>
						<?php } ?>
			            
			        </li>
			        <?php } ?>
			    </ol>
                
            </div>
		<?php } ?>
    	    
		<?php if (!empty($row->series_multimedia)) { ?>
    	    <h4 class="panel-trigger arrow-off panel" id="panel-multimedia"><?php echo JText::_( "Multimedia" ); ?></h4>
            <div class="panel-content" id="panel-container-multimedia">
                <?php echo $row->series_multimedia; ?>
            </div>
    	<?php } ?>
            
    	<?php if (!empty($row->series_custom)) { ?>
    	    <h4 class="panel-trigger arrow-off panel" id="panel-custom"><?php echo JText::_( "Custom" ); ?></h4>
            <div class="panel-content" id="panel-container-custom">
                <?php echo $row->series_custom; ?>
            </div>
    	<?php } ?>

    </div>

</div>