<?php defined( '_JEXEC' ) or die( 'Restricted access' );

$row = $vars->row;
?>

    <div class="feature">
        <a href="<?php echo JRoute::_( $row->link_view . "&Itemid=" . $vars->item_id ); ?>">
            <div class="feature-info">
                <h4><?php echo JText::_( "Event" ); ?></h4>
                <p class="date cat <?php echo $row->primary_category_class; ?>">
                    <?php echo date('M j', strtotime($row->eventinstance_date) ) . " " . JText::_( "at" ) . " ";  echo (date('i', strtotime( $row->eventinstance_start_time ) ) == '00') ? date( 'g a', strtotime( $row->eventinstance_start_time ) ) : date( 'g:i a', strtotime( $row->eventinstance_start_time ) ); ?>
                </p>
                <p><?php echo $row->event_short_title; ?></p>
            </div>
        
            <img src="<?php echo $row->image_src; ?>" width="<?php echo $vars->medium_width; ?>" height="<?php echo $vars->medium_height; ?>" /> 
            <span class="opacity"></span> 
        </a>
    </div>