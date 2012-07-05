<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >
			  
	<fieldset>
		<legend><?php echo JText::_( 'Form' ); ?></legend>
			<table class="admintable">
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Name' ); ?>:
					</td>
					<td>
						<input type="text" name="type_name" value="<?php echo @$row->type_name; ?>" size="48" maxlength="250"  />
					</td>
				</tr>				               
				<tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Params' ); ?>:
                    </td>
                    <td>
                        <textarea name="type_params" cols="75" rows="20"><?php echo @$row->type_params; ?></textarea>
                    </td>
                </tr> 
			</table>
			<input type="hidden" name="id" value="<?php echo @$row->type_id; ?>" />
			<input type="hidden" name="task" value="" />
	</fieldset>
</form>