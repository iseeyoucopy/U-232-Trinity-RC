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
if (!defined('IN_TRINITY20_ADMIN')) {
    $HTMLOUT = '';
    $HTMLOUT .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<title>Error!</title>
		</head>
		<body>
	<div style='font-size:33px;color:white;background-color:red;text-align:center;'>Incorrect access<br />You cannot access this file directly.</div>
	</body></html>";
    echo $HTMLOUT;
    exit();
}
require_once(INCL_DIR.'user_functions.php');
require_once(INCL_DIR.'password_functions.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('ad_reset'));
//== Reset Lost Password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim(htmlspecialchars($_POST['username']));
    $password = $_POST['password'];
    $uid = (int)$_POST["uid"];
    $query = sql_query("SELECT username, passhash, secret, hash3, email, hintanswer, added, pin_code, birthday FROM users WHERE username = ".sqlesc($username)." AND id=".sqlesc($uid));
    $row = $query->fetch_assoc();
    $secret = mksecret();
    $hash1 = t_Hash($row['email'], $row['username'], $row['added']);
    $hash2 = t_Hash($row['birthday'], $secret, $row['pin_code']);
    $hash3 = t_Hash($row['birthday'], $row['username'], $row['email']);
    if ((isset($_POST['email']) && $_POST['email'] == $row['email']) || (isset($_POST['birthday']) && $_POST['birthday'] == $row['birthday']) || (isset($_POST['username']) && $_POST['username'] == $row['username'])) {
        $passhash = make_passhash($hash1, hash("ripemd160", $password), $hash2);
    }
    $hint = $_POST["hintanswer"];
    $wanthintanswer = h_store($hint.$row['email']);
    $postkey = PostKey([
        $uid,
        $CURUSER['id'],
    ]);
    ($res = sql_query("UPDATE users SET secret=".sqlesc($secret).", passhash=".sqlesc($passhash).", hash3=".sqlesc($hash3).", hintanswer=".sqlesc($wanthintanswer)." WHERE username=".sqlesc($username)." AND id=".sqlesc($uid)." AND class<".$CURUSER['class'])) || sqlerr(__FILE__,
        __LINE__);
    $cache->update_row($keys['my_userid'].$uid, [
        'secret' => $secret,
        'passhash' => $passhash,
        'hash3' => $hash3,
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row($keys['user'].$uid, [
        'secret' => $secret,
        'passhash' => $passhash,
        'hash3' => $hash3,
    ], $TRINITY20['expires']['user_cache']);

    if ($mysqli->affected_rows != 1) {
        stderr($lang['reset_stderr'], $lang['reset_stderr1']);
    }
    if (CheckPostKey([
            $uid,
            $CURUSER['id'],
        ], $postkey) == false) {
        stderr($lang['reset_stderr2'], $lang['reset_stderr3']);
    }
    write_log($lang['reset_pwreset'], $lang['reset_pw_log1'].htmlspecialchars($username).$lang['reset_pw_log2'].htmlspecialchars($CURUSER['username']));
    stderr($lang['reset_pw_success'],
        ''.$lang['reset_pw_success1'].' <b>'.htmlspecialchars($username).'</b>The hint for'.htmlspecialchars($username).' is '.htmlspecialchars($hint).'<b>'.$lang['reset_pw_success2'].'<b>'.htmlspecialchars($newpassword).'</b>.');
}
$HTMLOUT = "";
$HTMLOUT .= "<div class='row'><div class='col-md-12'><h1>{$lang['reset_title']}</h1>
<form method='post' action='staffpanel.php?tool=reset&amp;action=reset'>
<table class='table table-bordered'>
<tr>
<td class='rowhead'>{$lang['reset_id']}</td><td>
<input type='text' name='uid' size='10' /></td></tr>
<tr>
<td class='rowhead'>{$lang['reset_username']}</td><td>
<input size='40' name='username' /></td></tr>
<tr>
<tr>
<td class='rowhead'>Password</td><td>
<input size='40' name='password' /></td></tr>
<tr>
<td colspan='2'>
<input type='submit' class='btn' value='reset' />
</td>
</tr>
</table></form></div></div>";
echo stdhead($lang['reset_stdhead']).$HTMLOUT.stdfoot();
?>
