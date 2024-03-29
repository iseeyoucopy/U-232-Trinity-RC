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
//putyn's rate mod
function getRate($id, $what)
{
    global $CURUSER, $cache;
    if ($id == 0 || !in_array($what, [
            'topic',
            'torrent',
        ])) {
        return;
    }
    //== lets memcache $what fucker
    $cache_keys['rating'] = 'rating_' . $what . '_' . $id . '_' . $CURUSER['id'];
    if (($rating_cache = $cache->get($cache_keys['rating'])) === false) {
        ($qy = sql_query("SELECT sum(r.rating) as sum, count(r.rating) as count, r2.id as rated, r2.rating  FROM rating as r LEFT JOIN rating as r2 ON (r2." . $what . " = " . sqlesc($id) . " AND r2.user = " . sqlesc($CURUSER["id"]) . ") WHERE r." . $what . " = " . sqlesc($id) . " GROUP BY r." . $what)) || sqlerr(__FILE__,
            __LINE__);
        $rating_cache = $qy->fetch_assoc();
        $cache->set($cache_keys['rating'], $rating_cache, 0);
    }
    //== lets memcache $count fucker
    $cache_keys['rating_count'] = 'rating_count_' . $what . '_' . $id . '_' . $CURUSER['id'];
    if (($completecount = $cache->get($cache_keys['rating_count'])) === false) {
        $completeres = sql_query("SELECT * FROM " . (XBT_TRACKER == true ? "xbt_peers" : "snatched") . " WHERE " . (XBT_TRACKER == true ? "completedtime !=0" : "complete_date !=0") . " AND " . (XBT_TRACKER == true ? "tid" : "userid") . " = " . $CURUSER['id'] . " AND " . (XBT_TRACKER == true ? "tid" : "torrentid") . " = " . $id);
        $completecount = $completeres->num_rows;
        $cache->set($cache_keys['rating_count'], $completecount, 180);
    }
    // outputs
    $rating_count = $rating_cache["count"] ?? 0;
    $p = ($rating_count > 0 ? round((($rating_cache["sum"] / $rating_count) * 20), 2) : 0);
    $rating_r = $rating_cache["rated"] ?? '';
    if ($rating_r) {
        $rate = "<ul class=\"star-rating\" title=\"You rated this " . $what . " " . htmlsafechars($rating_cache["rating"]) . " star" . (htmlsafechars($rating_cache["rating"]) > 1 ? "s" : "") . "\"><li style=\"width: " . $p . "%;\" class=\"current-rating\">.</li></ul>";
    } elseif ($what == 'torrent') {
        $rate = "<ul class=\"star-rating\" title=\"You must download this " . $what . " in order to rate it.\"><li style=\"width: %;\" class=\"current-rating\">" . $p . ".</li></ul>";
    } else {
        $i = 1;
        $rate = "<ul class=\"star-rating\"><li style=\"width: " . $p . "%;\" class=\"current-rating\">.</li>";
        foreach ([
                     "one-star",
                     "two-stars",
                     "three-stars",
                     "four-stars",
                     "five-stars",
                 ] as $star) {
            $rate .= "<li><a href=\"rating.php?id=" . (int)$id . "&amp;rate=" . $i . "&amp;ref=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;what=" . $what . "\" class=\"" . $star . "\" onclick=\"do_rate(" . $i . "," . $id . ",'" . $what . "" . $i . " star" . ($i > 1 ? "s" : "") . " out of 5\" >$i</a></li>";
            $i++;
        }
        $rate .= "</ul>";
    }
    switch ($what) {
        case "torrent":
            $return = "<div id=\"rate_" . $id . "\">" . $rate . "</div>";
            break;
        case "topic":
            $return = "<div id=\"rate_" . $id . "\">" . $rate . "</div>";
            break;
    }
    return $return;
}

function showRate($rate_sum, $rate_count)
{
    $p = ($rate_count > 0 ? round((($rate_sum / $rate_count) * 20), 2) : 0);
    return "<ul class=\"star-rating\"><li style=\"width: " . $p . "%;\" class=\"current-rating\" >.</li></ul>";
}

//end putyn's rate mode
?>
