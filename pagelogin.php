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
//== loginlink mod - stonebreath/laffin
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'user_functions.php');
require_once(INCL_DIR.'password_functions.php');
dbconn();
//== 09 failed logins thanks to pdq - Retro
function failedloginscheck()
{
    global $TRINITY20;
    $total = 0;
    $ip = getip();
    ($res = sql_query("SELECT SUM(attempts) FROM failedlogins WHERE ip=".sqlesc($ip))) || sqlerr(__FILE__, __LINE__);
    [$total] = $res->fetch_row();
    if ($total >= $TRINITY20['failedlogins']) {
        sql_query("UPDATE failedlogins SET banned = 'yes' WHERE ip=".sqlesc($ip)) || sqlerr(__FILE__, __LINE__);
        stderr("Login Locked!",
            "You have <b>Exceeded</b> the allowed maximum login attempts without successful login, therefore your ip address <b>(".htmlsafechars($ip).")</b> has been locked out for 24 hours.");
    }
}

//==End
failedloginscheck();
if (!mkglobal("qlogin") || (strlen($qlogin = $qlogin) != 96)) {
    die("n00b");
}
function bark($text = 'Username or password incorrect')
{
    global $lang, $TRINITY20, $cache;
    $sha = sha1($_SERVER['REMOTE_ADDR']);
    $flood = $cache->get($cache_keys['dictbreaker'].$sha);
    if ($flood === false) {
        $cache->set($cache_keys['dictbreaker'].$sha, 'flood_check', 20);
    } else {
        die('Minimum 8 seconds between login attempts :)');
    }
    stderr($lang['tlogin_failed'], $text);
}

$hash1 = substr($qlogin, 0, 32);
$hash2 = substr($qlogin, 32, 32);
$hash3 = substr($qlogin, 64, 32);
$hash1 .= $hash2.$hash3;
($res = sql_query("SELECT id, username, hash3, enabled FROM users WHERE hash1 = ".sqlesc($hash1)." AND class >= ".UC_STAFF." AND status = 'confirmed' LIMIT 1")) || sqlerr(__FILE__,
    __LINE__);
$row = $res->fetch_assoc();
$ip = getip();
if (!$row) {
    $added = TIME_NOW;
    ($fail_query = sql_query("SELECT COUNT(id) from failedlogins where ip=".sqlesc($ip))) || sqlerr(__FILE__, __LINE__);
    $fail = ($fail_query->fetch_row());
    if ($fail[0] == 0) {
        sql_query("INSERT INTO failedlogins (ip, added, attempts) VALUES (".sqlesc($ip).", ".sqlesc($added).", 1)") || sqlerr(__FILE__, __LINE__);
    } else {
        sql_query("UPDATE failedlogins SET attempts = attempts + 1 WHERE ip=".sqlesc($ip)) || sqlerr(__FILE__, __LINE__);
    }
    bark();
}
if ($row['enabled'] == 'no') {
    stderr("Error", "This account has been disabled.");
}
$passh = h_cook($row['hash3'], $_SERVER["REMOTE_ADDR"], $row['id']);
logincookie($row["id"], $passh);
sql_query("DELETE FROM failedlogins WHERE ip = ".sqlesc($ip)) || sqlerr(__FILE__, __LINE__);
$HTMLOUT = '';
$HTMLOUT .= "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'
'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>{$TRINITY20['site_name']} Redirecting</title>
<link rel='stylesheet' href='./templates/1/1.css' type='text/css'>
<meta http-equiv='Refresh' content='1; URL=index.php'>
</head>
<body>
<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>
<p></p>
<p align='center'><strong>Welcome Back - ".htmlsafechars($row['username']).".</strong></p><br>
<p align='center'><strong>Click <a href='index.php'>here</a> if you are not redirected automatically.</strong></p><br>
</body>
</html>";
echo $HTMLOUT;
?>
