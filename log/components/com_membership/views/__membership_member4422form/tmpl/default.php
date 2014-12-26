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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_membership', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/components/com_membership/assets/js/form.js');


?>
</style>
<script type="text/javascript">
    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', function() {
        jQuery(document).ready(function() {
            jQuery('#form-__membership_member4422').submit(function(event) {
                
            });

            
			jQuery('input:hidden.user_id').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('user_idhidden')){
					jQuery('#jform_user_id option[value="' + jQuery(this).val() + '"]').attr('selected', 'selected');
				}
			});
					jQuery("#jform_user_id").trigger("liszt:updated");
        });
    });

</script>

<div class="__membership_member4422-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-__membership_member4422" action="<?php echo JRoute::_('index.php?option=com_membership&task=__membership_member4422.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
        
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

	<?php if(empty($this->item->created_by)): ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
	<?php endif; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('user_id'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('user_id'); ?></div>
	</div>
	<?php foreach((array)$this->item->user_id as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="user_id" name="jform[user_idhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />';
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('start_member'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('start_member'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('end_member'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('end_member'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('payment'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('payment'); ?></div>
	</div>
	<input type="hidden" name="jform[last_visit]" value="<?php echo $this->item->last_visit; ?>" />

	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('category_member'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('category_member'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('state_member'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('state_member'); ?></div>
	</div>				<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','membership')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
				<?php if (!JFactory::getUser()->authorise('core.admin','membership')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-__membership_member4422").appendChild(input);
                    });
                </script>
             <?php endif; ?>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="validate btn btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_membership&task=__membership_member4422form.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
            </div>
        </div>
        
        <input type="hidden" name="option" value="com_membership" />
        <input type="hidden" name="task" value="__membership_member4422form.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
