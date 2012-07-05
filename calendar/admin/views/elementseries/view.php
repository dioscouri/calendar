<?php
/**
 * @version		$Id: view.php 10710 2008-08-21 10:08:12Z eddieajau $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * HTML User Element View class for the Content component
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class CalendarViewElementSeries extends JView
{
	function display()
	{
		global $mainframe;

		// Initialize variables
		$db			= &JFactory::getDBO();
		$nullDate	= $db->getNullDate();

		$document	= & JFactory::getDocument();
		$document->setTitle('Series Selection');

		JHTML::_('behavior.modal');

		$template = $mainframe->getTemplate();
		$document->addStyleSheet("templates/$template/css/general.css");
		$document->addScript( 'media/com_calendar/js/common.js' );

		$limitstart = JRequest::getVar('limitstart', '0', '', 'int');

		$lists = $this->_getLists();

		//Ordering allowed ?
		// $ordering = ($lists['order'] == 'section_name' && $lists['order_Dir'] == 'ASC');

		$rows = &$this->get('List');
		$page = &$this->get('Pagination');
		JHTML::_('behavior.tooltip');

		$object = JRequest::getVar( 'object' );
		$link = 'index.php?option=com_calendar&task=elementSeries&tmpl=component&object='.$object;

		Calendar::load( 'CalendarGrid', 'library.grid' );
		?>
		<?php JHTML::_('script', 'common.js', 'media/com_calendar/js/'); ?>

<form action="<?php echo $link; ?>" method="post" name="adminForm">

<table>
	<tr>
		<td width="100%" ><?php echo JText::_( 'Filter' ); ?>: <input
			type="text" name="search" id="search"
			value="<?php echo $lists['search'];?>" class="text_area"
			onchange="document.adminForm.submit();" />
		<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
		<button
			onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
		
	</tr>
</table>

<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			
			
			<th width="2%" class="title"><?php echo JHTML::_('grid.sort',   'ID', 'c.series_id', @$lists['order_Dir'], @$lists['order'] ); ?>
			</th>
			<th style="width:50px;"><?php echo JText::_( 'Image' ); ?></th>
			<th class="title"><?php echo JHTML::_('grid.sort',   'Name', 'c.series_name', @$lists['order_Dir'], @$lists['order'] ); ?>
			</th>
			<th class="title"><?php echo JHTML::_('grid.sort',   'Description', 'c.series_description', @$lists['order_Dir'], @$lists['order'] ); ?>
			</th>
			
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="15"><?php echo $page->getListFooter(); ?></td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $rows ); $i < $n; $i++)
	{
		$row = &$rows[$i];

		$onclick = "
					window.parent.jSelectSeries(
					'{$row->series_id}', '".str_replace(array("'", "\""), array("\\'", ""), $row->series_name)."', '".JRequest::getVar('object')."'
					);";
		?>
		<tr class="<?php echo "row$k"; ?>">
			
			
			<td style="text-align: center;"><a style="cursor: pointer;"
				onclick="<?php echo $onclick; ?>"> <?php echo $row->series_id;?> </a>
			</td>
			<td>
			<?php
				jimport('joomla.filesystem.file');
				if (!empty($row->series_thumb_image) && JFile::exists( Calendar::getPath( 'series_thumbs').DS.$row->series_thumb_image ))
				{
					?>
					<img src="<?php echo Calendar::getURL( 'series_thumbs').$row->series_thumb_image; ?>" style="display: block;" />
					<?php	
				}
			?>	
			</td>				
			<td><a style="cursor: pointer;"
				onclick="<?php echo $onclick; ?>"> <?php echo htmlspecialchars($row->series_name, ENT_QUOTES, 'UTF-8'); ?>
			</a></td>
			<td style="text-align: center;"><a style="cursor: pointer;"
				onclick="<?php echo $onclick; ?>"> <?php echo $row->series_description;?>
			</a></td>
			
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
</table>

<input type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="filter_order" value="<?php echo $lists['order']; ?>" /> <input
	type="hidden" name="filter_order_Dir"
	value="<?php echo $lists['order_Dir']; ?>" /></form>
	<?php
	}

	function _getLists()
	{
		global $mainframe;

		// Initialize variables
		$db		= &JFactory::getDBO();

		// Get some variables from the request
		//		$sectionid			= JRequest::getVar( 'sectionid', -1, '', 'int' );
		//		$redirect			= $sectionid;
		//		$option				= JRequest::getCmd( 'option' );
		$filter_order		= $mainframe->getUserStateFromRequest('userelement.filter_order',		'filter_order',		'',	'cmd');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest('userelement.filter_order_Dir',	'filter_order_Dir',	'',	'word');
		$limit				= $mainframe->getUserStateFromRequest('global.list.limit',					'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart			= $mainframe->getUserStateFromRequest('userelement.limitstart',			'limitstart',		0,	'int');
		$search				= $mainframe->getUserStateFromRequest('userelement.search',				'search',			'',	'string');
		$search				= JString::strtolower($search);

		// get list of sections for dropdown filter
		$javascript = 'onchange="document.adminForm.submit();"';

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search'] = $search;

		return $lists;
	}
}