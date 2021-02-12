var UC_USER = 0;
var UC_MIN = 0;
var UC_POWER_USER = 1;
var UC_VIP = 2;
var UC_UPLOADER = 3;
var UC_MODERATOR = 4;
var UC_STAFF = 4;
var UC_ADMINISTRATOR = 5;
var UC_SYSOP = 6;
var UC_MAX = 6;
var AJAX_CHAT_CHATBOT = 100;

ajaxChat.getRoleClass = function(roleID) {
    switch (parseInt(roleID)) {
        case parseInt(UC_USER):
            return 'user';
        case parseInt(UC_POWER_USER):
            return 'power_user';
        case parseInt(UC_VIP):
            return 'vip';
        case parseInt(UC_UPLOADER):
            return 'uploader';
        case parseInt(UC_MODERATOR):
            return 'moderator';
        case parseInt(UC_ADMINISTRATOR):
            return 'administrator';
        case parseInt(UC_SYSOP):
            return 'sysop';
        case parseInt(AJAX_CHAT_CHATBOT):
            return 'chatBot';
        default:
            return 'default';
    }
    /*switch(parseInt(roleID)) {
            case 0:
                return 'user';
            case 1:
                return 'power_user';
            case 2:
                return 'vip';
            case 3:
                return 'uploader';
            case 4:
                return 'moderator';
            case 5:
                return 'administrator';             
            case 6:
                return 'sysop';
            
            case 100:
                return 'chatBot';
            default:
                return 'default';
        }*/
};