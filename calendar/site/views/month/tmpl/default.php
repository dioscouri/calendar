<?php defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_('behavior.modal');
$state = @$this->state;
$form = @$this->form;
$items = @$this->items;
$date = @$this->date;
//echo DSC::dump($this->availability);
$itemid_string = "";
?>

<div id="calendar-content" class="wrap month-view">

    <h2 class="content-title events-date-range left">
        <?php echo date( 'M j', strtotime( $state->filter_date_from ) ) . "-" . date( 'M j, Y', strtotime( $state->filter_date_to) ); ?>    
    </h2>
    
    <div id="date-navigation" class="wrap right">
        <ul class="controls controls-medium flat">
    		<li class="prev"><a href="<?php echo JRoute::_( 'index.php?option=com_calendar&view=month&date=' . $this->date_navigation->prev . $itemid_string ); ?>"><?php echo JText::_( "Previous" ); ?></a></li>
    		<li class="next"><a href="<?php echo JRoute::_( 'index.php?option=com_calendar&view=month&date=' . $this->date_navigation->next . $itemid_string ); ?>"><?php echo JText::_( "Next" ); ?></a></li>
        </ul>    
    </div>
	
	<div class="event-dates wrap clear">
		<?php if ( !empty($this->items) ) { ?>
            <ul class="events-list need-actionbuttons">
            <?php 
            foreach ($this->items as $key=>$item) 
            {
                ?>
                <li class="wrap eventinstance instance" id="<?php echo $item->getDataSourceID(); ?>">
                        
                        <a href="<?php echo JRoute::_( $item->link_detail . $itemid_string ); ?>" class="wrap left">
                        <div class="image-frame left small <?php if (empty($item->event_small_image)) { ?>no-image<?php } ?>">
                        	<?php if (!empty($item->event_small_image)) { ?>
                            <img class="small" src="<?php echo $item->event_small_image; ?>" alt="<?php echo htmlspecialchars( strip_tags( $item->title ) ); ?>" title="<?php echo htmlspecialchars( strip_tags( $item->title ) ); ?>" />
                            <?php } ?>
                        </div>
                        </a>
                        
                        <div class="instance-data inner wrap left">
                            <a href="<?php echo JRoute::_( $item->link_detail . $itemid_string ); ?>" class="wrap left">
                            <div class="overview left">
                                <h5>
                                    <?php
                                    $textAboveTitle = '';

                                    if ($item->isCourseSession && $item->series_title) {
                                        $textAboveTitle .= $item->series_title . " | " ;
                                    }
                                    elseif ($item->event->isPresentedByVisitingPresenter()) {
                                        $textAboveTitle .= 'Visiting Presenter | ';
                                    }
                                    
                                    $textAboveTitle .= $item->venue->name;
                                    echo $textAboveTitle;
                                    ?>
                                </h5>
                                <h3><?php echo $item->title; ?></h3>
                                <?php /* if (!empty($item->event_description_short)) { ?>
                                    <div class="description"><?php echo $item->event_description_short; ?></div>
                                <?php } */ ?>
                            </div>
                            <div class="date-time left">
                                <h3><?php echo date( 'l n/j', strtotime( $item->eventinstance_date ) ); ?></h3>
                                <h3><?php echo date( 'g:iA', strtotime( $item->eventinstance_start_time ) ); ?></h3>
                                <p class="date-range"><?php echo str_replace('-', '&#8211;', $item->date_range); ?></p>
                            </div>
                            </a>
                            <div class="actions wrap left">
                            
					        	<?php if ($actionbutton = $this->getmodel()->getActionbutton( $item, $this->availability ) ) { ?>
					        	<div class="actionbutton button h5 <?php echo implode( " ", $actionbutton->classes ); ?>">
									<?php if ($actionbutton->url) { ?>
										<a href="<?php echo $actionbutton->url; ?>" class="<?php echo implode( " ", $actionbutton->classes_span ); ?>">
									<?php } ?>
									
									<?php echo str_replace( "<br/>", " ", $actionbutton->label ); ?>
									
									<?php if ($actionbutton->url) { ?>
										</a>
									<?php } ?>
					        	</div>
					        	<?php } ?>
                                
                                <div class="user-actions right clear">
                                    <?php
                                        $favsHelper = new CalendarHelperFavorites();
                                        if ($favsHelper->isInstalled())
                                        {
                                            echo $favsHelper->getForm( $item->getDataSourceID(), $item->title ); 
                                        }                                
                                    ?>
                                    <div class="add-to-calendar">
                                        <ul class="content-left">
                                            <li class="summary content-left"><?php echo str_replace( array('&'), array('and'), $item->title ); ?></li>
                                            <li class="location content-left"><?php echo strip_tags( $item->venue->name ); ?></li>
                                            <li class="dtstart content-left" data-start="<?php echo gmdate('Ymd', strtotime( $item->startDateTime->format('Y-m-d H:i:s') )) . "T" . gmdate('His', strtotime( $item->startDateTime->format('Y-m-d H:i:s') ) ) . "Z"; ?>"></li>
                                            <li class="dtend content-left" data-end="<?php echo gmdate('Ymd', strtotime( $item->startDateTime->format('Y-m-d H:i:s') )) . "T" . gmdate('His', strtotime( $item->startDateTime->format('Y-m-d H:i:s') ) ) . "Z"; ?>"></li>
                                            <li class="ical content-left" data-url="<?php echo JRoute::_( "index.php?option=com_calendar&view=event&task=ical&format=raw&id=" . $item->getDataSourceID() . $itemid_string  ); ?>"></li>
                                            <li class="description content-left"><?php if (!empty($item->show->shortDescription)) { ?><?php echo trim( strip_tags( $item->show->shortDescription ) ); ?><?php } ?></li>                                        
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                </li>
                <?php
            }
            ?>
            </ul>
        <?php } else { ?>
        
            <div class="no-events">
                <?php echo JText::_( "No events found that match your search" ); ?>
            </div>
            
        <?php } ?>
	</div>

</div>