<?php

/**
 * @version     1.0.0
 * @package     com_membership
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      shahriar <apachish@gmail.com> - http://bmsystem.ir
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Membership helper.
 */
class MembershipHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
        		JHtmlSidebar::addEntry(
			JText::_('COM_MEMBERSHIP_TITLE___MEMBERSHIP_CATEGORY6955S'),
			'index.php?option=com_membership&view=__membership_category6955s',
			$vName == '__membership_category6955s'
		);
                            JHtmlSidebar::addEntry(
            JText::_('COM_MEMBERSHIP_TITLE___MEMBERSHIP_MEMBER4422S'),
            'index.php?option=com_membership&view=__membership_member4422s',
            $vName == '__membership_member4422s'
        );

    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_membership';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


}
