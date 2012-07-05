<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_('script', 'common.js', 'media/com_calendar/js/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>
<?php jimport('joomla.html.pane'); ?>
<?php $tabs = JPane::getInstance( 'tabs' ); ?>
<?php JHTML::_('behavior.tooltip'); ?>
<?php $categories_list = @$this->categories_list ?>
<?php $config = Calendar::getInstance(); ?>

<div id="validation_message"></div>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" id="adminForm" enctype="multipart/form-data" onsubmit="Dsc.formValidation( '<?php echo @$form['validation_url']; ?>', 'validation_message', document.adminForm.task.value, document.adminForm );" >

<?php 
echo $tabs->startPane( "pane_events" );

echo $tabs->startPanel( JText::_( 'Basic Information' ), "panel_basics");
?>
	<fieldset>
			<table class="admintable">
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Short Title' ); ?>:
					</td>
					<td>
						<input type="text" name="event_short_title" value="<?php echo @$row->event_short_title; ?>" size="72" maxlength="250" style="font-size: 20px;" />
                        <br/>
                        Please limit to 60 characters
					</td>
				</tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Long Title' ); ?>:
                    </td>
                    <td>
                        <input name="event_long_title" value="<?php echo @$row->event_long_title; ?>" type="text" size="150" maxlength="250" />
                        <br/>
                        Please limit to 100 characters
                    </td>
                </tr>
				<tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Alias' ); ?>:
                    </td>
                    <td>
                        <input name="event_alias" value="<?php echo @$row->event_alias; ?>" type="text" size="48" maxlength="250" />
                        <br/>
                        This will be automatically created for you
                    </td>
                </tr>
    			<tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Calendar' ); ?>:
                    </td>
                    <td>
    					<?php echo CalendarSelect::type( @$row->type_id, 'type_id', '', 'type_id', false, true ); ?>
    				</td>
                </tr>
                <tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<label for="enabled">
    						<?php echo JText::_( 'Published' ); ?>:
    					</label>
    				</td>
    				<td>
    					<?php echo JHTML::_( 'select.booleanlist', 'event_published', '', @$row->event_published ); ?>
    				</td>
    			</tr>
                <tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<label for="enabled">
    						<?php echo JText::_( 'Enable in Upcoming Events Queue' ); ?>:
    					</label>
    				</td>
    				<td>
    					<?php echo JHTML::_( 'select.booleanlist', 'event_upcoming_enabled', '', @$row->event_upcoming_enabled ); ?>
    				</td>
    			</tr>
                <?php /*<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<label for="event_full_image">
    					<?php echo JText::_( 'Image' ); ?>:
    					</label>
    				</td>
    				<td>
    					
						if ( !empty( $row->event_full_image ) )
						{
							$table = JTable::getInstance( 'Events', 'CalendarTable' );
							$table->load( @$row->event_id );
							$img = "<img src='" .$table->getImage('full', true). "' height='128px' />";
							echo CalendarUrl::popup( $table->getImage( 'full', true ), $img, array( 'update' => false, 'img' => true ) );
						} 
						
    					<br />
    					<input type="text" name="event_full_image" id="event_full_image" size="125" value="<?php echo @$row->event_full_image; ?>" />
                        <br />
                        Please enter the full URL to the image
						 * 
    				</td>
    			</tr>*/
                      
                 ?>
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<label for="event_full_image_new">
    					<?php echo JText::_( 'Upload New Image' ); ?>:
    					</label>
    				</td>
    				<td>
    					<input name="event_full_image_new" type="file" size="40" />
    				</td>
    			</tr> 
    		
    			<tr>
    				<td style="width: 100px; text-align: right;" class="key">
    					<label for="event_primary_category_id">
    					<?php echo JText::_( 'Primary Category' ); ?>:
    					</label>
    				</td>
    				<td>
    					<div>
        					<?php echo JText::_( 'Select an existing one' ); ?>:
    					    <?php echo CalendarSelect::category( @$row->event_primary_category_id, 'event_primary_category_id', '', 'event_primary_category_id', true, false, 'Select Category' ); ?>
    					</div>
                        
                        <?php if ($config->get('enable_add_new')) { ?>
                        <div>
        					<?php echo JText::_( 'Or enter new one' ); ?>:
    						<input name="new_primary_category_name" value="" type="text" size="48" maxlength="250" />
    					</div>
                        <?php } ?>
    				</td>
    			</tr>             
    			<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Select Secondary Categories' ); ?>:
					</td>
					<td>
                        <?php $cat_attribs = array('class' => 'inputbox', 'size' => '10', 'multiple' => 'multiple'); ?>
                        <?php echo CalendarSelect::secondcategory( $this->secondary_categories, 'secondary_categories[]', $cat_attribs, 'secondary_categories', false, false ); ?>
                        <?php if ($config->get('enable_add_new')) { ?>
                        <div>
        					<?php echo JText::_( 'Or enter new one' ); ?>:
    						<input name="new_secondary_category_name" value="" type="text" size="48" maxlength="250" />
    					</div>
                        <?php } ?>
					</td>
				</tr>
				<tr>
    				<td style="width: 100px; text-align: right;" class="key">    					
    					<?php echo JText::_( 'Series' ); ?>:    					
    				</td>
    				<td>
    					<div>
    					<?php echo JText::_( 'Select an existing one' ); ?>:  					
    					<?php
						echo CalendarSelect::series( @$row->series_id, 'series_id', '', 'series_id', true, 'No Series Selected' );
						?>
    					</div>
                        <?php if ($config->get('enable_add_new')) { ?>
    					<div>
        					<?php echo JText::_( 'Or enter new one' ); ?>: 
    						<input name="new_series_name" value="" type="text" size="48" maxlength="250" />
    					</div>
                        <?php } ?>
    				</td>
    			</tr>
               
    			<tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Display Type' ); ?>:
                    </td>
                    <td>
    					<?php echo CalendarSelect::displaytype( @$row->event_display_type, 'event_display_type', '', 'event_display_type', false, true ); ?>
    				</td>
                </tr>
                </table>
    </fieldset>
    
    <div style="clear: both;"></div>
    
    <p></p>
    
    <?php
    if ( !empty( $row->event_id ) )
    {						
    	echo $this->loadTemplate( 'instance' );
    }
    ?>
        
	<fieldset>
		<legend><?php echo JText::_( 'Existing Event Instances' ); ?></legend>
        
        <div id="event_instances">
            <?php echo $this->loadTemplate( 'instances' ); ?>
        </div>
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
                        <?php echo $editor->display( 'event_short_description', @$row->event_short_description, '100%', '450', '100', '20' );
                        ?>
                    </td>
                </tr>  
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Long Description' ); ?>:
                    </td>
                    <td>
                        <?php $editor = JFactory::getEditor( ); ?>
                        <?php echo $editor->display( 'event_long_description', @$row->event_long_description, '100%', '450', '100', '20' );
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
                        <?php echo JText::_( 'Event Multimedia' ); ?>:
                    </td>
                    <td>
                        <?php $editor = JFactory::getEditor( ); ?>
                        <?php echo $editor->display( 'event_multimedia', @$row->event_multimedia, '100%', '450', '100', '20' );
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
    
    <div>
        <input type="hidden" name="id" value="<?php echo @$row->event_id; ?>" />
        <input type="hidden" name="task" id="task" value="" />
    </div>
    
</form>