<?php
/**
 * -------   U-232 Codename Trinity   ----------*
 * ---------------------------------------------*
 * --------  @authors U-232 Team  --------------*
 * ---------------------------------------------*
 * -----  @site https://u-232.duckdns.org/  ----*
 * ---------------------------------------------*
 * -----  @copyright 2020 U-232 Team  ----------*
 * ---------------------------------------------*
 * ------------  @version V6  ------------------*
 */

/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

class CustomAJAXChat extends AJAXChat
{

    function __construct()
    {
        parent::__construct();

    }

    function revalidateUserID()
    {
        global $CURUSER;
        return $this->getUserRole() >= UC_USER && $this->getUserID() === $CURUSER['id'];
    }

    // Initialize custom request variables:
    function initCustomRequestVars()
    {
        global $CURUSER;
        //Auto-login users:
        if (!$this->getRequestVar('logout') && $this->getUserID() === $CURUSER['id']) {
            $this->setRequestVar('login', true);
        }
    }

    function getValidLoginUserData()
    {
        global $CURUSER;
        if (isset($CURUSER)) {
            $userData = [];
            $userData['userID'] = $CURUSER['id'];
            $userData['userName'] = $this->trimUserName($CURUSER['username']);
            $user_class = $CURUSER['override_class'] != 255 ? $CURUSER['override_class'] : $CURUSER['class'];
            $userData['userRole'] = $user_class;

            return $userData;
        }
    }

    function &getChannels()
    {
        global $TRINITY20;
        if ($this->_channels === null) {
            $this->_channels = [];
            // Get the channels, the user has access to:
            if ($this->getUserRole() == UC_SYSOP) {
                $validChannels = $TRINITY20['ajax_chat']['sysop_access'];
            } elseif ($this->getUserRole() >= UC_MODERATOR && $this->getUserRole() <= UC_ADMINISTRATOR) {
                $validChannels = $TRINITY20['ajax_chat']['staff_access'];
            } else {
                $validChannels = $TRINITY20['ajax_chat']['user_access'];
            }

            // Add the valid channels to the channel list (the defaultChannelID is always valid):
            foreach ($this->getAllChannels() as $key => $value) {
                if ($value == $this->getConfig('defaultChannelID')) {
                    $this->_channels[$key] = $value;
                    continue;
                }
                // Check if we have to limit the available channels:
                if ($this->getConfig('limitChannelList') && !in_array($value, $this->getConfig('limitChannelList'))) {
                    continue;
                }
                if (in_array($value, $validChannels)) {
                    $this->_channels[$key] = $value;
                }
            }
        }
        return $this->_channels;
    }
    // Store all existing channels
    // Make sure channel names don't contain any whitespace
    function &getAllChannels()
    {
        if ($this->_allChannels === null) {
            // Get all existing channels:
            $customChannels = $this->getCustomChannels();

            $defaultChannelFound = false;
            foreach ($customChannels as $name => $id) {
                $this->_allChannels[$this->trimChannelName($name)] = $id;
                if ($id == $this->getConfig('defaultChannelID')) {
                    $defaultChannelFound = true;
                }
            }
            if (!$defaultChannelFound) {
                // Add the default channel as first array element to the channel list
                // First remove it in case it appeard under a different ID
                unset($this->_allChannels[$this->getConfig('defaultChannelName')]);
                $this->_allChannels = array_merge(
                    [
                        $this->trimChannelName($this->getConfig('defaultChannelName')) => $this->getConfig('defaultChannelID'),
                    ],
                    $this->_allChannels
                );
            }
        }
        return $this->_allChannels;
    }

    function getCustomChannels()
    {
        global $TRINITY20;
        // List containing the custom channels:
        $channels = null;
        $channels = $TRINITY20['ajax_chat']['channels'];
        return array_flip($channels);
    }


}
