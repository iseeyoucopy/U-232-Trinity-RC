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
require_once(INCL_DIR.'password_functions.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('ad_adduser'));
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $insert = [
        'username' => '',
        'email' => '',
        'passhash' => '',
        'secret' => '',
        'birthday' => '',
        'pin_code' => '',
        'hash3' => '',
        'status' => 'confirmed',
        'added' => TIME_NOW,
        'last_access' => TIME_NOW,
    ];
    if (isset($_POST['username']) && strlen($_POST['username']) >= 5) {
        $insert['username'] = $_POST['username'];
    } else {
        stderr($lang['std_err'], $lang['err_username']);
    }

    if (isset($_POST['email']) && validemail($_POST['email'])) {
        $insert['email'] = htmlsafechars($_POST['email']);
    } else {
        stderr($lang['std_err'], $lang['err_email']);
    }

    if (isset($_POST['birthday'])) {
        $insert['birthday'] = $_POST['birthday'];
    } else {
        stderr($lang['std_err'], $lang['err_birthday']);
    }

    if (isset($_POST['pincode'])) {
        $insert['pin_code'] = $_POST['pincode'];
    } else {
        stderr($lang['std_err'], $lang['err_pincode']);
    }

    $added = TIME_NOW;
    $secret = mksecret();
    $hash1 = t_Hash($insert['email'], $insert['username'], $added);
    $hash2 = t_Hash($insert['birthday'], $secret, $insert['pin_code']);
    $hash3 = t_Hash($insert['birthday'], $insert['username'], $insert['email']);
    $insert['hash3'] = $hash3;
    $insert['secret'] = $secret;
    if (isset($_POST['password']) && isset($_POST['password2']) && strlen($_POST['password']) > 6 && $_POST['password'] == $_POST['password2']) {
        $insert['passhash'] = make_passhash($hash1, hash("ripemd160", $_POST['password']), $hash2);
    } else {
        stderr($lang['std_err'], $lang['err_password']);
    }
    if (sql_query(sprintf('INSERT INTO users (username, email, passhash, secret, birthday, pin_code, hash3, status, added, last_access) VALUES (%s)',
        implode(', ', array_map('sqlesc', $insert))))) {

        $user_id = $mysqli->insert_id;
        write_log("User account ".(int)$user_id." (".htmlsafechars($insert['username']).") was created by {$CURUSER['username']}");
        stderr($lang['std_success'], sprintf($lang['text_user_added'], $user_id));
    } else {
        if ($mysqli->errno) {
            ($res = sql_query(sprintf('SELECT id FROM users WHERE username = %s', sqlesc($insert['username'])))) || sqlerr(__FILE__, __LINE__);
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
$HTMLOUT = '<div class="card">
    <div class="card-divider">'.$lang['std_adduser'].'</div>
    <form method="post" action="staffpanel.php?tool=adduser&amp;action=adduser" data-abide novalidate>
        <div data-abide-error class="sr-only">
            There are some errors in your form.
        </div>
        <div class="card-section">
            <div class="grid-x grid-padding-x">
                <div class="small-3 cell hide-for-small-only">
                    <label for="usr-label" class="text-right middle">
                        <strong>'.$lang['text_username'].'</strong>
                    </label>
                </div>
                <div class="small-9 cell">
                    <input type="text" name="username" id="usr-label" placeholder="Please enter your username" required>
                </div>
            </div>
            <div class="grid-x grid-padding-x">
                <div class="small-3 cell hide-for-small-only">
                    <label for="pass-label" class="text-right middle">
                        <strong>'.$lang['text_password'].'</strong>
                    </label>
                </div>
                <div class="small-9 cell">
                    <input type="password" name="password" id="pass-label" placeholder="Please enter your password" required>
                </div>
            </div>
            <div class="grid-x grid-padding-x">
                <div class="small-3 cell hide-for-small-only">
                    <label for="pass2-label" class="text-right middle">
                        <strong>'.$lang['text_password2'].'</strong>
                    </label>
                </div>
                <div class="small-9 cell">
                    <input type="password" name="password2" id="pass2-label" placeholder="Please re-enter your password" required>
                </div>
            </div>
            <div class="grid-x grid-padding-x">
                <div class="small-3 cell hide-for-small-only">
                    <label for="email-label" class="text-right middle">
                        <strong>'.$lang['text_email'].'</strong>
                    </label>
                </div>
                <div class="small-9 cell">
                    <input type="text" name="email" id="email-label" placeholder="Please enter your email address" required>
                </div>
            </div>
            <div class="grid-x grid-padding-x">
                <div class="small-3 cell hide-for-small-only">
                    <label for="bday-label" class="text-right middle">
                        <strong>'.$lang['text_birthday'].'</strong>
                    </label>
                </div>
                <div class="small-9 cell">
                    <input type="date" name="birthday" id="bday-label" required>
                </div>
            </div>
            <div class="grid-x grid-padding-x">
                <div class="small-3 cell hide-for-small-only">
                    <label for="pin-label" class="text-right middle">
                        <strong>'.$lang['text_pin_code'].'</strong>
                    </label>
                </div>
                <div class="small-9 cell">
                    <input type="number" name="pincode" id="pin-label" placeholder="Please choose a PIN number" required>
                </div>
            </div>
            <div class="grid-x grid-padding-x">
            <div class="cell small-offset-3 small-4 medium-offset-4 large-offset-4">
                <input class="button float-center expanded warning" type="submit" value="'.$lang['btn_okay'].'">
            </div>
            </div>
        </div>
    </form>
</div>';
echo stdhead($lang['std_adduser']).$HTMLOUT.stdfoot();
?>
