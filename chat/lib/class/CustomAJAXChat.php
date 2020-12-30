<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

class CustomAJAXChat extends AJAXChat {

	function getValidLoginUserData() {
        global $CURUSER;
	 
		// Check if we have a valid registered user:
		if(isset($CURUSER)) {
			$userData = array();
			$userData['userID'] = $CURUSER['id'];
			$userData['userName'] = $this->trimUserName($CURUSER['username']);
			//$user_class = $CURUSER['override_class'] != 255 ? (int) $CURUSER['override_class'] : (int) $CURUSER['class'];
			//$userData['userRole'] = $user_class;
			
			if($CURUSER['class'] == UC_SYSOP)
				$userData['userRole'] = AJAX_CHAT_SYSOP;
			elseif($CURUSER['class'] == UC_ADMINISTRATOR)
				$userData['userRole'] = AJAX_CHAT_ADMIN;
			elseif($CURUSER['class'] == UC_MODERATOR)
				$userData['userRole'] = AJAX_CHAT_MODERATOR;
			elseif($CURUSER['class'] == UC_UPLOADER)
				$userData['userRole'] = AJAX_CHAT_UPLOADER;
			elseif($CURUSER['class'] == UC_VIP)
				$userData['userRole'] = AJAX_CHAT_VIP;
			elseif($CURUSER['class'] == UC_POWER_USER)
				$userData['userRole'] = AJAX_CHAT_POWER_USER;
			else if($CURUSER['class'] == UC_USER)
				$userData['userRole'] = AJAX_CHAT_USER;
			
			return $userData;
		}
		
	}

	// Store the channels the current user has access to
	// Make sure channel names don't contain any whitespace
	function &getChannels() {
		global $TRINITY20, $CURUSER;
		if($this->_channels === null) {
			$this->_channels = array();
			
			// Get the channels, the user has access to:
			if($this->getUserRole() == AJAX_CHAT_SYSOP) {
				$validChannels = $TRINITY20['ajax_chat']['sysop_access'];
			}elseif($this->getUserRole() == AJAX_CHAT_ADMIN) {
				$validChannels = $TRINITY20['ajax_chat']['staff_access'];
			}elseif($this->getUserRole() == AJAX_CHAT_MODERATOR) {
				$validChannels = $TRINITY20['ajax_chat']['staff_access'];
			}else {
				$validChannels = $TRINITY20['ajax_chat']['user_access'];
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
		require(AJAXCHAT_DIR.'lib' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'channels.php');
		// Channel array structure should be:
		// ChannelName => ChannelID
		return array_flip($channels);
	}

   
 /*  
    // Add custom commands (Speak as U232_Bot)
    function parseCustomCommands($text, $textParts) {
	    if($this->getUserRole() >= AJAX_CHAT_MODERATOR) {
		    switch($textParts[0]) {
			    case '/takeover':
			        $this->insertChatBotMessage( $this->getChannel(), $text );
			        return true;
                //NSFW 
	            case '/nsfw':
                    $text = str_replace('/nsfw ','',$text); //remove the "/nsfw " part
                    if ($text == "/nsfw") {	
	                    $say = " image is not safe for work";
		            } else {
	$say = " image is not safe for work! [img]" . $text . "#nsfw[/img]";// This appends the #nsfw fragment to the image, allowing the css rules 
						// supplied in nsfw.css to catch it, and apply the svg blur filter.
		            }
                    $this->insertChatBotMessage( $this->getChannel(), $this->getUserName(). $say );
	                return true; 
                case '/afk':
			        // Set the userName:
			        $this->setUserName('<AFK>_'.$this->getUserName());
			        // Update the online user table:
			        $this->updateOnlineList();
			        // Add info message to update the client-side stored userName:
			        $this->addInfoMessage($this->getUserName(), 'userName');
			        // Store AFK status as session variable:
			        $this->setSessionVar('AwayFromKeyboard', true);
			        return true;
                // User Away Mod		
	            case '/away':
		            $this->insertChatBotMessage($this->getChannel(), $this->getLoginUserName().' has set their status to Away');
		            $this->setUserName($this->getLoginUserName().'[Away]');
		            $this->updateOnlineList();
		            $this->addInfoMessage($this->getUserName(), 'userName');
		            return true;
	            case '/online':
		            $this->insertChatBotMessage($this->getChannel(), $this->getLoginUserName().' has set their status to Online');
		            $this->setUserName($this->getLoginUserName());
		            $this->updateOnlineList();
		            $this->addInfoMessage($this->getUserName(), 'userName');
		            return true;	
		
                // Global Annoucment 	
		        case '/wall':
                    if($this->getUserRole()>=AJAX_CHAT_MODERATOR) {
                        $text = str_replace('/wall','',$text);
                        $users=$this->getCustomUsers(); // Had to change my Yii call, this may breaks… :/
                        switch($this->getUserRole()) { // BBCode colors for the roles
                            case AJAX_CHAT_SYSOP: $col="fuchsia"; break;
                            case AJAX_CHAT_ADMIN: $col="blue"; break;
                            case AJAX_CHAT_MODERATOR: $col="orange"; break;
                        }  
                        foreach($users as $id=>$user) {
                            $this->insertChatBotMessage(
                        $this->getPrivateMessageID($id),
                        '[color=red][i]'.$this->getLang("wall").'[/i][/color]|[color='.$col.'][b]'.$this->getUserName().'[/b][/color]: '.$text
                    );
                        }
                    } else {
                $this->insertChatBotMessage(
                    $this->getPrivateMessageID(),
                    '/error CommandNotAllowed '.$textParts[0]
                );
                    }
                return true;
		}
	}
} 


    function onNewMessage($text) {
	    // Reset AFK status on first inserted message:
	    if($this->getSessionVar('AwayFromKeyboard')) {
		    $this->setUserName($this->subString($this->getUserName(), 6));
		    $this->updateOnlineList();
		    $this->addInfoMessage($this->getUserName(), 'userName');
		    $this->setSessionVar('AwayFromKeyboard', false);
	    }
	    return true;
    } 
*/
}
