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
    global $INSTALLER09, $queries, $cache;
    set_time_limit(1200);
    ignore_user_abort(1);
    require_once (INCL_DIR . 'function_account_delete.php');
    //== Delete inactive user accounts
    $days = 350 * 86400;
    $sec = 21 * 86400;
    $dt = (TIME_NOW - $days);
    $warn = (TIME_NOW - $sec);
    $maxclass = UC_STAFF;
    $res_in = sql_query("SELECT id, parked, status, last_access, mail_notified, last_notified FROM users WHERE parked='no' AND status='confirmed' AND class < $maxclass AND last_access < $dt AND mail_notified='yes' AND last_notified < $warn ORDER BY id ASC");
    if(mysqli_num_rows($res_in) > 0) {
	while ($arr = mysqli_fetch_assoc($res_in)){
	    $userid = isset($arr['id']) ? intval($arr['id']) : "";
	    sql_query(account_delete($userid)) or sqlerr(__FILE__, __LINE__);
            if (mysqli_affected_rows($GLOBALS["___mysqli_ston"]) !== false) {
	        $cache->delete('MyUser_' . $userid);
                $cache->delete('user' . $userid);
	    }
	}
    }
    if ($queries > 0) write_log("Inactive Clean -------------------- Inactive Clean Complete using $queries queries--------------------");
    if (false !== mysqli_affected_rows($GLOBALS["___mysqli_ston"])) {
        $data['clean_desc'] = mysqli_affected_rows($GLOBALS["___mysqli_ston"]) . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>