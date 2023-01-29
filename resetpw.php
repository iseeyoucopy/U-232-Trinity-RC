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
require_once(INCL_DIR.'password_functions.php');
dbconn();
global $CURUSER;
if ($CURUSER) {
    header("Location: {$TRINITY20['baseurl']}/index.php");
    exit;
}
ini_set('session.use_trans_sid', '0');
session_start();
get_template();
$lang = array_merge(load_language('global'), load_language('passhint'));
$stdfoot = '';
if ($TRINITY20['captcha_on'] === true) {
    $stdfoot = [
        /** include js **/
        'js' => [
            'captcha',
            'jquery.simpleCaptcha-0.2',
        ],
    ];
}

$HTMLOUT = '';
$step = (isset($_GET["step"]) ? (int)$_GET["step"] : (isset($_POST["step"]) ? (int)$_POST["step"] : ''));
if ($step == '1') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!mkglobal('email'.($TRINITY20['captcha_on'] ? ":captchaSelection" : "").'')) {
            stderr("Oops",
                "Missing form data - You must fill all fields");
        }
        if ($TRINITY20['captcha_on'] && (empty($captchaSelection) || $_SESSION['simpleCaptchaAnswer'] != $captchaSelection)) {
            stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_error2']}");
            exit();
        }
        if (!validemail($email)) {
            stderr($lang['stderr_errorhead'], $lang['stderr_invalidemail1']);
        }
        ($check = sql_query('SELECT id, status, passhint, hintanswer FROM users WHERE email = '.sqlesc($email))) || sqlerr(__FILE__, __LINE__);
        ($assoc = $check->fetch_assoc()) || stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_notfound']}");
        if (empty($assoc['passhint']) || empty($assoc['hintanswer'])) {
            stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_error3']}");
        }
        if ($assoc['status'] != 'confirmed') {
            stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_error4']}");
        } else {
            $HTMLOUT .= "<div class='grid-container'>
				<div class='grid-x grid-padding-x align-center-middle text-center margin-top-3'>
					<div class='callout margin-top-3'>
						<img src='pic/logo.png'>
						<form role='form' title='reset_step1' method='post' action='".$_SERVER['PHP_SELF']."?step=2'>
						<h2 class='text-center text-info'><span style='font-weight: bold;'>{$lang['main_question']}</span></h2>";
            $id[1] = '/1/';
            $id[2] = '/2/';
            $id[3] = '/3/';
            $id[4] = '/4/';
            $id[5] = '/5/';
            $id[6] = '/6/';
            $question[1] = "{$lang['main_question1']}";
            $question[2] = "{$lang['main_question2']}";
            $question[3] = "{$lang['main_question3']}";
            $question[4] = "{$lang['main_question4']}";
            $question[5] = "{$lang['main_question5']}";
            $question[6] = "{$lang['main_question6']}";
            $passhint = preg_replace($id, $question, (int)$assoc['passhint']);
            $HTMLOUT .= "<div class='callout alert-callout-border warning'>{$passhint} ?
				<input type='hidden' name='id' value='".(int)$assoc['id']."' /></div>
				<input type='text' placeholder='{$lang['main_sec_answer']}' name='answer'>
				<input type='submit' value='{$lang['main_next']}' class='button float-right'>
				</form>
				</div>
				</div>
				</div>";
            echo stdhead($lang['main_header']).$HTMLOUT.stdfoot();
        }
    }
} elseif ($step == '2') {
    if (!mkglobal('id:answer')) {
        die();
    }
    ($select = sql_query('SELECT id, username, birthday, added, pin_code, hintanswer, email FROM users WHERE id = '.sqlesc($id))) || sqlerr(__FILE__,
        __LINE__);
    $fetch = $select->fetch_assoc();
    if (!$fetch) {
        stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_error5']}");
    }
    if (empty($answer)) {
        stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_error6']}");
    }
    if (($fetch['hintanswer'] != h_store($answer.$fetch['email'])) && ($fetch['hintanswer'] != hash("tiger128,4",
                "".$answer."") && ($fetch['hintanswer'] != md5($answer)))) {
        $ip = getip();
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $msg = "".htmlspecialchars($fetch['username']).", on ".get_date(TIME_NOW, '', 1,
                0).", {$lang['main_message']}"."\n\n{$lang['main_message1']} ".$ip." (".@gethostbyaddr($ip).")"."\n {$lang['main_message2']} ".$useragent."\n\n {$lang['main_message3']}\n {$lang['main_message4']}\n";
        $subject = "Failed password reset";
        sql_query('INSERT INTO messages (receiver, msg, subject, added) VALUES ('.sqlesc((int)$fetch['id']).', '.sqlesc($msg).', '.sqlesc($subject).', '.TIME_NOW.')') || sqlerr(__FILE__,
            __LINE__);
        stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_error7']}");
    } else {
        $sec = mksecret();
        $sechash = h_cook($fetch['username'], $fetch['id'], $fetch['birthday']);
        sql_query("UPDATE users SET editsecret = ".sqlesc($sec)." WHERE id = ".sqlesc($id));
        $cache->update_row($cache_keys['my_userid'].$fetch["id"], ['editsecret' => $sec], $TRINITY20['expires']['curuser']);
        $cache->update_row($cache_keys['user'].$fetch["id"], ['editsecret' => $sec], $TRINITY20['expires']['user_cache']);
        $HTMLOUT .= "<div class='grid-container'>
				<div class='grid-x grid-padding-x align-center-middle text-center margin-top-3'>
					<div class='callout margin-top-3'>
						<img src='pic/logo.png'>
<form role='form' title='reset_step2' method='post' action='?step=3'>
	<div class='callout alert-callout-border warning'>
		<p class='text-center'>{$lang['main_changepass']}</p>
	</div>
	<div class='input-group'>
		<span class='input-group-label'>
			<i class='fa fa-lock'></i>
		</span>
		<input class='input-group-field' type='password' placeholder='{$lang['main_new_pass']}' name='newpass'>
	</div>
	<div class='input-group'>
		<span class='input-group-label'>
			<i class='fa fa-lock'></i>
		</span>
		<input class='input-group-field'  type='password' placeholder='{$lang['main_new_pass_confirm']}' name='newpassagain'>
	</div> 
    <input type='submit' value='{$lang['main_changeit']}' class='button float-right'>
    <input type='hidden' name='id' value='".(int)$fetch['id']."'>
    <input type='hidden' name='hash' value='".$sechash."'></form></div></div></div>";

        echo stdhead($lang['main_header']).$HTMLOUT.stdfoot();
    }
} elseif ($step == '3') {
    if (!mkglobal('id:newpass:newpassagain:hash')) {
        die();
    }
    if (strlen($hash) != 128) {
        die('access denied');
    }
    ($select = sql_query('SELECT id, added, email, username, birthday, pin_code FROM users WHERE id = '.sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    ($fetch = $select->fetch_assoc()) || stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_error8']}");
    if (empty($newpass)) {
        stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_error9']}");
    }
    if ($newpass != $newpassagain) {
        stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_error10']}");
    }
    if (strlen($newpass) < 6) {
        stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_error11']}");
    }
    if (strlen($newpass) > 64) {
        stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_error12']}");
    }
    if ($hash != h_cook($fetch['username'], $fetch['id'], $fetch['birthday'])) {
        die('invalid hash');
    }
    $secret = mksecret();
    $hash1 = t_Hash($fetch['email'], $fetch['username'], $fetch['added']);
    $hash2 = t_Hash($fetch['birthday'], $secret, $fetch['pin_code']);
    $hash3 = t_Hash($fetch['birthday'], $fetch['username'], $fetch['email']);
    $newpassword = make_passhash($hash1, hash("ripemd160", $newpass), $hash2);
    sql_query('UPDATE users SET passhash='.sqlesc($newpassword).', secret='.sqlesc($secret).', hash3='.sqlesc($hash3).' WHERE id = '.sqlesc($id));
    $cache->update_row($cache_keys['my_userid'].$id, [
        'secret' => $secret,
        'editsecret' => '',
        'passhash' => $newpassword,
        'hash3' => $hash3,
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row($cache_keys['user'].$id, [
        'secret' => $secret,
        'editsecret' => '',
        'passhash' => $newpassword,
        'hash3' => $hash3,
    ], $TRINITY20['expires']['user_cache']);
    if (!$mysqli->affected_rows) {
        stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_error13']}");
    } else {
        header("Refresh:3; url={$TRINITY20['baseurl']}/login.php");
        stderr("{$lang['stderr_successhead']}",
            "{$lang['stderr_error14']} <a href='{$TRINITY20['baseurl']}/login.php' class='altlink'><b>{$lang['stderr_error15']}</b></a> {$lang['stderr_error16']}",
            false);
    }
} else {
    $HTMLOUT .= "".($TRINITY20['captcha_on'] ? "<script type='text/javascript'>
	  /*<![CDATA[*/
	  $(document).ready(function () {
	  $('#captchareset').simpleCaptcha();
    });
    /*]]>*/
    </script>" : "")."
<div class='grid-container'>
	<div class='grid-x grid-padding-x align-center-middle text-center margin-top-3'>
		<div class='callout margin-top-3'>
		<img src='pic/logo.png'>
		<form title='restpw' role='form' method='post' action='".$_SERVER['PHP_SELF']."?step=1'>
		<div class='input-group'>
		    <span class='input-group-label'>
				<i class='fas fa-envelope-open-text'></i>
			</span>
			<input class='input-group-field' type='text' placeholder='{$lang['main_email_add']}' name='email'>
		</div>
".($TRINITY20['captcha_on'] ? "<div class='form-group'><div class='col-sm-10 col-sm-offset-1' id='captchareset'></div></div>" : "")."
		<input type='submit' value='{$lang['main_recover']}' class='button float-right'>
</form>
</div></div></div>";
    echo stdhead($lang['main_header']).$HTMLOUT.stdfoot($stdfoot);
}
?>

