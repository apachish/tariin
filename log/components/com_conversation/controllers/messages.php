<?php
/**
 * @version     1.0.0
 * @package     com_conversation
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      shahriar <apachish@gmail.com> - http://www.bmsystem.ir
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Messages list controller class.
 */
class ConversationControllerMessages extends ConversationController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Messages', $prefix = 'ConversationModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}