<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab">Basic Information</a></li>
            <li><a href="#tab4" data-toggle="tab">Gory Details</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
    			<table class="dsc-table dsc-clear table table-striped table-bordered">
    				<tr>
    					<td class="dsc-key">
    						<?php echo JText::_( 'Name from DataSource' ); ?>:
    					</td>
    					<td>
    						<h2><?php echo @$row->name; ?></h2>
    					</td>
    				</tr>
                    <tr>
        				<td class="dsc-key">
        				    <?php echo JText::_( 'Name Override' ); ?>:
        				</td>
        				<td>
        					<p class="dsc-tip">If present, this will override the name from the datasource.</p>
        					<input type="text" name="venue_name" value="<?php echo @$row->venue_name; ?>" size="50" />
        				</td>
        			</tr>
    				<tr>
    					<td class="dsc-key">
    						<?php echo JText::_( 'Website' ); ?>:
    					</td>
    					<td>
    						<h3>[$row->website is inaccessible at the moment]<?php //echo @$row->website; ?></h3>
    					</td>
    				</tr>
                    <tr>
        				<td class="dsc-key">
        				    <?php echo JText::_( 'Admin Only' ); ?>:
        				</td>
        				<td>
        					<?php echo JHTML::_( 'select.booleanlist', 'admin_only', '', @$row->admin_only); ?>
        				</td>
        			</tr>
                    <tr>
        				<td class="dsc-key">
        				    <?php echo JText::_( 'Menu ItemID' ); ?>:
        				</td>
        				<td>
        					<p class="dsc-tip">This associates a venue with an existing menu item.  If this association exists, it will take priority over other associations.</p>
        					<input type="text" name="item_id" value="<?php echo @$row->item_id; ?>" size="10" />
        				</td>
        			</tr>
                    <tr>
        				<td class="dsc-key">
        				    <?php echo JText::_( 'Article ID' ); ?>:
        				</td>
        				<td>
		                <?php
		                $model = Calendar::getClass( 'CalendarModelElementArticle', 'models.elementarticle' );
		                echo $model->fetchElement( 'article_id', @$row->article_id );
		                echo $model->clearElement( 'article_id', '' );
		                ?>        					
        				</td>
        			</tr>
    			</table>
			</div>
			
            <div class="tab-pane" id="tab4">
                <table class="table table-striped table-bordered">
                    <tr>
                        <td class="dsc-key"><?php echo JText::_( 'DataSource ID' ); ?>:</td>
                        <td><?php echo @$row->datasource_id; ?></td>
                    </tr>
                    <tr>
                        <td class="dsc-key"><?php echo JText::_( 'Tessitura ID' ); ?>:</td>
                        <td><?php echo @$row->tessitura_id; ?></td>
                    </tr>
                    <tr>
                        <td class="dsc-key"><?php echo JText::_( 'ArtsVision ID' ); ?>:</td>
                        <td><?php echo @$row->artsvision_id; ?></td>
                    </tr>
                </table>
            </div>
	    </div>
	    
		<input type="hidden" name="id" value="<?php echo @$row->getDataSourceID(); ?>" />
		<input type="hidden" name="task" value="" />
	</div>
</form>
