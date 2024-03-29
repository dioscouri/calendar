<style>
	/* calendar */
	table.calendar {
	}
	tr.calendar-row {
		border-left: 1px solid #d5d5d5;
	}
	td.calendar-day {
		min-height: 80px;
		font-size: 11px;
		position: relative;
	}
	div.calendar-day {
		min-height: 80px;
	}
	td.calendar-day:hover {
		background: #eceff5;
	}
	td.calendar-day-np {
		background: #eee;
		min-height: 80px;
	}
	* html div.calendar-day-np {
		height: 80px;
	}
	th.calendar-day-head {
		background: #002b54;
		color: #FFF;
		font-weight: bold;
		text-align: center;
		width: 125px;
		padding: 5px;
		border-bottom: 1px solid #d5d5d5;
		border-top: 1px solid #002b54;
		border-right: 1px solid #002b54;
	}
	thead {
		background: #002b54;
		color: #FFF;
		font-weight: bold;
		text-align: center;
		width: 125px;
		padding: 5px;
		border-bottom: 1px solid #999;
		border-top: 1px solid #002b54;
		border-right: 1px solid #002b54;
	}

	div.day-number {
		color: #b0b0b0;
		/*font-weight: bold;*/
		float: left;
	}
	/* shared */
	td.calendar-day, td.calendar-day-np {
		width: 125px;
		padding: 5px;
		border-bottom: 1px solid #d5d5d5;
		border-right: 1px solid #d5d5d5;
	}

	div#date-navigation ul.horiz {
	    width: 945px;
		margin: 0;
		padding-top: 5px;
		padding-bottom: 5px;
	}
	div#date-navigation ul.horiz li {
		display: inline-block;
		width: 310px;
		height:20px;
		list-style: none;
	}
	div#date-navigation ul.horiz li.prev {
		
	}
	div#date-navigation ul.horiz li.next {
	
	}
	div#date-navigation ul.horiz li a.prev {
		
		background-image: url(/media/com_calendar/images/prev.png);
		background-repeat:no-repeat;
		display: block;
		height: 16px;
		text-indent: -9999px;
		width: 9px;
		margin-left:39px;
		margin-top:15px;
	}
	div#date-navigation ul.horiz li.date-range {
		text-align: center;
	}
	div#date-navigation ul.horiz li.date-range h2 {
		color:#FFF;
		
	}
	
	div#date-navigation ul.horiz li a.next {
	
		background-image: url(/media/com_calendar/images/next.png);
		display: block;
		height: 16px;
		text-indent: -9999px;
		width: 9px;
		margin-left:278px;
		margin-top:15px;
	}

	div#date-navigation {
		width: 953px;
		background: #002b54;
		color: #FFF;
		-webkit-border-top-left-radius: 10px;
		-webkit-border-top-right-radius: 10px;
		-moz-border-radius-topleft: 10px;
		-moz-border-radius-topright: 10px;
		border-top-left-radius: 10px;
		border-top-right-radius: 10px;
	}

</style>

<?php defined('_JEXEC') or die('Restricted access');
	JHTML::_('behavior.modal');
	JHTML::_('script', 'common.js', 'media/com_calendar/js/');
	$state = @$this -> state;
	$form = @$this -> form;
	$items = @$this -> items;
	$date = @$this -> date;
	Calendar::load('CalendarHelperCategory', 'helpers.category');
	if (empty($state))
		$state = @$vars -> state;

	if (empty($items))
		$items = @$vars -> items;

	if (empty($date))
		$date = @$vars -> date;

	if (empty($this -> days))
		$this -> days = @$vars -> days;

	if (empty($this -> workingday))
		$this -> workingday = @$vars -> workingday;

	$itemid_string = "";
	if (!empty($vars -> item_id)) {
		$itemid_string = "&Itemid=" . $vars -> item_id;
	}
?>

<div id="calendar-content" class="wrap">
    <?php
	if (@$state -> filter_primary_categories == array('-1')) {
		/*
		 ?>
		 <div class="error">
		 <?php echo JText::_( "Please select at least one category of events to display"); ?>
		 </div>
		 <?php
		 */
	}
    ?>
    
    <div id="date-navigation" class="wrap">
        <ul class="horiz">
    		<li class="prev"><a class="prev" href="<?php echo JRoute::_('index.php?option=com_calendar&view=month&reset=0&month=' . $date -> prevmonth . '&year=' . $date -> prevyear . $itemid_string); ?>"><?php echo JText::_("Previous"); ?></a></li>
            <li class="date-range"><h2><?php echo strtoupper($date -> month_name); ?></h2></li>
    		<li class="next" ><a class="next" href="<?php echo JRoute::_('index.php?option=com_calendar&view=month&reset=0&month=' . $date -> nextmonth . '&year=' . $date -> nextyear . $itemid_string); ?>"><?php echo JText::_("Next"); ?></a></li>
        </ul>    
    </div>
<?php

$config = JFactory::getConfig();
$today = JFactory::getDate() -> toFormat('%e', $config -> getValue('config.offset'));
?>
	<table cellpadding="0" cellspacing="0" class="calendar">
	
	
	
	<?php

	/* table headings */
	$headers = '<thead>';
	$headers .= '<tr class="calendar-row-header"><th class="calendar-day-head">' . implode('</th><th class="calendar-day-head">', array_keys($date -> weekdays)) . '</th></tr>';
	$headers .= '</thead>';
	echo $headers;

	/* days and weeks vars now ... */
	$blankdays = date('w', mktime(0, 0, 0, $date -> month, 1, $date -> year));

	$days_in_month = date('t', mktime(0, 0, 0, $date -> month, 1, $date -> year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$weeks = '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for ($x = 0; $x < $blankdays; $x++) :
		$weeks .= '<td class="calendar-day-np">&nbsp;</td>';
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */

	for ($list_day = 1; $list_day <= $days_in_month; $list_day++) :

		$weeks .= '<td class="calendar-day"><div class="calendar-day">';
		/* add in the day number */
		$weeks .= '<div class="day-number">' . $list_day . '</div>';

		$key = $date -> year . '-' . $date -> month . '-' . $list_day;
		if (!empty($this -> days[$key])) {
			$events = $this -> days[$key] -> events;
			$weeks .= '<ul>';
			foreach ($events as $event) {
				$weeks .= '<li>';
				$weeks .= $event -> event_short_title;
				$weeks .= '</li>';
			} $weeks .= '</ul>';
		}

		$weeks .= '</div></td>';
		if ($blankdays == 6) :
			$weeks .= '</tr>';
			if (($day_counter + 1) != $days_in_month) :
				$weeks .= '<tr class="calendar-row">';
			endif;
			$blankdays = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++;
		$blankdays++;
		$day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if ($days_in_this_week < 8) :
		for ($x = 1; $x <= (8 - $days_in_this_week); $x++) :
			$weeks .= '<td class="calendar-day-np">&nbsp;</td>';
		endfor;
	endif;

	/* final row */
	$weeks .= '</tr>';

	echo $weeks;
	  ?>
	</table>
</div>
