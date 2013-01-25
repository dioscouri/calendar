<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_('script', 'common.js', 'media/com_calendar/js/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<div id="validation_message"></div>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" id="adminForm" enctype="multipart/form-data" onsubmit="calendarFormValidation( '<?php echo @$form['validation_url']; ?>', 'validation_message', document.adminForm.task.value, document.adminForm );" >
			  
	<fieldset>
		<table class="table table-striped table-bordered">
			<tr>
				<td class="dsc-key">
					<?php echo JText::_( 'Event' ); ?>:
				</td>
				<td>
                                <h2>
                                    <?php echo @$row->show->title; ?>
                                </h2>
                                <h3>
                                    <?php echo @$row->startDateTime->format('l, M j, Y, g:ia'); ?>
                                </h3>
                                <h4>
                                    <?php echo @$row->getVenue_name(); ?>
                                </h4>
                                <h5>
                                    <?php echo @$row->show->getSeries()->title; ?>
                                </h5>
				</td>
			</tr>
			<tr>
				<td class="dsc-key">
					<?php echo JText::_( 'Title Override' ); ?>:
				</td>
				<td>
				    <input name="eventinstance_title" type="text" value="<?php echo @$row->eventinstance_title; ?>" />
					<p class="dsc-tip dsc-clear">
					If provided, this will override the title of the parent Event. 
					</p>
				</td>
			</tr>
			<tr>
				<td class="dsc-key">
					<?php echo JText::_( 'Image Override' ); ?>:
				</td>
				<td>
					<?php $media = new DSCElementMedia(); ?> <?php echo $media->fetchElement( 'eventinstance_full_image', @$row->eventinstance_full_image ); ?>
					<p class="dsc-tip dsc-clear">
					If selected, this will override the image specified for the parent Event. 
					</p>
				</td>
			</tr>
			<tr>
				<td class="dsc-key">
					<?php echo JText::_( 'Prices Override' ); ?>:
				</td>
				<td>
				    <textarea name="eventinstance_prices" class="input-xxlarge"><?php echo @$row->eventinstance_prices; ?></textarea>
					<p class="dsc-tip dsc-clear">
					If provided, this will override the "prices" text of the parent Event. 
					</p>
				</td>
			</tr>
			<tr>
				<td class="dsc-key">
					<?php echo JText::_( 'Description Override' ); ?>:
				</td>
				<td>
					<?php $editor = JFactory::getEditor( ); ?>
					<?php echo $editor->display( 'eventinstance_description', @$row->eventinstance_description, '100%', '150', '100', '10' ); ?>
					<p class="dsc-tip dsc-clear">
					If provided, this will override the description of the parent Event. 
					</p>
				</td>
			</tr>
			<tr>
				<td class="dsc-key">
					<?php echo JText::_( 'Actionbutton URL Override' ); ?>:
				</td>
				<td>
				    <input name="actionbutton_url" type="text" value="<?php echo @$row->actionbutton_url; ?>" class="input-xxlarge" />
					<p class="dsc-tip dsc-clear">
					If provided, this will take priority over the Event Type's action button URL, the Event's actionbutton url, and the Tess/AV purchase URL
                    Leave this blank to just use the default.  
					</p>
				</td>
			</tr>
            <tr>
                <td class="dsc-key">
                <?php echo JText::_( 'Actionbutton Label Override' ); ?>:
                </td>
                <td>
                    <input name="actionbutton_string" type="text" value="<?php echo @$row->actionbutton_string; ?>" class="input-xlarge" />
            		<p class="dsc-tip dsc-clear">
            		If provided, this will take priority over the Event Type's actionbutton label, the Event's actionbutton label, and the Tess/AV default label.
                    Leave this blank to just use the default. 
            		</p>
                </td>
            </tr>
		</table>
		
		<div>
			<input type="hidden" name="datasource_id" value="<?php echo @$row->getDataSourceID(); ?>" />
			<input type="hidden" name="id" value="<?php echo @$row->getDataSourceID(); ?>" />
			<input type="hidden" name="task" value="" />
		</div>
		
	</fieldset>
</form>