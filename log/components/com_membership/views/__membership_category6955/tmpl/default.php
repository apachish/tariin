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
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_CATEGORY6955_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_CATEGORY6955_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_CATEGORY6955_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_CATEGORY6955_NAME_CATEGORY'); ?></th>
			<td><?php echo $this->item->name_category; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_CATEGORY6955_TYPE_MEMBER'); ?></th>
			<td><?php echo $this->item->type_member; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_CATEGORY6955_PAYMENT'); ?></th>
			<td><?php echo $this->item->payment; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_MEMBERSHIP_FORM_LBL___MEMBERSHIP_CATEGORY6955_DESCRIPTION'); ?></th>
			<td><?php echo $this->item->description; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_membership&task=__membership_category6955.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_MEMBERSHIP_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_membership.__membership_category6955.'.$this->item->id)):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_membership&task=__membership_category6955.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_MEMBERSHIP_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_MEMBERSHIP_ITEM_NOT_LOADED');
endif;
?>
