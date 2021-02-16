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
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once INCL_DIR . 'html_functions.php';
dbconn(true);
$lang = array_merge(load_language('global') , load_language('topten'));
$HTMLOUT = '';
function mysql_fetch_rowsarr($result)
{
    $i = 0;
    $keys = array_keys($result->fetch_array(MYSQLI_BOTH));
    $result->data_seek(0);
    while ($row = $result->fetch_array(MYSQLI_BOTH)) {
        foreach ($keys as $speckey) {
            $got[$i][$speckey] = $row[$speckey];
        }
        $i++;
    }
    return $got;
}
$HTMLOUT.= "<div class='article_header' style='text-align:center'><a href='topten.php'>".$lang['gl_members']."</a> | <a href='topten.php?view=t'>".$lang['gl_torrents']."</a> | <a href='topten.php?view=c'>".$lang['nav_countries']."</a></div>";
if (isset($_GET['view']) && $_GET['view'] == "t") {
    $view = strip_tags(isset($_GET["t"]));
    // Top Torrents
    $HTMLOUT.= "<div class='card'><div class='card-divider'><h2 class='text-center'>".$lang['torrent_mostact_10']."</h2><hr></div>";
    $result = sql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' GROUP BY t.id ORDER BY seeders + leechers DESC, seeders DESC, added ASC LIMIT 10");
    $counted = $result->num_rows;
    if ($counted == "10") {
        $arr = mysql_fetch_rowsarr($result);
        $tor1 = $arr[0]["name"];
        $tot1 = $arr[0]["leechers"] + $arr[0]["seeders"];
        $tor2 = $arr[1]["name"];
        $tot2 = $arr[1]["leechers"] + $arr[1]["seeders"];
        $tor3 = $arr[2]["name"];
        $tot3 = $arr[2]["leechers"] + $arr[2]["seeders"];
        $tor4 = $arr[3]["name"];
        $tot4 = $arr[3]["leechers"] + $arr[3]["seeders"];
        $tor5 = $arr[4]["name"];
        $tot5 = $arr[4]["leechers"] + $arr[4]["seeders"];
        $tor6 = $arr[5]["name"];
        $tot6 = $arr[5]["leechers"] + $arr[5]["seeders"];
        $tor7 = $arr[6]["name"];
        $tot7 = $arr[6]["leechers"] + $arr[6]["seeders"];
        $tor8 = $arr[7]["name"];
        $tot8 = $arr[7]["leechers"] + $arr[7]["seeders"];
        $tor9 = $arr[8]["name"];
        $tot9 = $arr[8]["leechers"] + $arr[8]["seeders"];
        $tor10 = $arr[9]["name"];
        $tot10 = $arr[9]["leechers"] + $arr[9]["seeders"];
        $HTMLOUT.= '';
        $HTMLOUT.= "<ul class='stats-list'>
        <li class='stats-list-positive'><ul class='stats-list'>
         <li class='stats-list-positive'>
            $tor1<span class='stats-list-label'>" . mksize($tot1) . "</span>
         </li>
          <li class='stats-list-positive'>
            $tor2<span class='stats-list-label'>" . mksize($tot2) . "</span>
         </li>
          <li class='stats-list-positive'>
            $tor3<span class='stats-list-label'>" . mksize($tot3) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor4<span class='stats-list-label'>" . mksize($tot4) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor5<span class='stats-list-label'>" . mksize($tot5) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor6<span class='stats-list-label'>" . mksize($tot6) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor7<span class='stats-list-label'>" . mksize($tot7) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor8<span class='stats-list-label'>" . mksize($tot8) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor9<span class='stats-list-label'>" . mksize($tot9) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor10<span class='stats-list-label'>" . mksize($tot10) . "</span>
         </li>
         </ul></div>";
    } else {
        $HTMLOUT.= "<h4 class='text-center'><hr>".$lang['torrent_insuff_tt']."(" . $counted . ")</h4></div>";
    }
    $HTMLOUT.= "<div class='card'><div class='card-divider'><h2 class='text-center'>".$lang['torrent_mostsna_10']."</h2><hr></div>";
    $result = sql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' GROUP BY t.id ORDER BY times_completed DESC LIMIT 10");
    $counted = $result->num_rows;
    if ($counted == "10") {
        $arr = mysql_fetch_rowsarr($result);
        $tor1 = $arr[0]["name"];
        $tot1 = $arr[0]["times_completed"];
        $tor2 = $arr[1]["name"];
        $tot2 = $arr[1]["times_completed"];
        $tor3 = $arr[2]["name"];
        $tot3 = $arr[2]["times_completed"];
        $tor4 = $arr[3]["name"];
        $tot4 = $arr[3]["times_completed"];
        $tor5 = $arr[4]["name"];
        $tot5 = $arr[4]["times_completed"];
        $tor6 = $arr[5]["name"];
        $tot6 = $arr[5]["times_completed"];
        $tor7 = $arr[6]["name"];
        $tot7 = $arr[6]["times_completed"];
        $tor8 = $arr[7]["name"];
        $tot8 = $arr[7]["times_completed"];
        $tor9 = $arr[8]["name"];
        $tot9 = $arr[8]["times_completed"];
        $tor10 = $arr[9]["name"];
        $tot10 = $arr[9]["times_completed"];
        $HTMLOUT.= "<ul class='stats-list'>
         <li class='stats-list-positive'>
            $tor1<span class='stats-list-label'>" . mksize($tot1) . "</span>
         </li>
          <li class='stats-list-positive'>
            $tor2<span class='stats-list-label'>" . mksize($tot2) . "</span>
         </li>
          <li class='stats-list-positive'>
            $tor3<span class='stats-list-label'>" . mksize($tot3) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor4<span class='stats-list-label'>" . mksize($tot4) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor5<span class='stats-list-label'>" . mksize($tot5) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor6<span class='stats-list-label'>" . mksize($tot6) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor7<span class='stats-list-label'>" . mksize($tot7) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor8<span class='stats-list-label'>" . mksize($tot8) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor9<span class='stats-list-label'>" . mksize($tot9) . "</span>
         </li>
         <li class='stats-list-positive'>
            $tor10<span class='stats-list-label'>" . mksize($tot10) . "</span>
         </li>
         </ul></div>";
    } else {
        $HTMLOUT.= "<h4 class='text-center'><hr>".$lang['torrent_insuff_tt']."(" . $counted . ")</h4></div>";
    }
    echo stdhead($lang['head_title']) . $HTMLOUT . stdfoot();
    die();
}
if (isset($_GET['view']) && $_GET['view'] == "c") {
    $view = strip_tags(isset($_GET["c"]));
    // Top Countries
    $HTMLOUT.= "<div class='card'><div class='card-divider'><h2 class='text-center'>".$lang['country_mostact_10']."</h2><hr></div>";
    $result = sql_query("SELECT name, flagpic, COUNT(users.country) as num FROM countries LEFT JOIN users ON users.country = countries.id GROUP BY name ORDER BY num DESC LIMIT 10");
    $counted = $result->num_rows;
    if ($counted == "10") {
        $arr = mysql_fetch_rowsarr($result);
        $name1 = $arr[0]["name"];
        $num1 = $arr[0]["num"];
        $name2 = $arr[1]["name"];
        $num2 = $arr[1]["num"];
        $name3 = $arr[2]["name"];
        $num3 = $arr[2]["num"];
        $name4 = $arr[3]["name"];
        $num4 = $arr[3]["num"];
        $name5 = $arr[4]["name"];
        $num5 = $arr[4]["num"];
        $name6 = $arr[5]["name"];
        $num6 = $arr[5]["num"];
        $name7 = $arr[6]["name"];
        $num7 = $arr[6]["num"];
        $name8 = $arr[7]["name"];
        $num8 = $arr[7]["num"];
        $name9 = $arr[8]["name"];
        $num9 = $arr[8]["num"];
        $name10 = $arr[9]["name"];
        $num10 = $arr[9]["num"];
        $HTMLOUT.= "<ul class='stats-list'>
         <li class='stats-list-positive'>
            $name1<span class='stats-list-label'>" . mksize($num1) . "</span>
         </li>
          <li class='stats-list-positive'>
            $name2<span class='stats-list-label'>" . mksize($num2) . "</span>
         </li>
          <li class='stats-list-positive'>
            $name3<span class='stats-list-label'>" . mksize($num3) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name4<span class='stats-list-label'>" . mksize($num4) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name5<span class='stats-list-label'>" . mksize($num5) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name6<span class='stats-list-label'>" . mksize($num6) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name7<span class='stats-list-label'>" . mksize($num7) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name8<span class='stats-list-label'>" . mksize($num8) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name9<span class='stats-list-label'>" . mksize($num9) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name10<span class='stats-list-label'>" . mksize($num10) . "</span>
         </li>
         </ul></div>";
    } else {
        $HTMLOUT.= "<h4 class='text-center'><hr>".$lang['country_insuff_ct']."(" . $counted . ")</h4></div>";
    }
    $HTMLOUT.= "<div class='card'><div class='card-divider'><h2 class='text-center'>".$lang['country_mostsna_10']."</h2><hr></div>";
    $result = sql_query("SELECT c.name, c.flagpic, sum(u.uploaded) AS ul FROM users AS u LEFT JOIN countries AS c ON u.country = c.id WHERE u.enabled = 'yes' GROUP BY c.name ORDER BY ul DESC LIMIT 10");
    $counted = $result->num_rows;
    if ($counted == "10") {
        $arr = mysql_fetch_rowsarr($result);
        $name1 = $arr[0]["name"];
        $num1 = $arr[0]["ul"];
        $name2 = $arr[1]["name"];
        $num2 = $arr[1]["ul"];
        $name3 = $arr[2]["name"];
        $num3 = $arr[2]["ul"];
        $name4 = $arr[3]["name"];
        $num4 = $arr[3]["ul"];
        $name5 = $arr[4]["name"];
        $num5 = $arr[4]["ul"];
        $name6 = $arr[5]["name"];
        $num6 = $arr[5]["ul"];
        $name7 = $arr[6]["name"];
        $num7 = $arr[6]["ul"];
        $name8 = $arr[7]["name"];
        $num8 = $arr[7]["ul"];
        $name9 = $arr[8]["name"];
        $num9 = $arr[8]["ul"];
        $name10 = $arr[9]["name"];
        $num10 = $arr[9]["ul"];
        $HTMLOUT.= "<ul class='stats-list'>
         <li class='stats-list-positive'>
            $name1<span class='stats-list-label'>" . mksize($num1) . "</span>
         </li>
          <li class='stats-list-positive'>
            $name2<span class='stats-list-label'>" . mksize($num2) . "</span>
         </li>
          <li class='stats-list-positive'>
            $name3<span class='stats-list-label'>" . mksize($num3) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name4<span class='stats-list-label'>" . mksize($num4) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name5<span class='stats-list-label'>" . mksize($num5) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name6<span class='stats-list-label'>" . mksize($num6) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name7<span class='stats-list-label'>" . mksize($num7) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name8<span class='stats-list-label'>" . mksize($num8) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name9<span class='stats-list-label'>" . mksize($num9) . "</span>
         </li>
         <li class='stats-list-positive'>
            $name10<span class='stats-list-label'>" . mksize($num10) . "</span>
         </li>
         </ul>
         </div>";
    } else {
        $HTMLOUT.= "<h4 class='text-center'><hr>".$lang['country_insuff_ct']."(" . $counted . ")</h4></div>";
    }
    echo stdhead($lang['head_title']) . $HTMLOUT . stdfoot();
    die();
}
// Default display / Top Users
$HTMLOUT.= "<div class='card'><div class='card-divider'><h2 class='text-center'>".$lang['user_mostup_10']."</h2><hr></div>";
$result = sql_query("SELECT id, username, uploaded FROM users WHERE enabled = 'yes' ORDER BY uploaded DESC LIMIT 10");
$counted = $result->num_rows;
if ($counted == "10") {
    $arr = mysql_fetch_rowsarr($result);
    $user1 = $arr[0]['username'];
    $user2 = $arr[1]['username'];
    $user3 = $arr[2]['username'];
    $user4 = $arr[3]['username'];
    $user5 = $arr[4]['username'];
    $user6 = $arr[5]['username'];
    $user7 = $arr[6]['username'];
    $user8 = $arr[7]['username'];
    $user9 = $arr[8]['username'];
    $user10 = $arr[9]['username'];
    $upped1 = $arr[0]['uploaded'];
    $upped2 = $arr[1]['uploaded'];
    $upped3 = $arr[2]['uploaded'];
    $upped4 = $arr[3]['uploaded'];
    $upped5 = $arr[4]['uploaded'];
    $upped6 = $arr[5]['uploaded'];
    $upped7 = $arr[6]['uploaded'];
    $upped8 = $arr[7]['uploaded'];
    $upped9 = $arr[8]['uploaded'];
    $upped10 = $arr[9]['uploaded'];
    $HTMLOUT.= "<ul class='stats-list'>
         <li class='stats-list-positive'>
            $user1<span class='stats-list-label'>" . mksize($upped1) . "</span>
         </li>
         <li class='stats-list-negative'>
            $user2<span class='stats-list-label'>" . mksize($upped2) . "</span>
         </li>
         <li class='stats-list-positive'>
            $user3<span class='stats-list-label'>" . mksize($upped3) . "</span>
         </li>
         <li class='stats-list-negative'>
            $user4<span class='stats-list-label'>" . mksize($upped4) . "</span>
         </li>
         <li class='stats-list-positive'>
            $user5<span class='stats-list-label'>" . mksize($upped5) . "</span>
         </li>
         <li class='stats-list-negative'>
            $user6<span class='stats-list-label'>" . mksize($upped6) . "</span>
         </li>
         <li class='stats-list-positive'>
            $user7<span class='stats-list-label'>" . mksize($upped7) . "</span>
         </li>
         <li class='stats-list-negative'>
            $user8<span class='stats-list-label'>" . mksize($upped8) . "</span>
         </li>
         <li class='stats-list-positive'>
            $user9<span class='stats-list-label'>" . mksize($upped9) . "</span>
         </li>
         <li class='stats-list-negative'>
            $user10<span class='stats-list-label'>" . mksize($upped10) . "</span>
         </li>         
         </ul>
        </div>";
} else {
    $HTMLOUT.= "<h4 class='text-center'><hr>".$lang['user_insuff_up']."(" . $counted . ")</h4></div>";
}
$HTMLOUT.= "<div class='card'><div class='card-divider'><h2 class='text-center'>".$lang['user_mostdl_10']."</h2><hr></div>";
$result = sql_query("SELECT username, downloaded FROM users WHERE enabled = 'yes' ORDER BY downloaded DESC LIMIT 10");
$counted = $result->num_rows;
if ($counted == "10") {
    $arr = mysql_fetch_rowsarr($result);
    $user1 = $arr[0]['username'];
    $user2 = $arr[1]['username'];
    $user3 = $arr[2]['username'];
    $user4 = $arr[3]['username'];
    $user5 = $arr[4]['username'];
    $user6 = $arr[5]['username'];
    $user7 = $arr[6]['username'];
    $user8 = $arr[7]['username'];
    $user9 = $arr[8]['username'];
    $user10 = $arr[9]['username'];
    $upped1 = $arr[0]['downloaded'];
    $upped2 = $arr[1]['downloaded'];
    $upped3 = $arr[2]['downloaded'];
    $upped4 = $arr[3]['downloaded'];
    $upped5 = $arr[4]['downloaded'];
    $upped6 = $arr[5]['downloaded'];
    $upped7 = $arr[6]['downloaded'];
    $upped8 = $arr[7]['downloaded'];
    $upped9 = $arr[8]['downloaded'];
    $upped10 = $arr[9]['downloaded'];
    $HTMLOUT.= "<ul class='stats-list'>
         <li class='stats-list-positive'>
            $user1<span class='stats-list-label'>" . mksize($upped1) . "</span>
         </li>
         <li class='stats-list-negative'>
            $user2<span class='stats-list-label'>" . mksize($upped2) . "</span>
         </li>
         <li class='stats-list-positive'>
            $user3<span class='stats-list-label'>" . mksize($upped3) . "</span>
         </li>
         <li class='stats-list-negative'>
            $user4<span class='stats-list-label'>" . mksize($upped4) . "</span>
         </li>
         <li class='stats-list-positive'>
            $user5<span class='stats-list-label'>" . mksize($upped5) . "</span>
         </li>
         <li class='stats-list-negative'>
            $user6<span class='stats-list-label'>" . mksize($upped6) . "</span>
         </li>
         <li class='stats-list-positive'>
            $user7<span class='stats-list-label'>" . mksize($upped7) . "</span>
         </li>
         <li class='stats-list-negative'>
            $user8<span class='stats-list-label'>" . mksize($upped8) . "</span>
         </li>
         <li class='stats-list-positive'>
            $user9<span class='stats-list-label'>" . mksize($upped9) . "</span>
         </li>
         <li class='stats-list-negative'>
            $user10<span class='stats-list-label'>" . mksize($upped10) . "</span>
         </li>         
         </ul></div>";
} else {
    $HTMLOUT.= "<h4 class='text-center'><hr>".$lang['user_insuff_dl']."(" . $counted . ")</h4></div>";
}
$HTMLOUT.= "<div class='card'><div class='card-divider'><h2 class='text-center'>".$lang['user_mostup_fst']."</h2><hr></div>";
$result = sql_query("SELECT  username, uploaded / (" . TIME_NOW . " - added) AS upspeed FROM users WHERE enabled = 'yes' ORDER BY upspeed DESC LIMIT 10");
$counted = $result->num_rows;
if ($counted == "10") {
    $arr = mysql_fetch_rowsarr($result);
    $user1 = $arr[0]['username'];
    $user2 = $arr[1]['username'];
    $user3 = $arr[2]['username'];
    $user4 = $arr[3]['username'];
    $user5 = $arr[4]['username'];
    $user6 = $arr[5]['username'];
    $user7 = $arr[6]['username'];
    $user8 = $arr[7]['username'];
    $user9 = $arr[8]['username'];
    $user10 = $arr[9]['username'];
    $upped1 = $arr[0]['upspeed'];
    $upped2 = $arr[1]['upspeed'];
    $upped3 = $arr[2]['upspeed'];
    $upped4 = $arr[3]['upspeed'];
    $upped5 = $arr[4]['upspeed'];
    $upped6 = $arr[5]['upspeed'];
    $upped7 = $arr[6]['upspeed'];
    $upped8 = $arr[7]['upspeed'];
    $upped9 = $arr[8]['upspeed'];
    $upped10 = $arr[9]['upspeed'];
    $HTMLOUT.= "<ul class='stats-list'>
         <li class='stats-list-positive'>
            $user1<span class='stats-list-label'>" . mksize($upped1) . "/s</span>
         </li>
         <li class='stats-list-negative'>
            $user2<span class='stats-list-label'>" . mksize($upped2) . "/s</span>
         </li>
         <li class='stats-list-positive'>
            $user3<span class='stats-list-label'>" . mksize($upped3) . "/s</span>
         </li>
         <li class='stats-list-negative'>
            $user4<span class='stats-list-label'>" . mksize($upped4) . "/s</span>
         </li>
         <li class='stats-list-positive'>
            $user5<span class='stats-list-label'>" . mksize($upped5) . "/s</span>
         </li>
         <li class='stats-list-negative'>
            $user6<span class='stats-list-label'>" . mksize($upped6) . "/s</span>
         </li>
         <li class='stats-list-positive'>
            $user7<span class='stats-list-label'>" . mksize($upped7) . "/s</span>
         </li>
         <li class='stats-list-negative'>
            $user8<span class='stats-list-label'>" . mksize($upped8) . "/s</span>
         </li>
         <li class='stats-list-positive'>
            $user9<span class='stats-list-label'>" . mksize($upped9) . "/s</span>
         </li>
         <li class='stats-list-negative'>
            $user10<span class='stats-list-label'>" . mksize($upped10) . "/s</span>
         </li>         
         </ul></div>";
} else {
    $HTMLOUT.= "<h4 class='text-center'><hr>".$lang['user_insuff_up']."(" . $counted . ")</h4></div>";
}
$HTMLOUT.= "<div class='card'><div class='card-divider'><h2 class='text-center'>".$lang['user_mostdl_fst']."</h2><hr></div>";
$result = sql_query("SELECT username, downloaded / (" . TIME_NOW . " - added) AS downspeed FROM users WHERE enabled = 'yes' ORDER BY downspeed DESC LIMIT 10");
$counted = $result->num_rows;
if ($counted == "10") {
    $arr = mysql_fetch_rowsarr($result);
    $user1 = $arr[0]['username'];
    $user2 = $arr[1]['username'];
    $user3 = $arr[2]['username'];
    $user4 = $arr[3]['username'];
    $user5 = $arr[4]['username'];
    $user6 = $arr[5]['username'];
    $user7 = $arr[6]['username'];
    $user8 = $arr[7]['username'];
    $user9 = $arr[8]['username'];
    $user10 = $arr[9]['username'];
    $upped1 = $arr[0]['downspeed'];
    $upped2 = $arr[1]['downspeed'];
    $upped3 = $arr[2]['downspeed'];
    $upped4 = $arr[3]['downspeed'];
    $upped5 = $arr[4]['downspeed'];
    $upped6 = $arr[5]['downspeed'];
    $upped7 = $arr[6]['downspeed'];
    $upped8 = $arr[7]['downspeed'];
    $upped9 = $arr[8]['downspeed'];
    $upped10 = $arr[9]['downspeed'];
    $HTMLOUT.= "<ul class='stats-list'>
         <li class='stats-list-positive'>
            $user1<span class='stats-list-label'>" . mksize($upped1) . "/s</span>
         </li>
         <li class='stats-list-negative'>
            $user2<span class='stats-list-label'>" . mksize($upped2) . "/s</span>
         </li>
         <li class='stats-list-positive'>
            $user3<span class='stats-list-label'>" . mksize($upped3) . "/s</span>
         </li>
         <li class='stats-list-negative'>
            $user4<span class='stats-list-label'>" . mksize($upped4) . "/s</span>
         </li>
         <li class='stats-list-positive'>
            $user5<span class='stats-list-label'>" . mksize($upped5) . "/s</span>
         </li>
         <li class='stats-list-negative'>
            $user6<span class='stats-list-label'>" . mksize($upped6) . "/s</span>
         </li>
         <li class='stats-list-positive'>
            $user7<span class='stats-list-label'>" . mksize($upped7) . "/s</span>
         </li>
         <li class='stats-list-negative'>
            $user8<span class='stats-list-label'>" . mksize($upped8) . "/s</span>
         </li>
         <li class='stats-list-positive'>
            $user9<span class='stats-list-label'>" . mksize($upped9) . "/s</span>
         </li>
         <li class='stats-list-negative'>
            $user10<span class='stats-list-label'>" . mksize($upped10) . "/s</span>
         </li>         
         </ul></div>";
} else {
    $HTMLOUT.= "<h4 class='text-center'><hr>".$lang['user_insuff_dl']."(" . $counted . ")</h4></div>";
}
echo stdhead($lang['head_title']) . $HTMLOUT . stdfoot();
?>
