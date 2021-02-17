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
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'user_functions.php');
require_once(INCL_DIR.'bbcode_functions.php');
require_once(INCL_DIR.'html_functions.php');
require_once(INCL_DIR.'pager_new.php');
require_once(CLASS_DIR.'class_user_options.php');
require_once(CLASS_DIR.'class_user_options_2.php');
dbconn(false);
loggedinorreturn();
flood_limit('messages');
define('BUNNY_PM_SYSTEM', true);
/*********************************************************
 * - Pm system by snuggles
 * - write up some credits... based on Tux mailbox mod, using code from Retro
 *******************************************************/
// Define constants
define("INBOX_SCRIPT", null);
define('PM_DELETED', 0); // Message was deleted
define('PM_INBOX', 1); // Message located in Inbox for reciever
define('PM_SENTBOX', -1); // GET value for sent box
define('PM_DRAFTS', -2); //  new drafts folder
$lang = array_merge(load_language('global'), load_language('pm'));
$stdhead = [
    /** include css **/
    'css' => [
    ],
];
$stdfoot = [
    /** include js **/
    'js' => [
        'check_selected',
    ],
];
$HTMLOUT = $count2 = $other_box_info = $maxbox = '';
//== validusername
function validusername($username)
{
    global $lang;
    if ($username == "") {
        return false;
    }
    $namelength = strlen($username);
    if ($namelength < 3 || $namelength > 32) {
        stderr('Error', 'Username too long or too short');
    }
    // The following characters are allowed in user names
    $allowedchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 Only.";
    for ($i = 0; $i < $namelength; ++$i) {
        if (strpos($allowedchars, (string)$username[$i]) === false) {
            return false;
        }
    }
    return true;
}

switch ($CURUSER['class']) {
    case UC_USER:
        $maxbox = 50;
        $maxboxes = 5;
        break;

    case UC_POWER_USER:
        $maxbox = 100;
        $maxboxes = 6;
        break;

    case UC_VIP:
        $maxbox = 250;
        $maxboxes = 10;
        break;

    case UC_UPLOADER:
        $maxbox = 300;
        $maxboxes = 15;
        break;

    case UC_MODERATOR:
        $maxbox = 300;
        $maxboxes = 20;
        break;

    case UC_ADMINISTRATOR:
        $maxbox = 400;
        $maxboxes = 30;
        break;

    case UC_SYSOP:
        $maxbox = 500;
        $maxboxes = 40;
        break;
    case UC_MAX:
        $maxbox = 900;
        $maxboxes = 90;
        break;
    default:
        $maxbox = 100;
        $maxboxes = 15;
        break;
}
//=== get action and check to see if it's ok...
$returnto = $_GET['returnto'] ?? '/index.php';
$possible_actions = [
    'view_mailbox',
    'use_draft',
    'new_draft',
    'save_or_edit_draft',
    'view_message',
    'move',
    'forward',
    'forward_pm',
    'edit_mailboxes',
    'delete',
    'search',
    'move_or_delete_multi',
    'send_message',
];
$action = (isset($_GET['action']) ? htmlsafechars($_GET['action']) : (isset($_POST['action']) ? htmlsafechars($_POST['action']) : 'view_mailbox'));
if (!in_array($action, $possible_actions)) {
    stderr($lang['pm_error'], $lang['pm_error_ruffian']);
}
//=== possible stuff to be $_GETting lol
$change_pm_number = (isset($_GET['change_pm_number']) ? (int)$_GET['change_pm_number'] : (isset($_POST['change_pm_number']) ? (int)$_POST['change_pm_number'] : 0));
$page = (isset($_GET['page']) ? (int)$_GET['page'] : 0);
$perpage = (isset($_GET['perpage']) ? (int)$_GET['perpage'] : ($CURUSER['pms_per_page'] > 0 ? $CURUSER['pms_per_page'] : 20));
$mailbox = (isset($_GET['box']) ? (int)$_GET['box'] : (isset($_POST['box']) ? (int)$_POST['box'] : 1));
$pm_id = (isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0));
$save = ((isset($_POST['save']) && $_POST['save'] === 1) ? '1' : '0');
$urgent = ((isset($_POST['urgent']) && $_POST['urgent'] === 'yes') ? 'yes' : 'no');
//=== change ASC to DESC and back for sort by
$desc_asc = (isset($_GET['ASC']) ? '&amp;DESC=1' : (isset($_GET['DESC']) ? '&amp;ASC=1' : ''));
$desc_asc_2 = (isset($_GET['DESC']) ? 'ascending' : 'descending');
$spacer = '&nbsp;&nbsp;&nbsp;&nbsp;';
//=== get orderby and check to see if it's ok...
$good_order_by = [
    'username',
    'added',
    'subject',
    'id',
];
$order_by = (isset($_GET['order_by']) ? htmlsafechars($_GET['order_by']) : 'added');
if (!in_array($order_by, $good_order_by)) {
    stderr($lang['pm_error'], $lang['pm_error_temp']);
}
//=== top of page:
$top_links = '<li><a href="pm_system.php?action=search">'.$lang['pm_search'].'</a></li>
        <li><a href="pm_system.php?action=edit_mailboxes">'.$lang['pm_manager'].'</a></li>
        <li><a href="pm_system.php?action=new_draft">'.$lang['pm_write_new'].'</a></li>
		<li><a href="pm_system.php?action=view_mailbox&amp;box=1">'.$lang['pm_inbox'].'</a></li>
		<li><a href="pm_system.php?action=view_mailbox&amp;box=-1">'.$lang['pm_sentbox'].'</a></li>
		<li><a href="pm_system.php?action=view_mailbox&amp;box=-2">'.$lang['pm_drafts'].'</a></li>';
//=== change  number of PMs per page on the fly
if (isset($_GET['change_pm_number'])) {
    $change_pm_number = (isset($_GET['change_pm_number']) ? (int)$_GET['change_pm_number'] : 20);
    sql_query('UPDATE users SET pms_per_page = '.sqlesc($change_pm_number).' WHERE id = '.sqlesc($CURUSER['id'])) || sqlerr(__FILE__, __LINE__);
    $cache->update_row('user'.$CURUSER['id'], [
        'pms_per_page' => $change_pm_number,
    ], $TRINITY20['expires']['user_cache']);
    $cache->update_row($keys['my_userid'].$CURUSER['id'], [
        'pms_per_page' => $change_pm_number,
    ], $TRINITY20['expires']['curuser']);
    if (isset($_GET['edit_mail_boxes'])) {
        header('Location: pm_system.php?action=edit_mailboxes&pm=1');
    } else {
        header('Location: pm_system.php?action=view_mailbox&pm=1&box='.$mailbox);
    }
    die();
}
//=== show small avatar drop down thingie / change on the fly
if (isset($_GET['show_pm_avatar'])) {
    $show_pm_avatar = ($_GET['show_pm_avatar'] === 'yes' ? 'yes' : 'no');
    sql_query('UPDATE users SET show_pm_avatar = '.sqlesc($show_pm_avatar).' WHERE id = '.sqlesc($CURUSER['id'])) || sqlerr(__FILE__, __LINE__);
    $cache->update_row('user'.$CURUSER['id'], [
        'show_pm_avatar' => $show_pm_avatar,
    ], $TRINITY20['expires']['user_cache']);
    $cache->update_row($keys['my_userid'].$CURUSER['id'], [
        'show_pm_avatar' => $show_pm_avatar,
    ], $TRINITY20['expires']['curuser']);
    if (isset($_GET['edit_mail_boxes'])) {
        header('Location: pm_system.php?action=edit_mailboxes&avatar=1');
    } else {
        header('Location: pm_system.php?action=view_mailbox&avatar=1&box='.$mailbox);
    }
    die();
}
//=== some get stuff to display messages
$HTMLOUT = $h1_thingie = '';
$h1_thingie .= (isset($_GET['deleted']) ? '<div class="callout alert-callout-border success"><strong>'.$lang['pm_deleted'].'</strong></div>' : '');
$h1_thingie .= (isset($_GET['avatar']) ? '<div class="callout alert-callout-border success"><strong>'.$lang['pm_avatar'].'</strong></div>' : '');
$h1_thingie .= (isset($_GET['pm']) ? '<div class="callout alert-callout-border success"><strong>'.$lang['pm_changed'].'</strong></div>' : '');
$h1_thingie .= (isset($_GET['singlemove']) ? '<div class="callout alert-callout-border success"><strong>'.$lang['pm_moved'].'</strong></div>' : '');
$h1_thingie .= (isset($_GET['multi_move']) ? '<div class="callout alert-callout-border success"><strong>'.$lang['pm_moved_s'].'</strong></div>' : '');
$h1_thingie .= (isset($_GET['multi_delete']) ? '<div class="callout alert-callout-border success"><strong>'.$lang['pm_deleted_s'].'</strong></div>' : '');
$h1_thingie .= (isset($_GET['forwarded']) ? '<div class="callout alert-callout-border success"><strong>'.$lang['pm_forwarded'].'</strong></div>' : '');
$h1_thingie .= (isset($_GET['boxes']) ? '<div class="callout alert-callout-border success"><strong>'.$lang['pm_box_added'].'</strong></div>' : '');
$h1_thingie .= (isset($_GET['name']) ? '<div class="callout alert-callout-border success"><strong>'.$lang['pm_box_updated'].'</strong></div>' : '');
$h1_thingie .= (isset($_GET['new_draft']) ? '<div class="callout alert-callout-border success"><strong>'.$lang['pm_draft_saved'].'</strong></div>' : '');
$h1_thingie .= (isset($_GET['sent']) ? '<div class="callout alert-callout-border success"><strong>'.$lang['pm_msg_sent'].'</strong></div>' : '');
$h1_thingie .= (isset($_GET['pms']) ? '<div class="callout alert-callout-border success"><strong>'.$lang['pm_msg_sett'].'</strong></div>' : '');
//=== mailbox name default:
$mailbox_name = ($mailbox === PM_INBOX ? $lang['pm_inbox'] : ($mailbox === PM_SENTBOX ? $lang['pm_sentbox'] : $lang['pm_drafts']));
switch ($action) {
    case 'view_mailbox':
        require_once(PM_DIR.'view_mailbox.php');
        break;

    case 'view_message':
        require_once(PM_DIR.'view_message.php');
        break;

    case 'send_message':
        require_once(PM_DIR.'send_message.php');
        break;

    case 'move':
        require_once(PM_DIR.'move.php');
        break;

    case 'delete':
        require_once(PM_DIR.'delete.php');
        break;

    case 'move_or_delete_multi':
        require_once(PM_DIR.'move_or_delete_multi.php');
        break;

    case 'forward':
        require_once(PM_DIR.'forward.php');
        break;

    case 'forward_pm':
        require_once(PM_DIR.'forward_pm.php');
        break;

    case 'new_draft':
        require_once(PM_DIR.'new_draft.php');
        break;

    case 'save_or_edit_draft':
        require_once(PM_DIR.'save_or_edit_draft.php');
        break;

    case 'use_draft':
        require_once(PM_DIR.'use_draft.php');
        break;

    case 'search':
        require_once(PM_DIR.'search.php');
        break;

    case 'edit_mailboxes':
        require_once(PM_DIR.'edit_mailboxes.php');
        break;
}
//=== get all PM boxes
function get_all_boxes()
{
    global $CURUSER, $cache, $TRINITY20, $lang;
    if (($get_all_boxes = $cache->get('get_all_boxes'.$CURUSER['id'])) === false) {
        ($res = sql_query('SELECT boxnumber, name FROM pmboxes WHERE userid='.sqlesc($CURUSER['id']).' ORDER BY boxnumber')) || sqlerr(__FILE__,
            __LINE__);
        $get_all_boxes = '<select name="box">
					<option value="">'.$lang['pm_search_move_to'].'</option>

                                            <option value="1">'.$lang['pm_inbox'].'</option>
                                            <option value="-1">'.$lang['pm_sentbox'].'</option>
                                            <option value="-2">'.$lang['pm_drafts'].'</option>';
        while ($row = $res->fetch_assoc()) {
            $get_all_boxes .= '<option value="'.(int)$row['boxnumber'].'">'.htmlsafechars($row['name']).'</option>';
        }
        $get_all_boxes .= '</select>';
        $cache->set('get_all_boxes'.$CURUSER['id'], $get_all_boxes, $TRINITY20['expires']['get_all_boxes']);
    }
    return $get_all_boxes;
}

//=== insert jump to box
function insertJumpTo($mailbox)
{
    global $CURUSER, $cache, $TRINITY20, $lang;
    if (($insertJumpTo = $cache->get('insertJumpTo'.$CURUSER['id'])) === false) {
        ($res = sql_query('SELECT boxnumber,name FROM pmboxes WHERE userid='.sqlesc($CURUSER['id']).' ORDER BY boxnumber')) || sqlerr(__FILE__,
            __LINE__);
        $insertJumpTo = '<form role="form" action="pm_system.php" method="get">
                                    <input type="hidden" name="action" value="view_mailbox">
									<label>'.$lang['pm_jump_to'].'
                                    <select name="box" onchange="location = this.options[this.selectedIndex].value;">';
        while ($row = $res->fetch_assoc()) {
            $insertJumpTo .= '<option value="pm_system.php?action=view_mailbox&amp;box='.(int)$row['boxnumber'].'" '.((int)$row['boxnumber'] == $mailbox ? 'selected="selected"' : '').'>'.htmlsafechars($row['name']).'</option></label>';
        }
        $insertJumpTo .= '</select></form>';
        $cache->set('insertJumpTo'.$CURUSER['id'], $insertJumpTo, $TRINITY20['expires']['insertJumpTo']);
    }
    return $insertJumpTo;
}

//=== progress bar
function get_percent_completed_image($p)
{
    $img = '';
    switch (true) {
        case ($p >= 100):
            $img .= 5;
            break;
        case (($p >= 0) && ($p <= 10)):
            $img .= '10%';
            break;
        case (($p >= 11) && ($p <= 40)):
            $img .= '40%';
            break;
        case (($p >= 41) && ($p <= 60)):
            $img .= '60%';
            break;
        case (($p >= 61) && ($p <= 80)):
            $img .= '80%';
            break;
        case (($p >= 81) && ($p <= 99)):
            $img .= '99%';
            break;
    }
    return '<div class="progress" role="progressbar" tabindex="0" aria-valuenow="'.$img.'" aria-valuemin="0" aria-valuetext="'.$img.'" aria-valuemax="100">
  <div class="progress-meter" style="width: '.$img.'"></div>
  </div>';
}

echo stdhead($lang['pm_stdhead'], true, $stdhead).$HTMLOUT.stdfoot($stdfoot);
?>
