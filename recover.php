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
dbconn();
// Begin the session
ini_set('session.use_trans_sid', '0');
session_start();
global $CURUSER;
if (!$CURUSER) {
    get_template();
}
$lang = array_merge(load_language('global') , load_language('recover'));
$stdhead = array(
    /** include js **/
    'js' => array(
        'jquery',
        'jquery.simpleCaptcha-0.2'
    )
);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!mkglobal('email' . ($INSTALLER09['captcha_on'] ? ":captchaSelection" : "") . '')) stderr("Oops", "Missing form data - You must fill all fields");
    if ($INSTALLER09['captcha_on']) {
        if (empty($captchaSelection) || $_SESSION['simpleCaptchaAnswer'] != $captchaSelection) {
            header('Location: recover.php');
            exit();
        }
    }
    $email = trim($_POST["email"]);
    if (!validemail($email)) stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_invalidemail']}");
    $res = sql_query("SELECT id, hintanswer FROM users WHERE email=" . sqlesc($email) . " LIMIT 1") or sqlerr(__FILE__, __LINE__);
    $arr = mysqli_fetch_assoc($res) or stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_notfound']}");
	$hintanswer = $arr['hintanswer'];
	if (strlen($hintanswer) != 32 || !ctype_xdigit($hintanswer))
    die('access denied');
    if (!mysqli_affected_rows($GLOBALS["___mysqli_ston"])) stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_dberror']}");
    //$hash = md5($sec . $email . $arr["passhash"] . $sec);
	//$to = $arr["email"];
	$subject = "{$INSTALLER09['site_name']} {$lang['email_subjreset']}";
	$body = 'Someone, hopefully you, requested a hint reminder
The request originated from '.$_SERVER["REMOTE_ADDR"].'.

Your hint answer is '.$hintanswer.'

If you did not do this please contact us at contact@u-232.servebeer.com

'. $INSTALLER09['site_name'].' Team';
    //$body = sprintf($lang['email_request'], , $_SERVER["REMOTE_ADDR"], $INSTALLER09['baseurl'], $arr["id"]) . $INSTALLER09['site_name'];
	// More headers
	$headers = "From:{$INSTALLER09['site_email']}" . "\r\n";
    mail($email, $subject, $body, $headers) or stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_nomail']}");
    stderr($lang['stderr_successhead'], $lang['stderr_confmailsent']);
} else {
    $HTMLOUT = '';
    $HTMLOUT.= "<script type='text/javascript'>
	  /*<![CDATA[*/
	  $(document).ready(function () {
	  $('#captcharec').simpleCaptcha();
    });
    /*]]>*/
      </script>
<div style='margin-left:24%;width:45%'>
<form class='form-horizontal  panel inverse' role='form' method='post' action='{$_SERVER['PHP_SELF']}'>
<h2>{$lang['recover_unamepass']}</h2>
<p>{$lang['recover_form']}</p>
<div class='form-group'><div class='col-sm-10 col-sm-offset-1'>" . ($INSTALLER09['captcha_on'] ? "</div></div>
<div class='form-group'><div class='col-sm-10 col-sm-offset-1' id='captcharec'></div></div>" : "") . "
<div class='form-group'><div class='col-sm-10 col-sm-offset-1'><input class='form-control' type='text' placeholder='{$lang['recover_regdemail']}' name='email'></div></div>
<div class='form-group'><div class='col-sm-10 col-sm-offset-5'><input type='submit' value='{$lang['recover_btn']}' class='btn btn-default active'></div></div>
</form></div>";
echo stdhead($lang['head_recover'], true, $stdhead) . $HTMLOUT . stdfoot();
}
?>
