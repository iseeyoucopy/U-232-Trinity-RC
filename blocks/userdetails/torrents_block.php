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
($r = sql_query("SELECT 
    t.id, 
    t.name, 
    t.seeders, 
    t.leechers, 
    c.name AS cname, 
    c.image 
    FROM torrents t 
    LEFT JOIN categories c 
    ON t.category = c.id 
    WHERE t.owner = ".sqlesc($id)." 
    ORDER BY t.name")) || sqlerr(__FILE__, __LINE__);
if ($r->num_rows > 0) {
    $torrents = '';
    $torrents .= '<div class="divTable">'.'
        <div class="divTableHeading">'.'  
                <div class="divTableCell">'.$lang['userdetails_type'].'</div>
                <div class="divTableCell">'.$lang['userdetails_name'].'</div>
                <div class="divTableCell">'.$lang['userdetails_seeders'].'</div>
                <div class="divTableCell">'.$lang['userdetails_leechers'].'</div>
                <div class="divTableCell">Edit</div>
        </div>';
    while ($a = $r->fetch_assoc()) {
        $cat = "<img src={$TRINITY20['pic_base_url']}/caticons/{$CURUSER['categorie_icon']}/".htmlspecialchars($a['image'])." title=".htmlspecialchars($a['cname'])." alt=".htmlspecialchars($a['cname']).">";
        $torrents .= '
        <div class="divTableBody">
            <div class="divTableRow">
                <div class="divTableCell">'.$cat.'</div>
                <div class="divTableCell"><a href="details.php?id='.(int)$a['id'].'&amp;hit=1"><strong>'.htmlspecialchars($a['name']).'</strong></a></div>
                <div class="divTableCell">'.(int)$a['seeders'].'</div>
                <div class="divTableCell">'.(int)$a['leechers'].'</div>';
        if (($CURUSER["id"] != $id && $CURUSER["class"] < UC_STAFF)) {
            $torrents .= '<div class="divTableCell"><a href="edit.php?id='.(int)$a['id'].'"><i class="fas fa-edit" title="edit"></i></a></div>.';
        }
        $torrents .= '</div>
        </div>';
    }
    $torrents .= '</div>';
}
if (XBT_TRACKER == true) {
    ($res_tb = sql_query("SELECT 
                        x.tid, 
                        x.uploaded, 
                        x.downloaded, 
                        x.active, 
                        x.left, 
                        t.added, 
                        t.name as torrentname, 
                        t.size, 
                        t.category, 
                        t.seeders, 
                        t.leechers, 
                        c.name as catname, 
                        c.image 
                        FROM xbt_peers x 
                        LEFT JOIN torrents t 
                        ON x.tid = t.id 
                        LEFT JOIN categories c 
                        ON t.category = c.id 
                        WHERE x.uid=".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    while ($arr = $res_tb->fetch_assoc()) {
        if ($arr['left'] == '0') {
            $seeding[] = $arr;
        } else {
            $leeching[] = $arr;
        }
    }
} else {
    ($res_tb = sql_query(
        "SELECT p.torrent, 
                p.uploaded, 
                p.downloaded, 
                p.seeder, 
                t.added, 
                t.name as torrentname, 
                t.size, 
                t.category, 
                t.seeders, 
                t.leechers, 
                c.name as catname, 
                c.image FROM peers p 
                LEFT JOIN torrents t 
                ON p.torrent = t.id 
                LEFT JOIN categories c 
                ON t.category = c.id 
                WHERE p.userid=".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    while ($arr = $res_tb->fetch_assoc()) {
        if ($arr['seeder'] == 'yes') {
            $seeding[] = $arr;
        } else {
            $leeching[] = $arr;
        }
    }
}
function maketable($res_tb)
{
    global $TRINITY20, $lang, $CURUSER;

    $htmlout = '';
    foreach ($res_tb as $arr) {
        if ($arr["downloaded"] > 0) {
            $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
            $ratio = "<font color='".get_ratio_color($ratio)."'>$ratio</font>";
        } elseif ($arr["uploaded"] > 0) {
            $ratio = "{$lang['userdetails_inf']}";
        } else {
            $ratio = "---";
        }
        $catimage = "{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/{$arr['image']}";
        $catname = "&nbsp;&nbsp;".htmlspecialchars($arr["catname"]);
        $catimage = "<img src=\"".htmlspecialchars($catimage)."\" title=\"$catname\" alt=\"$catname\" width='42' height='42' />";
        $size = str_replace(" ", "<br />", mksize($arr["size"]));
        $uploaded = str_replace(" ", "<br />", mksize($arr["uploaded"]));
        $downloaded = str_replace(" ", "<br />", mksize($arr["downloaded"]));
        $seeders = number_format($arr["seeders"]);
        $leechers = number_format($arr["leechers"]);
        $XBT_or_PHP = (XBT_TRACKER == true ? $arr['tid'] : $arr['torrent']);
        $htmlout .= '
        <div class="divTable">'.'
            <div class="divTableHeading">'.'  
                <div class="divTableCell">'.$lang['userdetails_type'].'</div>
                <div class="divTableCell">'.$lang['userdetails_name'].'</div>
                <div class="divTableCell">'.$lang['userdetails_size'].'</div>
                <div class="divTableCell">'.$lang['userdetails_se'].'</div>
                <div class="divTableCell">'.$lang['userdetails_le'].'</div>
                <div class="divTableCell">'.$lang['userdetails_upl'].'</div>
                <div class="divTableCell">'.($TRINITY20['ratio_free'] ? "" : $lang['userdetails_downl']).' </div>
                <div class="divTableCell">'.$lang['userdetails_ratio'].'</div>
            </div>
            <div class="divTableBody">
                <div class="divTableRow">
                    <div class="divTableCell">'.$catimage.'</div>
                    <div class="divTableCell"><a href="details.php?id='.(int)$XBT_or_PHP.'&amp;hit=1"><b>'.htmlspecialchars($arr['torrentname']).'</b></a>
                    <div class="divTableCell">'.$size.'</div>
                    <div class="divTableCell">'.$seeders.'</div>
                    <div class="divTableCell">'.$leechers.'</div>
                    <div class="divTableCell">'.$uploaded.'</div>
                    <div class="divTableCell">'.($TRINITY20['ratio_free'] ? "" : $downloaded).'</div>
                    <div class="divTableCell">'.$ratio.'</div>
                </div>
            </div>
        </div>';
    }
    return $htmlout;
}

if ($user['opt1'] & user_options::HIDECUR || $CURUSER['id'] == $id || $CURUSER['class'] >= UC_STAFF) {
    if (isset($torrents)) {
        $HTMLOUT .= '<div class="reveal" id="uploadedt" data-reveal>'.$torrents.'
    <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <a class="small button" data-open="uploadedt">'.$lang['userdetails_uploaded_t'].'</a>';
    }
    if (isset($seeding)) {
        $HTMLOUT .= '<div class="reveal" id="currentSeed" data-reveal>
            '.maketable($seeding).'
            <button class="close-button" data-close aria-label="Close modal" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
<a class="small button" data-open="currentSeed">'.$lang['userdetails_cur_seed'].'</a>';
    }
    if (isset($leeching)) {
        $HTMLOUT .= '<div class="reveal" id="currentLeech" data-reveal>
  '.maketable($leeching).'
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<a class="small button" data-open="currentLeech">'.$lang['userdetails_cur_leech'].'</a>';
    }
}
//==End
// End Class
// End File
