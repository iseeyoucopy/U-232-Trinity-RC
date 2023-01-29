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
    global $TRINITY20, $queries, $cache, $mysqli, $cache_keys;
    set_time_limit(0);
    ignore_user_abort(1);
    //=== delete from now viewing after 15 minutes
    sql_query('DELETE FROM now_viewing WHERE added < '.(TIME_NOW - 900));
    //=== fix any messed up counts
    $forums = sql_query('SELECT f.id, count( DISTINCT t.id ) AS topics, count(p.id) AS posts
                          FROM forums f
                          LEFT JOIN topics t ON f.id = t.forum_id
                          LEFT JOIN posts p ON t.id = p.topic_id
                          GROUP BY f.id');
    while ($forum = $forums->fetch_assoc()) {
        $forum['posts'] = $forum['topics'] > 0 ? $forum['posts'] : 0;
        sql_query('UPDATE forums SET post_count = '.sqlesc($forum['posts']).', topic_count = '.sqlesc($forum['topics']).' WHERE id='.sqlesc($forum['id']));
    }
    if ($queries > 0) {
        write_log("Forum clean-------------------- Forum cleanup Complete using $queries queries --------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows." items updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
