<?php defined( '_JEXEC' ) or die( 'Restricted access' );


JHTML::_('script', 'jquery-latest.min.js', 'http://code.jquery.com/');
JHTML::_( 'script', 'jquery.jcarousel.js', 'templates/default/js/' );
// JHTML::_( 'stylesheet', 'skin.css', 'modules/mod_calendar_eventinstances/css/jcarousel-skins/bgl-small/' );

$document = &JFactory::getDocument( );
$noConflict = "jQuery.noConflict();";
$document->addScriptDeclaration( $noConflict );

Calendar::load( 'DisqusAPI', 'library.disqus.disqusapi' );
$config = Calendar::getInstance();
$disqus = new DisqusAPI( $config->get( 'disqus_api_key' ) );
?>

<div class="slideshow">
    <ul class="horiz features small slideshow-content jcarousel-skin-bgl">
    <?php foreach ($vars->rows as $row) { ?>
        <li class="slideshow-slide">
            <div class="feature">
                <a href="<?php echo JRoute::_( $row->link_view . "&Itemid=" . $vars->item_id ); ?>">
                    <div class="feature-info">
                        <h4><?php echo JText::_( "Event" ); ?></h4>
                        <p class="date cat <?php echo $row->primary_category_class; ?>">
                            <?php echo date('M j', strtotime($row->eventinstance_date) ) . " " . JText::_( "at" ) ." ";  echo (date('i', strtotime( $row->eventinstance_start_time ) ) == '00') ? date( 'g a', strtotime( $row->eventinstance_start_time ) ) : date( 'g:i a', strtotime( $row->eventinstance_start_time ) ); ?>
                        </p>
                        <p>
                            <?php echo $row->event_short_title; ?>
                            <?php
                            $disqus_thread_details = $disqus->threads->details( array( 'thread:ident'=>'eventinstance_' . $row->eventinstance_id, 'forum'=>$config->get( 'disqus_forum_id' ) ) );
                            if (!empty($disqus_thread_details->posts)) { 
                            ?>
                            <span onclick="window.location='<?php echo JRoute::_( $row->link_view . "&Itemid=" . $vars->item_id ); ?>#disqus';" class="disqus_count"><?php echo $disqus_thread_details->posts; ?></span>
                            <?php } ?>
                        </p>
                    </div>

                    <img src="<?php echo $row->image_src; ?>" width="<?php echo $small_width; ?>" height="<?php echo $small_height; ?>" /> 
                    <span class="opacity"></span> 
                </a>
            </div>
        </li>
    <?php } ?>
    </ul>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.slideshow-content').jcarousel({
        scroll: 1
    });
});
</script>