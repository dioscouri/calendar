<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_( 'script', 'common.js', 'media/com_calendar/js/' ); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>
<?php Calendar::load( 'CalendarHelperCategory', 'helpers.category' ); ?>
<?php $helper = CalendarHelperBase::getInstance('Category'); ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
			  
    <?php echo CalendarGrid::pagetooltip( JRequest::getVar( 'view' ) ); ?>

    <ul class="unstyled dsc-flat pad-left pull-right">
        <li>
            <input class="search-query" type="text" name="filter" value="<?php echo @$state->filter; ?>" />
        </li>
        <li>
            <button class="btn btn-primary" onclick="this.form.submit();"><?php echo JText::_( 'Search' ); ?></button>
        </li>
        <li>
            <button class="btn btn-danger" onclick="Dsc.resetFormFilters(this.form);"><?php echo JText::_( 'Reset' ); ?></button>
        </li>
    </ul>
    
    <table class="table table-striped table-bordered" style="clear: both;">
        <thead> 
            <tr>
                <th style="width: 5px;">
                    <?php echo JText::_( "Num" ); ?>
                </th>
                <th style="width: 20px;">
                   	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( @$items ); ?>);" />
                </th>
                <th style="width: 50px;">
                    <?php echo CalendarGrid::sort( 'ID', "tbl.event_id", @$state->direction, @$state->order ); ?>
                </th>   
                <th style="width: 50px;">
                </th>             
                <th style="text-align: left;">
                    <?php echo CalendarGrid::sort( 'Name', "tbl.event_short_title", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo CalendarGrid::sort( 'Series', "tbl.event_published", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo JText::_( "Venues" ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo JText::_( "Dates" ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo CalendarGrid::sort( 'Published', "tbl.event_published", @$state->direction, @$state->order ); ?>
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
                            <input type="text" placeholder="FROM" id="filter_id_from" name="filter_id_from" value="<?php echo @$state->filter_id_from; ?>" size="5" class="input input-tiny" />
                        </div>
                        <div class="rangeline">
                            <input type="text" placeholder="TO" id="filter_id_to" name="filter_id_to" value="<?php echo @$state->filter_id_to; ?>" size="5" class="input input-tiny" />
                        </div>
                    </div>
                </th>
                <th>
                </th>
                <th style="text-align: left;">
                    <input id="filter_name" type="text" name="filter_name" value="<?php echo @$state->filter_name; ?>" size="25" /><br>
                </th>
                <th>
                    <?php echo CalendarSelect::series( @$state->filter_series, 'filter_series', $attribs, 'filter_series', true ); ?>
                </th>
                <th>
                    <?php echo CalendarSelect::venue( @$state->filter_venue_id, 'filter_venue_id', $attribs, 'filter_venue_id', true ); ?>
                </th>
                <th>
                </th>
                <th>
                    <?php echo CalendarSelect::booleans( @$state->filter_enabled, 'filter_enabled', $attribs, 'enabled', true ); ?>
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
                   	<?php echo CalendarGrid::checkedout( $item, $i, 'event_id' ); ?>
                </td>                
                <td style="text-align: center;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->event_id; ?>
                    </a>
                </td>   
                <td style="text-align: center;">
                    <?php if (!empty($item->event_full_image)) { ?>
                        <img src="<?php echo $item->event_full_image; ?>" style="max-width: 36px; max-height: 36px;" />
                    <?php } ?>
                </td>
                <td style="text-align: left;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->event_short_title; ?>
                    </a>
                </td>
                <td style="text-align: center;">
					<?php echo $item->series_name; ?>
				</td>
                <td style="text-align: center;">
					<?php echo $this->getModel()->getVenuesString( $item->event_id ); ?>
				</td>
                <td style="text-align: center;">
                    <a href="index.php?option=com_calendar&view=eventinstances&filter_event=<?php echo $item->event_id; ?>&filter_order=tbl.eventinstance_date&filter_direction=DESC">
                    <?php echo $this->getModel()->getDatesString( $item ); ?>
                    </a>
				</td>
                <td style="text-align: center;">
					<?php echo CalendarGrid::enable( $item->event_published, $i, 'event_published.' ); ?>
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