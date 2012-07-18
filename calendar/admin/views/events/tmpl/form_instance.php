<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_('script', 'common.js', 'media/com_calendar/js/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>
<?php $config = Calendar::getInstance(); ?>


    <h3><?php echo JText::_('Add New Event Instance'); ?></h3>

    <table class="table table-striped table-bordered">
        <tr>
            <td style="width: 100px; text-align: right;" class="key">                       
                <?php echo JText::_( 'Venue' ); ?>:                     
            </td>
            <td>
                <div id="venues">
                    <?php echo CalendarSelect::venue( '', 'venue_id_insert', '', 'venue_id_insert' ); ?>                     
                </div>
                <?php if ($config->get('enable_add_new')) { ?>
                <div>
                    <?php echo JText::_( 'Or enter new one' ); ?>: 
                    <input name="new_venue_name" value="" type="text" size="48" maxlength="250" />
                </div>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td style="width: 100px; text-align: right;" class="key">
                <label for="enabled">
                    <?php echo JText::_( 'Published' ); ?>:
                </label>
            </td>
            <td>
                <?php echo JHTML::_( 'select.booleanlist', 'eventinstance_published', '', '' ); ?>
            </td>
        </tr>               
        <tr>
            <td style="width: 100px; text-align: right;" class="key">
                <?php echo JText::_( 'Date' ); ?>:
            </td>
            <td>
                <?php echo JHTML::calendar( '', "eventinstance_date_insert", "eventinstance_date_insert", '%Y-%m-%d', array('size'=>'25') ); ?>
            </td>
        </tr> 
        <tr>
            <td style="width: 100px; text-align: right;" class="key">
                <?php echo JText::_( 'Start Time' ); ?>:
            </td>
            <td>
                <?php
                $time = explode( ':', '' );
                echo JText::_( "Hour" ) . ": " . CalendarSelect::integerlist( '0', '23', '1', 'eventinstance_start_time_hours', array(), @$time[0] );
                echo JText::_( "Minute" ) . ": " . CalendarSelect::integerlist( '0', '59', '1', 'eventinstance_start_time_minutes', array(), @$time[1] );
                ?>
                <br/>
                (00-23 hours time format)
            </td>
        </tr>
        <tr>
            <td style="width: 100px; text-align: right;" class="key">
                <?php echo JText::_( 'Ending Time' ); ?>:
            </td>
            <td>
                <?php
                $time = explode( ':', '' );
                echo JText::_( "Hour" ) . ": " . CalendarSelect::integerlist( '0', '23', '1', 'eventinstance_end_time_hours', array(), @$time[0] );
                echo JText::_( "Minute" ) . ": " . CalendarSelect::integerlist( '0', '59', '1', 'eventinstance_end_time_minutes', array(), @$time[1] );
                ?>
                <br/>
                (00-23 hours time format)
            </td>
        </tr>
        <tr>
            <td style="width: 100px; text-align: right;" class="key">                       
                <?php echo JText::_( 'Action Button' ); ?>:                     
            </td>
            <td>
                <?php echo CalendarSelect::actionbutton( '', 'actionbutton_id_insert', '', 'actionbutton_id_insert' ); ?>
            </td>
        </tr>
        <tr>
            <td style="width: 100px; text-align: right;" class="key">
                <?php echo JText::_( 'Action Button URL' ); ?>:
            </td>
            <td>
                <input type="text" name="actionbutton_url_insert" value="" size="48" maxlength="250"  />
            </td>
        </tr>
        <tr>
            <td style="width: 100px; text-align: right;" class="key">
                <?php echo JText::_( 'Action Button Label' ); ?>:
            </td>
            <td>
                <input type="text" name="actionbutton_string_insert" value="" size="48" maxlength="250"  />
            </td>
        </tr>
        <tr>
            <td style="width: 100px; text-align: right;" class="key">
                <?php echo JText::_( 'Recurring' ); ?>?
            </td>
            <td>
                <?php echo JHTML::_( 'select.booleanlist', 'eventinstance_recurring', array( 'onclick'=>'calendarDisplayDivOnBoolean( \'eventinstance_recurring_params\', \'eventinstance_recurring\', document.adminForm );' ), @$row->eventinstance_recurring ); ?>
                <div id="eventinstance_recurring_params" style="<?php if (!empty($row->eventinstance_recurring)) { echo 'display: block;'; } else { echo 'display: none;'; }?>">
                    <?php echo $this->loadTemplate( 'recurring' ); ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>

            </td>
            <td>
                <button class="btn btn-primary"  onclick="calendarAddEventInstance( 'event_instances', '<?php echo JText::_( "Adding Instance" ); ?>', '<?php if ($config->get('enable_add_new')) { echo "1"; } else { echo "0"; } ?>' );" ><?php echo JText::_( "Add Instance" ); ?></button>
            </td>
        </tr>
    </table>
    