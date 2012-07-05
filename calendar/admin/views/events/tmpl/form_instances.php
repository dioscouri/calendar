<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_('script', 'common.js', 'media/com_calendar/js/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>
<?php $items = @$this->items; ?>
<?php jimport('joomla.html.pane'); ?>
<?php $tabs = JPane::getInstance( 'tabs' ); ?>
<?php JHTML::_('behavior.tooltip'); ?>

<?php
if ( empty( $row->event_id ) )
{						
	?>
	<div class="note"><?php echo JText::_( "Click Apply to be able to create event instances from here" ); ?></div>
	<?php
}
?>

<div>
    <table class="adminlist" style="clear: both;">
        <thead>
            <tr>
                <th style="width: 5px;">
                    <?php echo JText::_( 'ID' ); ?>
                </th>
                <th style="width: 150px;">
                    <?php echo JText::_( 'Date' ); ?>
                </th>
                <th style="width: 50px;">
                    <?php echo JText::_( 'Start Time' ); ?>
                </th>
                <th style="width: 50px;">
                    <?php echo JText::_( 'End Time' ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo JText::_( 'Venue' ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo JText::_( 'Button' ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo JText::_( 'Button Label' ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo JText::_( 'Button URL' ); ?>
                </th>
                <th style="width: 50px;">
                </th>
                <th style="width: 50px;">
                </th>
            </tr>
        </thead>
        <tbody>
        <?php $i=0; $k=0; ?>
        <?php foreach (@$items as $item) : ?>
            <tr class='row<?php echo $k; ?>'>
                <td align="center">
                    <?php echo $item->eventinstance_id; ?>
                </td>
                <td style="text-align: center;">
                	<?php echo JHTML::calendar( $item->eventinstance_date, "eventinstance_date[$item->eventinstance_id]", "eventinstance_date[$item->eventinstance_id]", '%Y-%m-%d', array('size'=>'25') ); ?>                     
                </td>
                <td style="text-align: center;">
                    <input type="text" name="eventinstance_start_time[<?php echo $item->eventinstance_id; ?>]" value="<?php echo $item->eventinstance_start_time; ?>" size="25" maxlength="250"  />
                </td>
                <td style="text-align: center;">
                    <input type="text" name="eventinstance_end_time[<?php echo $item->eventinstance_id; ?>]" value="<?php echo $item->eventinstance_end_time; ?>" size="25" maxlength="250"  />
                </td>
                <td style="text-align: center;">
                    <?php echo CalendarSelect::venue( $item->venue_id, "venue_id[$item->eventinstance_id]" ); ?>
                </td>
                <td style="text-align: center;">
                    <?php echo CalendarSelect::actionbutton( $item->actionbutton_id, "actionbutton_id[$item->eventinstance_id]"  ); ?>
                </td>
                <td style="text-align: center;">
                    <input type="text" name="actionbutton_string[<?php echo $item->eventinstance_id; ?>]" value="<?php echo $item->actionbutton_string; ?>" size="25" maxlength="250"  />
                </td>
                <td style="text-align: center;">
                    <input type="text" name="actionbutton_url[<?php echo $item->eventinstance_id; ?>]" value="<?php echo $item->actionbutton_url; ?>" size="50" maxlength="250"  />
                </td>
                <td style="text-align: center;">
                    <a href="<?php echo $item->link; ?>" target="_blank">
                    <?php echo JText::_( "Edit" ); ?>
                    </a>
                </td>
                <td style="text-align: center;">
                    <input type="button" onclick="calendarRemoveEventInstance(<?php echo $item->eventinstance_id; ?>, 'event_instances', '<?php echo JText::_( "Deleting" ); ?>');" value="<?php echo JText::_( "Delete" ); ?>" />
                </td>
            </tr>
            <?php $i=$i+1; $k = (1 - $k); ?>
            <?php endforeach; ?>
            
            <?php if (!count(@$items)) : ?>
            <tr>
                <td colspan="10" align="center">
                    <?php echo JText::_('No items found'); ?>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>