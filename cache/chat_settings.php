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


$TRINITY20['ajax_chat']['online'] = 1;
$TRINITY20['ajax_chat']['bot_id'] = 2;
$TRINITY20['ajax_chat']['bot_name'] = 'System';
$TRINITY20['ajax_chat']['bot_role'] = 100;
$TRINITY20['ajax_chat']['base_channel'] = 1;
$TRINITY20['ajax_chat']['channel'] = 'U-232';
$TRINITY20['ajax_chat']['channels'] = array('u', 'U-232', 'Announce', 'News', 'Staff', 'Sysop');
$TRINITY20['ajax_chat']['sysop_access'] = array(1,2,3,4,5);
$TRINITY20['ajax_chat']['staff_access'] = array(1,2,3,4);
$TRINITY20['ajax_chat']['user_access'] = array(1,2,3);
$TRINITY20['sysop_channel'] = 5;

define('AJAX_CHAT_CHATBOT', $TRINITY20['ajax_chat']['bot_role']);

// Available languages:
$TRINITY20['langAvailable'] = array(
	'ar','bg','ca','cy','cz','da','de','el','en','es','et','fa','fi','fr','gl','he','hr','hu','in','it','ja','ka','kr','mk','nl','nl-be','no','pl','pt-br','pt-pt','ro','ru','sk','sl','sr','sv','th','tr','uk','zh','zh-tw'
);
//$TRINITY20['langAvailable'] = ['en'];
// Default language:
$TRINITY20['langDefault'] = 'en';
// Language names (each languge code in available languages must have a display name assigned here):
$TRINITY20['langNames'] = array(
	'ar'=>'عربي', 'bg'=>'Български', 'ca'=>'Català', 'cy'=>'Cymraeg', 'cz'=>'Česky', 'da'=>'Dansk', 'de'=>'Deutsch', 'el'=>'Ελληνικα', 'en'=>'English',
	'es'=>'Español', 'et'=>'Eesti', 'fa'=>'فارسی', 'fi'=>'Suomi', 'fr'=>'Français', 'gl'=>'Galego', 'he'=>'עברית', 'hr' => 'Hrvatski', 'hu' => 'Magyar', 'in'=>'Bahasa Indonesia', 'it'=>'Italiano',
	'ja'=>'日本語','ka'=>'ქართული','kr'=>'한 글','mk'=>'Македонски', 'nl'=>'Nederlands', 'nl-be'=>'Nederlands (België)', 'no'=>'Norsk', 'pl'=> 'Polski', 'pt-br'=>'Português (Brasil)', 'pt-pt'=>'Português (Portugal)', 
	'ro'=>'România', 'ru'=>'Русский', 'sk'=> 'Slovenčina', 'sl'=>'Slovensko', 'sr'=>'Srpski', 'sv'=> 'Svenska', 'th'=>'&#x0e20;&#x0e32;&#x0e29;&#x0e32;&#x0e44;&#x0e17;&#x0e22;', 
	'tr'=>'Türkçe', 'uk'=>'Українська', 'zh'=>'中文 (简体)', 'zh-tw'=>'中文 (繁體)'
);

// Available styles:
$TRINITY20['styleAvailable'] = array('beige','black','grey','Oxygen','Lithium','Sulfur','Cobalt','Mercury','Uranium','Pine','Plum','prosilver','Core','MyBB','vBulletin','XenForo');
// Default style:
$TRINITY20['styleDefault'] = 'MyBB';

// The encoding used for the XHTML content:
$TRINITY20['contentEncoding'] = 'UTF-8';
// The encoding of the data source, like userNames and channelNames:
$TRINITY20['sourceEncoding'] = 'UTF-8';
// The content-type of the XHTML page (e.g. "text/html", will be set dependent on browser capabilities if set to null):
$TRINITY20['contentType'] = null;
// Site name:
$TRINITY20['siteName'] = $TRINITY20['site_name'];
// Session name used to identify the session cookie:
$TRINITY20['sessionName'] = 'chat';
// Prefix added to every session key:
$TRINITY20['sessionKeyPrefix'] = $TRINITY20['cookie_prefix'];
// The lifetime of the language, style and setting cookies in days:
$TRINITY20['sessionCookieLifeTime'] = 365;
// The path of the cookies, '/' allows to read the cookies from all directories:
$TRINITY20['sessionCookiePath'] = $TRINITY20['cookie_path'];
// The domain of the cookies, defaults to the hostname of the server if set to null:
$TRINITY20['sessionCookieDomain'] = $TRINITY20['cookie_domain'];
// If enabled, cookies must be sent over secure (SSL/TLS encrypted) connections:
$TRINITY20['sessionCookieSecure'] = null;

// Default channelName used together with the defaultChannelID if no channel with this ID exists:
$TRINITY20['defaultChannelName'] = $TRINITY20['ajax_chat']['channel'];
// ChannelID used when no channel is given:
$TRINITY20['defaultChannelID'] = $TRINITY20['ajax_chat']['base_channel'];
// Defines an array of channelIDs (e.g. array(0, 1)) to limit the number of available channels, will be ignored if set to null:
$TRINITY20['limitChannelList'] = null;

// UserID plus this value are private channels (this is also the max userID and max channelID):
$TRINITY20['privateChannelDiff'] = '1000000000';
// UserID plus this value are used for private messages:
$TRINITY20['privateMessageDiff'] = '2000000000';

// Enable/Disable private Channels:
$TRINITY20['allowPrivateChannels'] = true;
// Enable/Disable private Messages:
$TRINITY20['allowPrivateMessages'] = true;

// Private channels should be distinguished by either a prefix or a suffix or both (no whitespace):
$TRINITY20['privateChannelPrefix'] = '[';
// Private channels should be distinguished by either a prefix or a suffix or both (no whitespace):
$TRINITY20['privateChannelSuffix'] = ']';

// If enabled, users will be logged in automatically as guest users (if allowed), if not authenticated:
$TRINITY20['forceAutoLogin'] = true;

// Defines if login/logout and channel enter/leave are displayed:
$TRINITY20['showChannelMessages'] = false;

// If enabled, the chat will only be accessible for the admin:
$TRINITY20['chatClosed'] = false;
// Defines the timezone offset in seconds (-12*60*60 to 12*60*60) - if null, the server timezone is used:
$TRINITY20['timeZoneOffset'] = null;
// Defines the hour of the day the chat is opened (0 - closingHour):
$TRINITY20['openingHour'] = 0;
// Defines the hour of the day the chat is closed (openingHour - 24):
$TRINITY20['closingHour'] = 24;
// Defines the weekdays the chat is opened (0=Sunday to 6=Saturday):
$TRINITY20['openingWeekDays'] = array(0,1,2,3,4,5,6);

// Enable/Disable guest logins:
$TRINITY20['allowGuestLogins'] = false;
// Enable/Disable write access for guest users - if disabled, guest users may not write messages:
$TRINITY20['allowGuestWrite'] = false;
// Allow/Disallow guest users to choose their own userName:
$TRINITY20['allowGuestUserName'] = false;
// Guest users should be distinguished by either a prefix or a suffix or both (no whitespace):
$TRINITY20['guestUserPrefix'] = '(';
// Guest users should be distinguished by either a prefix or a suffix or both (no whitespace):
$TRINITY20['guestUserSuffix'] = ')';
// Guest userIDs may not be lower than this value (and not higher than privateChannelDiff):
$TRINITY20['minGuestUserID'] = 400000000;

// Allow/Disallow users to change their userName (Nickname):
$TRINITY20['allowNickChange'] = false;
// Changed userNames should be distinguished by either a prefix or a suffix or both (no whitespace):
$TRINITY20['changedNickPrefix'] = '(';
// Changed userNames should be distinguished by either a prefix or a suffix or both (no whitespace):
$TRINITY20['changedNickSuffix'] = ')';

// Allow/Disallow registered users to delete their own messages:
$TRINITY20['allowUserMessageDelete'] = true;

// The userID used for ChatBot messages:
$TRINITY20['chatBotID'] = $TRINITY20['ajax_chat']['bot_id'];
// The userName used for ChatBot messages
$TRINITY20['chatBotName'] = $TRINITY20['ajax_chat']['bot_name'];
// The userRole used for ChatBot messages:
$TRINITY20['chatBotRole'] = $TRINITY20['ajax_chat']['bot_role'];
// Minutes until a user is declared inactive (last status update) - the minimum is 2 minutes:
$TRINITY20['inactiveTimeout'] = 1;
// Interval in minutes to check for inactive users:
$TRINITY20['inactiveCheckInterval'] = 1;

// Defines if messages are shown which have been sent before the user entered the channel:
$TRINITY20['requestMessagesPriorChannelEnter'] = true;
// Defines an array of channelIDs (e.g. array(0, 1)) for which the previous setting is always true (will be ignored if set to null):
$TRINITY20['requestMessagesPriorChannelEnterList'] = null;
// Max time difference in hours for messages to display on each request:
$TRINITY20['requestMessagesTimeDiff'] = 720;
// Max number of messages to display on each request:
$TRINITY20['requestMessagesLimit'] = 300;

// Max users in chat (does not affect moderators or admins):
$TRINITY20['maxUsersLoggedIn'] = 1000;
// Max userName length:
$TRINITY20['userNameMaxLength'] = 64;
// Max messageText length:
$TRINITY20['messageTextMaxLength'] = 2000;
// Defines the max number of messages a user may send per minute:
$TRINITY20['maxMessageRate'] = 20;

// Defines the default time in minutes a user gets banned if kicked from a moderator without ban minutes parameter:
$TRINITY20['defaultBanTime'] = 120;

// Argument that is given to the handleLogout JavaScript method:
//$TRINITY20['logoutData'] = './?logout=true';
$TRINITY20['logoutData'] = '';

// If true, checks if the user IP is the same when logged in:
$TRINITY20['ipCheck'] = false;

// Defines the max time difference in hours for logs when no period or search condition is given:
$TRINITY20['logsRequestMessagesTimeDiff'] = 12;
// Defines how many logs are returned on each logs request:
$TRINITY20['logsRequestMessagesLimit'] = 10;

// Defines the earliest year used for the logs selection:
$TRINITY20['logsFirstYear'] = 2019;

// Defines if old messages are purged from the database:
$TRINITY20['logsPurgeLogs'] = true;
// Max time difference in days for old messages before they are purged from the database:
$TRINITY20['logsPurgeTimeDiff'] = 15;

// Defines if registered users (including moderators) have access to the logs (admins are always granted access):
$TRINITY20['logsUserAccess'] = false;
// Defines a list of channels (e.g. array(0, 1)) to limit the logs access for registered users, includes all channels the user has access to if set to null:
$TRINITY20['logsUserAccessChannelList'] = null;

// Defines if the socket server is enabled:
$TRINITY20['socketServerEnabled'] = false;
// Defines the hostname of the socket server used to connect from client side (the server hostname is used if set to null):
$TRINITY20['socketServerHost'] = null;
// Defines the IP of the socket server used to connect from server side to broadcast update messages:
$TRINITY20['socketServerIP'] = '127.0.0.1';
// Defines the port of the socket server:
$TRINITY20['socketServerPort'] = 1935;
// This ID can be used to distinguish between different chat installations using the same socket server:
$TRINITY20['socketServerChatID'] = 0;

// This is used to anonymize the external urls
//$TRINITY20['anonymous_link'] = $site_config['site']['anonymizer_url'];