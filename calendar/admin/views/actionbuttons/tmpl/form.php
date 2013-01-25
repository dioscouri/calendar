<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >
			  
	<fieldset>
		<legend><?php echo JText::_( 'Form' ); ?></legend>
			<table class="admintable">
				<tr>
					<td class="dsc-key">
						<?php echo JText::_( 'Name' ); ?>:
					</td>
					<td>
						<input type="text" name="actionbutton_name" value="<?php echo @$row->actionbutton_name;
																		   ?>" size="48" maxlength="250"  />
					</td>
				</tr>
    			<tr>
					<td class="dsc-key">
						<?php echo JText::_( 'URL' ); ?>:
					</td>
					<td>
						<input type="text" name="actionbutton_url_default" value="<?php echo @$row->actionbutton_url_default; ?>" size="48" maxlength="250"  />
					</td>
				</tr>
            	<tr>
            		<td class="dsc-key">
            			<?php echo JText::_( 'Use Overrides on Main Site' ); ?>?
            		</td>
            		<td>
            			<?php echo JHTML::_( 'select.booleanlist', 'actionbutton_override_main_site', '', @$row->actionbutton_override_main_site ); ?>
            			<p class="dsc-tip">
            			By default, actionbutton Name and URLs are only used on the mobile site.  On the main site, they are created by Tessitura/Artsvision by default.  Set this to YES to use your custom values on both the main site and the mobile site.
            			</p>
            		</td>
            	</tr>
	            <tr>
                    <td class="dsc-key">
                        <?php echo JText::_( 'Params' ); ?>:
                    </td>
                    <td>
                        <textarea name="actionbutton_params" id="actionbutton_params" rows="10" cols="35"><?php echo @$row->actionbutton_params; ?></textarea>
                    </td>
                </tr>
	            <tr>
                    <td class="dsc-key">
                        <?php echo JText::_( 'Notes' ); ?>:
                    </td>
                    <td>
                        <textarea name="actionbutton_notes" id="actionbutton_notes" rows="10" cols="35"><?php echo @$row->actionbutton_notes; ?></textarea>
                    </td>
                </tr>
			</table>
			<input type="hidden" name="id" value="<?php echo @$row->actionbutton_id; ?>" />
			<input type="hidden" name="task" value="" />
	</fieldset>
</form>