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
    global $TRINITY20, $queries, $mysqli, $keys;
    set_time_limit(0);
    ignore_user_abort(1);
    //== 09 Stats
    ($XBT_Seeder_query = sql_query("SELECT sum(seeders) AS seeders FROM torrents")) || sqlerr(__FILE__, __LINE__);
    $XBT_Seeder = $XBT_Seeder_query->fetch_assoc();
    ($XBT_Leecher_query = sql_query("SELECT sum(leechers) AS leechers FROM torrents")) || sqlerr(__FILE__, __LINE__);
    $XBT_Leecher = $XBT_Leecher_query->fetch_assoc();
    $registered = get_row_count('users');
    $unverified = get_row_count('users', "WHERE status='pending'");
    $torrents = get_row_count('torrents');
    $seeders = (XBT_TRACKER == true ? $XBT_Seeder : get_row_count('peers', "WHERE seeder='yes'"));
    $leechers = (XBT_TRACKER == true ? $XBT_Leecher : get_row_count('peers', "WHERE seeder='no'"));
    $torrentstoday = get_row_count('torrents', 'WHERE added > '.TIME_NOW.' - 86400');
    $donors = get_row_count('users', "WHERE donor ='yes'");
    $unconnectables = (XBT_TRACKER == true ? '0' : get_row_count("peers", " WHERE connectable='no'"));
    $forumposts = get_row_count("posts");
    $forumtopics = get_row_count("topics");
    $dt = TIME_NOW - 300; // Active users last 5 minutes
    $numactive = get_row_count("users", "WHERE last_access >= $dt");
    $torrentsmonth = get_row_count('torrents', 'WHERE added > '.TIME_NOW.' - 2592000');
    $gender_na = get_row_count('users', "WHERE gender='NA'");
    $gender_male = get_row_count('users', "WHERE gender='Male'");
    $gender_female = get_row_count('users', "WHERE gender='Female'");
    $powerusers = get_row_count('users', "WHERE class='".UC_POWER_USER."'");
    $disabled = get_row_count('users', "WHERE enabled='no'");
    $uploaders = get_row_count('users', "WHERE class='".UC_UPLOADER."'");
    $vips = get_row_count('users', "WHERE class='".UC_VIP."'");
    $moderators = get_row_count('users', "WHERE class='".UC_MODERATOR."'");
    $administrators = get_row_count('users', "WHERE class='".UC_ADMINISTRATOR."'");
    $sysops = get_row_count('users', "WHERE class='".UC_SYSOP."'");
    $seeders = (int)$XBT_Seeder['seeders'];
    $leechers = (int)$XBT_Leecher['leechers'];
    sql_query("UPDATE stats SET regusers = '$registered', unconusers = '$unverified', torrents = '$torrents', seeders = '$seeders', leechers = '$leechers', unconnectables = '$unconnectables', torrentstoday = '$torrentstoday', donors = '$donors', forumposts = '$forumposts', forumtopics = '$forumtopics', numactive = '$numactive', torrentsmonth = '$torrentsmonth', gender_na = '$gender_na', gender_male = '$gender_male', gender_female = '$gender_female', powerusers = '$powerusers', disabled = '$disabled', uploaders = '$uploaders', vips = '$vips', moderators = '$moderators', administrators = '$administrators', sysops = '$sysops' WHERE id = '1' LIMIT 1");
    if ($queries > 0) {
        write_log("Stats clean-------------------- Stats cleanup Complete using $queries queries --------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows." items updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
