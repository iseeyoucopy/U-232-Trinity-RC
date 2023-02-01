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
function check_banned_emails($email)
{
    global $lang;
    $expl = explode("@", $email);
    $wildemail = "*@".$expl[1];
    /* Ban emails by x0r @tbdev.net */
    ($res = sql_query("SELECT id, comment FROM bannedemails WHERE email = ".sqlesc($email)." OR email = ".sqlesc($wildemail))) || sqlerr(__FILE__,
        __LINE__);
    if ($arr = $res->fetch_assoc()) {
        stderr("{$lang['takesignup_user_error']}", "{$lang['takesignup_bannedmail']}".htmlsafechars($arr['comment']));
    }
}

?>
