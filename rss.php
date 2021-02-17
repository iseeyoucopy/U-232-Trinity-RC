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
dbconn();
$torrent_pass = (isset($_GET["torrent_pass"]) ? htmlsafechars($_GET["torrent_pass"]) : '');
$feed = (isset($_GET["type"]) && $_GET['type'] == 'dl' ? 'dl' : 'web');
$cats = ($_GET["cats"] ?? '');
if ($cats) {
    $validate_cats = explode(',', $cats);
    $cats = implode(', ', array_map('intval', $validate_cats));
    $cats = implode(', ', array_map('sqlesc', $validate_cats));
}
if (!empty($torrent_pass)) {
    if (strlen($torrent_pass) != 32) {
        die("Your passkey is not long enough! Go to ".$TRINITY20['site_name']." and reset your passkey");
    }
    else {
        ($q0 = sql_query("SELECT * FROM users where torrent_pass = " . sqlesc($torrent_pass))) || sqlerr(__FILE__, __LINE__);
        if ($q0->num_rows == 0) {
            die("Your passkey is invalid! Go to ".$TRINITY20['site_name']." and reset your passkey");
        }
        else {
            $CURUSER = $q0->fetch_assoc();
        }
    }
} 
else {
    die('Your link doesn\'t have a passkey');
}
$TRINITY20['rssdescr'] = $TRINITY20['site_name'] . " some motto goes here!";
$where = empty($cats) ? '' : "t.category IN (" . $cats . ") AND ";
$where.= $CURUSER['class'] < UC_VIP ? 't.vip=\'0\' AND ' : '';
$counts = array(15, 30, 50, 100);
if (!empty($_GET["count"]) && in_array((int)$_GET["count"], $counts)) {
    $limit = 'LIMIT '.(int)$_GET['count'];
}
else {
    $limit = 'LIMIT 15';
}
header("Content-Type: application/xml");
$HTMLOUT = "<?xml version=\"1.0\" encoding=\"windows-1251\" ?>\n<rss version=\"0.91\">\n<channel>\n" . "<title>" . $TRINITY20['site_name'] . "</title>\n<link>" . $TRINITY20['baseurl'] . "</link>\n<description>" . $TRINITY20['rssdescr'] . "</description>\n" . "<language>en-usde</language>\n<copyright>Copyright � " . date('Y') . " " . $TRINITY20['site_name'] . "</copyright>\n<webMaster>" . $TRINITY20['site_email'] . "</webMaster>\n" . "<image><title>" . $TRINITY20['site_name'] . "</title>\n<url>" . $TRINITY20['baseurl'] . "/favicon.ico</url>\n<link>" . $TRINITY20['baseurl'] . "</link>\n" . "<width>16</width>\n<height>16</height>\n<description>" . $TRINITY20['rssdescr'] . "</description>\n</image>\n";
($res = sql_query('SELECT t.id,t.name,t.descr,t.size,t.category,t.seeders,t.leechers,t.added, c.name as catname FROM torrents as t LEFT JOIN categories as c ON t.category = c.id WHERE ' . $where . ' t.visible="yes" ORDER BY t.added DESC ' . $limit)) || sqlerr(__FILE__, __LINE__);
while ($a = $res->fetch_assoc()) {
    $link = $TRINITY20['baseurl'] . ($feed == "dl" ? "/download.php?torrent=" . (int)$a['id'] . '&amp;torrent_pass=' . $torrent_pass : "/details.php?id=" . (int)$a["id"] . "&amp;hit=1");
    $br = "&lt;br/&gt;";
    $HTMLOUT.= "<item><title>" . htmlsafechars($a["name"]) . "</title><link>{$link}</link><description>{$br}Category: " . htmlsafechars($a['catname']) . " {$br} Size: " . mksize((int)$a["size"]) . " {$br} Leechers: " . (int)$a["leechers"] . " {$br} Seeders: " . (int)$a["seeders"] . " {$br} Added: " . get_date($a['added'], 'DATE') . " {$br} Description: " . htmlsafechars(substr($a["descr"], 0, 450)) . " {$br}</description>\n</item>\n";
}
$HTMLOUT.= "</channel>\n</rss>\n";
echo ($HTMLOUT);
?>
