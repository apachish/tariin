<?php
/**
 * @version     1.0.0
 * @package     com_conversation
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      shahriar <apachish@gmail.com> - http://www.bmsystem.ir
 */
// no direct access
defined('_JEXEC') or die;

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_conversation', JPATH_ADMINISTRATOR);

?>
<?php if ($this->item && $this->item->state == 1) : ?>

    <div class="item_fields">
        <table class="table">
            <tr>
			<th><?php echo JText::_('COM_CONVERSATION_FORM_LBL_MESSAGE_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_CONVERSATION_FORM_LBL_MESSAGE_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_CONVERSATION_FORM_LBL_MESSAGE_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_CONVERSATION_FORM_LBL_MESSAGE_MESSAGE'); ?></th>
			<td><?php echo $this->item->message; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_CONVERSATION_FORM_LBL_MESSAGE_FATHER'); ?></th>
			<td><?php echo $this->item->father; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_CONVERSATION_FORM_LBL_MESSAGE_TEAM'); ?></th>
			<td><?php echo $this->item->team; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_CONVERSATION_FORM_LBL_MESSAGE_OPPOSITION'); ?></th>
			<td><?php echo $this->item->opposition; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_CONVERSATION_FORM_LBL_MESSAGE_AGREE'); ?></th>
			<td><?php echo $this->item->agree; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_CONVERSATION_FORM_LBL_MESSAGE_CREATE_TIME'); ?></th>
			<td><?php echo $this->item->create_time; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_CONVERSATION_FORM_LBL_MESSAGE_SENDER'); ?></th>
			<td><?php echo $this->item->sender; ?></td>
</tr>

        </table>
    </div>
    
    <?php
else:
    echo JText::_('COM_CONVERSATION_ITEM_NOT_LOADED');
endif;
?>
