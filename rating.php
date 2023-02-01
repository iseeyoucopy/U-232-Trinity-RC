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
//made by putyn @ tbade.net Monday morning :]
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'user_functions.php');
dbconn();
$lang = array_merge(load_language('global'));
$id = isset($_GET["id"]) ? 0 + $_GET["id"] : 0;
$rate = isset($_GET["rate"]) ? 0 + $_GET["rate"] : 0;
$uid = $CURUSER["id"];
$ajax = isset($_GET["ajax"]) && $_GET["ajax"] == 1;
$what = isset($_GET["what"]) && $_GET["what"] == "torrent" ? "torrent" : "topic";
$ref = $_GET["ref"] ?? ($what == "torrent" ? "details.php" : "forums/view.php");
$completeres = sql_query("SELECT * FROM ".(XBT_TRACKER == true ? "xbt_files_users" : "snatched")." WHERE ".(XBT_TRACKER == true ? "completedtime !=0" : "complete_date !=0")." AND ".(XBT_TRACKER == true ? "uid" : "userid")." = ".$CURUSER['id']." AND ".(XBT_TRACKER == true ? "fid" : "torrentid")." = ".$id);
$completecount = $completeres->num_rows;
if ($what == 'torrent' && $completecount == 0) {
    stderr("Failed", "You must have downloaded this torrent in order to rate it. ");
}
if ($id > 0 && $rate >= 1 && $rate <= 5) {
    if (sql_query("INSERT INTO rating(".$what.",rating,user) VALUES (".sqlesc($id).",".sqlesc($rate).",".sqlesc($uid).")")) {
        $table = ($what == "torrent" ? "torrents" : "topics");
        sql_query("UPDATE ".$table." SET num_ratings = num_ratings + 1, rating_sum = rating_sum+".sqlesc($rate)." WHERE id = ".sqlesc($id));
        $cache->delete($cache_keys['rating'].$what.'_'.$id.'_'.$CURUSER['id']);
        if ($what == "torrent") {
            ($f_r = sql_query("SELECT num_ratings, rating_sum FROM torrents WHERE id = ".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
            $r_f = $f_r->fetch_assoc();
            $update['num_ratings'] = ($r_f['num_ratings'] + 1);
            $update['rating_sum'] = ($r_f['rating_sum'] + $rate);
            $cache->update_row($cache_keys['torrent_details'].$id, [
                'num_ratings' => $update['num_ratings'],
                'rating_sum' => $update['rating_sum'],
            ], $TRINITY20['expires']['torrent_details']);
        }
        if ($TRINITY20['seedbonus_on'] == 1) {
            //===add karma
            $amount = ($what == 'torrent' ? $TRINITY20['bonus_per_rating'] : $TRINITY20['bonus_per_topic']);
            sql_query("UPDATE users SET seedbonus = seedbonus+".sqlesc($amount)." WHERE id = ".sqlesc($CURUSER['id'])) || sqlerr(__FILE__, __LINE__);
            $update['seedbonus'] = ($CURUSER['seedbonus'] + $amount);
            $cache->update_row($cache_keys['user_stats'].$CURUSER["id"], [
                'seedbonus' => $update['seedbonus'],
            ], $TRINITY20['expires']['u_stats']);
            $cache->update_row($cache_keys['user_statss'].$CURUSER["id"], [
                'seedbonus' => $update['seedbonus'],
            ], $TRINITY20['expires']['user_stats']);
            //===end
        }
        if ($ajax) {
            ($qy = sql_query("SELECT sum(r.rating) as sum, count(r.rating) as count, r2.rating as rate FROM rating as r LEFT JOIN rating AS r2 ON (r2.".$what." = ".sqlesc($id)." AND r2.user = ".sqlesc($uid).") WHERE r.".$what." = ".sqlesc($id)." GROUP BY r.".sqlesc($what))) || sqlerr(__FILE__,
                __LINE__);
            $a = $qy->fetch_assoc();
            echo "<ul class=\"star-rating\" title=\"Your rated this ".$what." ".htmlsafechars($a["rate"])." star".(htmlsafechars($a["rate"]) > 1 ? "s" : "")."\"  ><li style=\"width: ".(round((($a["sum"] / $a["count"]) * 20),
                    2))."%;\" class=\"current-rating\" />.</ul>";
        } else {
            header("Refresh: 2; url=".$ref);
            stderr("Success", "Your rate has been added, wait while redirecting! ");
        }
    } elseif ($mysqli->errno && $ajax) {
        echo "You already rated this ".$what."";
    } elseif ($mysqli->error && $ajax) {
        print("You cant rate twice, Err - ".$mysqli->error);
    } else {
        stderr("Err", "You cant rate twice, Err - ".$mysqli->error);
    }
}
?>
