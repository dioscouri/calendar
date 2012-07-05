<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >
			  
	<fieldset>
		<legend><?php echo JText::_( 'Form' ); ?></legend>
			<table class="admintable">
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Series Name' ); ?>:
					</td>
					<td>
						<input type="text" name="series_name" value="<?php echo @$row->series_name;
																	 ?>" size="48" maxlength="250"  />
					</td>
				</tr>
                <tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Title' ); ?>:
					</td>
					<td>
						<input type="text" name="series_title" value="<?php echo @$row->series_title;
																	  ?>" size="48" maxlength="250"  />
					</td>
				</tr>				
                <tr>
   					<td style="width: 100px; text-align: right;" class="key"> 		
    					<?php echo JText::_( 'Current Image' ); ?>:
    				</td>
    				<td>
    					<?php
						jimport( 'joomla.filesystem.file' );
						if ( !empty( $row->series_full_image ) && JFile::exists( Calendar::getPath( 'series_images' ) . DS . $row->series_full_image ) )
						{
							$table = JTable::getInstance( 'Series', 'CalendarTable' );
							$table->load( @$row->series_id );
							echo CalendarUrl::popup( $table->getImage( 'full', true ), $table->getImage( ), array( 'update' => false, 'img' => true ) );
						}
						?>
    					<br />
    					<input type="text" disabled="disabled" name="series_full_image" id="series_full_image" size="48" maxlength="250" value="<?php echo @$row->series_full_image;
																																				?>" />
    				</td>
    			</tr>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<label for="series_full_image_new">
    					<?php echo JText::_( 'Upload New Image' ); ?>:
    					</label>
    				</td>
    				<td>
    					<input name="series_full_image_new" type="file" size="40" />
    				</td>
    			</tr>
    			<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Tab Label' ); ?>:
					</td>
					<td>
						<input type="text" name="series_tab_label" value="<?php echo @$row->series_tab_label;
																		  ?>" size="48" maxlength="250"  />
					</td>
				</tr>
				<tr>
	              	<td style="width: 100px; text-align: right;" class="key">
	               		<?php echo JText::_( 'Associated Article' ); ?>:
					</td>
	                <td>
		                <?php $elements = CalendarSelect::article_element( @$row->series_associated_article_id, 'series_associated_article_id' );
						?>
		                <?php echo $elements['select']; ?>
		                <?php echo $elements['clear']; ?>
	                </td>
	            </tr>
	            <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Description' ); ?>:
                    </td>
                    <td>
                        <?php $editor = JFactory::getEditor( ); ?>
                        <?php echo $editor->display( 'series_description', @$row->series_description, '100%', '450', '100', '20' );
						?>
                    </td>
                </tr>
	            <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Series Multimedia' ); ?>:
                    </td>
                    <td>
                        <?php $editor = JFactory::getEditor( ); ?>
                        <?php echo $editor->display( 'series_multimedia', @$row->series_multimedia, '100%', '450', '100', '20' );
						?>
                    </td>
                </tr>
			</table>
			<input type="hidden" name="id" value="<?php echo @$row->series_id; ?>" />
			<input type="hidden" name="task" value="" />
	</fieldset>
</form>