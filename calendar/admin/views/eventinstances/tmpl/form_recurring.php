<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_('script', 'common.js', 'media/com_calendar/js/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<div id="form_recurring">

    <table class="admintable">
        <tr>
            <td class="key">                       
                <?php echo JText::_( 'Update all future instances' ); ?>:                     
            </td>
            <td>
                <?php echo JHTML::_( 'select.booleanlist', 'update_future_eventinstances', array( 'disabled'=>'disabled' ), '0' ); ?>
            </td>
        </tr>
        <tr>
            <td class="key">                       
                <?php echo JText::_( 'Repeats' ); ?>:                     
            </td>
            <td>
                <?php echo CalendarSelect::repeats( '', 'recurring_repeats', array('onchange'=>'calendarDisplayRecurringParams( \'recurring_repeats\', document.adminForm );'), 'recurring_repeats' ); ?>                     
            </td>
        </tr>
        <?php /* ?>
        <tr>
            <td style="width: 100px; text-align: right;" class="key">
                <?php echo JText::_( 'Time' ); ?>:
            </td>
            <td>
                <?php
                $time = explode( ':', '' );
                echo JText::_( "Hour" ) . ": " . CalendarSelect::integerlist( '0', '23', '1', 'recurring_time_hours', array(), @$time[0] );
                echo JText::_( "Minute" ) . ": " . CalendarSelect::integerlist( '0', '59', '1', 'recurring_time_minutes', array(), @$time[1] );
                ?>
                <br/>
                (00-23 hours time format)
            </td>
        </tr>
        <tr>
            <td class="key">                       
                <?php echo JText::_( 'Starts' ); ?>:                     
            </td>
            <td>
                <?php echo JHTML::calendar( '', "recurring_start_date", "recurring_start_date", '%Y-%m-%d', array('size'=>'25') ); ?>                     
            </td>
        </tr>
        */ ?>
        <tr>
            <td class="key">                       
                <?php echo JText::_( 'Ends' ); ?>:                     
            </td>
            <td>
                <div>
                    <input id="recurring_end_type0" type="radio" checked="checked" value="date" name="recurring_end_type">
                    <label for="recurring_end_type0"><?php echo JText::_("on"); ?></label>
                    <?php echo JHTML::calendar( '', "recurring_end_date", "recurring_end_date", '%Y-%m-%d', array('size'=>'25') ); ?>
                </div>
                <div>
                    <input id="recurring_end_type1" type="radio" value="occurances" name="recurring_end_type">
                    <label for="recurring_end_type1"><?php echo JText::_("After"); ?></label>
                    <?php echo CalendarSelect::integerlist( '1', '30', '1', 'recurring_end_occurances', array(), '1' ); ?>
                    <?php echo JText::_("occurances"); ?>
                </div>
                <div>
                    <input id="recurring_end_type2" type="radio" value="never" name="recurring_end_type">
                    <label for="recurring_end_type2"><?php echo JText::_("Never"); ?></label>                
                </div>                                                     
            </td>
        </tr>
    </table>
    
    <div id="daily" class="repeats_params">
        <table class="admintable">
            <tr>
                <td class="key">                       
                    <?php echo JText::_( 'Repeats Every' ); ?>:                     
                </td>
                <td>
                    <?php echo CalendarSelect::integerlist( '0', '30', '1', 'daily_repeats_every', array(), '1' ); ?>
                    <?php echo JText::_( "days" ); ?>                     
                </td>
            </tr>
        </table>
    </div>
    
    <div id="weekly" class="repeats_params" style="display: none">
        <table class="admintable">
            <tr>
                <td class="key">                       
                    <?php echo JText::_( 'Repeats Every' ); ?>:                     
                </td>
                <td>
                    <?php echo CalendarSelect::integerlist( '0', '30', '1', 'weekly_repeats_every', array(), '1' ); ?>
                    <?php echo JText::_( "weeks" ); ?>                    
                </td>
            </tr>
            <?php /* ?>
            <tr>
                <td class="key">                       
                    <?php echo JText::_( 'Repeat on' ); ?>:                     
                </td>
                <td>
                    <input name="weekly_repeats_on" type="checkbox" value="MON" /> MON
                    <input name="weekly_repeats_on" type="checkbox" value="TUE" /> TUE
                    <input name="weekly_repeats_on" type="checkbox" value="WED" /> WED
                    <input name="weekly_repeats_on" type="checkbox" value="THU" /> THU
                    <input name="weekly_repeats_on" type="checkbox" value="FRI" /> FRI
                    <input name="weekly_repeats_on" type="checkbox" value="SAT" /> SAT
                    <input name="weekly_repeats_on" type="checkbox" value="SUN" /> SUN                      
                </td>
            </tr>
            */ ?>
        </table>
    </div>
    
    <div id="monthly" class="repeats_params" style="display: none">
        <table class="admintable">
            <tr>
                <td class="key">                       
                    <?php echo JText::_( 'Repeats Every' ); ?>:                     
                </td>
                <td>
                    <?php echo CalendarSelect::integerlist( '0', '30', '1', 'monthly_repeats_every', array(), '1' ); ?>
                    <?php echo JText::_( "months" ); ?>                     
                </td>
            </tr>
        </table>
    </div>
    
    <div id="yearly" class="repeats_params" style="display: none">
        <table class="admintable">
            <tr>
                <td class="key">                       
                    <?php echo JText::_( 'Repeats Every' ); ?>:                     
                </td>
                <td>
                    <?php echo CalendarSelect::integerlist( '0', '30', '1', 'yearly_repeats_every', array(), '1' ); ?>
                    <?php echo JText::_( "years" ); ?>                     
                </td>
            </tr>
        </table>
    </div>
    
</div>