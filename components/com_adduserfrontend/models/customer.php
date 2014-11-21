<?php
/**
 * Joomla! 3.0 component Add user Frontend
 *
 * @version $Id: adduserfrontend.php 2014-08-24 23:00:13 svn $
 * @author Kim Pittoors
 * @package Joomla
 * @subpackage Add user Frontend
 * @license GNU/GPL
 *
 * Add users to Community builder on the frontend
 *
* @Copyright Copyright (C) 2009 - 2014 - Kim Pittoors - www.joomlacy.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 *
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
/**
 * Add user Frontend Component Add user Frontend Model
 *
 * @author      notwebdesign
 * @package		Joomla
 * @subpackage	Add user Frontend
 * @since 3.0
 */
class AdduserfrontendModelCustomer extends JModelLegacy {
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
    }
    function getcustomer(){
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $group=implode(',',$user->groups);
        $query_group="SELECT * FROM #__users where id IN(select user_id from #__user_usergroup_map where group_id IN (".$group."))";
        $db->setQuery($query_group);
        $list=$db->loadObjectlist();
        return $list;
    }


}
?>
