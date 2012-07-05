<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_('script', 'common.js', 'media/com_calendar/js/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<div id="validation_message"></div>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" id="adminForm" enctype="multipart/form-data" onsubmit="calendarFormValidation( '<?php echo @$form['validation_url']; ?>', 'validation_message', document.adminForm.task.value, document.adminForm );" >
			  
	<fieldset>
		<legend><?php echo JText::_( 'Form' ); ?></legend>
			<table class="admintable">
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">    					
    					<?php echo JText::_( 'Event' ); ?>:    					
    				</td>
    				<td>
    					<?php echo CalendarSelect::event( @$row->event_id, 'event_id', '', 'event_id' ); ?>
    				</td>
    			</tr>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">    					
    					<?php echo JText::_( 'Venue' ); ?>:    					
    				</td>
    				<td>
    					<div>
    					    <?php echo CalendarSelect::venue( @$row->venue_id, 'venue_id', '', 'venue_id' ); ?>  					
                        </div>
    					<div>
        					<?php echo JText::_( 'Or enter new one' ); ?>: 
    						<input name="new_venue_name" value="" type="text" size="48" maxlength="250" />
    					</div>
    				</td>
    			</tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Event Instance Name' ); ?>:
					</td>
					<td>
						<input type="text" name="eventinstance_name" value="<?php echo @$row->eventinstance_name; ?>" size="48" maxlength="250"  />
					</td>
				</tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Alias' ); ?>:
					</td>
					<td>
						<input type="text" name="eventinstance_alias" value="<?php echo @$row->eventinstance_alias; ?>" size="48" maxlength="250"  />
					</td>
				</tr>
				<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<label for="enabled">
    						<?php echo JText::_( 'Published' ); ?>:
    					</label>
    				</td>
    				<td>
    					<?php echo JHTML::_( 'select.booleanlist', 'eventinstance_published', '', @$row->eventinstance_published ); ?>
    				</td>
    			</tr>				
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Date' ); ?>:
                    </td>
                    <td>
                        <?php echo JHTML::calendar( @$row->eventinstance_date, "eventinstance_date", "eventinstance_date", '%Y-%m-%d' ); ?>
                    </td>
                </tr> 
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Time' ); ?>:
                    </td>
                    <td>
                        <?php
						$time = explode( ':', @$row->eventinstance_start_time );
						echo JText::_( "Hour" ) . ": " . CalendarSelect::integerlist( '0', '23', '1', 'eventinstance_start_time_hours', array(), @$time[0] );
						echo JText::_( "Minute" ) . ": " . CalendarSelect::integerlist( '0', '59', '1', 'eventinstance_start_time_minutes', array(), @$time[1] );
						/*
						?>                        
                        <input type="text" name="eventinstance_start_time_hours" value="<?php echo @$time[0]; ?>" size="5" maxlength="2"  />H:
						<input type="text" name="eventinstance_start_time_minutes" value="<?php echo @$time[1]; ?>" size="5" maxlength="2"  />M
						*/
						?>
                        <br/>
                        (00-23 hours time format)
                    </td>
                </tr>
                <?php 
                /*               
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<label for="eventinstance_full_image">
    					<?php echo JText::_( 'Current Image' ); ?>:
    					</label>
    				</td>
    				<td>
    					<?php
						jimport( 'joomla.filesystem.file' );
						if ( !empty( $row->eventinstance_full_image ) && JFile::exists( Calendar::getPath( 'eventinstances_images' ) . DS . $row->eventinstance_full_image ) )
						{
							$table = JTable::getInstance( 'Eventinstances', 'CalendarTable' );
							$table->load( @$row->eventinstance_id );
							echo CalendarUrl::popup( $table->getImage( 'full', true ), $table->getImage( ), array( 'update' => false, 'img' => true ) );
						}
						?>
    					<br />
    					<input type="text" disabled="disabled" name="eventinstance_full_image" id="eventinstance_full_image" size="48" maxlength="250" value="<?php echo @$row->eventinstance_full_image; ?>" />
    				</td>
    			</tr>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<label for="eventinstance_full_image_new">
    					<?php echo JText::_( 'Upload New Image' ); ?>:
    					</label>
    				</td>
    				<td>
    					<input name="eventinstance_full_image_new" type="file" size="40" />
    				</td>
    			</tr>
    			*/
                ?>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">    					
    					<?php echo JText::_( 'Action Button' ); ?>:    					
    				</td>
    				<td>
    					<?php echo CalendarSelect::actionbutton( @$row->actionbutton_id, 'actionbutton_id', '', 'actionbutton_id' ); ?>
    				</td>
    			</tr>
    			<tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Custom Text' ); ?>:
                    </td>
                    <td>
                        <textarea name="eventinstance_description" cols="50"><?php echo @$row->eventinstance_description; ?></textarea>
                        <?php //$editor = &JFactory::getEditor( ); ?>
                        <?php //echo $editor->display( 'eventinstance_description', @$row->eventinstance_description, '100%', '450', '100', '20' ); ?>
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
                 
			</table>
			<input type="hidden" name="id" value="<?php echo @$row->eventinstance_id; ?>" />
			<input type="hidden" name="task" value="" />
	</fieldset>
</form>