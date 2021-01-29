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
    $HTMLOUT.= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
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
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'password_functions.php');
require_once (CLASS_DIR . 'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('ad_adduser'));
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $insert = array(
        'username' => '',
        'email' => '',
        'passhash' => '',
		'birthday' => '',
		'pin_code' => '',
		'hash3' => '',
        'status' => 'confirmed',
        'added' => TIME_NOW,
        'last_access' => TIME_NOW
    );
    if (isset($_POST['username']) && strlen($_POST['username']) >= 5) 
	    $insert['username'] = $_POST['username'];
    else 
	    stderr($lang['std_err'], $lang['err_username']);
    
    if (isset($_POST['email']) && validemail($_POST['email'])) 
	    $insert['email'] = htmlsafechars($_POST['email']);
    else 
	    stderr($lang['std_err'], $lang['err_email']);
		
	if (isset($_POST['birthday'])) 
	    $insert['birthday'] = $_POST['birthday'];
    else 
	    stderr($lang['std_err'], $lang['err_birthday']);
		
	if (isset($_POST['pincode'])) 
	    $insert['pin_code'] = $_POST['pincode'];
    else 
	    stderr($lang['std_err'], $lang['err_pincode']);
		
	$added = TIME_NOW;
	$secret = mksecret();
    $hash1 = t_Hash($insert['email'], $insert['username'], $added);
    $hash2 = t_Hash($insert['birthday'], $secret, $insert['pin_code']);
	$hash3 = t_Hash($insert['birthday'], $insert['username'], $insert['email']);
	$insert['hash3'] = $hash3;
	
    if (isset($_POST['password']) && isset($_POST['password2']) && strlen($_POST['password']) > 6 && $_POST['password'] == $_POST['password2']) {
        $insert['passhash'] = make_passhash($hash1, hash("ripemd160", $_POST['password']), $hash2);
    } else stderr($lang['std_err'], $lang['err_password']);
    if (sql_query(sprintf('INSERT INTO users (username, email, passhash, birthday, pin_code, status, added, last_access, hash3) VALUES (%s)', join(', ', array_map('sqlesc', $insert))))) {
        $user_id = $mysqli->insert_id;
		write_log("User account " . (int)$user_id . " (" . htmlsafechars($insert['username']) . ") was created by {$CURUSER['username']}");
        stderr($lang['std_success'], sprintf($lang['text_user_added'], $user_id));
    } else {
        if ($mysqli->errno) {
            $res = sql_query(sprintf('SELECT id FROM users WHERE username = %s', sqlesc($insert['username']))) or sqlerr(__FILE__, __LINE__);
            if ($res->num_rows) {
                $arr = $res->fetch_assoc();
                header(sprintf('refresh:3; url=userdetails.php?id=%d', (int)$arr['id']));
            }
            stderr($lang['std_err'], $lang['err_already_exists']);
        }
        stderr($lang['std_err'], sprintf($lang['err_mysql_err'], $mysqli->error));
    }
    die;
}
$HTMLOUT = '<div class="row"><div class="col-md-12">
  <h1>' . $lang['std_adduser'] . '</h1><br />
  <form method="post" action="staffpanel.php?tool=adduser&amp;action=adduser">
  <table class="table table-bordered">
  <tr><td class="rowhead">' . $lang['text_username'] . '</td><td><input type="text" name="username" size="40" /></td></tr>
  <tr><td class="rowhead">' . $lang['text_password'] . '</td><td><input type="password" name="password" size="40" /></td></tr>
  <tr><td class="rowhead">' . $lang['text_password2'] . '</td><td><input type="password" name="password2" size="40" /></td></tr>
  <tr><td class="rowhead">' . $lang['text_email'] . '</td><td><input type="text" name="email" size="40" /></td></tr>
  <tr><td class="rowhead">' . $lang['text_birthday'] . '</td><td><input type="text" name="birthday" size="40" /></td></tr>
  <tr><td class="rowhead">' . $lang['text_pin_code'] . '</td><td><input type="text" name="pincode" size="40" /></td></tr>
  <tr><td colspan="2" align="center"><input type="submit" value="' . $lang['btn_okay'] . '" class="btn" /></td></tr>
  </table>
  </form></div></div>';
echo stdhead($lang['std_adduser']) . $HTMLOUT . stdfoot();
?>
