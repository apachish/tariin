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

jimport('joomla.application.component.view');

/**
 * View class for a list of Conversation.
 */
class ConversationViewMessages extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $params;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $app = JFactory::getApplication();

        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->params = $app->getParams('com_conversation');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
;
            throw new Exception(implode("\n", $errors));
        }

        $this->_prepareDocument();
        parent::display($tpl);
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument() {
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_CONVERSATION_DEFAULT_PAGE_TITLE'));
        }
        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }
    public function BaseFromMiladiDate($mldyear , $mldmonth , $mldday )
    {
        $PrevMonthDayMld = array(0,31,59,90,120,151,181,212,243,273,304,334);
        $iDaySum = 0 ;
        $iNewDateElapsed =0 ;
        $iBaseYear = 1996 ;
        $iBaseDateElapsed = 79 ;
        $iNewDateElapsed = ($mldday -1 ) + $PrevMonthDayMld[$mldmonth-1];
        if ( (($mldyear % 4 ) == 0 ) && ( $mldmonth > 2))
            $iNewDateElapsed++;

        $iDaySum = $iNewDateElapsed - $iBaseDateElapsed +($mldyear-$iBaseYear) *365 +(int)(($mldyear -$iBaseYear ) / 4 );

        if ((($mldyear - $iBaseYear) % 4 ) != 0 )
            $iDaySum = $iDaySum+1;
        return $iDaySum;
    }
//---------------------------------------------------------------------
    public function hijricalender ( $year , $month , $day )
    {
        $PrevMonthDayHjr = array(0,31,62,93,124,155,186,216,246,276,306,336);
        if ( $year < 1995 || $month < 1 || $month > 12 || $day > 31 || $day < 1 )
            return 0;

        $daysum = $this->BaseFromMiladiDate($year , $month , $day );
        $iaddyear=0;
        while ($daysum >0 )
        {
            $daysum = $daysum -365;
            if (($iaddyear % 4 ) == 0 )
                $daysum--;
            $iaddyear++;
        }
        if ( $daysum <0 )
        {
            $iaddyear--;
            $daysum = $daysum+365;
            if (($iaddyear % 4 ) == 0 )
                $daysum++;
        }
        $itodayyear = 1375+$iaddyear;
        $itodaymonth=1;
        while ( $daysum >= $PrevMonthDayHjr[$itodaymonth])
        {
            $itodaymonth++;
            if( $itodaymonth ==12 )
                break;
        }
        $daysum=$daysum - $PrevMonthDayHjr[$itodaymonth-1];
        $itodayday = 1 + $daysum;
        $isodate = sprintf("%04d/% 02d/% 02d",$itodayyear ,$itodaymonth, $itodayday);
        return  $isodate;
    }

    public function get_like($id){
        $db = JFactory::getDBO();
        $query_select="SELECT * FROM   #__conversation_like where user_id=".$id;
        $db->setQuery($query_select);
        $select=$db->loadObject();
        $return=explode(',',$select->liked);
        return  $return;
    }
    public function get_unlike($id){
        $db = JFactory::getDBO();
        $query_select="SELECT * FROM   #__conversation_unlike where user_id=".$id;
        $db->setQuery($query_select);
        $select=$db->loadObject();
        $return=explode(',',$select->unliked);
        return  $return;
    }
    public function getmessage($groups,$limit=10){
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $limited=17+$limit;
        $query_select="SELECT * FROM   #__conversation_lastmessage where user_id=".$user->id." and team=".$groups;
        $db->setQuery($query_select);
        $db->query();
         $rows = $db->getNumRows();
        if($rows>1)
        $query_select="SELECT * FROM   #__conversation_message where team=".$groups." and id > (select messageid from #__conversation_lastmessage where user_id=".$user->id." and team=".$groups.")ORDER BY create_time ASC ";
        else{
            $select_row="SELECT id FROM   #__conversation_message where team=".$groups;
            $db->setQuery($select_row);
            $db->query();
            $rows_limit = $db->getNumRows();
            if($rows_limit>10)
            $limited=$rows_limit-10;
            else
                $limited=0;
        $query_select="SELECT * FROM   #__conversation_message where team=".$groups."  ORDER BY create_time ASC LIMIT ".$limited.",".$rows_limit;
        }
        $db->setQuery($query_select);
        $select=$db->loadObjectList();
        return  $select;
    }
    public function getgroup_user($group){
        $db = JFactory::getDBO();
        $query_group="SELECT * FROM #__usergroups where id=".$group;
        $db->setQuery($query_group);
        $namegroup=$db->loadObject();
        return $namegroup;
    }
    public function getnumbermessage($group){
        $user = JFactory::getUser();
        $db = JFactory::getDBO();
         $query_group="SELECT * FROM #__conversation_message where team=".$group." ORDER BY id DESC";
        $db->setQuery($query_group);
        $idgroup=$db->loadObject();
      $query_select="SELECT * FROM   #__conversation_lastmessage where user_id=".$user->id." and team=".$group;
        $db->setQuery($query_select);
        $db->query();
        $lastid=$db->loadObject();
        $query_select="SELECT * FROM   #__conversation_message ";
            $query_select.=" where";
        if($lastid->messageid)
            $query_select.=" id > ".$lastid->messageid;
        if($lastid->messageid && $idgroup->id)
            $query_select.= " and ";
        if( $idgroup->id)
            $query_select.=" id <= ".$idgroup->id;
        if($lastid->messageid || $idgroup->id)
            $query_select.= " and ";
              $query_select.="  team=".$group." and sender !=".$user->id;
        $db->setQuery($query_select);
        $db->query();
        $rows = $db->getNumRows();
        return $rows;

    }
    public function list_top_group(){
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
        $menuItem = $app->getMenu()->getActive();
        $group_kar = $menuItem->params->get('groupe');

        $query_group="SELECT * FROM #__usergroups where parent_id = 2";
        $db->setQuery($query_group);
        $group=$db->loadObjectlist();

            $query_group="SELECT id FROM #__usergroups ";
            if($group){
                $query_group.=" where ";
                $size=sizeof($group);
                $i=1;
                if($group_kar)
                $query_group.="parent_id =".$group_kar ;
                else
                foreach($group as $gr){
                    if($i<$size)
                    $query_group.= "parent_id =".$gr->id." OR ";
                    else
                        $query_group.= "parent_id =".$gr->id."  ";
                    $i++;
                }
            }
            $db->setQuery($query_group);
            $group_list=$db->loadAssocList();
            $i=0;
            foreach($group_list as $list){
               $gr_list[$i++]=$list['id'];
            }
            return $gr_list;
    }
}
