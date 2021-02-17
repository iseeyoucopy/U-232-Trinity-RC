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
    global $TRINITY20, $queries, $cache, $mysqli, $keys;
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
    if($res_in->num_rows > 0) {
	while ($arr = $res_in->fetch_assoc()){
	    $userid = isset($arr['id']) ? (int) $arr['id'] : "";
	    sql_query(account_delete($userid)) || sqlerr(__FILE__, __LINE__);
            if ($mysqli->affected_rows !== false) {
	        $cache->delete($keys['my_userid'] . $userid);
                $cache->delete('user' . $userid);
	    }
	}
    }
    if ($queries > 0) {
        write_log("Inactive Clean -------------------- Inactive Clean Complete using $queries queries--------------------");
    }
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>