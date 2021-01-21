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
    // Remove expired readposts...
    $dt = TIME_NOW - $TRINITY20["readpost_expiry"];
    sql_query('DELETE read_posts FROM read_posts LEFT JOIN posts ON read_posts.last_post_read = posts.id WHERE posts.added < '.sqlesc($dt)) or sqlerr(__FILE__, __LINE__);
    // Remove expired readposts... Multilayer
    //$dt = TIME_NOW - $TRINITY20["readpost_expiry"];
    //sql_query('DELETE readposts FROM readposts LEFT JOIN posts ON readposts.lastpostread = posts.id WHERE posts.added < '.sqlesc($dt)) or sqlerr(__FILE__, __LINE__);
    if ($queries > 0) write_log("Readpost Clean -------------------- Readpost cleanup Complete using $queries queries --------------------");
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
