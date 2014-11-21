<?php

/**
 * @version     1.0.0
 * @package     com_conversation
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      shahriar <apachish@gmail.com> - http://www.bmsystem.ir
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class ConversationController extends JControllerLegacy {

    /**
     * Method to display a view.
     *
     * @param	boolean			$cachable	If true, the view output will be cached
     * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return	JController		This object to support chaining.
     * @since	1.5
     */
    public function display($cachable = false, $urlparams = false) {
        require_once JPATH_COMPONENT . '/helpers/conversation.php';

        $view = JFactory::getApplication()->input->getCmd('view', 'messages');
        JFactory::getApplication()->input->set('view', $view);

        parent::display($cachable, $urlparams);

        return $this;
    }
    public function upmessage(){
        $message = JRequest::getVar('styled-textarea');
        $model = &$this->getModel('message');
        $result = $model->sendmessage();
        if($message) {
            if(!$result) {
                echo 'can not send message';
                exit();
            }else{
                echo json_encode($result);
                exit();
            }
        }
    }
    public function liked(){
      $like = JRequest::getVar('like');
        $model = &$this->getModel('message');
        $result = $model->like_up();
        if($like) {
            if(!$result) {
                echo $result;
                exit();

            }else{
                echo $result;
                exit();


            }
        }
    }
    public function likedd(){
       $like = JRequest::getVar('like');
        $model = &$this->getModel('message');
        $result = $model->liked();
        if($like) {
            if(!$result) {
                echo $result;
                exit();
            }else{
                echo $result;
                exit();
            }
        }
    }
    public function unliked(){
        $like = JRequest::getVar('like');
        $model = &$this->getModel('message');
        $result = $model->unlike_up();
        if($like) {
            if(!$result) {
                echo $result;
                exit();

            }else{
                echo $result;
                exit();


            }
        }
    }
    public function unlikedd(){
        $like = JRequest::getVar('like');
        $model = &$this->getModel('message');
        $result = $model->unliked();
        if($like) {
            if(!$result) {
                echo $result;
                exit();
            }else{
                echo $result;
                exit();
            }
        }
    }
    public function loadmessage(){
        $group = JRequest::getVar('group');
        $model = &$this->getModel('message');
        $result = $model->loaedlastmassege();
        if($group) {
            if(!$result) {
                echo $result;
                exit();
            }else{
                echo $result;
                exit();
            }
        }
    }
    public function searched(){
        $search = JRequest::getVar('search_tel');
        $model = &$this->getModel('message');
        $result = $model->searched();
        if($search) {
            if(!$result) {
                echo '<h1 style="color: #353535" >';
                echo 'یافت نشد';
                echo '</h1>';
                echo '<p>'.JText::_('MESSAGE_ADD').'</p>';
                echo '<a href="index.php?option=com_adduserfrontend&view=adduserfrontend&telephon='.$search.'">'.JText::_('BHUTTON_ADD').'</a>';
                exit();

            }else{
                $j=1;
echo '<table class="rwd-table">';
    echo '<tr>';
                echo '<th>'.JText::_('LIST_ROW').'</th>';
                echo '<th>'.JText::_('LIST_NAME').'</th>';
echo '<th>'.JText::_('LIST_TELEPHON').'</th>';
echo '<th>'.JText::_('LIST_EDIT').'</th></tr>';
               foreach($result as $res){
                            echo '<tr>';
        echo '<td data-th="'.JText::_('LIST_ROW').'"><span>'.$j.'</span></td>';
        echo '<td data-th="'.JText::_('LIST_NAME').'"><span>'.$res->name.'</span></td>';
        echo '<td data-th="'.JText::_('LIST_TELEPHON').'"><span>'.str_replace('"','',$this->gettelephon($res->id)).'</span></td>';
        echo '<td data-th="'.JText::_('LIST_EDIT').'"><span><input type="radio" name="edit"></span></td>';
        echo '</tr>';
                   $j++;
               }
                echo '</table>';
                exit();


            }
        }
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
