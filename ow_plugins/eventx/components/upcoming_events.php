<?php

/**
 * This software is intended for use with Oxwall Free Community Software http://www.oxwall.org/ and is a proprietary licensed product. 
 * For more information see License.txt in the plugin folder.

 * ---
 * Copyright (c) 2012, Purusothaman Ramanujam
 * All rights reserved.

 * Redistribution and use in source and binary forms, with or without modification, are not permitted provided.

 * This plugin should be bought from the developer by paying money to PayPal account (purushoth.r@gmail.com).

 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
class EVENTX_CMP_UpcomingEvents extends BASE_CLASS_Widget {

    public function __construct(BASE_CLASS_WidgetParameter $paramsObj) {
        parent::__construct();

        $params = $paramsObj->customParamList;

        $eventService = EVENTX_BOL_EventService::getInstance();
        $events = $eventService->findPublicEvents(null, $params['events_count']);
        $count = $eventService->findPublicEventsCount();

        if ((!OW::getUser()->isAuthenticated() || !OW::getUser()->isAuthorized('eventx', 'add_event') ) && $count == 0) {
            $this->setVisible(false);
            return;
        }

        $this->assign('events', $eventService->getListingDataWithToolbar($events));
        $this->assign('no_content_message', OW::getLanguage()->text('eventx', 'no_index_events_label', array('url' => OW::getRouter()->urlForRoute('eventx.add'))));

        if ($eventService->findPublicEventsCount() > $params['events_count']) {
            $toolbarArray = array(array('href' => OW::getRouter()->urlForRoute('eventx.view_event_list', array('list' => 'latest')), 'label' => OW::getLanguage()->text('eventx', 'view_all_label')));
            $this->assign('toolbar', $toolbarArray);
        }
    }

    public static function getSettingList() {
        $eventConfigs = EVENTX_BOL_EventService::getInstance()->getConfigs();
        $settingList = array();
        $settingList['events_count'] = array(
            'presentation' => self::PRESENTATION_SELECT,
            'label' => OW::getLanguage()->text('eventx', 'cmp_widget_events_count'),
            'optionList' => $eventConfigs[EVENTX_BOL_EventService::CONF_WIDGET_EVENTS_COUNT_OPTION_LIST],
            'value' => $eventConfigs[EVENTX_BOL_EventService::CONF_WIDGET_EVENTS_COUNT]
        );

        return $settingList;
    }

    public static function getStandardSettingValueList() {
        return array(
            self::SETTING_SHOW_TITLE => true,
            self::SETTING_TITLE => OW::getLanguage()->text('eventx', 'up_events_widget_block_cap_label'),
            self::SETTING_WRAP_IN_BOX => false,
            self::SETTING_ICON => self::ICON_CALENDAR
        );
    }

    public static function getAccess() {
        return self::ACCESS_ALL;
    }

}
