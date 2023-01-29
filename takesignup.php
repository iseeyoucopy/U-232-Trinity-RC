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
require_once(CLASS_DIR.'page_verify.php');
require_once(INCL_DIR.'password_functions.php');
//require_once (INCL_DIR . 'bbcode_functions.php');
require_once(INCL_DIR.'function_bemail.php');
dbconn();
global $CURUSER, $TRINITY20;
if (!$CURUSER) {
    get_template();
}
$lang = array_merge(load_language('global'), load_language('takesignup'));
if (!$TRINITY20['openreg']) {
    stderr($lang['stderr_errorhead'],
        "{$lang['takesignup_invite_only']}<a href='".$TRINITY20['baseurl']."/invite_signup.php'><b>&nbsp;{$lang['takesignup_here']}</b></a>");
}
($res = sql_query("SELECT COUNT(id) FROM users")) || sqlerr(__FILE__, __LINE__);
$arr = $res->fetch_row();
if ($arr[0] >= $TRINITY20['maxusers']) {
    stderr($lang['takesignup_error'], $lang['takesignup_limit']);
}
$newpage = new page_verify();
$newpage->check('tesu');
if (!mkglobal('wantusername:wantpassword:passagain:email'.($TRINITY20['captcha_on'] ? ":captchaSelection:" : ":").'submitme:passhint:hintanswer:country')) {
    stderr($lang['takesignup_user_error'], $lang['takesignup_form_data']);
}
if ($submitme != 'Register') {
    stderr($lang['takesignup_x_head'], $lang['takesignup_x_body']);
}
if ($TRINITY20['captcha_on'] && (empty($captchaSelection) || $_SESSION['simpleCaptchaAnswer'] != $captchaSelection)) {
    header("Location: {$TRINITY20['baseurl']}/signup.php");
    exit;
}
function validusername($username)
{
    global $lang;
    if ($username == "") {
        return false;
    }
    $namelength = strlen($username);
    if ($namelength < 3 || $namelength > 32) {
        stderr($lang['takesignup_user_error'], $lang['takesignup_username_length']);
    }
    // The following characters are allowed in user names
    $allowedchars = $lang['takesignup_allowed_chars'];
    for ($i = 0; $i < $namelength; ++$i) {
        if (strpos($allowedchars, (string)$username[$i]) === false) {
            return false;
        }
    }
    return true;
}

if (empty($wantusername) || empty($wantpassword) || empty($email) || empty($passhint) || empty($hintanswer) || empty($country)) {
    stderr($lang['takesignup_user_error'], $lang['takesignup_blank']);
}
if (!blacklist($wantusername)) {
    stderr($lang['takesignup_user_error'], sprintf($lang['takesignup_badusername'], htmlspecialchars($wantusername)));
}
if ($wantpassword != $passagain) {
    stderr($lang['takesignup_user_error'], $lang['takesignup_nomatch']);
}
if (strlen($wantpassword) < 6) {
    stderr($lang['takesignup_user_error'], $lang['takesignup_pass_short']);
}
if (strlen($wantpassword) > 40) {
    stderr($lang['takesignup_user_error'], $lang['takesignup_pass_long']);
}
if ($wantpassword == $wantusername) {
    stderr($lang['takesignup_user_error'], $lang['takesignup_same']);
}
$pincode = (int)$_POST['pin_code'];
if ($pincode != $_POST['pin_code2']) {
    stderr($lang['takesignup_user_error'], "Pin Codes don't match");
}
if (strlen((string)$pincode) != 4) {
    stderr($lang['takesignup_user_error'], "Pin Code must be 4 digits");
}
if (!validemail($email)) {
    stderr($lang['takesignup_user_error'], $lang['takesignup_validemail']);
}
if (!validusername($wantusername)) {
    stderr($lang['takesignup_user_error'], $lang['takesignup_invalidname']);
}
if (!(isset($_POST['day']) || isset($_POST['month']) || isset($_POST['year']))) {
    stderr($lang['takesignup_error'], $lang['takesignup_birthday']);
}
if (checkdate($_POST['month'], $_POST['day'], $_POST['year'])) {
    $birthday = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
} else {
    stderr($lang['takesignup_error'], $lang['takesignup_correct_birthday']);
}
if ((date('Y') - $_POST['year']) < 17) {
    stderr($lang['takesignup_error'], $lang['takesignup_yearsold']);
}
if (!(isset($_POST['country']))) {
    stderr($lang['takesignup_error'], $lang['takesignup_country']);
}
$country = (((isset($_POST['country']) && is_valid_id($_POST['country'])) ? (int)$_POST['country'] : 0));
$gender = isset($_POST['gender']) && isset($_POST['gender']) ? htmlspecialchars($_POST['gender']) : '';
// make sure user agrees to everything...
if ($_POST["rulesverify"] != "yes" || $_POST["faqverify"] != "yes" || $_POST["ageverify"] != "yes") {
    stderr($lang['takesignup_failed'], $lang['takesignup_qualify']);
}
// check if username is already in use
($check_uname_query = sql_query("SELECT COUNT(id) username FROM users WHERE username = ".sqlesc($wantusername))) || sqlerr(__FILE__, __LINE__);
$check_uname = $check_uname_query->fetch_row();
if ($check_uname[0] != 0) {
    stderr($lang['takesignup_user_error'], 'username already in use');
}
// check if email addy is already in use
($a_query = sql_query("SELECT COUNT(id) FROM users WHERE email=".sqlesc($email))) || sqlerr(__FILE__, __LINE__);
$a = $a_query->fetch_row();
if ($a[0] != 0) {
    stderr($lang['takesignup_user_error'], $lang['takesignup_email_used']);
}
//=== check if ip addy is already in use
/*
if ($TRINITY20['dupeip_check_on'] == 1) {
    $c_query = sql_query("SELECT COUNT(id) FROM users WHERE ip=" . sqlesc($_SERVER['REMOTE_ADDR'])) or sqlerr(__FILE__, __LINE__);
    $c = $c_query->fetch_row();
    if ($c[0] != 0) stderr($lang['takesignup_error'], "{$lang['takesignup_ip']}&nbsp;" . htmlspecialchars($_SERVER['REMOTE_ADDR']) . "&nbsp;{$lang['takesignup_ip_used']}");
}*/
/*=== check for dupe account by GodFather ===*/
if ($TRINITY20['dupeaccount_check_on'] == 1) {

    if (!empty(get_mycookie('log_uid'))) {
        $ip = getip();
        $res = sql_query("SELECT * FROM users WHERE loginhash=".sqlesc(get_mycookie('log_uid')));
        if (($row = $res->fetch_assoc()) && $row['class'] < UC_SYSOP) {
            $u_ip = $ip;
            $n_username = $wantusername;
            $n_email = $email;
            $sign_time = time();
            $msg = " User : ".$row['username']." identified by ID : ".$row['id']." tried to create another account.\n New account with user : ".$n_username." and email : ".$n_email.".\n Email addres was banned, cheater user was warned ";
            sql_query("INSERT INTO bannedemails (added, addedby, comment, email) VALUES (".TIME_NOW.", '0', ".sqlesc($lang['takesignup_dupe']).", ".sqlesc($n_email).")") || sqlerr(__FILE__,
                __LINE__);
            sql_query("INSERT INTO doublesignup (userid, username, email, ip, sign_date, new_user, new_email, msg) VALUES (".sqlesc($row['id']).", ".sqlesc($row['username']).", ".sqlesc($row['email']).", ".sqlesc($u_ip).", ".TIME_NOW.", ".sqlesc($n_username).", ".sqlesc($n_email).", ".sqlesc($lang['takesignup_msg_body2']).")") || sqlerr(__FILE__,
                __LINE__);
            sql_query("UPDATE users SET ip = ".sqlesc($u_ip).", last_access = ".TIME_NOW.", warned = '1', warn_reason = ".sqlesc($lang['takesignup_warn'])." WHERE id = ".sqlesc($row['id'])) || sqlerr(__FILE__,
                __LINE__);
            sql_query("INSERT INTO ajax_chat_messages (userID, userName, userRole, channel, dateTime, ip, text) VALUES (".sqlesc($TRINITY20['bot_id']).",".sqlesc($TRINITY20['bot_name']).",".sqlesc($TRINITY20['bot_role']).",'4',".sqlesc(TIME_DATE).",".sqlesc($_SERVER['REMOTE_ADDR']).",".sqlesc($msg).")") || sqlerr(__FILE__,
                __LINE__);
            stderr($lang['takesignup_user_error'], $lang['takesignup_msg_dupe3']);
        }
    }

    if (!empty($email)) {
        ($x = sql_query("SELECT id, comment FROM bannedemails WHERE email = ".sqlesc($email))) || sqlerr(__FILE__, __LINE__);
        if ($yx = $x->fetch_assoc()) {
            stderr("{$lang['takesignup_user_error']}", "{$lang['takesignup_bannedmail']}".htmlspecialchars($yx['comment']));
        }
    }
}
/*=== end check for dupe account ===*/
// TIMEZONE STUFF
if (isset($_POST["user_timezone"]) && preg_match('#^\-?\d{1,2}(?:\.\d{1,2})?$#', $_POST['user_timezone'])) {
    $time_offset = sqlesc($_POST['user_timezone']);
} else {
    $time_offset = isset($TRINITY20['time_offset']) ? sqlesc($TRINITY20['time_offset']) : '0';
}
// have a stab at getting dst parameter?
$dst_in_use = localtime(TIME_NOW + ((int)$time_offset * 3600), true);
// TIMEZONE STUFF END
$added = TIME_NOW;
$secret = mksecret();
$hash1 = t_Hash($email, $wantusername, $added);
$hash2 = t_Hash($birthday, $secret, $pincode);
$hash3 = t_Hash($birthday, $wantusername, $email);


$wantpasshash = make_passhash($hash1, hash("ripemd160", $wantpassword), $hash2);
$editsecret = ($arr[0] ? EMAIL_CONFIRM : "") ? make_passhash_login_key($email, $added) : "";
$wanthintanswer = h_store($hintanswer.$email);
$user_frees = (XBT_TRACKER == true ? 0 : TIME_NOW + 14 * 86400);
check_banned_emails($email);
$psecret = $editsecret;

($ret = sql_query("INSERT INTO users (username, passhash, hash3, secret, editsecret, birthday, country, gender, pin_code, stylesheet, passhint, hintanswer, email, status, ".($arr[0] ? "" : "class, ")."added, last_access, time_offset, dst_in_use, free_switch) VALUES (".implode(",",
        array_map("sqlesc", [
            $wantusername,
            $wantpasshash,
            $hash3,
            $secret,
            $editsecret,
            $birthday,
            $country,
            $gender,
            $pincode,
            $TRINITY20['stylesheet'],
            $passhint,
            $wanthintanswer,
            $email,
            (!$arr[0] || !EMAIL_CONFIRM ? 'confirmed' : 'pending'),
        ])).", ".($arr[0] ? "" : UC_SYSOP.", ")."".TIME_NOW.",".TIME_NOW." , $time_offset, {$dst_in_use['tm_isdst']}, $user_frees)")) || sqlerr(__FILE__,
    __LINE__);

$cache->delete($cache_keys['birthdayusers']);

$message = "{$lang['takesignup_welcome']} {$TRINITY20['site_name']} {$lang['takesignup_member']} ".htmlspecialchars($wantusername)."";

if (!$ret && $mysqli->errno) {
    stderr($lang['takesignup_user_error'], $lang['takesignup_user_exists']);
}

$id = $mysqli->insert_id;

sql_query("INSERT INTO usersachiev (id, username) VALUES (".sqlesc($id).", ".sqlesc($wantusername).")") || sqlerr(__FILE__, __LINE__);

if (!$arr[0]) {
    write_staffs();
}

//==New member pm
//$added = TIME_NOW;
$subject = sqlesc($lang['takesignup_msg_subject']);
$msg = sqlesc("{$lang['takesignup_hey']} ".htmlspecialchars($wantusername)."{$lang['takesignup_msg_body0']} {$TRINITY20['site_name']} {$lang['takesignup_msg_body1']}");
sql_query("INSERT INTO messages (sender, subject, receiver, msg, added) VALUES (0, $subject, ".sqlesc($id).", $msg, $added)") || sqlerr(__FILE__,
    __LINE__);

//==End new member pm
$latestuser_cache['id'] = (int)$id;
$latestuser_cache['username'] = $wantusername;
$latestuser_cache['class'] = 0;
$latestuser_cache['donor'] = 'no';
$latestuser_cache['warned'] = 0;
$latestuser_cache['enabled'] = 'yes';
$latestuser_cache['chatpost'] = 1;
$latestuser_cache['leechwarn'] = 0;
$latestuser_cache['pirate'] = 0;
$latestuser_cache['king'] = 0;
$cache->set($cache_keys['latestuser'], $latestuser_cache, $TRINITY20['expires']['latestuser']);

write_log("User account ".(int)$id." (".htmlspecialchars($wantusername).") was succesfully register");

if ($TRINITY20['autoshout_on'] == 1) {
    autoshout($message);

}

$body = str_replace([
    '<#SITENAME#>',
    '<#USEREMAIL#>',
    '<#IP_ADDRESS#>',
    '<#REG_LINK#>',
], [
    $TRINITY20['site_name'],
    $email,
    $_SERVER['REMOTE_ADDR'],
    "{$TRINITY20['baseurl']}/confirm.php?id=$id&secret=$psecret",
], $lang['takesignup_email_body']);
$passh = h_cook($hash3, $_SERVER["REMOTE_ADDR"], $id);
/*=== for dupe account ===*/
$hashlog = make_hash_log($id, $passh);
($logs_query = sql_query("SELECT loginhash FROM users WHERE id= ".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
$logs = $logs_query->fetch_assoc();
if (empty($logs['loginhash']) || $logs['loginhash'] != $hashlog) {
    sql_query('UPDATE users SET loginhash='.sqlesc($hashlog).' WHERE id='.sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    ($a_query = sql_query("SELECT COUNT(id) FROM doublesignup WHERE userid=".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    $a = $a_query->fetch_row();
    if ($a[0] != 0) {
        sql_query('UPDATE doublesignup SET login_hash='.sqlesc($hashlog).' WHERE userid='.sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    }
}
/*=== ===*/

if ($arr[0] || EMAIL_CONFIRM) {
    mail($email, "{$TRINITY20['site_name']} {$lang['takesignup_confirm']}", $body, "{$lang['takesignup_from']} {$TRINITY20['site_email']}");
} else {
    logincookie($id, $passh);
}

header("Refresh: 0; url=ok.php?type=".($arr[0] ? EMAIL_CONFIRM ? "signup&email=".urlencode($email) : "confirm" : ("sysop")));
?>
