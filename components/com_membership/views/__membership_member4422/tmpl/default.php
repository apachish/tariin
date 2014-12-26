<?php
/**
 * @version     1.0.0
 * @package     com_membership
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      shahriar <apachish@gmail.com> - http://bmsystem.ir
 */
// no direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_membership.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_membership' . $this->item->id)) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
            <tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_MEMBER4422_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_MEMBER4422_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_MEMBER4422_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_MEMBER4422_USER_ID'); ?></th>
			<td><?php echo $this->item->user_id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_MEMBER4422_START_MEMBER'); ?></th>
			<td><?php echo $this->item->start_member; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_MEMBER4422_END_MEMBER'); ?></th>
			<td><?php echo $this->item->end_member; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_MEMBER4422_PAYMENT'); ?></th>
			<td><?php echo $this->item->payment; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_MEMBER4422_LAST_VISIT'); ?></th>
			<td><?php echo $this->item->last_visit; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_MEMBER4422_CATEGORY_MEMBER'); ?></th>
			<td><?php echo $this->item->category_member; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_MEMBER4422_STATE_MEMBER'); ?></th>
			<td><?php echo $this->item->state_member; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_membership&task=__membership_member4422.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_MEMBERSHIP_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_membership.__membership_member4422.'.$this->item->id)):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_membership&task=__membership_member4422.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_MEMBERSHIP_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_MEMBERSHIP_ITEM_NOT_LOADED');
endif;
?>
