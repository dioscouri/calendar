<?php defined( '_JEXEC' ) or die( 'Restricted access' );
$state = @$this->state;
$form = @$this->form;
$item = @$this->item;

$item_id = (!empty($this->item_id)) ? $this->item_id : JRequest::getInt( 'Itemid' );
$itemid_string = '';
if ($item_id) {
    $itemid_string = '&Itemid=' . $item_id;
}
$actionbutton = $this->getmodel()->getActionbutton( $item, $this->availability );
?>

<script type="text/javascript">
jQuery(document).ready(function(){
    url = jQuery('#purchase-url');    
    url.attr('href', jQuery('#select-instance option:selected').val() );

    var title = jQuery('h1.event-title span.main-title');
    var date = jQuery('#date');
    var time = jQuery('#time');
    var prices = jQuery('#display-prices');
    var description = jQuery('.instance-description');
        
    select = jQuery('#select-instance');
    select.change(function(){
        url.attr('href', jQuery('#select-instance option:selected').val() );
        time.text( jQuery('#select-instance option:selected').attr( 'data-time' ) );
        date.text( jQuery('#select-instance option:selected').attr( 'data-date' ) );
        title.text( jQuery('<div/>').html( jQuery('#select-instance option:selected').attr( 'data-title' ) ).text() );
        description.html( jQuery('<div/>').html( jQuery('#select-instance option:selected').attr( 'data-description' ) ).html() );
        prices.text( jQuery('<div/>').html( jQuery('#select-instance option:selected').attr( 'data-prices' ) ).text() );
    });
});
</script>

<div id="calendar-content" class="event-detail detail-page">

    <h1 class="content-title event-title page-title">
        <span class="main-title">
        <?php echo $item->title; ?>
        </span>
        <?php if (!empty($item->subtitle)) { ?>
        <div class="small italic">
            <?php echo $item->subtitle; ?>
        </div>
        <?php } ?>
    </h1>
    
    <div id="date-time-venue-buy" class="table wrap full">
        <div class="cell date-time wrap red">
            <h2><span id="date"><?php echo date( 'l, F j, Y', strtotime( $item->getEventInstance_Date() ) ); ?></span></h2>
            <h2><span id="time"><?php echo $item->startDateTime->format('g:ia'); ?></span> | <?php echo $item->getVenue_Name(); ?></h2>
            <h2><span id="display-prices"><?php if (!empty($item->eventinstance_display_prices)) { echo $item->eventinstance_display_prices; } ?></span></h2>
        </div>
        
        <?php if (!empty($this->instances)) { ?>
        <div class="cell other-instances wrap">
            <select id="select-instance">
                <?php foreach ($this->instances as $instance) { ?>
                    <option 
                        value="<?php echo ($actionbutton && !empty($actionbutton->actionbutton_override_main_site) && !empty($actionbutton->url) && $actionbutton->url != $this->getmodel()->getPurchaseURL( $instance )) ? $actionbutton->url : $this->getmodel()->getPurchaseURL( $instance ); ?>"
                        data-time="<?php echo $instance->startDateTime->format( 'g:ia' ); ?>"
                        data-date="<?php echo $instance->startDateTime->format( 'l, F j Y' ); ?>"
                        data-title="<?php echo htmlspecialchars( $instance->title ); ?>"
                        data-description="<?php echo htmlspecialchars( $instance->event_description_short ); ?>"
                        data-prices="<?php echo htmlspecialchars( $instance->eventinstance_display_prices ); ?>"
                        <?php if ($instance->getDataSourceID() == $item->getDataSourceID()) { echo "selected='selected'"; } ?>
                    >
                        <?php echo $instance->startDateTime->format( 'l, F j g:ia' ); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <?php } ?>
        
        <div class="cell buy buttons wrap right">

        	<?php if ($actionbutton) { ?>
        	<div class="right actionbutton button h5 <?php echo implode( " ", $actionbutton->classes ); ?>">
				<?php if ($actionbutton->url) { ?>
					<a id="purchase-url" href="<?php echo $actionbutton->url; ?>" class="<?php echo implode( " ", $actionbutton->classes_span ); ?>">
				<?php } ?>
				
				<?php echo str_replace( "<br/>", " ", $actionbutton->label ); ?>
				
				<?php if ($actionbutton->url) { ?>
					</a>
				<?php } ?>
        	</div>
        	<?php } ?>
            
            <div class="user-actions left clear wrap">
                <div id="share-button-wrapper" class="wrap left">
                    <div id="event-share-button" class="share-button wrap medium-grey-bg">
                        <div class="h4">
                            Share
                            <img class="arrow-down" src="<?php echo JURI::root(); ?>templates/default/images/arrow-down.png" alt="&#9660;">
                        
                            <div class="share-options wrap" style="display: none;">
                                <!-- AddThis Button BEGIN -->
                                <?php /* ?><div class="addthis_toolbox" addthis:url="<?php echo JRoute::_( "index.php?option=com_calendar&view=event&id=" . $item->getDataSourceID() . $itemid_string ); ?>"> */ ?>
                                <div class="addthis_toolbox">
                                    <ul class="networks">
                                        <li class="wrap fb">
                                            <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
                                        </li>
                                        <li class="wrap twitter">
                                            <a class="addthis_button_tweet"></a>
                                        </li>
                                    </ul>
    
                                    <ul class="manual">
                                        <li class="wrap email">
                                            <a class="addthis_button_email">
                                                <img class="left" src="templates/default/images/Email.png" alt="Share via Email" />
                                                <span class="h5 left">E-Mail</span>
                                            </a>
                                        </li>
                                        <?php /* ?>
                                        <li class="wrap clip">
                                        <div class="copy-clipboard">
                                        <img class="left " src="templates/default/images/CopyLink.png" alt="Copy Link" />
                                            <span class="h5 left">
                                                Copy Link
                                            </span>
                                        </div>
                                        </li>
                                        */ ?>
                                    </ul>
    
                                </div>
                                <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
                                <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-503d11b4393f1d4b"></script>
                                <!-- AddThis Button END -->                        
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                	$defines = Calendar::getInstance();
                    $favsHelper = new CalendarHelperFavorites();
                    if ($favsHelper->isInstalled() && $defines->get('enable_favorites'))
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
                        <li class="description content-left"><?php if (!empty($item->event_description_short)) { ?><?php echo trim( strip_tags( $item->event_description_short ) ); ?><?php } ?></li>                                        
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="image-frame">
        <img src="<?php echo $item->event_full_image; ?>" alt="<?php echo htmlspecialchars( strip_tags( $item->title ) ); ?>" title="<?php echo htmlspecialchars( strip_tags( $item->title ) ); ?>" />
    </div>
    
    <?php if (!empty($this->media_handler_html) && !empty($this->media_item)) { ?>
        <div id="mediamanager-content" class="dotted full">
            <div class="inner">
                <div id="media-item">
                    <div class="media-item-title media-item-property purple">
                        <?php $media_item_title = htmlspecialchars_decode( $this->media_item->media_title_long ? $this->media_item->media_title_long : $this->media_item->media_title ); 
                        echo $media_item_title; ?>
                    </div>
                    <?php echo $this->media_handler_html; ?>
                    <?php if (!empty($this->media_item->mediafiles[0]) && $this->media_item->mediafiles[0]->is_downloadable == '1') {  ?>
                    <div class="media-item-download media-item-property">
                        <a href="<?php echo JRoute::_( "index.php?option=com_mediamanager&view=item&task=downloadMedia&format=raw&id=".$this->media_item->mediafiles[0]->mediafile_id ); ?>" onclick="_gaq.push(['_trackEvent', 'Audio', 'Downloaded', '<?php echo $media_item_title; ?>']);" class="h5">
                            Download Song
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    
    <div id="event-details" class="tabs category-tabs">
    
        <?php 
        echo JHtml::_('tabs.start', 'event-detail-tabs', array( 'startOffset'=>0, 'useCookie'=>false ));
        
        echo JHtml::_('tabs.panel', JText::_( "Overview" ), 'tab overview' ); ?>
        
            <div class="article-default table full">
            	<div class="column-primary with-right cell">
            	
            	<div class="instance-description">
                <?php
                $description = trim( @$item->event_description_short );
                if (!empty($description)) 
                {
                        if (!empty($description)) 
                        {
                            echo $description;
                        } 
                            elseif (JDEBUG) 
                        {
                        	/*
                            ?>
                            <p>{Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolor em que laudantium, totam rem aperiam unde quae ab illo inventore. Imustiat iuribusapid qui rem eost dolorem qui illiam aciisin iasperu ptassinis et officilique vendia dolore lia conesequi con re quassin nos quidus, volestrunt dolupid enisimus sanienderis susam rerchilitiur as eaquate pa volupta tiustem faceatusam dolo cupientur sum rem quodi archicto quatas. Ferferfero corendis maiorem quideliquo magnimi nimus, sus. Xerios imusae iuntian torero comnis aut abo. Olecull aborem ius magnate Ratur? Minum voloris etur, unt dolluptatum voluptiorest. Ones modita pe que officiur alia commod etus et as repudi te num ea dolorene occatureped. Ipitiunt as necestruptur repelis conectur sam sequi occulpa dusdae volore optam, omnis sum sima cone re id moditat. Ihilis veligentur, sed quias quae et doluptas ero esci quam inciis evellaut es re odit pore, simo bea num repudae commo.}</p>
                            <?php
                            */ 
                        }
                }
                ?>
                </div>
                
                <?php
                if (!empty($item->event->event_type) && !empty($item->event->event_type->type_id)) 
				{
					// is it set to always display?
					if ($item->event->event_type->always_display) {
						echo $item->event->event_type->type_description_1;
						echo $item->event->event_type->type_description_2;
					} elseif(empty($description)) {
					    if (!empty($item->event->event_type->string_match_1)) 
					    {
					        if (strpos(strtolower($item->series_title), strtolower($item->event->event_type->string_match_1)) !== false || strpos(strtolower($item->title), strtolower($item->event->event_type->string_match_1)) !== false) {
					            echo $item->event->event_type->type_description_1;
					        }
					    }
						
						if (!empty($item->event->event_type->string_match_2)) 
						{
						    if (strpos(strtolower($item->series_title), strtolower($item->event->event_type->string_match_2)) !== false || strpos(strtolower($item->title), strtolower($item->event->event_type->string_match_2)) !== false) 
						    {
						        echo $item->event->event_type->type_description_2;
						    }						    
						}
						
						if (empty($item->event->event_type->string_match_1) && empty($item->event->event_type->string_match_2)) {
							echo $item->event->event_type->type_description_1;
						}
					}

				}
                ?>
                </div>
                
                <div class="cell column-right">
                    
                    <?php if (!empty($item->artist_website)) { ?>
                        <div class="section full">
                            <div class="meta-header h4 purple light-purple-bg">
                                artist website
                            </div>
                            <div class="meta-box-content inner">
                                <?php /* ?>{www.artist.com} */ ?>
                            </div>
                        </div>
                    <?php } ?>
                    
                    <?php if (!empty($item->tags_site)) { ?>
                        <div class="section full">
                            <div class="meta-header h4 purple light-purple-bg">
                                tags
                            </div>
                            <div class="inner">
                                <?php /* ?>{tag1, tag2, tag3, etc} */ ?>
                            </div>
                        </div>
                    <?php } ?>
                    
                    <?php if ((!empty($item->event->event_type) && !empty($item->event->event_type->display_partof)) || count($item->show->series) || count($item->event->event_types_additional) ) { ?>
                        <div class="section full">
                            <div class="meta-header h4 purple light-purple-bg">
                                part of
                            </div>
                            
                            <div class="inner meta-box-content">
                                <?php
                                if ((!empty($item->event->event_type) && !empty($item->event->event_type->display_partof))) { ?>
                                    <div class="button-label red lightest-red-bg h4">
                                        <?php if (!empty($item->event->event_type->type_url)) { ?>
                                            <a class="partof" href="<?php echo $item->event->event_type->type_url; ?>">
                                        <?php } ?>
                                    
                                        <?php echo $item->event->event_type->type_name; ?>
                                        
                                        <?php if (!empty($item->event->event_type->type_url)) { ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                
                                <?php
                                if (count($item->show->series)) 
                                {
                                    foreach ($item->show->series as $series) 
                                    {
                                        ?>
                                        <div class="button-label red lightest-red-bg h4">
                                            <?php echo $series->title; ?>
                                        </div>
                                        <?php 
                                    } 
                                }
                                
                                if (count($item->event->event_types_additional)) 
                                {
                                    foreach ($item->event->event_types_additional as $event_types_additional) 
                                    {
                                    	if ($event_types_additional->admin_only != '1')
                                    	{
                                        	?>
                                        	<div class="button-label red lightest-red-bg h4">
                                                <?php if (!empty($event_types_additional->type_url)) { ?>
                                                    <a class="partof" href="<?php echo $event_types_additional->type_url; ?>">
                                                <?php } ?>
                                                
                                                <?php echo $event_types_additional->type_name; ?>
                                                
                                                <?php if (!empty($event_types_additional->type_url)) { ?>
                                                    </a>
                                                <?php } ?>
                                        	</div>
                                        	<?php
                                    	}
                                    } 
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    
                </div>
                
            </div>

        <?php if (!empty($item->show->programNotes)) { ?>
            <?php echo JHtml::_('tabs.panel', JText::_( "Program Notes" ), 'tab program-notes' );  ?>
        
            <div class="article-default table full">
            	<div class="column-primary with-right cell">
                    <?php echo $item->show->programNotes; ?>
            	</div>
            	<div class="cell column-right">
            	
            	</div>
            </div>
            
        <?php } ?>
        
        <?php echo JHtml::_('tabs.end'); ?>
        
    </div>
    
    <?php if (!empty($item->event->event_sponsors)) { ?>
    <div class="meta-box sponsored-by">
        <div class="meta-header h4 purple light-purple-bg">
            Sponsored By
        </div>
        <div class="meta-box-content inner">
            <?php echo $item->event->event_sponsors; ?>
        </div>
    </div>
    <?php } ?>    
</div>

<?php /* ?>
<div class="related-items">

</div>
*/ ?>