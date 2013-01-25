<?php defined('_JEXEC') or die; ?>
<?php $n = 1; ?>
<?php $helper = new DSCHelperString(); ?>

<ul id="packages-grid" class="grid">
    <li>
    
    <ul class="packages wrap clear list left full">
    <?php 
    foreach ($items as $item) 
    {
        $itemid_string = (!empty($item->itemid)) ? "&Itemid=" . $item->itemid : '';
        ?>
        
        <?php if ($n > $params->get('grid_width', '3')) { ?>
        </ul>
        <ul class="packages wrap clear list left full">
        <?php $n=1; } ?>
        
        <li class="wrap package-instance instance left span3">
        
            <a href="<?php echo JRoute::_( "index.php?option=com_calendar&view=packages&task=view&id=" . $item->alias . $itemid_string ); ?>" class="bare wrap">
                <div class="instance-data wrap">
                    <h3 class="list-title"><?php echo $item->title; ?></h3>
                </div>
            
                <div class="image-frame medium <?php if (empty($item->full_image)) { ?>no-image<?php } ?>">
                	<?php if (!empty($item->full_image)) { ?>            	
                    <img class="medium" src="<?php echo $item->full_image; ?>" alt="<?php echo htmlspecialchars( strip_tags( $item->title ) ); ?>" title="<?php echo htmlspecialchars( strip_tags( $item->title ) ); ?>" />
                    <?php } ?>
                </div>
                
                <div class="instance-data wrap">
                    <div class="overview">
                        <div class="description">
                        	<?php echo $helper->truncateString( $item->long_description, '50' ); ?>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        
        <?php
        $n++; 
    } 
    ?>
    </ul>
    
    </li>
</ul>
