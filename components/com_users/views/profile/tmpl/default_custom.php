<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('JHtmlUsers', JPATH_COMPONENT . '/helpers/html/users.php');
JHtml::register('users.spacer', array('JHtmlUsers', 'spacer'));


$fieldsets = $this->form->getFieldsets();
if (isset($fieldsets['core']))   unset($fieldsets['core']);
if (isset($fieldsets['params'])) unset($fieldsets['params']);

foreach ($fieldsets as $group => $fieldset): // Iterate through the form fieldsets
	$fields = $this->form->getFieldset($group);
	if (count($fields)):
?>
<style type="text/css">
.dl-horizontal{
	width: 100%;

}
</style>
<?php //if ($this->params->get('show_tags')) : ?>
		<?php  //$this->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
		<?php //echo $this->tagLayout->render($this->tags); ?>
	<?php // endif; ?>
<?php if($fields['jform_profile_phone']->value || $fields['jform_profile_city']->value):?>
<fieldset  class="users-profile-custom-<?php echo $group;?>">
	<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
	<legend><?php echo JText::_($fieldset->label); ?></legend>
	<?php endif;?>
	<table class="dl-horizontal">
	<?php foreach ($fields as $field):
		if (!$field->hidden) :
		if($field->value): ?>
		<tr>
			<td><?php echo $field->title; ?></td>
			<td>
			<?php if (JHtml::isRegistered('users.'.$field->id)):?>
				<?php echo JHtml::_('users.'.$field->id, $field->value);?>
			<?php elseif (JHtml::isRegistered('users.'.$field->fieldname)):?>
				<?php echo JHtml::_('users.'.$field->fieldname, $field->value);?>
			<?php elseif (JHtml::isRegistered('users.'.$field->type)):?>
				<?php echo JHtml::_('users.'.$field->type, $field->value);?>
			<?php else:?>
				<?php echo JHtml::_('users.value', $field->value);?>
			<?php endif;?>
		</td>
				</tr>
						<?php endif;?>

		<?php endif;?>
	<?php endforeach;?>
	</table>
</fieldset>
	<?php endif;?>

	<?php endif;?>
<?php endforeach;?>
