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
	<div style='font-size:33px;color:white;background-color:red;text-align:center;'>Incorrect access<br>You cannot access this file directly.</div>
	</body></html>";
    echo $HTMLOUT;
    exit();
}
require_once(INCL_DIR.'user_functions.php');
require_once(CLASS_DIR.'class_check.php');
require_once(INCL_DIR.'password_functions.php');
require_once(INCL_DIR.'function_account_delete.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('ad_delacct'));

//==
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(htmlsafechars($_POST["username"]));
    $password = trim(htmlsafechars($_POST["password"]));
    if (!$username || !$password) {
        stderr("{$lang['text_error']}", "{$lang['text_please']}");
    }
    ($res = sql_query("SELECT id, secret, passhash FROM users WHERE username=".sqlesc($username)."")) || sqlerr(__FILE__, __LINE__);
    if ($res->num_rows != 1) {
        stderr("{$lang['text_error']}", "{$lang['text_bad']}");
    }
    $arr = $res->fetch_assoc();
    $wantpasshash = make_passhash($arr['secret'], md5($password));
    if ($arr['passhash'] != $wantpasshash) {
        stderr("{$lang['text_error']}", "{$lang['text_bad']}");
    }
    $userid = (int)$arr['id'];
    ($res = sql_query(account_delete($userid))) || sqlerr(__FILE__, __LINE__);
    //$res = sql_query("DELETE FROM users WHERE id=" . sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
    if ($mysqli->affected_rows !== false) {
        $cache->delete($cache_keys['my_userid'].$userid);
        $cache->delete($cache_keys['user'].$userid);
        write_log("User: $username Was deleted by {$CURUSER['username']}");
        stderr("{$lang['stderr_success']}", "{$lang['text_success']}");
    } else {
        stderr($lang['text_error'], $lang['text_unable']);
    }
}
$HTMLOUT = "<script type='text/javascript'>
function deleteConfirm(){
    var result = confirm('Are you sure to delete user?');
    if(result){
        return true;
    }else{
        return false;
    }
}
</script><div class='row'><div class='col-md-12'>
    <h1>{$lang['text_delete']}</h1>
    <form method='post' action='staffpanel.php?tool=delacct&amp;action=delacct' onsubmit='return deleteConfirm();'>
    <table class='table table-bordered'>
      <tr>
        <td class='rowhead'>{$lang['table_username']}</td>
        <td><input size='40' name='username'></td>
      </tr>
      <tr>
        <td class='rowhead'>{$lang['table_password']}</td>
        <td><input type='password' size='40' name='password'></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' class='btn btn-default' value='{$lang['btn_delete']}'></td>
      </tr>
    </table>
    </form></div></div><br>";
echo stdhead("{$lang['stdhead_delete']}").$HTMLOUT.stdfoot();
?>
