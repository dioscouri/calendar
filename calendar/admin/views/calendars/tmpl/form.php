<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >
			  
	<fieldset>
		<legend><?php echo JText::_( 'Form' ); ?></legend>
			<table class="admintable">
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Calendar Name' ); ?>:
					</td>
					<td>
						<input type="text" name="calendar_name" value="<?php echo @$row->calendar_name; ?>" size="48" maxlength="250"  />
					</td>
				</tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Calendar Alias' ); ?>:
					</td>
					<td>
						<input type="text" name="calendar_alias" value="<?php echo @$row->calendar_alias; ?>" size="48" maxlength="250"  />
					</td>
				</tr>
				<tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Date Range Filter' ); ?>:
                    </td>
                    <td>
                        <?php echo JText::_( 'From' ) . ': ' .JHTML::calendar( @$row->calendar_filter_date_from, "calendar_filter_date_from", "calendar_filter_date_from", '%Y-%m-%d' ); ?> 
                        <br>
                        <?php echo JText::_( ' To' ) . ': ' .JHTML::calendar( @$row->calendar_filter_date_to, "calendar_filter_date_to", "calendar_filter_date_to", '%Y-%m-%d' ); ?>
                    </td>
                </tr>
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Shown Primary Category' ); ?>:
					</td>
					<td>
                       <?php $cat_attribs = array('class' => 'inputbox', 'size' => '10', 'multiple' => 'multiple'); ?>
                       <?php echo CalendarSelect::category( @$this->filter_primary_categories, 'calendar_filter_primary_categories[]', $cat_attribs, 'calendar_filter_primary_categories', false, false ); ?>
                    </td>
				</tr>	
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Shown Secondary Categories' ); ?>:
					</td>
					<td>
						<?php echo CalendarSelect::secondcategory( @$this->filter_secondary_categories, 'calendar_filter_secondary_categories[]', $cat_attribs, 'calendar_filter_secondary_categories', false, false ); ?>
                    </td>
				</tr>	
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Shown Event Types' ); ?>:
					</td>
					<td>
						<?php echo CalendarSelect::type( @$this->filter_types, 'calendar_filter_types[]', $cat_attribs, 'calendar_filter_types', false, false ); ?>
                    </td>
				</tr>
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Event Types Displayed in Extra Tabs' ); ?>:
					</td>
					<td>
						<?php echo CalendarSelect::type( @$this->tabbed_types, 'calendar_tabbed_types[]', $cat_attribs, 'calendar_tabbed_types', false, false ); ?>
                    </td>
				</tr>
				<tr>
    				<td style="width: 100px; text-align: right;" class="key">    					
    					<?php echo JText::_( 'Default View' ); ?>:    					
    				</td>
    				<td>
    					<?php echo CalendarSelect::defaultview( @$row->calendar_default_view, 'calendar_default_view', '', 'calendar_default_view' ); ?>
    				</td>
    			</tr>    			
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<?php echo JText::_( 'Show Day View' ); ?>:
    				</td>
    				<td>
    					<?php echo JHTML::_( 'select.booleanlist', 'calendar_show_day', '', @$row->calendar_show_day ); ?>
    				</td>
    			</tr>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<?php echo JText::_( 'Show Three Days View' ); ?>:
    				</td>
    				<td>
    					<?php echo JHTML::_( 'select.booleanlist', 'calendar_show_three', '', @$row->calendar_show_three ); ?>
    				</td>
    			</tr>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<?php echo JText::_( 'Show Week View' ); ?>:
    				</td>
    				<td>
    					<?php echo JHTML::_( 'select.booleanlist', 'calendar_show_week', '', @$row->calendar_show_week ); ?>
    				</td>
    			</tr>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<?php echo JText::_( 'Show Month View' ); ?>:
    				</td>
    				<td>
    					<?php echo JHTML::_( 'select.booleanlist', 'calendar_show_month', '', @$row->calendar_show_month ); ?>
    				</td>
    			</tr>
                <?php /* ?>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<?php echo JText::_( 'Show View Navigation' ); ?>:
    				</td>
    				<td>
    					<?php echo JHTML::_( 'select.booleanlist', 'calendar_show_view_navigation', '', @$row->calendar_show_view_navigation ); ?>
    				</td>
    			</tr>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<?php echo JText::_( 'Show Mini Calendar' ); ?>:
    				</td>
    				<td>
    					<?php echo JHTML::_( 'select.booleanlist', 'calendar_show_mini_calendar', '', @$row->calendar_show_mini_calendar ); ?>
    				</td>
    			</tr>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<?php echo JText::_( 'Show Categories Module' ); ?>:
    				</td>
    				<td>
    					<?php echo JHTML::_( 'select.booleanlist', 'calendar_show_categories_module', '', @$row->calendar_show_categories_module ); ?>
    				</td>
    			</tr>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<?php echo JText::_( 'Show List View' ); ?>:
    				</td>
    				<td>
    					<?php echo JHTML::_( 'select.booleanlist', 'calendar_show_list_view', '', @$row->calendar_show_list_view ); ?>
    				</td>
    			</tr>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<?php echo JText::_( 'Show Upcoming Events' ); ?>:
    				</td>
    				<td>
    					<?php echo JHTML::_( 'select.booleanlist', 'calendar_show_upcoming_events', '', @$row->calendar_show_upcoming_events ); ?>
    				</td>
    			</tr>
                <?php */ ?>
				<tr>
	            	<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Default Date' ); ?>
					</td>
					<td>
                        <?php echo JHTML::calendar( @$row->default_date, "default_date", "default_date", '%Y-%m-%d' ); ?>
                        <br/>
                        Leave this value empty to use the current date as the default.  Use the format YYYY-MM-DD
					</td>
				</tr>
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Default Item ID' ); ?>
					</td>
					<td>
                         <input type="text" name="item_id" value="<?php echo @$row->item_id; ?>" />
					</td>
				</tr>
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Display Facebook Like Button' ); ?>
					</td>
					<td>
                        <?php echo JHTML::_('select.booleanlist', 'display_facebook_like', 'class="inputbox"', @$row->display_facebook_like ); ?>
					</td>
				</tr>
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Display Twitter Button' ); ?>
					</td>
					<td>
                        <?php echo JHTML::_('select.booleanlist', 'display_tweet', 'class="inputbox"', @$row->display_tweet ); ?>
					</td>
				</tr>
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Enter non working days (separated by commas)' ); ?>
                    </td>
                    <td>                             
						<input name="non_working_days" value="<?php echo @$row->non_working_days; ?>" type="text" size="100"/>
                    </td>
                </tr>
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Non working day text' ); ?>
                    </td>
                    <td>
                        <input name="non_working_day_text" value="<?php echo @$row->non_working_day_text; ?>" type="text" size="100"/>
                    </td>
                </tr>
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Working day text' ); ?>
                    </td>
                    <td>
                        <input name="working_day_text" value="<?php echo @$row->working_day_text; ?>" type="text" size="100"/>
                    </td>
                </tr>
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Working day link text' ); ?>
                    </td>
                    <td>
                        <input name="working_day_link_text" value="<?php echo @$row->working_day_link_text; ?>" type="text" size="100"/>
                    </td>
                </tr>
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Working day link' ); ?>
                    </td>
                    <td>
                        <input name="working_day_link" value="<?php echo @$row->working_day_link; ?>" type="text" size="100"/>
                    </td>
                </tr>
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Layout' ); ?>
                    </td>
                    <td>
                        <input name="calendar_layout" value="<?php echo @$row->calendar_layout; ?>" type="text" size="50" />
                    </td>
                </tr>
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Params' ); ?>
                    </td>
                    <td>
                        <textarea name="calendar_params" cols="50" rows="10"><?php echo @$row->calendar_params; ?></textarea>
                    </td>
                </tr>
			</table>
			<input type="hidden" name="id" value="<?php echo @$row->calendar_id; ?>" />
			<input type="hidden" name="task" value="" />
	</fieldset>
</form>