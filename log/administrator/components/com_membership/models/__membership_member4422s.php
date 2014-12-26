<?php

/**
 * @version     1.0.0
 * @package     com_membership
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      shahriar <apachish@gmail.com> - http://bmsystem.ir
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Membership records.
 */
class MembershipModel__membership_member4422s extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'user_id', 'a.user_id',
                'start_member', 'a.start_member',
                'end_member', 'a.end_member',
                'payment', 'a.payment',
                'last_visit', 'a.last_visit',
                'category_member', 'a.category_member',
                'state_member', 'a.state_member',

            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        
		//Filtering start_member
		$this->setState('filter.start_member.from', $app->getUserStateFromRequest($this->context.'.filter.start_member.from', 'filter_from_start_member', '', 'string'));
		$this->setState('filter.start_member.to', $app->getUserStateFromRequest($this->context.'.filter.start_member.to', 'filter_to_start_member', '', 'string'));

		//Filtering end_member
		$this->setState('filter.end_member.from', $app->getUserStateFromRequest($this->context.'.filter.end_member.from', 'filter_from_end_member', '', 'string'));
		$this->setState('filter.end_member.to', $app->getUserStateFromRequest($this->context.'.filter.end_member.to', 'filter_to_end_member', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_membership');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.user_id', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'DISTINCT a.*'
                )
        );
        $query->from('`#__membership_member` AS a');

        
		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

        

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.user_id LIKE '.$search.'  OR  a.start_member LIKE '.$search.'  OR  a.end_member LIKE '.$search.' )');
            }
        }

        

		//Filtering start_member
		$filter_start_member_from = $this->state->get("filter.start_member.from");
		if ($filter_start_member_from) {
			$query->where("a.start_member >= '".$db->escape($filter_start_member_from)."'");
		}
		$filter_start_member_to = $this->state->get("filter.start_member.to");
		if ($filter_start_member_to) {
			$query->where("a.start_member <= '".$db->escape($filter_start_member_to)."'");
		}

		//Filtering end_member
		$filter_end_member_from = $this->state->get("filter.end_member.from");
		if ($filter_end_member_from) {
			$query->where("a.end_member >= '".$db->escape($filter_end_member_from)."'");
		}
		$filter_end_member_to = $this->state->get("filter.end_member.to");
		if ($filter_end_member_to) {
			$query->where("a.end_member <= '".$db->escape($filter_end_member_to)."'");
		}


        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems() {
        $items = parent::getItems();
        
		foreach ($items as $oneItem) {

			if (isset($oneItem->user_id)) {
				$values = explode(',', $oneItem->user_id);

				$textValue = array();
				foreach ($values as $value){
					if(!empty($value)){
						$db = JFactory::getDbo();
						$query = "SELECT * FROM `#__users` WHERE 1 HAVING id LIKE '" . $value . "'";
						$db->setQuery($query);
						$results = $db->loadObject();
						if ($results) {
							$textValue[] = $results->username;
						}
					}
				}

			$oneItem->user_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->user_id;

			}
		}
        return $items;
    }

}
