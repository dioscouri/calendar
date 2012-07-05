<?php
/**
 * @version    1.5
 * @package    Calendar
 * @author     Dioscouri Design
 * @link     http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<div id="calendarSearch">
    <form class="search" action="<?php echo JRoute::_( 'index.php?option=com_calendar&view=search', false ); ?>" method="post" name="calendarSearch" onSubmit="if(this.elements['filter'].value == '<?php echo JText::_( $original ); ?>') this.elements['filter'].value = '';">
        <input class="text" type="text" name="filter_search" value="<?php echo JText::_( $filter_text ); ?>" onclick="this.value='';"/> 
        <input class="submit" name="submit" type="submit" value="<?php echo JText::_( "GO" ); ?>" />
        <input type="hidden" name="option" value="com_calendar" />
        <input type="hidden" name="view" value="search" />
        <input type="hidden" name="task" value="search" />
        <input type="hidden" name="search" value="1" />
        <input type="hidden" name="search_type" value="<?php echo ( int ) $params->get( 'filter_fields' ); ?>" />
        <input type="hidden" name="limit" value="<?php echo ( int ) $params->get( 'limit' ); ?>" />
        <input type="hidden" name="Itemid" value="<?php echo $item_id; ?>" />
    </form>
</div>
