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
    set_time_limit(0);
    ignore_user_abort(1);
    //==Irc idle mod - pdq
    $res = sql_query("SELECT id, seedbonus, irctotal FROM users WHERE onirc = 'yes'") or sqlerr(__FILE__, __LINE__);
    if (mysqli_num_rows($res) > 0) {
        while ($arr = mysqli_fetch_assoc($res)) {
            $users_buffer[] = '(' . $arr['id'] . ',0.225,' . $INSTALLER09['autoclean_interval'] . ')'; // .250 karma
            //$users_buffer[] = '('.$arr['id'].',15728640,'.$INSTALLER09['autoclean_interval'].')'; // 15 mb
            $update['seedbonus'] = ($arr['seedbonus'] + 0.225);
            $update['irctotal'] = ($arr['irctotal'] + $INSTALLER09['autoclean_interval']);
            $cache->begin_transaction('user' . $arr['id']);
            $cache->update_row(false, array(
                'irctotal' => $update['irctotal']
            ));
            $cache->commit_transaction($INSTALLER09['expires']['user_cache']);
            $cache->begin_transaction('user_stats' . $arr['id']);
            $cache->update_row(false, array(
                'seedbonus' => $update['seedbonus']
            ));
            $cache->commit_transaction($INSTALLER09['expires']['user_stats']);
            $cache->begin_transaction('userstats_' . $arr['id']);
            $cache->update_row(false, array(
                'seedbonus' => $update['seedbonus']
            ));
            $cache->commit_transaction($INSTALLER09['expires']['u_stats']);
        }
        $count = count($users_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO users (id,seedbonus,irctotal) VALUES " . implode(', ', $users_buffer) . " ON DUPLICATE key UPDATE seedbonus=seedbonus+values(seedbonus),irctotal=irctotal+values(irctotal)") or sqlerr(__FILE__, __LINE__);
            //sql_query("INSERT INTO users (id,uploaded,irctotal) VALUES ".implode(', ',$users_buffer)." ON DUPLICATE key UPDATE uploaded=uploaded+values(uploaded),irctotal=irctotal+values(irctotal)") or sqlerr(__FILE__,__LINE__);
            write_log("Cleanup " . $count . " users idling on IRC");
        }
        unset($users_buffer, $update, $count);
    }
    //== End
    if ($queries > 0) write_log("Irc clean-------------------- Irc cleanup Complete using $queries queries --------------------");
    if (false !== mysqli_affected_rows($GLOBALS["___mysqli_ston"])) {
        $data['clean_desc'] = mysqli_affected_rows($GLOBALS["___mysqli_ston"]) . " Users updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
