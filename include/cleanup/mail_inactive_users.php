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
function docleanup($data)
{
    global $TRINITY20, $queries, $cache;
    set_time_limit(1200);
    ignore_user_abort(1);
    //== Mail to inactive user accounts ==
    $secs = 350 * 86400;
    $dt = (TIME_NOW - $secs);
    $maxclass = UC_STAFF;
    $res = sql_query("SELECT id, username, parked, status, last_access, mail_notified, last_notified, email, added FROM users WHERE enabled='yes' AND parked='no' AND status='confirmed' AND class < $maxclass AND last_access < $dt AND mail_notified='no' AND last_notified='0' ORDER BY last_access ASC") or sqlerr(__FILE__, __LINE__);
    if (mysqli_num_rows($res) > 0) {
        while ($arr = mysqli_fetch_assoc($res)){
            $userid = $arr["id"];
            $username = $arr["username"];
            $email = $arr["email"];
	    $added = get_date($arr["added"], 'DATE');
            $last_access = get_date($arr["last_access"], 'DATE');
	    $subject = "Your account at {$TRINITY20['site_name']} !";
            $body = "Hey
            Your account at {$TRINITY20['site_name']} has been marked as inactive and will be deleted within 21 days. If you wish to remain a member at {$TRINITY20['site_name']} please login.\n
            Your username is : $username\n
            And was created : $added\n
            Last accessed : $last_access\n
            Login at : {$TRINITY20['baseurl']}/login.php\n
            If you have forgotten your password you can retrieve it at : {$TRINITY20['baseurl']}/resetpw.php\n
            Welcome back! {$TRINITY20['baseurl']}";
	    $headers = 'From: ' . $TRINITY20['site_email'] . "\r\n" . 'Reply-To:' . $TRINITY20['site_email'] . "\r\n" . 'X-Mailer: PHP/' . phpversion();
            mail($email, $subject, $body, $headers);  
        }
            
    }
    sql_query("UPDATE users SET mail_notified = 'yes', last_notified = ".TIME_NOW ." WHERE enabled='yes' AND parked='no' AND status='confirmed' AND class < $maxclass AND last_access < $dt AND mail_notified='no' AND last_notified='0' ORDER BY last_access ASC") or sqlerr(__FILE__, __LINE__);
    if ($queries > 0) write_log("Mail Inactive -------------------- Mail Inactive Users Complete using $queries queries--------------------");
    if (false !== mysqli_affected_rows($GLOBALS["___mysqli_ston"])) {
        $data['clean_desc'] = mysqli_affected_rows($GLOBALS["___mysqli_ston"]) . " users mailed.";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>