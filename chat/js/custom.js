/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Overriding client side functionality:

/*
// Example - Overriding the replaceCustomCommands method:
ajaxChat.replaceCustomCommands = function(text, textParts) {
	return text;
}
 */

//For Speak As U232_Bot//
/*ajaxChat.replaceCustomCommands = function(text, textParts) {
 switch(textParts[0]) {
   case '/takeover':
   text=text.replace('/takeover', ' ');
   return '<span class="chatBotMessage">' + text + '</span>';
   default:
   return text;
 }
}*/
//Welcome Message
//ajaxChat.customInitialize = function() {
//	ajaxChat.addChatBotMessageToChatList('Welcome to our chat. Please follow the rules.');
//}

// Announce Channel Information
// Return true to override built-in info message processing, return false to use your override and skip the built in handler
/*ajaxChat.handleCustomInfoMessage = function(infoType, infoData) {
	switch(infoType) {
		case 'channelID':
			this.channelID = infoData;
			//if (this.channelID == 0)
				//this.addChatBotMessageToChatList('Welcome to channel 0. Please follow the rules.');
			if (this.channelID == 1)
				this.addChatBotMessageToChatList('Welcome to channel 1. Nobody follows the rules here!');
			return true;
		default:
			return false;
	}
}*/