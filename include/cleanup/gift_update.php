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
    //===Reset Xmas gifts Bigjoos/pdq:)
    $res = sql_query("SELECT id, modcomment FROM users WHERE gotgift='yes'") or sqlerr(__FILE__, __LINE__);
    $users_buffer = array();
    if (mysqli_num_rows($res) > 0) {
        while ($arr = $res->fetch_assoc()) {
            $users_buffer[] = '(' . $arr['id'] . ', \'no\')';
            $cache->update_row('user' . $arr['id'], [
                'gotgift' => 'no'
            ], $TRINITY20['expires']['user_cache']);
            $cache->update_row($keys['my_userid'] . $arr['id'], [
                'gotgift' => 'no'
            ], $TRINITY20['expires']['curuser']);
        }
        $count = count($users_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO users (id, gotgift) VALUES " . implode(', ', $users_buffer) . " ON DUPLICATE key UPDATE gotgift=values(gotgift)") or sqlerr(__FILE__, __LINE__);
            write_log("Cleanup - Reset " . $count . " members Xmas gift");
        }
        unset($users_buffer, $count);
    }
    //==End
    if ($queries > 0) write_log("Xmas gift reset Clean -------------------- Xmas gift reset cleanup Complete using $queries queries --------------------");
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
