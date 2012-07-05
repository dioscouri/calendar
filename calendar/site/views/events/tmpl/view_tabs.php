<?php defined( '_JEXEC' ) or die( 'Restricted access' );

JHTML::_( 'script', 'common.js', 'media/com_calendar/js/' );
$item = @$this->row;
$state = @$this->state;
Calendar::load( 'CalendarArticle', 'library.article' );
Calendar::load( 'CalendarHelperICal', 'helpers.ical' );
Calendar::load( 'CalendarHelperVenue', 'helpers.venue' );
Calendar::load( 'CalendarHelperCategory', 'helpers.category' );

// get variables
$instance_id = JRequest::getVar('instance_id');
JModel::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_calendar' . DS . 'models' );
$model = JModel::getInstance( 'Eventinstances', 'CalendarModel' );
$model->setId( $instance_id );
$item = $model->getItem( );

$eventinstance_date = $item->eventinstance_date;
$eventinstance_time = $item->eventinstance_start_time;
$eventinstance_location = CalendarHelperVenue::getVenueName( $item->venue_id );

$eventCategory = CalendarHelperCategory::getCategoryName( $item->event_primary_category_id );
$categories_list = CalendarHelperCategory::getSecondaryCategories( $item->event_id );

$more_dates = count($this->instance->more_dates);
?>

		<div class="tabs">
			<ul class="horiz">
				<li><a href="javascript:void(0);" class="tab description active" onclick="calendarDisplayTab('description');"><span><?php echo JText::_( "Description" ); ?></span></a></li>
                <li><a href="javascript:void(0);" class="tab multimedia" onclick="calendarDisplayTab('multimedia');"><span><?php echo JText::_( 'Multimedia' ); ?></span></a></li>               
			</ul>
			<hr>
		</div>

        <div class="tabs-content" id="description">
        
            <!-- description tab -->
            <?php echo htmlspecialchars_decode( $this->instance->event_short_description ); ?>
            <!-- end description tab -->
            
            <?php if ($more_dates) { ?>
            <!-- more dates tab -->
            <a name='more_dates' href="javascript:void(0);" id="more_dates">
                <?php echo JText::_( 'More Dates' ); ?>
            </a>
            
            <ul class="more_dates">
                <?php foreach ($this->instance->more_dates as $mdate) { ?>
                    <li>
                        <a href="<?php echo JRoute::_( $mdate->link_view ); ?>">
                            <?php echo date('l F jS Y', strtotime( $mdate->eventinstance_date ) ) . " " . JText::_( "at" ) . " "; echo (date('i', strtotime( $mdate->eventinstance_start_time ) ) == '00') ? date( 'g a', strtotime( $mdate->eventinstance_start_time ) ) : date( 'g:i a', strtotime( $mdate->eventinstance_start_time ) ); ?>
                        </a>
                        <?php if (!empty($mdate->actionbutton_url)) { ?>
                        <a class="action" href="<?php echo JRoute::_( $mdate->actionbutton_url ); ?>"><?php echo JText::_( $mdate->actionbutton_name ); ?></a>
                        <?php } ?>                     
                    </li>
                <?php } ?>
            </ul>
            <!-- end more dates tab -->
            <?php } ?>
            
            <div style="clear: both;"></div>
        
        </div>
        
        <div class="tabs-content" id="multimedia" style="display: none;">
            <?php echo CalendarArticle::fromString( htmlspecialchars_decode( $this->instance->event_multimedia ) ); ?>
            <div style="clear: both;"></div>
        </div>
        
<script type="text/javascript">
    function calendarDisplayTab( id ) {
        $$('.tabs-content').each(function(el) {
            el.style.display = "none";
        });
        
        $(id).style.display = 'block';

        $$('a.tab').each(function(el) {
            el.removeClass('active');
        });
        
        $$('a.'+id).each(function(el) {
            el.addClass('active');
        });
    }
</script>