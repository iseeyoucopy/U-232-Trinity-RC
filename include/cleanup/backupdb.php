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
function tables($no_data = "")
{
    global $TRINITY20;
    if (!empty($no_data)) {
    }
    $no_data = explode("|", $no_data);
    ($r = sql_query("SHOW TABLES")) || sqlerr(__FILE__, __LINE__);
    while ($a = $r->fetch_assoc()) {
        $temp[] = $a;
    }
    foreach ($temp as $k => $tname) {
        $tn = $tname["Tables_in_{$TRINITY20['mysql_db']}"];
        if (in_array($tn, $no_data)) {
            continue;
        }
        $tables[] = $tn;
    }
    return implode(" ", $tables);
}

function docleanup($data)
{
    global $TRINITY20, $queries, $bdir, $mysqli;
    set_time_limit(0);
    ignore_user_abort(1);
    $mysql_host = $TRINITY20['mysql_host'];
    $mysql_user = $TRINITY20['mysql_user'];
    $mysql_pass = $TRINITY20['mysql_pass'];
    $mysql_db = $TRINITY20['mysql_db'];
    $bdir = $_SERVER["DOCUMENT_ROOT"]."/include/backup";
    $c1 = "mysqldump -h ".$mysql_host." -u ".$mysql_user." -p".$mysql_pass." ".$mysql_db." -d > ".$bdir."/db_structure.sql";
    $c = "mysqldump -h ".$mysql_host." -u ".$mysql_user." -p".$mysql_pass." ".$mysql_db." ".tables("peers|messages|sitelog")." | bzip2 -cq9 > ".$bdir."/db_".date("m_d_y",
            TIME_NOW).".sql.bz2";
    system($c1);
    system($c);
    $files = glob($bdir."/db_*");
    foreach ($files as $file) {
        if ((TIME_NOW - filemtime($file)) > 3 * 86400) {
            unlink($file);
        }
    }
    $ext = "db_".date("m_d_y", TIME_NOW).".sql.bz2";
    sql_query("INSERT INTO dbbackup (name, added, userid) VALUES (".sqlesc($ext).", ".TIME_NOW.", ".$TRINITY20['site']['owner'].")") || sqlerr(__FILE__,
        __LINE__);
    if ($queries > 0) {
        write_log("Auto-dbbackup----------------------Auto Back Up Complete using $queries queries---------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows." items deleted/updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
