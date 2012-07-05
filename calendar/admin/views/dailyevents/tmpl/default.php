<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_( 'script', 'common.js', 'media/com_calendar/js/' ); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>
<?php Calendar::load( 'CalendarHelperCategory', 'helpers.category' ); ?>
<?php $helper = CalendarHelperBase::getInstance('Category'); ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" name="adminForm" enctype="multipart/form-data">
			  
    <?php echo CalendarGrid::pagetooltip( JRequest::getVar( 'view' ) ); ?>
    
    <table>
        <tr>
            <td align="left" width="100%">
            </td>
            <td nowrap="nowrap">
                <input name="filter" value="<?php echo @$state->filter; ?>" />
                <button onclick="this.form.submit();"><?php echo JText::_( 'Search' );
													  ?></button>
                <button onclick="calendarFormReset(this.form);"><?php echo JText::_( 'Reset' );
																?></button>
            </td>
        </tr>
    </table>
																
    <table class="adminlist" style="clear: both;">
        <thead> 
            <tr>
                <th style="width: 5px;">
                    <?php echo JText::_( "Num" ); ?>
                </th>
                <th style="width: 20px;">
                   	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( @$items ); ?>);" />
                </th>
                <th style="width: 50px;">
                    <?php echo CalendarGrid::sort( 'ID', "tbl.dailyevent_id", @$state->direction, @$state->order );
					?>
                </th>   
                <th style="width: 50px;">
                </th>             
                <th style="text-align: left;">
                    <?php echo CalendarGrid::sort( 'Name', "tbl.dailyevent_name", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo JText::_( "Venues" ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo JText::_( "Date and Time" ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo CalendarGrid::sort( 'Enabled', "tbl.dailyevent_published", @$state->direction, @$state->order ); ?>
                </th>
            </tr>
            <tr class="filterline">
            	<th>
                </th>                
                <th colspan="2">
                    <?php $attribs = array( 'class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();' );
					?>
                    <div class="range">
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_( "From" ); ?>:</span> <input id="filter_id_from" name="filter_id_from" value="<?php echo @$state->filter_id_from; ?>" size="5" class="input" />
                        </div>
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_( "To" ); ?>:</span> <input id="filter_id_to" name="filter_id_to" value="<?php echo @$state->filter_id_to; ?>" size="5" class="input" />
                        </div>
                    </div>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                	<?php echo CalendarSelect::venue( @$state->filter_venue_id, 'filter_venue_id', $attribs, 'filter_venue_id', true ); ?>
                </th>
                <th>
                </th>
                <th>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="20">
                    <div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter( ); ?></div>
                    <?php echo @$this->pagination->getPagesLinks( ); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
        <?php $i = 0;
		$k = 0;
		?>
        <?php foreach ( @$items as $item ) : ?>
            <tr class='row<?php echo $k; ?>'>
                <td align="center">
                    <?php echo $i + 1; ?>
                </td>
                <td style="text-align: center;">
                   	<?php echo CalendarGrid::checkedout( $item, $i, 'dailyevent_id' ); ?>
                </td>                
                <td style="text-align: center;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->dailyevent_id; ?>
                    </a>
                </td>   
                <td style="text-align: center;">
                    <?php if (!empty($item->dailyevent_full_image)) { ?>
                        <img src="<?php echo Calendar::getUrl('dailyevents_images') . $item->dailyevent_full_image; ?>" style="max-width: 36px; max-height: 36px;" />
                    <?php } ?>
                </td>
                <td style="text-align: left;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->dailyevent_name; ?>
                    </a>
                </td>
                <td style="text-align: center;">
                	<?php
                    	Calendar::load( 'CalendarHelperVenue', 'helpers.venue' );
						echo CalendarHelperVenue::getVenueName( $item->venue_id );
					?>
                </td>
                <td style="text-align: center;">
                    <b><?php echo JText::_( "Date"); ?>:</b><?php echo $item->dailyevent_date; ?><br/>
                    &nbsp;<b><?php echo JText::_( "Start"); ?>:</b><?php echo $item->dailyevent_start_time; ?><br/>
                    &nbsp;<b><?php echo JText::_( "End"); ?>:</b><?php echo $item->dailyevent_end_time; ?><br/>
				</td>
                <td style="text-align: center;">
					<?php echo CalendarGrid::enable( $item->dailyevent_published, $i, 'dailyevent_published.' ); ?>
				</td>
            </tr>
            <?php $i = $i + 1;
				$k = ( 1 - $k );
			?>
            <?php endforeach; ?>
            
            <?php if ( !count( @$items ) ) : ?>
            <tr>
                <td colspan="10" align="center">
                    <?php echo JText::_( 'No items found' ); ?>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="20">
                    <?php echo @$this->pagination->getListFooter( ); ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <input type="hidden" name="order_change" value="0" />
    <input type="hidden" name="id" value="" />
    <input type="hidden" name="task" id="task" value="" />
    <input type="hidden" name="boxchecked" value="" />
    <input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
    <input type="hidden" name="filter_direction" value="<?php echo @$state->direction;
														?>" />
    
    <?php echo $this->form['validate']; ?>
</form>