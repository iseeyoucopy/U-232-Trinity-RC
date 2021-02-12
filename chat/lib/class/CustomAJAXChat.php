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

class CustomAJAXChat extends AJAXChat {
	
	//function __construct(){
       //parent::__construct();
	   
    //}
	function revalidateUserID() {
		if($this->getUserRole() >= UC_USER && $this->getUserID() === $this->_CurUser['id']) {
			return true;
		}
		return false;
	}
	// Initialize custom request variables:
	function initCustomRequestVars() {
		//Auto-login users:
        if(!$this->getRequestVar('logout') && $this->getUserID() === $this->_CurUser['id']) {
			$this->setRequestVar('login', true);
		}
	}
    function getValidLoginUserData() {
		
		$userData = array();
		$userData['userID'] = $this->_CurUser['id'];
		$userData['userName'] = $this->trimUserName($this->_CurUser['username']);
		$user_class = $this->_CurUser['override_class'] != 255 ? $this->_CurUser['override_class'] : $this->_CurUser['class'];
		$userData['userRole'] = $user_class;
			
		return $userData;
	}
	
	function &getChannels() {
		global $TRINITY20;
		if($this->_channels === null) {
			$this->_channels = array();
			// Get the channels, the user has access to:
			if($this->getUserRole() == UC_SYSOP) {
				$validChannels = $this->_Trinity['ajax_chat']['sysop_access'];
			}elseif($this->getUserRole() >= UC_MODERATOR && $this->getUserRole() <= UC_ADMINISTRATOR) {
				$validChannels = $this->_Trinity['ajax_chat']['staff_access'];
			}else {
				$validChannels = $this->_Trinity['ajax_chat']['user_access'];
			}
			
			// Add the valid channels to the channel list (the defaultChannelID is always valid):
			foreach($this->getAllChannels() as $key=>$value) {
				if ($value == $this->getConfig('defaultChannelID')) {
					$this->_channels[$key] = $value;
					continue;
				}
				// Check if we have to limit the available channels:
				if($this->getConfig('limitChannelList') && !in_array($value, $this->getConfig('limitChannelList'))) {
					continue;
				}
				if(in_array($value, $validChannels)) {
					$this->_channels[$key] = $value;
				}
			}
		}
		return $this->_channels;
	}
	// Store all existing channels
	// Make sure channel names don't contain any whitespace
	function &getAllChannels() {
		if($this->_allChannels === null) {
			// Get all existing channels:
			$customChannels = $this->getCustomChannels();
			
			$defaultChannelFound = false;
			foreach($customChannels as $name=>$id) {
				$this->_allChannels[$this->trimChannelName($name)] = $id;
				if($id == $this->getConfig('defaultChannelID')) {
					$defaultChannelFound = true;
				}
			}
			if(!$defaultChannelFound) {
				// Add the default channel as first array element to the channel list
				// First remove it in case it appeard under a different ID
				unset($this->_allChannels[$this->getConfig('defaultChannelName')]);
				$this->_allChannels = array_merge(
					array(
						$this->trimChannelName($this->getConfig('defaultChannelName'))=>$this->getConfig('defaultChannelID')
					),
					$this->_allChannels
				);
			}
		}
		return $this->_allChannels;
	}
	function getCustomChannels() {
		// List containing the custom channels:
		$channels = null;
        $channels = $this->_Trinity['ajax_chat']['channels'];
		return array_flip($channels);
	}
	

   
}
