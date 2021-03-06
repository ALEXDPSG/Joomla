<?php
/*------------------------------------------------------------------------
# Calendar Feed View for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
jimport( 'joomla.application.component.controller' );

class RaidPlannerViewCalendar extends JView
{

    function display($tpl = null)
    {
		$user =& JFactory::getUser();
		if($user->id == 0) {
			// user not logged in
			$user =& JUser::getInstance( intval(@$_REQUEST['user']) );
			if ( ($user->getParam('calendar_secret', '') != '') && ($user->getParam('calendar_secret', '') == $_REQUEST['secret'] ) ) {
				// access validated
			} else {
				die('Invalid access!');
			}
		}
		$tz = RaidPlannerHelper::getTimezone();
    	$tzname = timezone_name_from_abbr("", $tz * 3600, 0);
    	
		$canView = (RaidPlannerHelper::getPermission('view_raids') == 1);
		$version = new JVersion();
		switch ($version->RELEASE) {
			case '1.5':
				$dateformat = '%Y%m%dT%H%M%S';
			break;
			default:
			case '1.6':
				$dateformat = 'Ymd\THis';
			break;
		}
		
		$model = &$this->getModel();
		
		$this->assignRef( 'canView', $canView );
		$this->assignRef( 'tzoffset', $tz );
		$this->assignRef( 'tzname', $tzname );
		$this->assignRef( 'dateformat', $dateformat );
        $this->assignRef( 'events', $model->getEvents('own', $user->id) );
		
		header("Content-Type: text/Calendar");
		header("Content-Disposition: inline; filename=raid_calendar.ics");

        parent::display($tpl);
        
        die();
    }
    
}