<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_('script', 'common.js', 'media/com_calendar/js/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>
<?php jimport('joomla.html.pane'); ?>
<?php $tabs = JPane::getInstance( 'tabs' ); ?>
<?php JHTML::_('behavior.tooltip'); ?>
<?php $categories_list = @$this->categories_list ?>
<?php $config = Calendar::getInstance(); ?>
<?php $editor = JFactory::getEditor( ); ?>

<div id="validation_message"></div>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" id="adminForm" enctype="multipart/form-data" onsubmit="Dsc.formValidation( '<?php echo @$form['validation_url']; ?>', 'validation_message', document.adminForm.task.value, document.adminForm );">

    <div class="tabbable">
        <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab">Basic Information</a></li>
            <li><a href="#publication" data-toggle="tab">Publication</a></li>
            <li><a href="#tab2" data-toggle="tab">Descriptions</a></li>
            <li><a href="#program-notes" data-toggle="tab">Program Notes</a></li>
            <li><a href="#tab3" data-toggle="tab">Multimedia</a></li>
            <li><a href="#tab4" data-toggle="tab">Gory Details</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <fieldset>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Title' ); ?>:</td>
                            <td>
                                <input name="title" value="<?php echo @$row->title; ?>" type="text" size="100" maxlength="250" style="font-size: 20px;" />
                                <?php if (!empty($row->avInternalTitle) && $row->avInternalTitle != $row->title) { ?>
                                    <p class="dsc-tip">&nbsp;&nbsp;&bull;&nbsp;&nbsp;<b>AV Internal Title:</b> <?php echo $row->avInternalTitle; ?></p>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Subtitle' ); ?>:</td>
                            <td>
                                <input name="subtitle" value="<?php echo @$row->subtitle; ?>" type="text" size="100" maxlength="250" />
                            </td>
                        </tr>
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Date and Time' ); ?>:</td>
                            <td>
                                <h3>
                                    <?php echo @$row->getFirstDate()->format('l, M j, Y, g:ia'); ?>
                                    -
                                    <?php echo @$row->getLastDate()->format('l, M j, Y, g:ia'); ?>
                                </h3>
                            </td>
                        </tr>
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Venue' ); ?>:</td>
                            <td>
                                <h4>
                                    <?php echo @$row->getPrimaryVenue()->name; ?>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Prices' ); ?>:</td>
                            <td>
                                <textarea id="displayPrices" name="displayPrices" style="height: 100px; width: 350px;"><?php echo @$row->displayPrices; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Primary Event Type' ); ?>:</td>
                            <td><?php echo CalendarSelect::type( @$row->type_id, 'type_id', '', 'type_id', true ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Additional Event Types' ); ?>:</td>
                            <td>
                                <p class="dsc-tip">Use Ctrl+click to select multiple additional event types</p>
                                <?php echo CalendarSelect::type( @$row->event_types, 'eventtypes[]', array('class' => 'inputbox', 'size' => '10', 'multiple'=>'multiple'), 'eventtypes' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Local Image' ); ?>:</td>
                            <td><?php $media = new DSCElementMedia(); ?> <?php echo $media->fetchElement( 'event_full_image', @$row->event_full_image ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Sponsors' ); ?>:</td>
                            <td>
                                <?php $editor = JFactory::getEditor( ); ?>
                                <?php echo $editor->display( 'event_sponsors', @$row->event_sponsors, '100%', '150', '100', '10' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="dsc-key">
                            <?php echo JText::_( 'Actionbutton URL Override' ); ?>:
                            </td>
                            <td>
                                <input name="event_actionbutton_url" type="text" value="<?php echo @$row->event_actionbutton_url; ?>" class="input-xxlarge" />
                        		<p class="dsc-tip dsc-clear">
                        		If provided, this will take priority over the Event Type's actionbutton URL and the Tess/AV purchase URL.
                                If you need a unique URL for a specific performance, edit that performance.
                                Leave this blank to just use the default. 
                        		</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="dsc-key">
                            <?php echo JText::_( 'Actionbutton Label Override' ); ?>:
                            </td>
                            <td>
                                <input name="event_actionbutton_label" type="text" value="<?php echo @$row->event_actionbutton_label; ?>" class="input-xlarge" />
                        		<p class="dsc-tip dsc-clear">
                        		If provided, this will take priority over the Event Type's actionbutton label and the Tess/AV default label.
                                If you need a unique label for a specific performance, edit that performance.
                                Leave this blank to just use the default. 
                        		</p>
                            </td>
                        </tr>
                        <?php 
                        if (!empty($row->event_id))
                        {
                            $tagsHelper = new CalendarHelperTags();
                            if ($tagsHelper->isInstalled())
                            {
                                ?>
                        <tr>
                            <td colspan="2"><?php echo $tagsHelper->getForm( $row->event_id ); ?>
                            </td>
                        </tr>
                        <?php 
                            }
                        } 
                            else 
                        {
                            echo "Save this item to be able to add Tags";
                        }
                        ?>

                    </table>
                </fieldset>

                <div style="clear: both;"></div>

                <p></p>

            </div>
            
            <div class="tab-pane fade" id="publication">
                <fieldset>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Published' ); ?>:</td>
                            <td>
                                <?php echo JHTML::_( 'select.booleanlist', 'published', '', @$row->published ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'On Sale' ); ?>:</td>
                            <td>
                                <?php echo JHTML::_( 'select.booleanlist', 'onSale', '', @$row->onSaleArtsVisionCol ); ?>
                                <p class="dsc-tip">This only applies to ArtsVision events.</p>
                            </td>
                        </tr>
                        <?php if ($row->startPublishing) { ?>
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Start Publishing' ); ?>:</td>
                            <td>
                                <?php echo JHTML::calendar( @$row->startPublishing->format('Y-m-d'), "startPublishing", "startPublishing", '%Y-%m-%d', array('size'=>'20') ); ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </fieldset>
            </div>
            
            <div class="tab-pane fade" id="tab2">
                <fieldset>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Short Description' ); ?>:</td>
                            <td>
                                <?php echo $editor->display( 'shortDescription', @$row->shortDescription, '100%', '300', '100', '20' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Long Description' ); ?>:</td>
                            <td>
                                <?php echo $editor->display( 'fullDescription', @$row->fullDescription, '100%', '300', '100', '20' ); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>
            
            <div class="tab-pane fade" id="program-notes">
                <fieldset>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Program Notes' ); ?>:</td>
                            <td>
                                <?php echo $editor->display( 'programNotes', @$row->programNotes, '100%', '300', '100', '20' ); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>

            <div class="tab-pane" id="tab3">

                <fieldset>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Event Multimedia' ); ?>:</td>
                            <td><?php // ******************** MEDIAMANAGER ITEMS ******************** ?> <?php if (JFile::exists( JPATH_ADMINISTRATOR.DS."components".DS."com_mediamanager".DS."defines.php" )) { ?> <?php
                            if ( !class_exists('MediaManager') ) {
                                JLoader::register( "MediaManager", JPATH_ADMINISTRATOR.DS."components".DS."com_mediamanager".DS."defines.php" );
                            }
                            JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_mediamanager/models' );
                            $model = JModel::getInstance( 'ElementMedia', 'MediaManagerModel' );
                            echo $model->fetchElement( 'mediamanager_id', @$row->mediamanager_id );
                            echo $model->clearElement( 'mediamanager_id', '' );
                            ?> <?php } ?> <?php /*$editor = JFactory::getEditor( ); ?>
                        <?php echo $editor->display( 'event_multimedia', @$row->event_multimedia, '100%', '450', '100', '20' ); */ ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>
            
            <div class="tab-pane" id="tab4">

                <fieldset>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'DataSource ID' ); ?>:</td>
                            <td><?php echo @$row->datasource_id; ?></td>
                        </tr>
                        <tr>
                            <td class="dsc-key"><?php echo JText::_( 'Old Web ID' ); ?>:</td>
                            <td>
                                <input name="oldWebID" value="<?php echo @$row->oldWebID; ?>" type="text" size="10" maxlength="250" />
                            </td>
                        </tr>
                    </table>
                </fieldset>
                
            </div>
            
        </div>
    </div>

    <div>
        <input type="hidden" name="id" value="<?php echo @$row->getDataSourceID(); ?>" /> <input type="hidden" name="task" id="task" value="" />
    </div>

</form>