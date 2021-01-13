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
    global $TRINITY20, $queries, $cache, $keys;
    set_time_limit(0);
    ignore_user_abort(1);
    //=== Free user removal by Bigjoos/pdq:)
    $res = sql_query("SELECT id, modcomment FROM users WHERE free_switch > 1 AND free_switch < " . TIME_NOW) or sqlerr(__FILE__, __LINE__);
    $msgs_buffer = array();
	$users_buffer = array();
    if (mysqli_num_rows($res) > 0) {
        $subject = "Freeleech expired.";
        $msg = "Your freeleech has expired and has been auto-removed by the system.\n";
        while ($arr = mysqli_fetch_assoc($res)) {
            $modcomment = $arr['modcomment'];
            $modcomment = get_date(TIME_NOW, 'DATE', 1) . " - Freeleech Removed By System.\n" . $modcomment;
            $modcom = sqlesc($modcomment);
			$msgs_buffer[] = "(\"0\", " . $arr['id'] . ", " . TIME_NOW . ", " . sqlesc($msg) . ", " . sqlesc($subject) . ")";
			
            $users_buffer[] = "(" . $arr['id'] . ", 0, " . $modcom . ")";
            $cache->update_row('user' . $arr['id'], [
                'free_switch' => 0
            ], $TRINITY20['expires']['user_cache']);
            $cache->update_row('user_stats_' . $arr['id'], [
                'modcomment' => $modcomment
            ], $TRINITY20['expires']['user_stats']);
            $cache->update_row($keys['my_userid'] . $arr['id'], [
                'free_switch' => 0
            ], $TRINITY20['expires']['curuser']);
            $cache->delete('inbox_new::' . $arr['id']);
            $cache->delete('inbox_new_sb::' . $arr['id']);
        }
        $count = count($users_buffer);
        if ($count > 0) {
			sql_query("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES " . implode(', ', $msgs_buffer)) or sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO users (id, free_switch, modcomment) VALUES " . implode(', ', $users_buffer) . " ON DUPLICATE key UPDATE free_switch=values(free_switch), modcomment=values(modcomment)") or sqlerr(__FILE__, __LINE__);
            write_log("Cleanup - Removed Freeleech from " . $count . " members");
        }
        unset($users_buffer, $msgs_buffer, $count);
    }
    //==End
    if ($queries > 0) write_log("Freelech clean-------------------- Freeleech cleanup Complete using $queries queries --------------------");
    if (false !== mysqli_affected_rows($GLOBALS["___mysqli_ston"])) {
        $data['clean_desc'] = mysqli_affected_rows($GLOBALS["___mysqli_ston"]) . " items updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>