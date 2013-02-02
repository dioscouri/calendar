<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php 
echo $this->sliders->startPanel( JText::_( "General Settings" ), 'general' );
?>

<table class="table table-striped table-bordered">
<tbody>
        <tr>
            <td style="dsc-key"><?php echo JText::_( 'Default Date' ); ?>
            </th>
            <td><input type="text" name="default_date"
                value="<?php echo $this->row->get( 'default_date', '' ); ?>"
                size="25" />
            </td>
            <td>Leave this value empty to use the current date as the
                default. Use the format YYYY-MM-DD</td>
        </tr>
        <tr>
            <td style="dsc-key"><?php echo JText::_( 'Default Item ID' ); ?>
            </th>
            <td><input type="text" name="item_id"
                value="<?php echo $this->row->get( 'item_id', '' ); ?>" />
            </td>
            <td></td>
        </tr>
        <tr>
            <td style="dsc-key"><?php echo JText::_( 'Disqus API Key' ); ?>
            </th>
            <td><input type="text" name="disqus_api_key"
                value="<?php echo $this->row->get( 'disqus_api_key', '' ); ?>"
                size="100" />
            </td>
            <td></td>
        </tr>
        <tr>
            <td style="dsc-key"><?php echo JText::_( 'Disqus Forum ID' ); ?>
            </th>
            <td><input type="text" name="disqus_forum_id"
                value="<?php echo $this->row->get( 'disqus_forum_id', '' ); ?>"
                size="50" />
            </td>
            <td></td>
        </tr>
        <tr>
            <td style="dsc-key"><?php echo JText::_( 'ENABLE ADD NEW' ); ?>?
            </th>
            <td><?php echo JHTML::_( 'select.booleanlist', 'enable_add_new', 'class="inputbox"', $this->row->get( 'enable_add_new', '0' ) );
            ?>
            </td>
            <td></td>
        </tr>
        <tr>
            <td style="dsc-key"><?php echo JText::_( 'SET DATE FORMAT' ); ?>
            </th>
            <td><input name="date_format"
                value="<?php echo $this->row->get( 'date_format', '%a, %d %b %Y, %I:%M%p' );
								 ?>"
                type="text" size="40" />
            </td>
            <td><?php echo JText::_( "CONFIG SET DATE FORMAT" ); ?>
            </td>
        </tr>
        <tr>
            <td style="dsc-key"><?php echo JText::_( 'Show Linkback' ); ?>
            </th>
            <td><?php echo JHTML::_( 'select.booleanlist', 'show_linkback', 'class="inputbox"', $this->row->get( 'show_linkback', '1' ) );
            ?>
            </td>
            <td></td>
        </tr>
        <tr>
            <td style="dsc-key"><?php echo JText::_( 'Display Facebook Like Button' ); ?>
            </th>
            <td><?php echo JHTML::_('select.booleanlist', 'display_facebook_like', 'class="inputbox"', $this->row->get('display_facebook_like', '1') ); ?>
            </td>
            <td></td>
        </tr>
        <tr>
            <td style="dsc-key"><?php echo JText::_( 'Display Twitter Button' ); ?>
            </th>
            <td><?php echo JHTML::_('select.booleanlist', 'display_tweet', 'class="inputbox"', $this->row->get('display_tweet', '1') ); ?>
            </td>
            <td></td>
        </tr>
        <tr>
            <td style="dsc-key"><?php echo JText::_( 'Default Twitter Message' ); ?>
            </th>
            <td><input type="text" name="display_tweet_message"
                value="<?php echo $this->row->get('display_tweet_message', 'Check this out!'); ?>"
                class="inputbox" size="35" />
            </td>
            <td></td>
        </tr>
        <tr>
            <td style="dsc-key">
            <?php echo JText::_( 'Enable Favorites' ); ?>?            
            </th>
            <td>
            <?php echo JHTML::_( 'select.booleanlist', 'enable_favorites', 'class="inputbox"', $this->row->get( 'enable_favorites', '0' ) ); ?>
            </td>
            <td></td>
        </tr>
    </tbody>
</table>
<?php
echo $this->sliders->endPanel();

?>