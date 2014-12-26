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

jimport('joomla.application.component.view');

/**
 * View class for a list of Membership.
 */
class MembershipView__membership_member4422s extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        MembershipHelper::addSubmenu('__membership_member4422s');

        $this->addToolbar();

        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/membership.php';

        $state = $this->get('State');
        $canDo = MembershipHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_MEMBERSHIP_TITLE___MEMBERSHIP_MEMBER4422S'), '__membership_member4422s.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/__membership_member4422';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('__membership_member4422.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('__membership_member4422.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('__membership_member4422s.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('__membership_member4422s.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', '__membership_member4422s.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('__membership_member4422s.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('__membership_member4422s.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', '__membership_member4422s.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('__membership_member4422s.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_membership');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_membership&view=__membership_member4422s');

        $this->extra_sidebar = '';
        
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);

			//Filter for the field start_member
			$this->extra_sidebar .= '<small><label for="filter_from_start_member">From شروع عضویت</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.start_member.from'), 'filter_from_start_member', 'filter_from_start_member', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_start_member">To شروع عضویت</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.start_member.to'), 'filter_to_start_member', 'filter_to_start_member', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
			$this->extra_sidebar .= '<hr class="hr-condensed">';

			//Filter for the field end_member
			$this->extra_sidebar .= '<small><label for="filter_from_end_member">From پایان عضویت</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.end_member.from'), 'filter_from_end_member', 'filter_from_end_member', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_end_member">To پایان عضویت</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.end_member.to'), 'filter_to_end_member', 'filter_to_end_member', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
			$this->extra_sidebar .= '<hr class="hr-condensed">';

    }

	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		'a.user_id' => JText::_('COM_MEMBERSHIP___MEMBERSHIP_MEMBER4422S_USER_ID'),
		'a.start_member' => JText::_('COM_MEMBERSHIP___MEMBERSHIP_MEMBER4422S_START_MEMBER'),
		'a.end_member' => JText::_('COM_MEMBERSHIP___MEMBERSHIP_MEMBER4422S_END_MEMBER'),
		'a.payment' => JText::_('COM_MEMBERSHIP___MEMBERSHIP_MEMBER4422S_PAYMENT'),
		'a.last_visit' => JText::_('COM_MEMBERSHIP___MEMBERSHIP_MEMBER4422S_LAST_VISIT'),
		'a.category_member' => JText::_('COM_MEMBERSHIP___MEMBERSHIP_MEMBER4422S_CATEGORY_MEMBER'),
		'a.state_member' => JText::_('COM_MEMBERSHIP___MEMBERSHIP_MEMBER4422S_STATE_MEMBER'),
		);
	}

}
