<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php
JHtml::_('script', 'carousel.js', 'modules/mod_featureditems_items/media/js/');
?>

<div class="calendar-upcoming-dizzys wrap <?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
    
    <?php if ($params->get( 'item_id')) { ?>
    <div id="dizzys-see-all" class="see-all h4 right purple">
        <a class="bare" href="<?php echo JRoute::_( "index.php?option=com_calendar" . $itemid_string . "&reset=1" ); ?>">
            <?php echo JText::_( "See All Events" ); ?>
        </a>
    </div>
    <?php } ?>
<?php
echo JHtml::_('tabs.start', $module->id . '-tabs', array( 'startOffset'=>0, 'useCookie'=>false ));

if (!empty($items->today_items)) 
{
    // display the Today tab
    echo JHtml::_('tabs.panel', JText::_( "Today" ) . " (" . date( 'l, M j, Y', strtotime( $items->start_date ) ) . ")", 'tab today' );
    ?>
        <div class="today_items">
        <div class="container">
        
        <ul class="slides list events need-actionbuttons">
        <?php 
        foreach ($items->today_items as $key=>$item) 
        {
            ?>
            <li class="slide wrap instance table" id="<?php echo $item->getDataSourceID(); ?>">
                    <div class="image-frame wrap small cell inner" data-position="<?php echo $key; ?>">
                        <?php if (!empty($item->event_small_image)) { ?>
                        <a href="<?php echo JRoute::_( $item->link_detail . $itemid_string ); ?>" class="wrap left">
                            <img class="small" src="<?php echo $item->event_small_image; ?>" alt="<?php echo htmlspecialchars( strip_tags( $item->title ) ); ?>" title="<?php echo htmlspecialchars( strip_tags( $item->title ) ); ?>" />
                        </a>
                        <?php } ?>
                    </div>

                    <div class="instance-data inner cell">
                        <a href="<?php echo JRoute::_( $item->link_detail . $itemid_string ); ?>" class="wrap left">
                            <div class="overview left span3">
                                <h5><?php echo $item->series_title ? $item->series_title . " | " : ''; echo $item->venue->name; ?></h5>
                                <h3><?php echo $item->title; ?></h3>
                                <?php /* if (!empty($item->show->shortDescription)) { ?>
                                    <p class="description"><?php echo $item->show->shortDescription; ?></p>
                                <?php } */ ?>
                            </div>
                            <div class="date-time left indent-20">
                                <h3><?php echo date( 'l n/j', strtotime( $item->eventinstance_date ) ); ?></h3>
                                <h3><?php echo date( 'g:iA', strtotime( $item->eventinstance_start_time ) ); ?></h3>
                                <p class="date-range"><?php echo $item->date_range; ?></p>
                            </div>
                        </a>
                        <div class="actions right wrap">
				        	<?php if ($actionbutton = $helper->getmodel()->getActionbutton( $item, $availability_today ) ) { ?>
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
                        </div>
                    </div>

            </li>
            <?php
        }
        ?>
        </ul>
        </div>
        </div>
        <?php 
}

if (!empty($items->this_week_items))
{
    // display the This Week tab
    echo JHtml::_('tabs.panel', JText::_( "This Week" ) . " (" . date( 'M j', strtotime( $items->start_date ) ) . "-" . date( 'j', strtotime( $items->end_date ) ) . ")", 'tab this-week' );
    ?>
        <div class="this_week_items">
        <div class="container">
        
        <ul class="slides list events need-actionbuttons">
        <?php
        foreach ($items->this_week_items as $key=>$item)
        {
            ?>
            <li class="slide wrap instance table" id="<?php echo $item->getDataSourceID(); ?>">
                    <div class="image-frame wrap small cell inner" data-position="<?php echo $key; ?>">
                        <?php if (!empty($item->event_small_image)) { ?>
                        <a href="<?php echo JRoute::_( $item->link_detail . $itemid_string ); ?>" class="wrap left">
                            <img class="small" src="<?php echo $item->event_small_image; ?>" alt="<?php echo htmlspecialchars( strip_tags( $item->title ) ); ?>" title="<?php echo htmlspecialchars( strip_tags( $item->title ) ); ?>" />
                        </a>
                        <?php } ?>
                    </div>
                    
                    <div class="instance-data inner cell">
                        <a href="<?php echo JRoute::_( $item->link_detail . $itemid_string ); ?>" class="wrap left">
                        <div class="overview left span3">
                            <h5><?php echo $item->series_title ? $item->series_title . " | " : ''; echo $item->venue->name; ?></h5>
                            <h3><?php echo $item->title; ?></h3>
                            <?php /* if (!empty($item->show->shortDescription)) { ?>
                                <p class="description"><?php echo $item->show->shortDescription; ?></p>
                            <?php } */ ?>
                        </div>
                        <div class="date-time left indent-20">
                            <h3><?php echo date( 'l n/j', strtotime( $item->eventinstance_date ) ); ?></h3>
                            <h3><?php echo date( 'g:iA', strtotime( $item->eventinstance_start_time ) ); ?></h3>
                            <p class="date-range"><?php echo $item->date_range; ?></p>
                        </div>
                        </a>
                        <div class="actions right wrap">
				        	<?php if ($actionbutton = $helper->getmodel()->getActionbutton( $item, $availability_week ) ) { ?>
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

                        </div>
                    </div>
            </li>
            <?php
        }
        ?>
        </ul>
        </div>
        </div>
        <?php
}

echo JHtml::_('tabs.end');

?>
</div>

<?php 
/* Not using slideshow for Dizzys 
if (count($items->today_items) > '2') { ?>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.today_items').carousel({
        resizeImages: 0,
        setSlidesCSS: 0,
        scroll: 2,
        onCompleteTransitionFunction: Jalc.displayActiveImage,
        transition: '<?php echo $params->get( 'slideshow_transition', 'down' ); ?>',
        start: <?php echo $params->get( 'slideshow_start', '0' ); ?>,
        autoPlayInterval: <?php echo $params->get( 'slideshow_autoplayinterval', '6000' ); ?>,
        autoPlay: <?php echo $params->get( 'slideshow_autoplay', '0' ); ?>,
        autoPlayStopOnClick: <?php echo $params->get( 'slideshow_autoplaystoponclick', '1' ); ?>,
        hideControls: <?php echo $params->get( 'slideshow_hidecontrols', '0' ); ?>,
        insertControls: <?php echo $params->get( 'slideshow_insertcontrols', '1' ); ?>,
        loop: <?php echo $params->get( 'slideshow_loop', '0' ); ?>,
        duration: <?php echo $params->get( 'slideshow_duration', '1000' ); ?>,
        sizeToBrowser: <?php echo $params->get( 'slideshow_sizetobrowser', '0' ); ?>,
        containerWidth: <?php echo $params->get( 'slideshow_container_width', '700' ); ?>,
        containerHeight: <?php echo $params->get( 'slideshow_container_height', '300' ); ?>,
        slideWidth: <?php echo $params->get( 'slideshow_slide_width', '700' ); ?>,
        slideHeight: <?php echo $params->get( 'slideshow_slide_height', '108' ); ?>
    });

    jQuery('.today_items').find('.controls').addClass('controls-vertical');

});
</script>
<?php } ?>

<?php if (count($items->this_week_items) > '2') { ?>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.this_week_items').carousel({
        resizeImages: 0,
        setSlidesCSS: 0,
        scroll: 2,
        onCompleteTransitionFunction: Jalc.displayActiveImage,
        transition: '<?php echo $params->get( 'slideshow_transition', 'down' ); ?>',
        start: <?php echo $params->get( 'slideshow_start', '0' ); ?>,
        autoPlayInterval: <?php echo $params->get( 'slideshow_autoplayinterval', '6000' ); ?>,
        autoPlay: <?php echo $params->get( 'slideshow_autoplay', '0' ); ?>,
        autoPlayStopOnClick: <?php echo $params->get( 'slideshow_autoplaystoponclick', '1' ); ?>,
        hideControls: <?php echo $params->get( 'slideshow_hidecontrols', '0' ); ?>,
        insertControls: <?php echo $params->get( 'slideshow_insertcontrols', '1' ); ?>,
        loop: <?php echo $params->get( 'slideshow_loop', '0' ); ?>,
        duration: <?php echo $params->get( 'slideshow_duration', '1000' ); ?>,
        sizeToBrowser: <?php echo $params->get( 'slideshow_sizetobrowser', '0' ); ?>,
        containerWidth: <?php echo $params->get( 'slideshow_container_width', '700' ); ?>,
        containerHeight: <?php echo $params->get( 'slideshow_container_height', '300' ); ?>,
        slideWidth: <?php echo $params->get( 'slideshow_slide_width', '700' ); ?>,
        slideHeight: <?php echo $params->get( 'slideshow_slide_height', '108' ); ?>
    });

    jQuery('.this_week_items').find('.controls').addClass('controls-vertical');

});
</script>
<?php } 
*/
?>