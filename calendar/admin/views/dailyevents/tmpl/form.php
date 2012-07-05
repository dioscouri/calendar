<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_('script', 'common.js', 'media/com_calendar/js/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>
<?php jimport('joomla.html.pane'); ?>
<?php $tabs = JPane::getInstance( 'tabs' ); ?>
<?php JHTML::_('behavior.tooltip'); ?>

<div id="validation_message"></div>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" id="adminForm" enctype="multipart/form-data" onsubmit="calendarFormValidation( '<?php echo @$form['validation_url']; ?>', 'validation_message', document.adminForm.task.value, document.adminForm );" >

<?php 
echo $tabs->startPane( "pane_dailyevents" );

echo $tabs->startPanel( JText::_( 'Basic Information' ), "panel_basics");
?>
	<fieldset>
			<table class="admintable">
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Daily Event Name' ); ?>:
					</td>
					<td>
						<input type="text" name="dailyevent_name" value="<?php echo @$row->dailyevent_name; ?>" size="72" maxlength="250" style="font-size: 20px;" />
					</td>
				</tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Long Title' ); ?>:
                    </td>
                    <td>
                        <input name="dailyevent_long_title" value="<?php echo @$row->dailyevent_long_title; ?>" type="text" size="150" maxlength="250" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Short Title' ); ?>:
                    </td>
                    <td>
                        <input name="dailyevent_short_title" value="<?php echo @$row->dailyevent_short_title; ?>" type="text" size="50" maxlength="250" />
                    </td>
                </tr>
				<tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Alias' ); ?>:
                    </td>
                    <td>
                        <input name="dailyevent_alias" value="<?php echo @$row->dailyevent_alias; ?>" type="text" size="48" maxlength="250" />
                    </td>
                </tr>                
                <tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<label for="enabled">
    						<?php echo JText::_( 'Published' ); ?>:
    					</label>
    				</td>
    				<td>
    					<?php echo JHTML::_( 'select.booleanlist', 'dailyevent_published', '', @$row->dailyevent_published ); ?>
    				</td>
    			</tr>	
    			<tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Date' ); ?>:
                    </td>
                    <td>
                        <?php echo JHTML::calendar( @$row->dailyevent_date, "dailyevent_date", "dailyevent_date", '%Y-%m-%d' ); ?>
                    </td>
                </tr> 
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Start Time' ); ?>:
                    </td>
                    <td>
                        <?php
						$time = explode( ':', @$row->dailyevent_start_time );
						echo JText::_( "Hour" ) . ": " . CalendarSelect::integerlist( '0', '23', '1', 'dailyevent_start_time_hours', array(), @$time[0] );
						echo JText::_( "Minute" ) . ": " . CalendarSelect::integerlist( '0', '59', '1', 'dailyevent_start_time_minutes', array(), @$time[1] );						
						?>
                        <br/>
                        (00-23 hours time format)
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'End Time' ); ?>:
                    </td>
                    <td>
                        <?php
						$time = explode( ':', @$row->dailyevent_end_time );
						echo JText::_( "Hour" ) . ": " . CalendarSelect::integerlist( '0', '23', '1', 'dailyevent_end_time_hours', array(), @$time[0] );
						echo JText::_( "Minute" ) . ": " . CalendarSelect::integerlist( '0', '59', '1', 'dailyevent_end_time_minutes', array(), @$time[1] );						
						?>
                        <br/>
                        (00-23 hours time format)
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
    					<label for="dailyevent_full_image">
    					<?php echo JText::_( 'Current Image' ); ?>:
    					</label>
    				</td>
    				<td>
    					<?php
						jimport( 'joomla.filesystem.file' );
						if ( !empty( $row->dailyevent_full_image ) && JFile::exists( Calendar::getPath( 'dailyevents_images' ) . DS . $row->dailyevent_full_image ) )
						{
							$table = JTable::getInstance( 'Dailyevents', 'CalendarTable' );
							$table->load( @$row->dailyevent_id );
							echo CalendarUrl::popup( $table->getImage( 'full', true ), $table->getImage( ), array( 'update' => false, 'img' => true ) );
						}
						?>
    					<br />
    					<input type="text" disabled="disabled" name="dailyevent_full_image" id="dailyevent_full_image" size="48" maxlength="250" value="<?php echo @$row->dailyevent_full_image; ?>" />
    				</td>
    			</tr>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<label for="dailyevent_full_image_new">
    					<?php echo JText::_( 'Upload New Image' ); ?>:
    					</label>
    				</td>
    				<td>
    					<input name="dailyevent_full_image_new" type="file" size="40" />
    				</td>
    			</tr> 
    		</table>
    </fieldset>
    
    <div style="clear: both;"></div>
<?php 
echo $tabs->endPanel();

echo $tabs->startPanel( JText::_( 'Descriptions' ), "panel_descriptions");
?>
    <fieldset>
            <table class="admintable">
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Short Description' ); ?>:
                    </td>
                    <td>
                        <?php $editor = JFactory::getEditor( ); ?>
                        <?php echo $editor->display( 'dailyevent_short_description', @$row->dailyevent_short_description, '100%', '450', '100', '20' );
                        ?>
                    </td>
                </tr>  
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Long Description' ); ?>:
                    </td>
                    <td>
                        <?php $editor = JFactory::getEditor( ); ?>
                        <?php echo $editor->display( 'dailyevent_long_description', @$row->dailyevent_long_description, '100%', '450', '100', '20' );
                        ?>
                    </td>
                </tr>           
            </table>
    </fieldset>
    
    <div style="clear: both;"></div>
<?php 
echo $tabs->endPanel();

echo $tabs->startPanel( JText::_( 'Multimedia' ), "panel_multimedia");
?>
    <fieldset>
            <table class="admintable">
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Daily Event Multimedia' ); ?>:
                    </td>
                    <td>
                        <?php $editor = &JFactory::getEditor( ); ?>
                        <?php echo $editor->display( 'dailyevent_multimedia', @$row->dailyevent_multimedia, '100%', '450', '100', '20' );
                        ?>
                    </td>
                </tr>                
            </table>
    </fieldset>
    
    <div style="clear: both;"></div>
<?php 
echo $tabs->endPanel();

echo $tabs->endPane();
?>    
    
        <input type="hidden" name="id" value="<?php echo @$row->dailyevent_id; ?>" />
        <input type="hidden" name="task" id="task" value="" />
    
    
</form>