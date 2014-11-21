<?php

/**
 * @version     1.0.0
 * @package     com_sms
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      shahriar <apachish@gmail.com> - http://www.bmsystem.ir
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

/**
 * Sms model.
 */
class SmsModelMessage extends JModelItem {

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState() {
        $app = JFactory::getApplication('com_sms');

        // Load state from the request userState on edit or from the passed variable on default
        if (JFactory::getApplication()->input->get('layout') == 'edit') {
            $id = JFactory::getApplication()->getUserState('com_sms.edit.message.id');
        } else {
            $id = JFactory::getApplication()->input->get('id');
            JFactory::getApplication()->setUserState('com_sms.edit.message.id', $id);
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

    public function getTable($type = 'Message', $prefix = 'SmsTable', $config = array()) {
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
    public function send_sms($telephon,$message){
        ini_set("soap.wsdl_cache_enabled", "0");

        $client = new SoapClient("http://login.parsgreen.com/Api/SendSMS.asmx?WSDL");
        // $parameters['username'] = "PG1520WEB"; // نام کاربری
        // $parameters['signature'] = "79306AF4-4B30-4D96-AFFA-9F9439C5F1D0"; // رمز ورود
        $parameters['signature'] = "E86E76C1-3148-4A7C-BF2F-3DECCEF30EE1";//15
        //$parameters['from'] = "10000010000052"; // شماره پیامک
        $parameters['from']= "10007010001000"; // شماره پیامک
        $parameters['to'] =$telephon;
        $parameters['text'] =$message; // متن پیامک
        $parameters['isFlash']= false; //  پیامک عادی یافلش
        $parameters['udh'] = "";
        $parameters['success'] = 0x0; // تعدا  ارسال موفق
        $parameters['retStr'] =  array(0); // وضعیت ارسال
        //$client->GetCredit(array("username"=>"PG1032WEB","password"=>"97742"))->GetCreditResult;

        $result=$client->SendGroupSMS($parameters)->SendGroupSMSResul;

        $delivery=$client->GetDelivery(array("signature"=>"E86E76C1-3148-4A7C-BF2F-3DECCEF30EE1","recId"=>$parameters['retStr'] ))->GetDeliveryResult;
        foreach($telephon as &$value){
            $query ="INSERT INTO message (massagee,codedelivery,statuse,telephone,resulte,time,date,type)VALUES ('".$massage."','".$delivery."','".$parameters['status']."','".$value."','".$result."','".$time."','".$data."','".$type."')";
            $result= mysql_query($query,$db) or die("اشکال سیستمی");

        }

    }

}
