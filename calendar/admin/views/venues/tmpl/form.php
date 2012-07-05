<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >
			  
	<fieldset>
		<legend><?php echo JText::_( 'Form' ); ?></legend>
			<table class="admintable">
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Venue Name' ); ?>:
					</td>
					<td>
						<input type="text" name="venue_name" value="<?php echo @$row->venue_name; ?>" size="48" maxlength="250"  />
					</td>
				</tr>				               
    			<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Venue URL' ); ?>:
					</td>
					<td>
						<input type="text" name="venue_url" value="<?php echo @$row->venue_url; ?>" size="48" maxlength="250"  />
					</td>
				</tr>
<?php /* ?>
				<tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Description' ); ?>:
                    </td>
                    <td>
                        <?php $editor = &JFactory::getEditor( ); ?>
                        <?php echo $editor->display( 'venue_description', @$row->venue_description, '100%', '450', '100', '20' );
						?>
                    </td>
                </tr> 
*/ ?>
			</table>
			<input type="hidden" name="id" value="<?php echo @$row->venue_id; ?>" />
			<input type="hidden" name="task" value="" />
	</fieldset>
</form>