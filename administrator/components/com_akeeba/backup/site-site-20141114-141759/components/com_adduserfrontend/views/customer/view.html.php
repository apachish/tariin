<?php
/**
 * Joomla! 3.0 component Add user Frontend
 *
 * @version $Id: view.html.php 2014-08-24 23:00:13 svn $
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
jimport( 'joomla.application.component.view');
/**
 * HTML View class for the Add user Frontend component
 */
class AdduserfrontendViewCustomer extends JViewLegacy {
	function display($tpl = null) {
        $this->customer = $this->get('customer');
        parent::display($tpl);
    }
    function gettelephon($id){
        $db = JFactory::getDBO();
        $query_group="SELECT * FROM #__user_profiles where user_id=".$id." and  profile_key='profile.phone' ";
        $db->setQuery($query_group);
        $db->query();
        $rows = $db->getNumRows();
        if($rows)
        $list=$db->loadObject();
        else
            $list=false;
        return $list->profile_value;
    }

}
?>