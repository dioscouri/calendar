<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_( 'script', 'common.js', 'media/com_calendar/js/' ); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>

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
																
    <table class="table table-striped table-bordered" style="clear: both;" >
        <thead>
            <tr>
                <th style="width: 5px;">
                    <?php echo JText::_( "Num" ); ?>
                </th>
                <th style="width: 20px;">
                   	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( @$items ); ?>);" />
                </th>
                <th style="width: 50px;">
                    <?php echo JText::_( "DS-ID" ); ?>
                </th>
                <th style="width: 50px;">
                    <?php echo JText::_( "Joomla-ID" ); ?>
                </th>
                <th style="text-align: left;">
                    <?php echo CalendarGrid::sort( 'Name', "tbl.venue_name", @$state->direction, @$state->order ); ?>
                </th>
                <th>
                	<?php echo CalendarGrid::sort( 'Code', "tbl.code", @$state->direction, @$state->order ); ?>
                </th>
                <th>
                	<?php echo CalendarGrid::sort( 'URL', "tbl.venue_url", @$state->direction, @$state->order ); ?>
                </th>
                <th class="dsc-order">
    	            <?php echo CalendarGrid::sort( 'Order', "tbl.ordering", @$state->direction, @$state->order ); ?>
    	            <?php echo JHTML::_( 'grid.order', @$items ); ?>
                </th>
                <th>
                	<?php echo JText::_( "Admin Only" ); ?>?
                </th>
            </tr>
            <tr class="filterline">
            	<th>
                </th>
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
                <th style="text-align: left;">
                    <input id="filter_name" type="text" name="filter_name" value="<?php echo @$state->filter_name;  ?>" class="input span3" size="25"/>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                    <?php echo DSCSelect::booleans( @$state->filter_admin_only, 'filter_admin_only', $attribs, 'filter_admin_only', true, 'Select State', 'Admin Only', 'Front-end Only' ); ?>
                </th>
            </tr>
			<tr>
				<th colspan="20" style="font-weight: normal;">
					<div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter(); ?></div>
					<div style="float: left;"><?php echo @$this->pagination->getListFooter(); ?></div>
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
                   	<input id="cb<?php echo $i; ?>" type="checkbox" onclick="Joomla.isChecked(this.checked);" value="<?php echo $item->getDatasourceID(); ?>" name="cid[]">
                </td>
                <td style="text-align: center;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->getDatasourceID(); ?>
                    </a>
                </td>   
                <td style="text-align: center;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->venue_id; ?>
                    </a>
                </td>
                <td style="text-align: left;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->name; ?>
                    </a>
                    <p class="dsc-tip">
                    <?php echo (!empty($item->venue_name) && $item->venue_name != $item->name) ? 'Override: ' . $item->venue_name : ''; ?>
                    </p>
                </td>   
                <td style="text-align: left;">
                    <?php // WTF - these two fields now cause the app to explode. one day they work, next day BOOM ?>
                    <a href="<?php echo $item->link; ?>">
                        <?php //echo $item->code; ?>
                    </a>
                </td>   
                <td style="text-align: left;">
                    <a href="<?php //echo $item->website; ?>" target="_blank">
                        <?php //echo $item->website; ?>
                    </a>
                </td>
				<td style="text-align: center;">
					<?php echo DSCGrid::order( $item->venue_id ); ?>
					<?php echo DSCGrid::ordering( $item->venue_id, $item->ordering ); ?>
				</td>
                <td style="text-align: center;">
                    <?php echo CalendarGrid::enable( $item->admin_only, $i, 'admin_only.' ); ?>
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
    <input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
    
    <?php echo $this->form['validate']; ?>
</form>