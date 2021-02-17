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
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'user_functions.php');
require_once(INCL_DIR.'html_functions.php');
require_once INCL_DIR.'pager_functions.php';
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('filelist'));
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if (!is_valid_id($id)) {
    stderr('USER ERROR', 'Bad id');
}
($res = sql_query("SELECT COUNT(id) FROM files WHERE torrent =".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
$row = $res->fetch_row();
$count = $row[0];
$perpage = 10;
$pager = pager($perpage, $count, "filelist.php?id=$id&amp;");
$HTMLOUT = '';
$HTMLOUT .= "<table class='striped'>";
$subres = sql_query("SELECT * FROM files WHERE torrent = ".sqlesc($id)." ORDER BY id ".$pager['limit']);
$HTMLOUT .= "<thead><tr><td>{$lang["filelist_type"]}</td><td>{$lang["filelist_path"]}</td><td>{$lang["filelist_size"]}</td></tr><thead>";
$counter = 0;
while ($subrow = $subres->fetch_assoc()) {
    $ext = 'Unknown';
    if (preg_match('/\\.([A-Za-z0-9]+)$/', $subrow["filename"])) {
        $ext = strtolower($ext[1]);
    }
    if (!file_exists('pic/icons/'.$ext.'.png') || !is_file('pic/icons/'.$ext.'.png')) {
        $ext = "Unknown";
    }
    $HTMLOUT .= "<tbody><tr>";
    if ($counter !== 0 && $counter % 10 == 0) {
        $HTMLOUT .= "<td><a href='#top'><img src='{$TRINITY20['pic_base_url']}/top.gif' alt='' /></a></td>";
    }
    $HTMLOUT .= "<td><img src='pic/icons/".htmlsafechars($ext).".png' alt='".htmlsafechars($ext)." file' title='".htmlsafechars($ext)." file' /></td><td>".htmlsafechars($subrow["filename"])."</td><td>".mksize($subrow["size"])."</td>";
    $HTMLOUT .= "</tr></tbody>";
    $counter++;
}
$HTMLOUT .= "</table>";
if ($count > $perpage) {
    $HTMLOUT .= $pager['pagerbottom'];
}
echo stdhead($lang["filelist_header"]).$HTMLOUT.stdfoot();
?>
