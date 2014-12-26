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

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

/**
 * Conversation model.
 */
class ConversationModelMessage extends JModelItem {

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState() {
        $app = JFactory::getApplication('com_conversation');

        // Load state from the request userState on edit or from the passed variable on default
        if (JFactory::getApplication()->input->get('layout') == 'edit') {
            $id = JFactory::getApplication()->getUserState('com_conversation.edit.message.id');
        } else {
            $id = JFactory::getApplication()->input->get('id');
            JFactory::getApplication()->setUserState('com_conversation.edit.message.id', $id);
        }
        $this->setState('message.id', $id);

        // Load the parameters.
        $params = $app->getParams();
        $params_array = $params->toArray();
        if (isset($params_array['item_id'])) {
            $this->setState('message.id', $params_array['item_id']);
        }
        $this->setState('params', $params);
    }

    /**
     * Method to get an ojbect.
     *
     * @param	integer	The id of the object to get.
     *
     * @return	mixed	Object on success, false on failure.
     */
    public function &getData($id = null) {
        if ($this->_item === null) {
            $this->_item = false;

            if (empty($id)) {
                $id = $this->getState('message.id');
            }

            // Get a level row instance.
            $table = $this->getTable();

            // Attempt to load the row.
            if ($table->load($id)) {
                // Check published state.
                if ($published = $this->getState('filter.published')) {
                    if ($table->state != $published) {
                        return $this->_item;
                    }
                }

                // Convert the JTable to a clean JObject.
                $properties = $table->getProperties(1);
                $this->_item = JArrayHelper::toObject($properties, 'JObject');
            } elseif ($error = $table->getError()) {
                $this->setError($error);
            }
        }

        
		if ( isset($this->_item->created_by) ) {
			$this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
		}

        return $this->_item;
    }

    public function getTable($type = 'Message', $prefix = 'ConversationTable', $config = array()) {
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to check in an item.
     *
     * @param	integer		The id of the row to check out.
     * @return	boolean		True on success, false on failure.
     * @since	1.6
     */
    public function checkin($id = null) {
        // Get the id.
        $id = (!empty($id)) ? $id : (int) $this->getState('message.id');

        if ($id) {

            // Initialise the table
            $table = $this->getTable();

            // Attempt to check the row in.
            if (method_exists($table, 'checkin')) {
                if (!$table->checkin($id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Method to check out an item for editing.
     *
     * @param	integer		The id of the row to check out.
     * @return	boolean		True on success, false on failure.
     * @since	1.6
     */
    public function checkout($id = null) {
        // Get the user id.
        $id = (!empty($id)) ? $id : (int) $this->getState('message.id');

        if ($id) {

            // Initialise the table
            $table = $this->getTable();

            // Get the current user object.
            $user = JFactory::getUser();

            // Attempt to check the row out.
            if (method_exists($table, 'checkout')) {
                if (!$table->checkout($user->get('id'), $id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }

        return true;
    }

    public function getCategoryName($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
                ->select('title')
                ->from('#__categories')
                ->where('id = ' . $id);
        $db->setQuery($query);
        return $db->loadObject();
    }

    public function publish($id, $state) {
        $table = $this->getTable();
        $table->load($id);
        $table->state = $state;
        return $table->store();
    }

    public function delete($id) {
        $table = $this->getTable();
        return $table->delete($id);
    }
    public function sendmessage(){
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $send['message'] = JRequest::getVar('styled-textarea');
        $send['groups'] = JRequest::getVar('groups');
        $send['today'] = date("Y-m-d H:i:s");                   // 2001-03-10 17:16:18 (the MySQL DATETIME format)
        $send['time'] = time();                   // 2001-03-10 17:16:18 (the MySQL DATETIME format)
        if($user->id &&  $send['message']){
            $query_select="SELECT * FROM   #__conversation_message where team=".$send['groups']." ORDER BY create_time DESC ";
            $db->setQuery($query_select);
            $select=$db->loadObject();
            $send['last']=0;
            if($user->id == $select->created_by && ($send['time']-$select->checked_out)< 60){
                $send['last']=1;
            }
            if(!$send['last']){
                $query_up="UPDATE ipb1e_conversation_message SET father=1 WHERE 1";
                $db->setQuery($query_up);
                $db->query();

            }
            $query_group="INSERT INTO #__conversation_message(state,checked_out,created_by,message,team,create_time,sender,father)
          VALUES (1,'".$send['time']."',".$user->id.",'".$send['message']."',".$send['groups'].",'".$send['today']."',".$user->id.",".$send['last'].")";
            $db->setQuery($query_group);
            $db->query();
            $send['id']=$db->insertid();
            $send['userid']=$user->id;
            return $send;
        }else{
            return 0;
        }

    }
    public function like_up(){
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $like = trim(JRequest::getVar('like'));
      $query_select="SELECT * FROM   #__conversation_like where user_id=".$user->id;
        $db->setQuery($query_select);
        $db->query();
        $rows = $db->getNumRows();
        $select=$db->loadObject();
        $query_like="select * from #__conversation_like  WHERE liked like '%,".$like."' or liked like ',".$like.",%'";
        $db->setQuery($query_like);
        $db->query();
        $numberlike = $db->getNumRows();
        if(!$numberlike)
            $numberlike=1;
        if($rows){
            $arraylike=explode(',',$select->liked);
            array_push($arraylike,$like);
            $arraylike=implode(',',$arraylike);
            $query_up="UPDATE #__conversation_like SET liked='".$arraylike."' WHERE id=".$select->id;
            $db->setQuery($query_up);
            $db->query();
            $query_up_m="UPDATE #__conversation_message SET agree=".$numberlike." WHERE id=".$like;
            $db->setQuery($query_up_m);
            $db->query();
            return $numberlike;

        }else{
            $query_group="INSERT INTO #__conversation_like(user_id,liked) VALUES (".$user->id.",'".$like."')";
            $db->setQuery($query_group);
            $db->query();
            $query_up_m="UPDATE #__conversation_message SET agree=".$numberlike." WHERE id=".$like;
            $db->setQuery($query_up_m);
            $db->query();
            return $numberlike;

        }
        return false;


    }

    public function liked(){
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $like = trim(JRequest::getVar('like'));
        $query_select="SELECT * FROM   #__conversation_like where user_id=".$user->id;
        $db->setQuery($query_select);
        $db->query();
        $rows = $db->getNumRows();
        $select=$db->loadObject();
        if($rows){
            $arraylike=explode(',',$select->liked);
            $key=array_search($like,$arraylike);
            unset($arraylike[$key]);

            $arraylike=implode(',',$arraylike);
            $query_up="UPDATE #__conversation_like SET liked='".$arraylike."' WHERE id=".$select->id;
            $db->setQuery($query_up);
            $db->query();
            $query_like="select * from #__conversation_like  WHERE liked like '%,".$like."' or liked like ',".$like.",%'";
            $db->setQuery($query_like);
            $db->query();
            $numberlike = $db->getNumRows();
            $query_up_m="UPDATE #__conversation_message SET agree=".$numberlike." WHERE id=".$like;
            $db->setQuery($query_up_m);
            $db->query();
            return $numberlike;
        }else{
            return false;
        }

    }
    public function unlike_up(){
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $like = trim(JRequest::getVar('like'));
        $query_select="SELECT * FROM   #__conversation_unlike where user_id=".$user->id;
        $db->setQuery($query_select);
        $db->query();
        $rows = $db->getNumRows();
        $select=$db->loadObject();
        $query_like="select * from #__conversation_unlike  WHERE unliked like '%,".$like."' or unliked like ',".$like.",%'";
        $db->setQuery($query_like);
        $db->query();
        $numberlike = $db->getNumRows();
        if(!$numberlike)
            $numberlike=1;
        if($rows){
            $arraylike=explode(',',$select->unliked);
            array_push($arraylike,$like);
            $arraylike=implode(',',$arraylike);
            $query_up="UPDATE #__conversation_unlike SET unliked='".$arraylike."' WHERE id=".$select->id;
            $db->setQuery($query_up);
            $db->query();
            $query_up_m="UPDATE #__conversation_message SET opposition=".$numberlike." WHERE id=".$like;
            $db->setQuery($query_up_m);
            $db->query();
            return $numberlike;

        }else{
            $query_group="INSERT INTO #__conversation_unlike(user_id,unliked) VALUES (".$user->id.",'".$like."')";
            $db->setQuery($query_group);
            $db->query();
            $query_up_m="UPDATE #__conversation_message SET opposition=".$numberlike." WHERE id=".$like;
            $db->setQuery($query_up_m);
            $db->query();
            return $numberlike;

        }
        return false;


    }

    public function unliked(){
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $like = trim(JRequest::getVar('like'));
        $query_select="SELECT * FROM   #__conversation_unlike where user_id=".$user->id;
        $db->setQuery($query_select);
        $db->query();
        $rows = $db->getNumRows();
        $select=$db->loadObject();
        if($rows){
            $arraylike=explode(',',$select->unliked);
            $key=array_search($like,$arraylike);
            unset($arraylike[$key]);

            $arraylike=implode(',',$arraylike);
            $query_up="UPDATE #__conversation_unlike SET unliked='".$arraylike."' WHERE id=".$select->id;
            $db->setQuery($query_up);
            $db->query();
            $query_like="select * from #__conversation_unlike  WHERE unliked like '%,".$like."' or unliked like ',".$like.",%'";
            $db->setQuery($query_like);
            $db->query();
            $numberlike = $db->getNumRows();
            $query_up_m="UPDATE #__conversation_message SET opposition=".$numberlike." WHERE id=".$like;
            $db->setQuery($query_up_m);
            $db->query();
            return $numberlike;
        }else{
            return false;
        }

    }
    public function loaedmoremassege($groups,$rows_limit){
        $groups = JRequest::getVar('group');
        $lastid = JRequest::getVar('lastid');
        $numbermessage = JRequest::getVar('numbermessage');
         $first_limit=$numbermessage-10;
         if($first_limit<0){
            $first_limit=0;
         }
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $query_select="SELECT * FROM  #__conversation_message where team=".$groups." and id < ".$lastid." ORDER BY create_time ASC LIMIT ".$first_limit.",".$numbermessage;
        $db->setQuery($query_select);
        $select_more['query']=$db->loadAssocList();
        $select_more['exist']=$first_limit;
        return  $select_more;
    }
    public function loaedlastmassege(){
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $group = JRequest::getVar('group');
        $query_select="SELECT * FROM   #__conversation_lastmessage where user_id=".$user->id." and team=".$group;
        $db->setQuery($query_select);
        $db->query();
        $rows = $db->getNumRows();
        $select=$db->loadObject();
        $query_select="SELECT * FROM   #__conversation_message where team=".$group." ORDER BY id DESC ";
        $db->setQuery($query_select);
        $db->query();

        $select_id=$db->loadObject();
        if($rows){
            $query_up="UPDATE #__conversation_lastmessage SET messageid=".$select_id->id." where user_id=".$user->id." and team=".$group;

            $db->setQuery($query_up);
            $db->query();
            return true;
        }else{
            $query_ins="INSERT INTO #__conversation_lastmessage(user_id,team,messageid) VALUES (".$user->id.",".$group.",".$select_id->id.")";
            $db->setQuery($query_ins);
            $db->query();
            return true;
        }
        return false;
    }
    function searched(){
        $search = JRequest::getVar('search_tel');
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $group=implode(',',$user->groups);
        $query_group="SELECT * FROM #__users where id IN(select user_id from #__user_usergroup_map where group_id IN (".$group."))
         and id IN (SELECT user_id FROM #__user_profiles where profile_value like '%".$search."%' and  profile_key='profile.phone')";
        $db->setQuery($query_group);
        $db->query();
        $rows = $db->getNumRows();
        if($rows)
            $list=$db->loadObjectlist();
        else
            $list=false;
        return $list;
    }
}
