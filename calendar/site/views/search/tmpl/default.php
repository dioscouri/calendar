<?php defined( '_JEXEC' ) or die( 'Restricted access' );

JHTML::_( 'script', 'common.js', 'media/com_calendar/js/' );
$state = @$this->state;
$form = @$this->form;
$items = @$this->items;
$series = @$this->series;
$items_count = $this->pagination->total + count($series);

$app = &JFactory::getApplication( );
$router = &$app->getRouter( );
$vars = array( 'task' => 'search', 'search_type' => $state->search_type , 'filter_search' => $state->filter_search );
$router->setVars( $vars );
?> 
	<div id="page-title">
    	<a class="back" href="<?php echo JRoute::_( "index.php?option=com_calendar&view=month" ); ?>"><?php echo JText::_( 'Back to Calendar' ); ?></a>
        
		<div class="title-wrap">
            <h1><?php echo sprintf( JText::_( 'TPL_DEFAULT_X_RESULTS_FOR' ), (int) $items_count, $state->filter_search ); ?></h1>
		</div>
	</div>
    
	<?php if ( !empty( $this->pagination->total ) ) : ?>
	    <?php echo $this->pagination->getPagesLinks( ); ?>
	<?php endif; ?>
	
	<?php if ( !empty( $series ) && !empty($display_series) ) : ?>
	<div class="searchresults_series">		
		<div><?php echo JText::_( 'Series (' ) . count( $series ) . ')'; ?></div>
		<?php foreach ( @$series as $serie ) : ?>        
        <div class="event_item">
		
			<?php
					jimport( 'joomla.filesystem.file' );
					if ( !empty( $serie->series_full_image ) && JFile::exists( Calendar::getPath( 'series_images' ) . DS . $serie->series_full_image ) )
					{
						$table = JTable::getInstance( 'Series', 'CalendarTable' );
						$table->load( @$serie->series_id );
						Calendar::load( 'CalendarUrl', 'library.url' );
						echo '<img class="search_event_img" src="' . $table->getImage( 'full', true ) . '" />';
					}
			?>
						
			<div class="search_long_title">
			<a href="<?php echo JRoute::_( $serie->link_view )?>">
			<?php echo $serie->series_title; ?>
			</a>
		    </div>
							 
			<div class="search_short_desc">
			<?php echo htmlspecialchars_decode( $serie->series_description ); ?>
			</div>						
        </div>           
        <?php endforeach; ?>
    </div>	
    <?php endif; ?>
	
	<?php if ( !empty( $items ) ) : ?>
	<ul id="search-results" class="wrap">
		<?php foreach ( @$items as $item ) : ?>        
        <li>
			<?php
			    if (!empty($display_categories))
			    {
			        ?>
                    <div class="search_categories">
                    <?php
        			Calendar::load( 'CalendarHelperCategory', 'helpers.category' );
        			echo CalendarHelperCategory::getCategoryName( $item->event_primary_category_id );
        			$categories_list = CalendarHelperCategory::getSecondaryCategories( $item->event_id );
        			if( !empty($categories_list))
        			{
        				echo  ', ' . $categories_list . '<br/>';
        			}
    			    ?>
                    </div>
                    <?php
			    }
			?>
			
			<h3>
                <a href="<?php echo JRoute::_( $item->link_view )?>">
    			<?php
    			if ( !empty( $item->event_full_image ) )
    			{
    				$table = JTable::getInstance( 'Events', 'CalendarTable' );
    				$table->load( @$item->event_id );
    				echo '<img class="search_event_img" src="' . $table->getImage( 'full', true ) . '" />';
    			}
    			?>
                <?php echo $item->event_short_title; ?>
                </a>
		    </h3>
            
            <h4>
    			<?php echo JRoute::_( $item->link_view )?>
            </h4>
			
			<p>
				<span class="search_datetime">
                    <?php echo date('M j', strtotime($item->eventinstance_date) ); ?> at <?php echo (date('i', strtotime( $item->eventinstance_start_time ) ) == '00') ? date( 'g a', strtotime( $item->eventinstance_start_time ) ) : date( 'g:i a', strtotime( $item->eventinstance_start_time ) ); ?> 
				</span>
                <br/>
                
				<span class="search_actionbutton">
    				<?php
    				Calendar::load( 'CalendarHelperActionbutton', 'helpers.actionbutton' );
    				echo CalendarHelperActionbutton::getActionbuttonHTML( $item->actionbutton_id );
    				?>
				</span>

    			<span class="search_venue_name">
    	   		    <?php
					Calendar::load( 'CalendarHelperVenue', 'helpers.venue' );
					echo CalendarHelperVenue::getVenueName( $item->venue_id );
        			?>
    			</span>
                <br/>
                
    			<span class="search_short_desc">
        			<?php echo htmlspecialchars_decode( $item->event_short_description ); ?>
    			</span>
			</p>
        </li>           
        <?php endforeach; ?>
    </ul>
    
    <?php endif; ?>

    <?php if ( !empty( $this->pagination->total ) ) : ?>
        <?php echo $this->pagination->getPagesLinks( ); ?>
    <?php endif; ?>	