<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >
			<table class="table table-striped table-bordered">
				<tr>
					<td class="dsc-key">
						<?php echo JText::_( 'Title' ); ?>:
					</td>
					<td>
						<input type="text" name="type_name" value="<?php echo @$row->type_name; ?>" size="48" maxlength="250"  />
					</td>
				</tr>
				<tr>
					<td class="dsc-key">
						<?php echo JText::_( 'Subtitle' ); ?>:
					</td>
					<td>
						<input type="text" name="type_subtitle" value="<?php echo @$row->type_subtitle; ?>" size="48" maxlength="250"  />
					</td>
				</tr>
				<tr>
					<td class="dsc-key">
						<?php echo JText::_( 'Display in "Part Of"' ); ?>?
					</td>
					<td>
						<?php echo JHTML::_( 'select.booleanlist', 'display_partof', '', @$row->display_partof ); ?>
						<p class="dsc-tip">
						Set this to yes if you want this Event Type to be listed under the "Part of" section on the Event Detail page
						</p>
					</td>
				</tr>
				<tr>
					<td class="dsc-key">
						<?php echo JText::_( 'URL' ); ?>:
					</td>
					<td>
						<input type="text" name="type_url" value="<?php echo @$row->type_url; ?>" size="100" />
						<p class="dsc-tip">
						If you want this Event Type to link to another page on the website, provide the URL here
						</p>
					</td>
				</tr>
				<tr>
					<td class="dsc-key">
						<?php echo JText::_( 'Admin Only' ); ?>?
					</td>
					<td>
						<?php echo JHTML::_( 'select.booleanlist', 'admin_only', '', @$row->admin_only ); ?>
						<p class="dsc-tip">
						Don't show this event type in the list of Filters on the Events and Tickets page
						</p>
					</td>
				</tr>
				<tr>
					<td class="dsc-key">
						<?php echo JText::_( 'Hide Action Button' ); ?>?
					</td>
					<td>
						<?php echo JHTML::_( 'select.booleanlist', 'hide_actionbutton', '', @$row->hide_actionbutton ); ?>
						<p class="dsc-tip">
						Set this to yes if the Buy/Reserve/Enroll action button should never display for events of this type.  If set to no, the button will display/hide based on availability.
						</p>
					</td>
				</tr>
				<tr>
					<td class="dsc-key">
						<?php echo JText::_( 'Actionbutton ID' ); ?>:
					</td>
					<td>
						<input type="text" name="actionbutton_id" value="<?php echo @$row->actionbutton_id; ?>" size="10" />
						<p class="dsc-tip">
						To override the action button for these kinds of events -- but only on the event view (not the eventinstance view) -- set this to an Actionbutton ID.  The event view is currently only used for mobile.
						To get the Actionbutton ID, go to the <a href="index.php?option=com_calendar&view=actionbuttons" taget="_blank">Actionbuttons admin</a> and note the ID number.
						</p>
					</td>
				</tr>
				<tr>
					<td class="dsc-key">
						<?php echo JText::_( 'Classification' ); ?>:
					</td>
					<td>
						<input type="text" name="type_class" value="<?php echo @$row->type_class; ?>" size="48" maxlength="250"  />
						<p class="dsc-tip">
						Classify this event type.  For example, if this is a "Festival" event type, enter 'festival'.  If you don't know what to put here, then put nothing.
						</p>
					</td>
				</tr>
                <tr>
                    <td class="dsc-key"><?php echo JText::_( 'Image' ); ?>:</td>
                    <td><?php $media = new DSCElementMedia(); ?> <?php echo $media->fetchElement( 'type_image', @$row->type_image ); ?>
                    </td>
                </tr>
				<tr>
					<td class="dsc-key">
						<?php echo JText::_( 'Always Display Description' ); ?>?
					</td>
					<td>
						<?php echo JHTML::_( 'select.booleanlist', 'always_display', '', @$row->always_display ); ?>
						<p class="dsc-tip">
						If set to Yes, this will always display Description 1 below the event's description.  If set to No, Description 1 (and Description 2, if the title matches the string) will only display if an event description doesn't exist.
						</p>
					</td>
				</tr>
				<tr>
					<td class="dsc-key">
						<?php echo JText::_( 'Only Display Description 1 on Titles with this String' ); ?>:
					</td>
					<td>
						<input type="text" name="string_match_1" value="<?php echo @$row->string_match_1; ?>" size="48" maxlength="250"  />
						<p class="dsc-tip">
						If provided, Description 1 will only display on event detail pages where the event title contains the string.
						</p>
					</td>
				</tr>
	            <tr>
                    <td class="dsc-key">
                        <?php echo JText::_( 'Description 1' ); ?>:
                    </td>
                    <td>
                        <?php $editor = JFactory::getEditor( ); ?>
                        <?php echo $editor->display( 'type_description_1', @$row->type_description_1, '100%', '450', '100', '20' );
						?>
                    </td>
                </tr>
				<tr>
					<td class="dsc-key">
						<?php echo JText::_( 'Only Display Description 2 on Titles with this String' ); ?>:
					</td>
					<td>
						<input type="text" name="string_match_2" value="<?php echo @$row->string_match_2; ?>" size="48" maxlength="250"  />
						<p class="dsc-tip">
						If provided, Description 2 will only display on event detail pages where the event title contains the string.
						</p>
					</td>
				</tr>
	            <tr>
                    <td class="dsc-key">
                        <?php echo JText::_( 'Description 2' ); ?>:
                    </td>
                    <td>
                        <?php $editor = JFactory::getEditor( ); ?>
                        <?php echo $editor->display( 'type_description_2', @$row->type_description_2, '100%', '450', '100', '20' );
						?>
                    </td>
                </tr>
			</table>
	<div>
		<input type="hidden" name="id" value="<?php echo @$row->type_id; ?>" />
		<input type="hidden" name="task" value="" />
	</div>
</form>