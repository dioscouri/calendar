<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php 
echo $this->sliders->startPanel( JText::_( "Advanced Settings" ), 'advanced' );
?>

<table class="table table-striped table-bordered">
    <tbody>
        <tr>
            <td style="dsc-key">
            	<?php echo JText::_( 'Strip HTML Tags from Event Instance Descriptions' ); ?>?
            </th>
            <td>
            	<?php echo JHTML::_( 'select.booleanlist', 'strip_tags_eventinstance_description_short', 'class="inputbox"', $this->row->get( 'strip_tags_eventinstance_description_short', '0' ) ); ?>
            </td>
        </tr>
        <tr>
            <td style="dsc-key">
            	<?php echo JText::_( 'Load ActionButtons Asynchronously' ); ?>?
            </th>
            <td>
            	<?php echo JHTML::_( 'select.booleanlist', 'async_actionbuttons', 'class="inputbox"', $this->row->get( 'async_actionbuttons', '0' ) ); ?>
            	<p class="dsc-tip">Set this to YES if the Tessitura Web API is working slowly.  The real-time request for show availability will be executed with javascript in order to send the page's content to the user faster.  If the Tess Web API is working quickly, set this to NO to include the availability query with the initial page load.</p>
            </td>
        </tr>
    </tbody>
</table>
<?php
echo $this->sliders->endPanel();

?>