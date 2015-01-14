<?php
/**
 * @package     Brainymore.com
 * @subpackage  mod_bm_slide_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
<script type="text/javascript">
   jQuery(document).ready(function(){
        jQuery( "form" ).submit(function( event ) {
            event.preventDefault();
            var data=jQuery( this ).serialize();
            jQuery.ajax({
                url:'index.php?option=com_users&viwe=login',
                type:'POST',
                data:data,
               success:function(session){
                   var url = "index.php?option=com_conversation&view=messages&Itemid=207";
                   jQuery(location).attr('href',url);

               }
            });
       });
        jQuery('.inline').click(function(){
            jQuery('#div_login').hide();
            jQuery('#div_forget').show();
        })
        jQuery('.go_login').click(function(){
            jQuery('#div_login').show();
            jQuery('#div_forget').hide();
        })
    })
</script>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" target="RSS_target"  method="post" id="login-form"  class="form-1">
	<div id="div_login">
    <p class="field">
<!--        <img id="passwd" class="tooltip" title="کیبرد" src="modules/mod_bm_slide_login/assets/images/keyboard.png">
-->		<input class="keyboard" id="modlgn-username" type="text" name="username" placeholder="<?php echo JText::_('MOD_BM_SLIDE_LOGIN_VALUE_USERNAME') ?>" />

   <!-- <div class="code ui-corner-all">

        <script>
            $('.qwerty:eq(1)')
                    .keyboard({
                        openOn : null,
                        stayOpen : true,
                        layout : 'qwerty'
                    })
                    .addTyping();
            $('#passwd').click(function(){
                $('.qwerty:eq(1)').getkeyboard().reveal();
            });
        </script>
    </div>-->

			<i class="icon-user icon-large"></i>
	</p>
		<p class="field">
            <input class="keyboard" id="modlgn-passwd" type="password" name="password" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
   <!-- <div class="code ui-corner-all">

            <script>
                $('.qwerty:eq(1)')
                        .keyboard({
                            openOn : null,
                            stayOpen : true,
                            layout : 'qwerty'
                        })
                        .addTyping();
                $('#passwd').click(function(){
                    $('.qwerty:eq(1)').getkeyboard().reveal();
                });
            </script>
    </div>-->

            <i class="icon-lock icon-large"></i>
	</p>
	<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
	<div id="form-login-remember" class="control-group checkbox">
		<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
		<label for="modlgn-remember" class="control-label"><?php echo JText::_('MOD_BM_SLIDE_LOGIN_REMEMBER_ME') ?></label>
	</div>
	<?php endif; ?>
	<div id="form-login-submit" class="control-group">
		<div class="controls">
			<button type="submit" tabindex="0" name="Submit" class="btn-submit"><?php echo JText::_('JLOGIN') ?></button>
		</div>
	</div>
    
	<?php
		$usersConfig = JComponentHelper::getParams('com_users'); ?>
		<ul class="unstyled">
		<?php if ($usersConfig->get('allowUserRegistration')) : ?>
			<li>
                <span class="icon-user"></span><a href="<?php echo JRoute::_('index.php?option=com_users&view=registration&Itemid='.UsersHelperRoute::getRegistrationRoute()); ?>">
				<?php echo JText::_('MOD_BM_SLIDE_LOGIN_REGISTER'); ?></a>
			</li>
		<?php endif; ?>
			<!--<li>
                <span class="icon-arrow-left"></span><a href="<?php /*echo JRoute::_('index.php?option=com_users&view=remind&Itemid='.UsersHelperRoute::getRemindRoute()); */?>">
				<?php /*echo JText::_('MOD_BM_SLIDE_LOGIN_FORGOT_YOUR_USERNAME'); */?></a>
			</li>-->
			<li>
                <span class="icon-refresh"></span><a class='inline' href="#inline_content">
				<?php echo JText::_('MOD_BM_SLIDE_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
                			</li>
		</ul>
	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHtml::_('form.token'); ?>
    <iframe id="RSS_target" name="RSS_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
</div>
<div style='display:none' id="div_forget">
    <div id='inline_content' style='padding:10px; background:#fff;'>
        <p class="field">
            <input class="keyboard" id="modlgn-telephon" type="text" name="telephon" placeholder="<?php echo JText::_('JGLOBAL_TELEPHON') ?>" />

    </p>
    <div id="form-login-submit" class="control-group">
        <div class="controls">
            <button type="button" tabindex="0" name="send" class="btn-submit send_tel"><?php echo JText::_('SEND') ?></button>
        </div>
    </div>
    <p><?php echo JText::_('TEXT_FORGET') ?></p>
    <?php
$scrip="  jQuery(document).ready(function() {
            jQuery('.send_tel').click(function() {
                var tel=jQuery('#modlgn-telephon').val();
                if(tel){
                    jQuery.ajax({
                        url : 'index.php?option=com_smsing&view=message&telephon='+tel+'&text=forget',
                        type: 'POST',
                        success:function(data, textStatus, jqXHR)
                        {
                            console.log(data);
                        } 
                    })
                 }else{
                    alert('لطفا شماره تلفن خود را وارد کنید.');
                }  
            ;});
            })";
                                $doc =JFactory::getDocument();
                                $doc->addScriptDeclaration( $scrip );
    ?>
    <p><span class="icon-refresh go_login"></span></p>
    </div>
</div>
</form>

