<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_( 'script', 'common.js', 'media/com_calendar/js/' ); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<?php JFilterOutput::objectHTMLSafe( $row ); ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" name="adminForm" enctype="multipart/form-data">
			  
		<div id='onBeforeDisplay_wrapper'>
			<?php
			$dispatcher = JDispatcher::getInstance( );
			$dispatcher->trigger( 'onBeforeDisplayConfigForm', array( ) );
			?>
		</div>                

		<table style="width: 100%;">
			<tbody>
                <tr>
					<td style="vertical-align: top; min-width: 70%;">

					<?php
					// display defaults
					$pane = '1';
					echo $this->sliders->startPane( "pane_$pane" );
					
					$legend = JText::_( "General Settings" );
					echo $this->sliders->startPanel( JText::_( $legend ), 'general' );
					?>
					
					<table class="adminlist">
					<tbody>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_( 'Default Date' ); ?>
							</th>
							<td>
		                         <input type="text" name="default_date" value="<?php echo $this->row->get( 'default_date', '' ); ?>" size="25" />
							</td>
                            <td>
                                Leave this value empty to use the current date as the default.  Use the format YYYY-MM-DD
                            </td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_( 'Default Item ID' ); ?>
							</th>
							<td>
		                         <input type="text" name="item_id" value="<?php echo $this->row->get( 'item_id', '' ); ?>" />
							</td>
                            <td>
                                
                            </td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_( 'Disqus API Key' ); ?>
							</th>
							<td>
		                         <input type="text" name="disqus_api_key" value="<?php echo $this->row->get( 'disqus_api_key', '' ); ?>" size="100" />
							</td>
                            <td>
                                
                            </td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_( 'Disqus Forum ID' ); ?>
							</th>
							<td>
		                         <input type="text" name="disqus_forum_id" value="<?php echo $this->row->get( 'disqus_forum_id', '' ); ?>" size="50" />
							</td>
                            <td>
                                
                            </td>
						</tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'ENABLE ADD NEW' ); ?>?
                            </th>
                            <td>
                                <?php echo JHTML::_( 'select.booleanlist', 'enable_add_new', 'class="inputbox"', $this->row->get( 'enable_add_new', '0' ) );
								?>
                            </td>
                            <td>
                                
                            </td>
                        </tr>                        
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'SET DATE FORMAT' ); ?>
                            </th>
                            <td>
                                <input name="date_format" value="<?php echo $this->row->get( 'date_format', '%a, %d %b %Y, %I:%M%p' );
																 ?>" type="text" size="40"/>
                            </td>
                            <td>
                                <?php echo JText::_( "CONFIG SET DATE FORMAT" ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Show Linkback' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_( 'select.booleanlist', 'show_linkback', 'class="inputbox"', $this->row->get( 'show_linkback', '1' ) );
								?>
                            </td>
                            <td>
                                
                            </td>
                        </tr> 
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Display Facebook Like Button' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'display_facebook_like', 'class="inputbox"', $this->row->get('display_facebook_like', '1') ); ?>
                            </td>
                            <td>
                                
                            </td>
                        </tr>
                         <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Display Twitter Button' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'display_tweet', 'class="inputbox"', $this->row->get('display_tweet', '1') ); ?>
                            </td>
                            <td>
                                
                            </td>
                        </tr>
                         <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Default Twitter Message' ); ?>
                            </th>
                            <td>
                                <input type="text" name="display_tweet_message" value="<?php echo $this->row->get('display_tweet_message', 'Check this out!'); ?>" class="inputbox" size="35" />
                            </td>
                            <td>
                                
                            </td>
                        </tr>                       
					</tbody>
					</table>
					<?php
					echo $this->sliders->endPanel( );
					
					$legend = JText::_( "Working Days" );
					echo $this->sliders->startPanel( JText::_( $legend ), 'workingdays' );
					?>
					
					<table class="adminlist">
					<tbody>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Enter non working days (separated by commas)' ); ?>
                            </th>
                            <td>                             
								<input name="non_working_days" value="<?php echo $this->row->get( 'non_working_days', '' );
																	  ?>" type="text" size="100"/>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Non working day text' ); ?>
                            </th>
                            <td>
                                <input name="non_working_day_text" value="<?php echo $this->row->get( 'non_working_day_text', '' );
																		  ?>" type="text" size="100"/>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Working day text' ); ?>
                            </th>
                            <td>
                                <input name="working_day_text" value="<?php echo $this->row->get( 'working_day_text', '' );
																	  ?>" type="text" size="100"/>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Working day link text' ); ?>
                            </th>
                            <td>
                                <input name="working_day_link_text" value="<?php echo $this->row->get( 'working_day_link_text', '' );
																		   ?>" type="text" size="100"/>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Working day link' ); ?>
                            </th>
                            <td>
                                <input name="working_day_link" value="<?php echo $this->row->get( 'working_day_link', '' );
																	  ?>" type="text" size="100"/>
                            </td>
                        </tr>
					</tbody>
					</table>
					<?php
					echo $this->sliders->endPanel( );
					
					$legend = JText::_( "Images Settings" );
					echo $this->sliders->startPanel( JText::_( $legend ), 'images' );
					?>
					
					<table class="adminlist">
					<tbody>                        
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Default Series Image Height' );
								?>
                            </th>
                            <td>
                                <input type="text" name="series_img_height" value="<?php echo $this->row->get( 'series_img_height', '' );
																				   ?>" />
                            </td>
                        </tr>
						<tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Default Series Image Width' );
								?>
                            </th>
                            <td>
                                <input type="text" name="series_img_width" value="<?php echo $this->row->get( 'series_img_width', '' );
																				  ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Recreate Series Thumbnails' );
								?>
                            </th>
                            <td>
                                <a href="index.php?option=com_calendar&view=series&task=recreateThumbs" onClick="return confirm('<?php echo JText::_( 'Are you sure? Remember to save your new Configuration Values before doing this!' );
																																 ?>');"><?php echo JText::_( 'Click here to recreate the Series Thumbnails' );
																																		?></a>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Display Default Category Image' );
								?>
                            </th>
                            <td>
                                <?php echo JHTML::_( 'select.booleanlist', 'use_default_category_image', 'class="inputbox"', $this->row->get( 'use_default_category_image', '1' ) );
								?>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Default Category Image Height' );
								?>
                            </th>
                            <td>
                                <input type="text" name="category_img_height" value="<?php echo $this->row->get( 'category_img_height', '' );
																					 ?>" />
                            </td>
                        </tr>
						<tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Default Category Image Width' );
								?>
                            </th>
                            <td>
                                <input type="text" name="category_img_width" value="<?php echo $this->row->get( 'category_img_width', '' );
																					?>" />
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Recreate Category Thumbnails' );
								?>
                            </th>
                            <td>
                                <a href="index.php?option=com_calendar&view=categories&task=recreateThumbs" onClick="return confirm('<?php echo JText::_( 'Are you sure? Remember to save your new Configuration Values before doing this!' );
																																	 ?>');"><?php echo JText::_( 'Click here to recreate the Category Thumbnails' );
																																			?></a>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Default Event Image Height' );
								?>
                            </th>
                            <td>
                                <input type="text" name="event_img_height" value="<?php echo $this->row->get( 'event_img_height', '' );
																				  ?>" />
                            </td>
                        </tr>
						<tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Default Event Image Width' );
								?>
                            </th>
                            <td>
                                <input type="text" name="event_img_width" value="<?php echo $this->row->get( 'event_img_width', '' );
																				 ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Recreate Event Thumbnails' );
								?>
                            </th>
                            <td>
                                <a href="index.php?option=com_calendar&view=events&task=recreateThumbs" onClick="return confirm('<?php echo JText::_( 'Are you sure? Remember to save your new Configuration Values before doing this!' );
																																 ?>');"><?php echo JText::_( 'Click here to recreate the Event Thumbnails' );
																																		?></a>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Default Daily Event Image Height' );
								?>
                            </th>
                            <td>
                                <input type="text" name="dailyevent_img_height" value="<?php echo $this->row->get( 'dailyevent_img_height', '' );
																				  ?>" />
                            </td>
                        </tr>
						<tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Default Daily Event Image Width' );
								?>
                            </th>
                            <td>
                                <input type="text" name="dailyevent_img_width" value="<?php echo $this->row->get( 'dailyevent_img_width', '' );
																				 ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Recreate Daily Event Thumbnails' );
								?>
                            </th>
                            <td>
                                <a href="index.php?option=com_calendar&view=dailyevents&task=recreateThumbs" onClick="return confirm('<?php echo JText::_( 'Are you sure? Remember to save your new Configuration Values before doing this!' );
																																 ?>');"><?php echo JText::_( 'Click here to recreate the Daily Event Thumbnails' );
																																		?></a>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Default Event Instance Image Height' );
								?>
                            </th>
                            <td>
                                <input type="text" name="eventinstance_img_height" value="<?php echo $this->row->get( 'eventinstance_img_height', '' );
																						  ?>" />
                            </td>
                        </tr>
						<tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Default Event Instance Image Width' );
								?>
                            </th>
                            <td>
                                <input type="text" name="eventinstance_img_width" value="<?php echo $this->row->get( 'eventinstance_img_width', '' );
																						 ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Recreate Event Instance Thumbnails' );
								?>
                            </th>
                            <td>
                                <a href="index.php?option=com_calendar&view=eventinstances&task=recreateThumbs" onClick="return confirm('<?php echo JText::_( 'Are you sure? Remember to save your new Configuration Values before doing this!' );
																																		 ?>');"><?php echo JText::_( 'Click here to recreate the Event Instance Thumbnails' );
																																				?></a>
                            </td>
                        </tr>
					</tbody>
					</table>
					
					<?php
					echo $this->sliders->endPanel( );
					
					$legend = JText::_( "Administrator ToolTips" );
					echo $this->sliders->startPanel( JText::_( $legend ), 'defaults' );
					?>
					
					<table class="adminlist">
					<tbody>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_( 'Hide Dashboard Note' ); ?>
							</th>
							<td>
		                        <?php echo JHTML::_( 'select.booleanlist', 'page_tooltip_dashboard_disabled', 'class="inputbox"', $this->row->get( 'page_tooltip_dashboard_disabled', '0' ) );
								?>
							</td>
                            <td>
                                
                            </td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_( 'Hide Configuration Note' ); ?>
							</th>
							<td>
		                        <?php echo JHTML::_( 'select.booleanlist', 'page_tooltip_config_disabled', 'class="inputbox"', $this->row->get( 'page_tooltip_config_disabled', '0' ) );
								?>
							</td>
                            <td>
                                
                            </td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_( 'Hide Tools Note' ); ?>
							</th>
							<td>
		                        <?php echo JHTML::_( 'select.booleanlist', 'page_tooltip_tools_disabled', 'class="inputbox"', $this->row->get( 'page_tooltip_tools_disabled', '0' ) );
								?>
							</td>
                            <td>
                                
                            </td>
						</tr>
					</tbody>
					</table>
					<?php
					
					echo $this->sliders->endPanel( );
					
					$legend = JText::_( "iCal Settings" );
					echo $this->sliders->startPanel( JText::_( $legend ), 'ical' );
					?>
					
					<table class="adminlist">
					<tbody>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_( 'Unique ID' ); ?>
							</th>
							<td>
		                         <input type="text" name="ical_unique_id" value="<?php echo $this->row->get( 'ical_unique_id', '' ); ?>" />
							</td>
                            <td>
                                
                            </td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_( 'Atendee email' ); ?>
							</th>
							<td>
		                         <input type="text" name="ical_atendee_email" value="<?php echo $this->row->get( 'ical_atendee_email', '' ); ?>" />
							</td>
                            <td>
                                
                            </td>
						</tr>
					</tbody>
					</table>
					<?php
					
					echo $this->sliders->endPanel( );
					
					?>
					</td>
					<td style="vertical-align: top; max-width: 30%;">
						
						<?php echo CalendarGrid::pagetooltip( JRequest::getVar( 'view' ) );
						?>
						
						<div id='onDisplayRightColumn_wrapper'>
							<?php
							$dispatcher = JDispatcher::getInstance( );
							$dispatcher->trigger( 'onDisplayConfigFormRightColumn', array( ) );
							?>
						</div>

					</td>
                </tr>
            </tbody>
		</table>

		<div id='onAfterDisplay_wrapper'>
			<?php
			$dispatcher = JDispatcher::getInstance( );
			$dispatcher->trigger( 'onAfterDisplayConfigForm', array( ) );
			?>
		</div>
        
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction;
														?>" />
	
	<?php echo $this->form['validate']; ?>
</form>
