<?php defined( '_JEXEC' ) or die( 'Restricted access' );

JHTML::_( 'script', 'common.js', 'media/com_calendar/js/' );

$items = @$this->items;
if( empty($items) )
	$items = @$vars->items;
	
$date = @$this->date;
if( empty($date) )
	$date = @$vars->date;
	
$days = $date->days;
$hours = $date->hours;
$datetime = $date->datetime;
$nonworkingdays = $date->nonworkingdays;
$header_title = JText::_( $date->startmonthname ) . ' ' . $date->startday . ' ' . $date->startyear . ' - ' . JText::_( $date->endmonthname ) . ' ' . $date->endday . ' ' . $date->endyear;
$config = CalendarConfig::getInstance( );
?> 
<div id="calendar_container" name="calendar_container">
	
	<div class="header_calendar_links">
		<div class="header_link">
			<a href="index.php?option=com_calendar&view=month<?php echo '&month=' . $date->month . '&year=' . $date->year; ?>">Month</a>		
		</div>
		<div class="header_link">
			<a href="index.php?option=com_calendar&view=week<?php echo '&current_date=' . $date->current . '&month=' . $date->month . '&year=' . $date->year; ?>">Week</a>
		</div>
		<div class="header_link">
			<a href="index.php?option=com_calendar&view=three<?php echo '&current_date=' . $date->current . '&month=' . $date->month . '&year=' . $date->year; ?>">3 Day</a>
		</div>
		<div class="header_link">
			<a href="index.php?option=com_calendar&view=day<?php echo '&current_date=' . $date->current . '&month=' . $date->month . '&year=' . $date->year; ?>">Day</a>
		</div>
	</div>
		
	<div class="header_title">
	
		<?php echo $header_title; ?>
				  
		<!-- left arrow -->
		<a class="calendar_left_arrow" href="<?php echo 'index.php?option=com_calendar&view=three&current_date=' . $date->prevthreedate . '&month=' . $date->prevmonth . '&year=' . $date->prevyear; ?>">«««</a>
				 
		<!-- right arrow -->
		<a class="calendar_right_arrow" href="<?php echo 'index.php?option=com_calendar&view=three&current_date=' . $date->nextthreedate . '&month=' . $date->nextmonth . '&year=' . $date->nextyear; ?>">»»»</a>
		
	</div>
	
	<div class="calendar_three">
		<table class="events_calendar_three">
				 
		<thead>
			<tr>
				<th class="three_hour_header">
					
				</th>
				<?php
				foreach ( $days as $day )
				{
					echo '<th class="three_header_days">';
					echo trim( strtoupper( JText::_( date( 'D', strtotime( $day ) ) ) ) );
					echo '</th>';
				}
				?>
			</tr>			
		</thead>
		<tbody>
			<tr>
				<td class="three_hour_header">
					
				</td>
				<?php
				// echo column headers (depending of working and non-working days)
				$nonworking = array( );
				foreach ( $days as $day )
				{
					if ( in_array( JText::_( date( 'l', strtotime( $day ) ) ), $nonworkingdays ) )
					{
						$nonworking[] = '_non';
					}
					else
					{
						$nonworking[] = '';
					}
				}
				
				if ( !in_array( '_non', $nonworking ) )
				{
					echo '<td colspan="3" class="three_working_day">';
					echo $config->get( 'working_day_text' ) . ' <a href="' . $config->get( 'working_day_link' ) . '">' . $config->get( 'working_day_link_text' ) . '</a>';
					echo '</td>';
				}
				else
				{
					for ( $i = 0; $i < 3; $i++ )
					{
						echo '<td class="three' . $nonworking[$i] . '_working_day">';
						if ( $nonworking[$i] == '_non' )
						{
							echo $config->get( 'non_working_day_text' );
						}
						else
						{
							echo '<a href="' . $config->get( 'working_day_link' ) . '">' . $config->get( 'working_day_link_text' ) . '</a>';
						}
						echo '</td>';
					}
				}
				?>
			</tr>
		 	<?php
			 // echo table cells with data (hours and eventinstances)
			 foreach ( $days as $day )
			 {
				 foreach ( $hours as $hour )
				 {
					 foreach ( $items as $item )
					 {
						 if ( date( 'Y-m-d G', strtotime( $item->eventinstance_date . ' ' . $item->eventinstance_start_time ) ) == date( 'Y-m-d G', strtotime( $day . ' ' . $hour ) ) )
						 {
							 $datetime[$day][$hour][] = $item;
						 }
					 }
				 }
			 }
			 
			 foreach ( $hours as $hour )
			 {
			 	if( !empty( $datetime[$days[0]][$hour] ) || !empty( $datetime[$days[1]][$hour] ) || !empty( $datetime[$days[2]][$hour] ) )
			 	{
			 		echo '<tr>';
			 		
			 		echo '<td class="three_hours">';
						echo $hour;
					echo '</td>';
					
					echo '<td class="three_days">';
			 	 	if( !empty( $datetime[$days[0]][$hour] ) )
					{
						for ( $i = 0; $i < count( @$datetime[$days[0]][$hour] ); $i++ )
						{
							if ( !empty( $datetime[$days[0]][$hour][$i] ) )
							{
								// echo time
								echo '<span class="three_datetime">';
								echo date( 'Y-m-d h:ia', strtotime( @$datetime[$days[0]][$hour][$i]->eventinstance_date . ' ' . @$datetime[$days[0]][$hour][$i]->eventinstance_start_time ) );
								echo '</span>';
								 
								// echo categories
								echo '<div class="three_categories">';
								Calendar::load( 'CalendarHelperCategory', 'helpers.category' );
								echo CalendarHelperCategory::getCategoryName( @$datetime[$days[0]][$hour][$i]->event_primary_category_id );
								$categories_list = CalendarHelperCategory::getSecondaryCategories( @$datetime[$days[0]][$hour][$i]->event_id );
							 	if( !empty($categories_list))
								{
									echo  ', ' . $categories_list . '<br/>';
								}	
								echo '</div>';
								 
								// echo long title
								echo '<div class="three_long_title">';
								echo '<a href="' . JRoute::_( @$datetime[$days[0]][$hour][$i]->link_view ) . '">';
								echo @$datetime[$days[0]][$hour][$i]->event_long_title;
								echo '</a>';
								echo '</div>';
							 }
						 }
					}
					echo '</td>';

					echo '<td class="three_days">';
					if( !empty( $datetime[$days[1]][$hour] ) )
					{
						for ( $i = 0; $i < count( @$datetime[$days[1]][$hour] ); $i++ )
						{
							if ( !empty( $datetime[$days[1]][$hour][$i] ) )
							{
								// echo time
								echo '<span class="week_datetime">';
								echo date( 'Y-m-d h:ia', strtotime( @$datetime[$days[1]][$hour][$i]->eventinstance_date . ' ' . @$datetime[$days[1]][$hour][$i]->eventinstance_start_time ) );
								echo '</span>';
								 
								// echo categories
								echo '<div class="week_categories">';
								Calendar::load( 'CalendarHelperCategory', 'helpers.category' );
								echo CalendarHelperCategory::getCategoryName( @$datetime[$days[1]][$hour][$i]->event_primary_category_id );
								$categories_list = CalendarHelperCategory::getSecondaryCategories( @$datetime[$days[1]][$hour][$i]->event_id );
							 	if( !empty($categories_list))
								{
									echo  ', ' . $categories_list . '<br/>';
								}
								echo '</div>';
								 
								// echo long title
								echo '<div class="week_long_title">';
								echo '<a href="' . JRoute::_( @$datetime[$days[1]][$hour][$i]->link_view ) . '">';
								echo @$datetime[$days[1]][$hour][$i]->event_long_title;
								echo '</a>';
								echo '</div>';
							 }
						 }
					}
					echo '</td>';

					echo '<td class="three_days">';
					if( !empty( $datetime[$days[2]][$hour] ) )
					{
						for ( $i = 0; $i < count( @$datetime[$days[2]][$hour] ); $i++ )
						{
							if ( !empty( $datetime[$days[2]][$hour][$i] ) )
							{
								// echo time
								echo '<div class="three_datetime">';
								echo date( 'Y-m-d h:ia', strtotime( @$datetime[$days[2]][$hour][$i]->eventinstance_date . ' ' . @$datetime[$days[2]][$hour][$i]->eventinstance_start_time ) );
								echo '</div>';
								
								// echo categories
								echo '<div class="three_categories">';
								Calendar::load( 'CalendarHelperCategory', 'helpers.category' );
								echo CalendarHelperCategory::getCategoryName( @$datetime[$days[2]][$hour][$i]->event_primary_category_id );
								$categories_list = CalendarHelperCategory::getSecondaryCategories( @$datetime[$days[2]][$hour][$i]->event_id );
								if( !empty($categories_list))
								{
									echo  ', ' . $categories_list . '<br/>';
								}
								echo '</div>';
								 
								// echo long title
								echo '<div class="three_long_title">';
								echo '<a href="' . JRoute::_( @$datetime[$days[2]][$hour][$i]->link_view ) . '">';
								echo @$datetime[$days[2]][$hour][$i]->event_long_title;
								echo '</a>';
								echo '</div>';
							 }
						 }
					}
					echo '</td>';
						 
					echo '</tr>';
			 	}
			 }			 
		?>						
		</tbody>
		</table>
	</div>
	
	<div class="header_title">
	
		<?php echo $header_title; ?>
				  
		<!-- left arrow -->
		<a class="calendar_left_arrow" href="<?php echo 'index.php?option=com_calendar&view=three&current_date=' . $date->prevthreedate . '&month=' . $date->prevmonth . '&year=' . $date->prevyear; ?>">«««</a>
				 
		<!-- right arrow -->
		<a class="calendar_right_arrow" href="<?php echo 'index.php?option=com_calendar&view=three&current_date=' . $date->nextthreedate . '&month=' . $date->nextmonth . '&year=' . $date->nextyear; ?>">»»»</a>
		
	</div>
 
</div>