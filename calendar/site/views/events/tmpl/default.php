<?php defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_('behavior.modal');
$state = @$this->state;
$form = @$this->form;
$items = @$this->items;
$date = @$this->date;
$helper = new DSCHelperString();
$itemid_string = "";
?>

<div id="calendar-content" class="wrap events-view">
	
	<div class="event-dates wrap clear">
		<?php if ( !empty($this->items) ) { ?>
            <ul class="events-list table full">
            <?php 
            foreach ($this->items as $key=>$item) 
            {
                ?>
                <li class="wrap eventinstance row full hover-dark-purple-bg">
                    <a href="<?php echo JRoute::_( $item->link_detail . $itemid_string ); ?>" class="wrap full">                    
                        <div class="cell image <?php if (!empty($item->event_small_image)) { ?>has-image<?php } else { ?>no-image<?php } ?>">
                            <div class="image-frame">
                                <?php if (strtolower( substr( $item->primaryVenue->name, 0, 5 ) ) == 'dizzy') { /*?>
                                    <span class="overlay overlay-dizzys"></span>
                                <?php */ } ?>
                                <?php if (!empty($item->event_small_image)) { ?>
                                    <img class="small" src="<?php echo $item->event_small_image; ?>" alt="<?php echo htmlspecialchars( strip_tags( $item->title ) ); ?>" title="<?php echo htmlspecialchars( strip_tags( $item->title ) ); ?>" />
                                <?php } ?>
                            </div>
                        </div>
                        
                        <div class="cell event-data full">
                            <div class="overview">
                                <?php if (!empty($item->series) && is_array($item->series)) { ?>
                                    <h4 class="font">
                                        <?php 
                                        $key = 0;
                                        foreach ($item->series as $series) 
                                        {
                                            if ($key>0) { echo ", "; }
                                            echo $series->title;
                                            $key++;
                                        }
                                        ?>
                                    </h4>
                                <?php } ?>
                                <h2 class="font">
                                    <?php
                                    $string = $item->title;
                                    echo $helper->truncateString( $string, '60' );
                                    ?>
                                </h2>
                                <h3 class="font">
                                    <?php 
                                    	echo $item->firstDate->format( 'M j'); if ($item->firstDate->format( 'M') != $item->lastDate->format( 'M')) { echo "&ndash;"; echo $item->lastDate->format( 'M j'); } elseif ($item->firstDate->format( 'M j') != $item->lastDate->format( 'M j')) { echo "&ndash;"; echo $item->lastDate->format( 'j'); }
                                    ?>
                                </h3>
                            </div>
                        </div>
                    </a>
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