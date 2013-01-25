<?php defined( '_JEXEC' ) or die( 'Restricted access' );
$state = @$this->state;
$form = @$this->form;
$item = @$this->item;

if ( !class_exists( 'Calendar' ) ) {
	JLoader::register( "Calendar", JPATH_ADMINISTRATOR . "/components/com_calendar/defines.php" );
}
$cal_defines = Calendar::getInstance();
$itemid_string = $cal_defines->get( 'item_id' ) ? "&Itemid=" . $cal_defines->get( 'item_id' ) : "";

$this->item_id = JRequest::getInt('Itemid');
$helper = new DSCHelperString();
?>

<div id="calendar-content" class="event-detail detail-page">

    <h1 class="content-title event-title page-title">
        <?php echo $item->type_name; ?>
        <?php if (!empty($item->type_subtitle)) { ?>
        <div class="small italic"><?php echo $item->type_subtitle; ?></div>
        <?php } ?>
    </h1>
    
    <div class="wrap top-20">
	<?php 
	    $module_position_html = DSCModule::renderModules( 'event-type-above', $this->item_id );
	    echo $module_position_html;
    ?>        
    </div>

    <div class="secondary full wrap">
        <div class="category-desc wrap">
        	<p>
            <?php echo htmlspecialchars_decode( $item->type_description_1 ); ?>
            </p>
        </div>
    </div>

    <div id="event-details" class="tabs category-tabs">

            <ul id="package-items-list" class="article-default list">
                <?php foreach ($this->shows as $show) { ?>
                <li class="instance wrap">

                    <div class="wrap table full">
                        <div class="inner overview cell">
                        	
                            <h4><?php echo $show->title; ?></h4>
                            <?php if (!empty($show->primaryVenue)) { ?>
                            <h3><?php echo $show->primaryVenue->name; ?></h3>
                            <?php } ?>
                            <h3>
                            <?php 
                            $date_range = '';
                            if (!empty($show->firstDate)) {
                            	$date_range .= $show->firstDate->format('l n/j');
                            }
                            if (!empty($show->lastDate) && $show->lastDate != $show->firstDate) {
                            	if (!empty($date_range)) {
                            		$date_range .= '&#8211;';
                            	}
                            	$date_range .= $show->lastDate->format('l n/j');
                            }
							
                            $past = false;
                            if (!empty($date_range) && $show->lastDate->format('Y-m-d') < date('Y-m-d')) {
                            	echo "This event took place " . $date_range;
                            	$past = true;
                            } else {
                            	echo $date_range ? $date_range : "";
                            }
                            ?>
                            </h3>
                            
                            <div class="description">
	                            <?php // echo $helper->truncateString( $show->shortDescription, '250' ); ?>
	                            <?php echo $show->shortDescription; ?>
                            </div>
                            
                            <?php if ($past) { ?><span class="more-info bottom-40">&nbsp;</span><?php } else { ?>
                            <a href="<?php echo JRoute::_( "index.php?option=com_calendar&view=event&id=" . $show->getDataSourceID() . $itemid_string ); ?>" class="bottom-40">
	                            <span class="more-info">
	                            More Info
	                            </span>
                            </a>
                            <?php } ?>
                        </div>

                        <div class="cell">
							<img src="<?php echo $show->event_full_image; ?>" class="medium" />
                        </div>
					
                    </div>
                    
                </li>
                <?php } ?>
            </ul>

    </div>

</div>