<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >
			  
	<fieldset>
		<legend><?php echo JText::_( 'Form' ); ?></legend>
			<table class="admintable">
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Actionbutton Name' ); ?>:
					</td>
					<td>
						<input type="text" name="actionbutton_name" value="<?php echo @$row->actionbutton_name;
																		   ?>" size="48" maxlength="250"  />
					</td>
				</tr>				               
    			<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Actionbutton URL' ); ?>:
					</td>
					<td>
						<input type="text" name="actionbutton_url_default" value="<?php echo @$row->actionbutton_url_default;
																		  ?>" size="48" maxlength="250"  />
					</td>
				</tr>
	            <tr>
                    <td style="vertical-align: top; width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Actionbutton Params' ); ?>:
                    </td>
                    <td>
                        <textarea name="actionbutton_params" id="actionbutton_params" rows="10" cols="35"><?php echo @$row->actionbutton_params; ?></textarea>
                    </td>
                </tr>
			</table>
			<input type="hidden" name="id" value="<?php echo @$row->actionbutton_id; ?>" />
			<input type="hidden" name="task" value="" />
	</fieldset>
</form>