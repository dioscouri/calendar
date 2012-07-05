<?php defined( '_JEXEC' ) or die( 'Restricted access' );

Calendar::load( 'DisqusAPI', 'library.disqus.disqusapi' );
$config = CalendarConfig::getInstance();
$disqus = new DisqusAPI( $config->get( 'disqus_api_key' ) );
?>

<ul class="horiz features small">
<?php foreach ($vars->rows as $row) { ?>
<li>
    <div class="feature">
        <a href="<?php echo JRoute::_( $row->link_view . "&Itemid=" . $vars->item_id ); ?>">
            <div class="feature-info">
                <h4><?php echo JText::_( "Event" ); ?></h4>
                <p class="date cat <?php echo $row->primary_category_class; ?>">
                    <?php echo date('M j', strtotime($row->eventinstance_date) ) . " " . JText::_( "at" ) . " ";  echo (date('i', strtotime( $row->eventinstance_start_time ) ) == '00') ? date( 'g a', strtotime( $row->eventinstance_start_time ) ) : date( 'g:i a', strtotime( $row->eventinstance_start_time ) ); ?>
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
        
            <img src="<?php echo $row->image_src; ?>" width="<?php echo $vars->small_width; ?>" height="<?php echo $vars->small_height; ?>" /> 
            <span class="opacity"></span> 
        </a>
    </div>
</li>
<?php } ?>
</ul>