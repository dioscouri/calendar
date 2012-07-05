function calendarChangeDate( module_id, container, form, msg, year, month, current, handler ) 
{
    var url = 'index.php?option=com_calendar&view=events&task=loadModuleAjax&format=raw&v=2&element=' + module_id + '&year=' + year + '&month=' + month + '&current_date=' + current + '&handler=' + handler;
    calendarDoTask( url, container, form, msg, false );
}