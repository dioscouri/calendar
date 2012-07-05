<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php
$url = "http://www.dioscouri.com/";
if ( $amigosid = Calendar::getInstance( )->get( 'amigosid', '' ) )
{
	$url .= "?amigosid=" . $amigosid;
}
?>

<p align="center" <?php echo @$this->style; ?> style="clear:both;">
	<?php echo JText::_( 'Powered by' ) . " <a href='{$url}' target='_blank'>" . JText::_( 'Calendar' ) . "</a>";
	?>
</p>
