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
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'password_functions.php');
require_once (INCL_DIR . 'function_bemail.php');
require_once (CLASS_DIR . 'page_verify.php');
dbconn();
global $CURUSER;
if (!$CURUSER) {
    get_template();
}
$lang = array_merge(load_language('global') , load_language('takesignup'));
$newpage = new page_verify();
$newpage->check('tkIs');
($res = sql_query("SELECT COUNT(id) FROM users")) || sqlerr(__FILE__, __LINE__);
$arr = $res->fetch_row();
if ($arr[0] >= $TRINITY20['invites']) stderr($lang['stderr_errorhead'], sprintf($lang['stderr_ulimit'], $TRINITY20['invites']));
if (!$TRINITY20['openreg_invites']) stderr('Sorry', 'Invite Signups are closed presently');
if (!mkglobal('wantusername:wantpassword:passagain:email:invite' . ($TRINITY20['captcha_on'] ? ":captchaSelection:" : ":") . 'submitme:passhint:hintanswer')) stderr("Oops", "Missing form data - You must fill all fields");
if ($submitme != 'Register') stderr($lang['takesignup_x_head'], $lang['takesignup_x_body']);
if ($TRINITY20['captcha_on'] && (empty($captchaSelection) || $_SESSION['simpleCaptchaAnswer'] != $captchaSelection)) {
    header("Location: {$TRINITY20['baseurl']}/invite_signup.php");
    exit;
}
function validusername($username)
{
    global $lang;
    if ($username == "") return false;
    $namelength = strlen($username);
    if ($namelength < 3 || $namelength > 32) {
        stderr($lang['takesignup_user_error'], $lang['takesignup_username_length']);
    }
    // The following characters are allowed in user names
    $allowedchars = $lang['takesignup_allowed_chars'];
    for ($i = 0; $i < $namelength; ++$i) {
        if (strpos($allowedchars, (string) $username[$i]) === false) return false;
    }
    return true;
}
if (empty($wantusername) || empty($wantpassword) || empty($email) || empty($invite) || empty($passhint) || empty($hintanswer)) stderr("Error", "Don't leave any fields blank.");
if (!blacklist($wantusername)) stderr($lang['takesignup_user_error'], sprintf($lang['takesignup_badusername'], htmlsafechars($wantusername)));
if (strlen($wantusername) > 12) stderr("Error", "Sorry, username is too long (max is 12 chars)");
if ($wantpassword != $passagain) stderr("Error", "The passwords didn't match! Must've typoed. Try again.");
if (strlen($wantpassword) < 6) stderr("Error", "Sorry, password is too short (min is 6 chars)");
if (strlen($wantpassword) > 40) stderr("Error", "Sorry, password is too long (max is 40 chars)");
if ($wantpassword == $wantusername) stderr("Error", "Sorry, password cannot be same as user name.");
if (!validemail($email)) stderr("Error", "That doesn't look like a valid email address.");
if (!validusername($wantusername)) stderr("Error", "Invalid username.");
if (!(isset($_POST['day']) || isset($_POST['month']) || isset($_POST['year']))) stderr('Error', 'You have to fill in your birthday.');
if (checkdate($_POST['month'], $_POST['day'], $_POST['year'])) $birthday = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
else stderr('Error', 'You have to fill in your birthday correctly.');
if ((date('Y') - $_POST['year']) < 17) stderr('Error', 'You must be at least 18 years old to register.');
// make sure user agrees to everything...
if ($_POST["rulesverify"] != "yes" || $_POST["faqverify"] != "yes" || $_POST["ageverify"] != "yes") stderr("Error", "Sorry, you're not qualified to become a member of this site.");
if (!(isset($_POST['country']))) stderr($lang['takesignup_error'], $lang['takesignup_country']);
$pincode = (int)$_POST['pin_code'];
if ($pincode != $_POST['pin_code2']) stderr($lang['takesignup_user_error'], "Pin Codes don't match");
if (strlen((string)$pincode) != 4) stderr($lang['takesignup_user_error'], "Pin Code must be 4 digits");
$country = (((isset($_POST['country']) && is_valid_id($_POST['country'])) ? (int) $_POST['country'] : 0));
$gender = isset($_POST['gender']) && isset($_POST['gender']) ? htmlsafechars($_POST['gender']) : '';
// check if email addy is already in use
($a_query = sql_query('SELECT COUNT(id) FROM users WHERE email = ' . sqlesc($email))) || sqlerr(__FILE__, __LINE__);
$a = $a_query->fetch_row();
if ($a[0] != 0) stderr('Error', 'The e-mail address <b>' . htmlsafechars($email) . '</b> is already in use.');
//=== check if ip addy is already in use
if ($TRINITY20['dupeip_check_on']) {
    ($c_query = sql_query("SELECT COUNT(id) FROM users WHERE ip=" . sqlesc($_SERVER['REMOTE_ADDR']))) || sqlerr(__FILE__, __LINE__);
    $c = $c_query->fetch_row();
    if ($c[0] != 0) stderr("Error", "The ip " . htmlsafechars($_SERVER['REMOTE_ADDR']) . " is already in use. We only allow one account per ip address.");
}

/*=== check for dupe account by GodFather ===*/
if ($TRINITY20['dupeaccount_check_on'] == 1) {
    if(!empty(get_mycookie('log_uid'))){
	    $ip = getip();
	    $res = sql_query("SELECT * FROM users WHERE loginhash=" . sqlesc(get_mycookie('log_uid')));
        if (($row = $res->fetch_assoc()) && $row['class'] < UC_SYSOP) {
            $u_ip = $ip;
            $n_username = $wantusername;
            $n_email = $email;
            $sign_time = time();
            $msg = " User : " . $row['username'] . " identified by ID : " . $row['id'] . " tried to create another account.\n New account with user : " . $n_username . " and email : " . $n_email . ".\n Email addres was banned, cheater user was warned ";
            sql_query("INSERT INTO bannedemails (added, addedby, comment, email) VALUES (" . TIME_NOW . ", '0', " . sqlesc($lang['takesignup_dupe']) . ", " . sqlesc($n_email) . ")") || sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO doublesignup (userid, username, email, ip, sign_date, new_user, new_email, msg) VALUES (" . sqlesc($row['id']) . ", " . sqlesc($row['username']) . ", " . sqlesc($row['email']) . ", " . sqlesc($u_ip) . ", " . TIME_NOW . ", " . sqlesc($n_username) . ", " . sqlesc($n_email) . ", " . sqlesc($lang['takesignup_msg_body2']) . ")") || sqlerr(__FILE__, __LINE__);
            sql_query("UPDATE users SET ip = ". sqlesc($u_ip) .", last_access = " . TIME_NOW . ", warned = '1', warn_reason = " . sqlesc($lang['takesignup_warn']) . " WHERE id = " . sqlesc($row['id'])) || sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO ajax_chat_messages (userID, userName, userRole, channel, dateTime, ip, text) VALUES (" . sqlesc($TRINITY20['bot_id']) . "," . sqlesc($TRINITY20['bot_name']) . "," . sqlesc($TRINITY20['bot_role']) . ",'4'," . sqlesc(TIME_DATE) . "," . sqlesc($_SERVER['REMOTE_ADDR']) . "," . sqlesc($msg) . ")") || sqlerr(__FILE__, __LINE__);
            stderr($lang['takesignup_user_error'], $lang['takesignup_msg_dupe3']);
        }
    }
	
	if(!empty($email)){
	    ($x = sql_query("SELECT id, comment FROM bannedemails WHERE email = " . sqlesc($email))) || sqlerr(__FILE__, __LINE__);
        if ($yx = $x->fetch_assoc()) stderr("{$lang['takesignup_user_error']}", "{$lang['takesignup_bannedmail']}" . htmlsafechars($yx['comment']));
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
$dst_in_use = localtime(TIME_NOW + ((int)$time_offset * 3600) , true);
// TIMEZONE STUFF END
($select_inv = sql_query('SELECT sender, receiver, status FROM invite_codes WHERE code = ' . sqlesc($invite))) || sqlerr(__FILE__, __LINE__);
$rows = $select_inv->num_rows;
$assoc = $select_inv->fetch_assoc();
if ($rows == 0) stderr("Error", "Invite not found.\nPlease request a invite from one of our members.");
if ($assoc["receiver"] != 0) stderr("Error", "Invite already taken.\nPlease request a new one from your inviter.");
$added = TIME_NOW;
$secret = mksecret();
$hash1 = t_Hash($email, $wantusername, $added);
$hash2 = t_Hash($birthday, $secret, $pincode);
$hash3 = t_Hash($birthday, $wantusername, $email);

$wantpasshash = make_passhash($hash1, hash("ripemd160", $wantpassword), $hash2);
$editsecret = ($arr[0] ? make_passhash_login_key($email, $added) : "");
$wanthintanswer = h_store($hintanswer.$email);
check_banned_emails($email);
$user_frees = (TIME_NOW + 14 * 86400);
//$emails = encrypt_email($email);
($new_user = sql_query("INSERT INTO users (username, passhash, hash3, secret, passhint, hintanswer, editsecret, birthday, country, gender, stylesheet, invitedby, email, added, last_access, last_login, time_offset, dst_in_use, free_switch, pin_code) VALUES (" . implode(",", array_map("sqlesc", array(
    $wantusername,
    $wantpasshash,
	$hash3,
    $secret,
    $passhint,
    $wanthintanswer,
    $editsecret,
    $birthday,
    $country,
    $gender,
    $TRINITY20['stylesheet'],
    (int)$assoc['sender'],
    $email,
    TIME_NOW,
    TIME_NOW,
    TIME_NOW,
    $time_offset,
    $dst_in_use['tm_isdst'],
    $user_frees,
    $pincode
))) . ")")) || sqlerr(__FILE__, __LINE__);
$id = $mysqli->insert_id;
sql_query("INSERT INTO usersachiev (id, username) VALUES (" . sqlesc($id) . ", " . sqlesc($wantusername) . ")") || sqlerr(__FILE__, __LINE__);
sql_query("UPDATE usersachiev SET invited=invited+1 WHERE id =" . sqlesc($assoc['sender'])) || sqlerr(__FILE__, __LINE__);
$message = "Welcome New {$TRINITY20['site_name']} Member : - " . htmlsafechars($wantusername) . "";
if (!$new_user && $mysqli->errno) {
    stderr("Error", "Username already exists!");
}
//===send PM to inviter
$sender = (int)$assoc["sender"];
$added = TIME_NOW;
$msg = sqlesc("Hey there [you] ! :wave:\nIt seems that someone you invited to {$TRINITY20['site_name']} has arrived ! :clap2: \n\n Please go to your [url={$TRINITY20['baseurl']}/invite.php]Invite page[/url] to confirm them so they can log in.\n\ncheers\n");
$subject = sqlesc("Someone you invited has arrived!");
sql_query("INSERT INTO messages (sender, subject, receiver, msg, added) VALUES (0, $subject, " . sqlesc($sender) . ", $msg, $added)") || sqlerr(__FILE__, __LINE__);
$cache->delete('inbox_new::' . $sender);
$cache->delete('inbox_new_sb::' . $sender);
//////////////end/////////////////////
sql_query('UPDATE invite_codes SET receiver = ' . sqlesc($id) . ', status = "Confirmed" WHERE sender = ' . sqlesc((int)$assoc['sender']) . ' AND code = ' . sqlesc($invite)) || sqlerr(__FILE__, __LINE__);
$latestuser_cache['id'] = (int)$id;
$latestuser_cache['username'] = $wantusername;
$latestuser_cache['class'] = '0';
$latestuser_cache['donor'] = 'no';
$latestuser_cache['warned'] = '0';
$latestuser_cache['enabled'] = 'yes';
$latestuser_cache['chatpost'] = '1';
$latestuser_cache['leechwarn'] = '0';
$latestuser_cache['pirate'] = '0';
$latestuser_cache['king'] = '0';
//$latestuser_cache['perms'] =  (int)$arr['perms'];

/** OOPs **/
$cache->set('latestuser', $latestuser_cache, 0, $TRINITY20['expires']['latestuser']);
$cache->delete('birthdayusers');
write_log('User account ' . htmlsafechars($wantusername) . ' was created!');
if ($TRINITY20['autoshout_on'] == 1) {
    autoshout($message);
}
stderr('Success', 'Signup successfull, Your inviter needs to confirm your account now before you can use your account !');
?>
