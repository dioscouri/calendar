<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php
jimport('joomla.html.pane');
$pane = JPane::getInstance( 'tabs' );
?>

<div class="wrap <?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
    
    <div class="see-all">
    <?php echo JText::_( "See All" ); ?>
    </div>    

<?php
echo $pane->startPane( $module->id . '-tabs' );

if (!empty($items->today_items)) 
{
    // display the Today tab
    echo $pane->startPanel( JText::_( "Today" ), 'today' );
    
    echo $pane->endPanel();
}

if (!empty($items->this_week_items))
{
    // display the This Week tab
    echo $pane->startPanel( JText::_( "This Week" ), 'this-week' );
    
    echo $pane->endPanel();
}

echo $pane->endPane();
?>
</div>