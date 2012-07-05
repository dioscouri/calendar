<?php defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_( 'script', 'common.js', 'media/com_calendar/js/' );
$state = @$this->state;
$form = @$this->form;
$items = @$this->items;
?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" name="adminForm" enctype="multipart/form-data">
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
                <th style="width: 50px;">
                    <?php echo CalendarGrid::sort( 'ID', "tbl.id", @$state->direction, @$state->order );
					?>
                </th>   
                <th>
                	<?php echo JText::_( "Image" ); ?>
                </th>             
                <th style="text-align: left;">
                    <?php echo CalendarGrid::sort( 'Title', "tbl.title", @$state->direction, @$state->order );
					?>
                </th>
            </tr>
            <tr class="filterline">
                <th colspan="2">
                    <?php $attribs = array( 'class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();' );
					?>
                    <div class="range">
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_( "From" ); ?>:</span> <input id="filter_id_from" name="filter_id_from" value="<?php echo @$state->filter_id_from;
																																				  ?>" size="5" class="input" />
                        </div>
                        <div class="rangeline">
                            <span class="label"  style="float:left;"><?php echo JText::_( "To" ); ?>:</span> <input id="filter_id_to"  style="float:right;" name="filter_id_to" value="<?php echo @$state->filter_id_to;
																																													   ?>" size="5" class="input" />
                        </div>
                    </div>
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
                    <div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter( );
															 ?></div>
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
                    <a href="<?php echo JRoute::_( $item->link_view ); ?>">
                        <?php echo $item->event_id; ?>
                    </a>
                </td>   
                <td style="text-align: left; width:150px">
                   <?php
					   $table = JTable::getInstance( 'Events', 'CalendarTable' );
					   $table->load( $item->event_id );
					   echo $table->getImage( );
				   ?>
                </td>          
                <td style="text-align: left;">
                    <a href="<?php echo JRoute::_( $item->link_view ); ?>">
                        <?php echo $item->event_short_title; ?>
                    </a>
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
    <input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
    <input type="hidden" name="filter_direction" value="<?php echo @$state->direction;
														?>" />
    
    <?php echo $this->form['validate']; ?>
</form>