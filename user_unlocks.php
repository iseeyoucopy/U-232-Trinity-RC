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
+------------------------------------------------
|   $Date$ 10022011
|   $Revision$ 1.0
|   $Author$ pdq,Bigjoos
|   $User unlocks
|
+------------------------------------------------
*/
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'html_functions.php');
require_once(INCL_DIR.'user_functions.php');
dbconn(false);
loggedinorreturn();
$stdfoot = [
    /** include js **/
    'js' => [
        'custom-form-elements',
    ],
];
$stdhead = [
    /** include css **/
    'css' => [
        'user_blocks',
        'checkbox',
    ],
];
//         'hide'

$lang = array_merge(load_language('global'), load_language('user_unlocks'));

$id = ($_GET['id'] ?? $CURUSER['id']);
if (!is_valid_id($id) || $CURUSER['class'] < UC_STAFF) {
    $id = $CURUSER['id'];
}
if ($CURUSER['got_moods'] == 'no') {
    stderr($lang['gl_error'], "{$lang['uunlk_std_err1']}\n\n{$lang['uunlk_std_err2']}");
    die;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateset = [];
    $setbits = $clrbits = 0;
    if (isset($_POST['unlock_user_moods'])) {
        $setbits |= bt_options::UNLOCK_MORE_MOODS;
    } // Unlock bonus moods
    else {
        $clrbits |= bt_options::UNLOCK_MORE_MOODS;
    } // lock bonus moods
    if (isset($_POST['disable_beep'])) {
        $setbits |= bt_options::NOFKNBEEP;
    } // Unlock bonus moods
    else {
        $clrbits |= bt_options::NOFKNBEEP;
    } // lock bonus moods
    if (isset($_POST['perms_stealth'])) {
        $setbits |= bt_options::PERMS_STEALTH;
    } // stealth on
    else {
        $clrbits |= bt_options::PERMS_STEALTH;
    } // stealth off
    // update perms
    if ($setbits || $clrbits) {
        sql_query('UPDATE users SET perms = ((perms | '.$setbits.') & ~'.$clrbits.') 
                 WHERE id = '.sqlesc($id)) || sqlerr(__file__, __line__);
    }
    if ($id == $CURUSER['id']) {
        // grab current data
        ($res = sql_query('SELECT perms FROM users 
                     WHERE id = '.sqlesc($id).' LIMIT 1')) || sqlerr(__file__, __line__);
        $row = $res->fetch_assoc();
        $row['perms'] = (int)$row['perms'];
        $cache->update_row($cache_keys['my_userid'].$id, [
            'perms' => $row['perms'],
        ], $TRINITY20['expires']['curuser']);
        $cache->update_row($cache_keys['user'].$id, [
            'perms' => $row['perms'],
        ], $TRINITY20['expires']['user_cache']);
    }
    header('Location: '.$TRINITY20['baseurl'].'/user_unlocks.php');
    exit();
}
$checkbox_unlock_moods = ((($CURUSER['perms'] & bt_options::UNLOCK_MORE_MOODS) !== 0) ? ' checked="checked"' : '');
$checkbox_unlock_stealth = ((($CURUSER['perms'] & bt_options::PERMS_STEALTH) !== 0) ? ' checked="checked"' : '');
$checkbox_unlock_nofknbeep = ((($CURUSER['perms'] & bt_options::NOFKNBEEP) !== 0) ? ' checked="checked"' : '');
$HTMLOUT = '';
$HTMLOUT .= begin_frame();
$HTMLOUT .= '<div class="container"><form action="" method="post">        
        <fieldset><legend>'.$lang['uunlk_user_unlock_settings'].'</legend></fieldset>
        <div class="row-fluid">
        '.($TRINITY20['mood_sys_on'] ? '<div class="span3 offset1">
        <table class="table table-bordered">
	<tr>
        <td>
        <b>'.$lang['uunlk_enable_bonus_moods'].'?</b>
        <div class="slideThree"> <input type="checkbox" id="unlock_user_moods" name="unlock_user_moods" value="yes"'.$checkbox_unlock_moods.'>
        <label for="unlock_user_moods"></label></div>
        <div><hr style="color:#A83838;" size="1"></div>
        <span>'.$lang['uunlk_check_this_option_to_unlock'].' '.$lang['uunlk_bonus_mood_smilies'].'.</span>
	</td>
	</tr>
	</table>
	</div>' : '').'
        <!--<div><h1>'.$lang['uunlk_unlock_user_moods'].'</h1></div>
        <table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
        <td width="50%">
        <b>'.$lang['uunlk_enable_bonus_moods'].'?</b>
        <div style="color: gray;">'.$lang['uunlk_check_this_option_to_unlock'].' '.$lang['uunlk_bonus_mood_smilies'].'.</div></td>
        <td width="30%"><div style="width: auto;" align="right">
        <input class="styled" type="checkbox" name="unlock_user_moods" value="yes"'.$checkbox_unlock_moods.'>
        </div></td>
        </tr></table>-->
        <div class="span3 offset1">
        <table class="table table-bordered">
	<tr>
        <td>
        <b>'.$lang['uunlk_enable_username_shout_alert'].'?</b>
        <div class="slideThree"> <input type="checkbox" id="disable_beep" name="disable_beep" value="yes"'.$checkbox_unlock_nofknbeep.'>
        <label for="disable_beep"></label></div>
        <div><hr style="color:#A83838;" size="1"></div>
        <span>'.$lang['uunlk_check_this_option_to_unlock'].' '.$lang['uunlk_shout_beep_option'].'.</span>
	</td>
	</tr>
	</table>
	</div>
        <!--<div><h1>Check this option to unlock shout beep option.</h1></div>
        <table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
        <td width="50%">
        <b>'.$lang['uunlk_enable_username_shout_alert'].'?</b>
        <div style="color: gray;">'.$lang['uunlk_check_this_option_to_unlock'].' '.$lang['uunlk_shout_beep_option'].'.</div></td>
        <td width="30%"><div style="width: auto;" align="right">
        <input class="styled" type="checkbox" name="disable_beep" value="yes"'.$checkbox_unlock_nofknbeep.'>
        </div></td>
        </tr></table>-->
        <!--
        <div><h1>'.$lang['uunlk_user_stealth_mod'].'e</h1></div>
        <table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
        <td width="50%">
        <b>'.$lang['uunlk_enable_stealth'].'?</b>
        <div style="color: gray;">'.$lang['uunlk_check_this_option_to_unlock'].' '.$lang['uunlk_stealth_mode'].'.</div></td>
        <td width="30%"><div style="width: auto;" align="right">
        <input class="styled" type="checkbox" name="perms_stealth" value="yes"'.$checkbox_unlock_stealth.'></div></td>
        </tr></table>-->
        <div class="span3 offset1">
        <table class="table table-bordered">
	<tr>
        <td>
        <b>'.$lang['uunlk_enable_stealth'].'?</b>
        <div class="slideThree"><input type="checkbox" name="perms_stealth" value="yes"'.$checkbox_unlock_stealth.'>
        <label for="perms_stealth"></label></div>
        <div><hr style="color:#A83838;" size="1"></div>
        <span>'.$lang['uunlk_check_this_option_to_unlock'].' '.$lang['uunlk_stealth_mode'].'.</span>
	</td>
	</tr>
	</table>
	</div>';
$HTMLOUT .= '<div class="span7 offset1">
<input class="btn btn-primary" type="submit" name="submit" value="'.$lang['uunlk_submit'].'" tabindex="2" accesskey="s"></div></div></form></div>';

$HTMLOUT .= end_frame();
echo stdhead($lang['uunlk_std_head'], true, $stdhead).$HTMLOUT.stdfoot($stdfoot);
