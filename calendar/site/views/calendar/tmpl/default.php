<style>
	/* calendar */
table.calendar		{ border-left:1px solid #999; }
tr.calendar-row	{  }
td.calendar-day	{ min-height:80px; font-size:11px; position:relative; } * html div.calendar-day { height:80px; }
td.calendar-day:hover	{ background:#eceff5; }
td.calendar-day-np	{ background:#eee; min-height:80px; } * html div.calendar-day-np { height:80px; }
td.calendar-day-head { background:#ccc; font-weight:bold; text-align:center; width:120px; padding:5px; border-bottom:1px solid #999; border-top:1px solid #999; border-right:1px solid #999; }
div.day-number		{ color:#000; font-weight:bold; float:left;  }
/* shared */
td.calendar-day, td.calendar-day-np { width:120px; padding:5px; border-bottom:1px solid #999; border-right:1px solid #999; }

</style>

<?php defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_('behavior.modal');
JHTML::_( 'script', 'common.js', 'media/com_calendar/js/' );
$state = @$this->state;
$form = @$this->form;
$items = @$this->items;
$date = @$this->date;
Calendar::load( 'CalendarHelperCategory', 'helpers.category' );
if( empty($state) )
	$state = @$vars->state;
	
if( empty($items) )
	$items = @$vars->items;

if( empty($date) )
	$date = @$vars->date;
	
if( empty($this->days) )
	$this->days = @$vars->days;

if( empty($this->workingday) )
	$this->workingday = @$vars->workingday;
	
$itemid_string = "";
if (!empty($vars->item_id))
{
    $itemid_string = "&Itemid=" . $vars->item_id;
}

/* draws a calendar */
function draw_calendar($month,$year){

	/* draw table */
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

	/* table headings */
	$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$calendar.= '<td class="calendar-day">';
			/* add in the day number */
			$calendar.= '<div class="day-number">'.$list_day.'</div>';

			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
			$calendar.= str_repeat('<p>&nbsp;</p>',2);
			
		$calendar.= '</td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table>';
	
	/* all done, return result */
	return $calendar;
}


/* sample usages */
echo '<h2>July 2009</h2>';
echo draw_calendar(7,2009);

var_dump($date);
var_dump($items);
var_dump($this->days);

?>
