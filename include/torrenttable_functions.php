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
function linkcolor($num)
{
    if (!$num) return "red";
    return "pink";
}
function readMore($text, $char, $link)
{
    return (strlen($text) > $char ? substr(htmlsafechars($text) , 0, $char - 1) . "...<br /><a href='$link'>Read more...</a>" : htmlsafechars($text));
}
function torrenttable($res, $variant = "index")
{
    global $TRINITY20, $CURUSER, $lang, $free, $cache, $keys;
    require_once (INCL_DIR . 'bbcode_functions.php');
    require_once (CLASS_DIR . 'class_user_options_2.php');
    $htmlout = $prevdate = $nuked = $free_slot = $freetorrent = $uploader = $alltags = $free_color = $slots_check = $double_slot = $private = $newgenre = $newbutton = $oldlink = $char = $description = $type = $sort = $row = $youtube = '';
    $count_get = $wait = 0;
    if ($CURUSER["class"] < UC_VIP && $TRINITY20['wait_times'] == 1)
    {
      $gigs = $CURUSER["uploaded"] / (1024*1024*1024);
      $ratio = (($CURUSER["downloaded"] > 0) ? ($CURUSER["uploaded"] / $CURUSER["downloaded"]) : 1);
      if ($ratio < 0.5 || $gigs < 5) $wait = 48;
      elseif ($ratio < 0.65 || $gigs < 6.5) $wait = 24;
      elseif ($ratio < 0.8 || $gigs < 8) $wait = 12;
      elseif ($ratio < 0.95 || $gigs < 9.5) $wait = 6;
      else $wait = 0;
    }

    /** ALL FREE/DOUBLE **/
    foreach ($free as $fl) {
        switch ($fl['modifier']) {
        case 1:
            $free_display = '[Free]';
            break;
        case 2:
            $free_display = '[Double]';
            break;
        case 3:
            $free_display = '[Free and Double]';
            break;
        case 4:
            $free_display = '[Silver]';
            break;
        }
        //$slot = make_freeslots($CURUSER['id'], 'fllslot_');
        $book = make_bookmarks($CURUSER['id'], $keys['bookmark_key']);
        $all_free_tag = ($fl['modifier'] != 0 && ($fl['expires'] > TIME_NOW || $fl['expires'] == 1) ? '
            <div class="grid-x grid-padding-x">
            <div class="column small-3 float-center">
            <table><thead><tr><th class="text-center"><b>Sitewide : ' . $free_display . '</b></th></tr></thead> 
            <tbody><tr><td class="text-center">' . ($fl['expires'] != 1 ? 'Expires: ' . get_date($fl['expires'], 'DATE') . '<br />
            (' . mkprettytime($fl['expires'] - TIME_NOW) . ' to go)' : 'Unlimited').'</td></tr></tbody></table></div></div>' : '');
    }
    $oldlink = array();
    foreach ($_GET as $key => $var) {
        if (in_array($key, array(
            'sort',
            'type'
        ))) continue;
        if (is_array($var)) {
            foreach ($var as $s_var) $oldlink[] = sprintf('%s=%s', urlencode($key) . '%5B%5D', urlencode($s_var));
        } else
            $oldlink[] = sprintf('%s=%s', urlencode($key), urlencode($var));
    }
    $oldlink = !empty($oldlink) ? join('&amp;', array_map('htmlsafechars', $oldlink)) .'&amp;' : '';   
    $links = array(
        'link1',
        'link2',
        'link3',
        'link4',
        'link5',
        'link6',
        'link7',
        'link8',
        'link9',
	'link10'
    );
    $i = 1;
    foreach ($links as $link) {
        if (isset($_GET['sort']) && $_GET['sort'] == $i) $$link = (isset($_GET['type']) && $_GET['type'] == 'desc') ? 'asc' : 'desc';
        else $$link = 'desc';
        $i++;
    }
    $htmlout.= "
    {$all_free_tag}
    <div class='table-scroll'>
    <table class='striped'>
    <thead>
    <tr>
    <td class='text-center' style='padding-right: 2px;padding-left: 2px;'>{$lang["torrenttable_type"]}</td>
    <td class='text-left'><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=1&amp;type={$link1}'>{$lang["torrenttable_name"]}</a></td>
    <td class='text-center'><i class='fas fa-save'></i></td>";
    $htmlout.= ($variant == 'index' ? "<td class='text-center'><a href='{$TRINITY20['baseurl']}/bookmarks.php'><i class='fas fa-bookmark'></i></a></td>" : '');
    if ($variant == "mytorrents") {
        $htmlout.= "<td class='text-center'><i class='fas fa-edit' title='{$lang["torrenttable_edit"]}'></i></td>";
        $htmlout.= "<td class='text-center'>{$lang["torrenttable_visible"]}</td>";
    }
    $htmlout.= "<td class='text-center'><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=3&amp;type={$link3}'><i class='fas fa-comments'></i></a></td>
    <td class='text-center'><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=4&amp;type={$link4}'><i class='fas fa-stopwatch'></i></a></td>
    ".($TRINITY20['wait_times'] == 1 && $CURUSER['class'] < UC_VIP ? "<td class='text-center'><i class='fas fa-clock' title='Wait Time'></td>" : "")."
    <td class='text-center'><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=5&amp;type={$link5}'><i class='fas fa-chart-pie'></i></a></td>
    <td class='text-center'><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=6&amp;type={$link6}'><i class='fa fa-download'></i></a></td>
    <td class='text-center'><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=7&amp;type={$link7}'><i class='fas fa-arrow-up' style='color:green'></i></a></td>
    <td class='text-center'><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=8&amp;type={$link8}'><i class='fas fa-arrow-down' style='color:red'></i></a></td>";
    //if ($variant == 'index') $htmlout.= "<td class='text-left'><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=9&amp;type={$link9}'>{$lang["torrenttable_uppedby"]}</a></td>";
    if ($CURUSER['class'] >= UC_STAFF && $variant == "index")  {
    $htmlout .= "<td class='text-center'>Tools</td>";
    }
	$htmlout .= "</tr></thead><tbody>";
    $categories = genrelist();
    foreach ($categories as $key => $value) $change[$value['id']] = array(
        'id' => $value['id'],
        'name' => $value['name'],
        'image' => $value['image'],
        'min_class' => $value['min_class']
    );
    while ($row = mysqli_fetch_assoc($res)) {
    //==
		if ($CURUSER['opt2'] & user_options_2::SPLIT && $row['sticky'] == 'no' && $variant == 'index') {
            $TRINITY20['time_use_relative'] = 0;
            if (get_date($row['added'], 'DATE') == $prevdate) $cleandate = '';
            else $htmlout.= "<thead><tr><th class='text-left' colspan='".(($CURUSER['class'] >=UC_STAFF) || ($CURUSER['class'] < UC_VIP) ? 13 : 12) ."'><b>{$lang['torrenttable_upped']} ".get_date($row['added'], 'DATE')."</b></th></tr></thead>";
            $prevdate = get_date($row['added'], 'DATE');
        }
        $row['cat_name'] = htmlsafechars($change[$row['category']]['name']);
        $row['cat_pic'] = htmlsafechars($change[$row['category']]['image']);
        $row['min_class'] = htmlsafechars($change[$row['category']]['min_class']);
        /** Freeslot/doubleslot in Use **/
        $id = (int)$row["id"];
        //foreach ($slot as $sl) $slots_check = ($sl['torrentid'] == $id && $sl['free'] == 'yes' OR $sl['doubleup'] == 'yes');
        $htmlout.= '<tr>';
        $htmlout.= "<td class='text-center' style='padding-top: 2px;padding-right: 2px;padding-bottom: 2px;padding-left: 2px;'><div>";
        if (isset($row["cat_name"])) {
            $htmlout.= "<a href='browse.php?cat=" . (int)$row['category'] . "'>";
            if (isset($row["cat_pic"]) && $row["cat_pic"] != "") $htmlout.= "<img border='0' src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/{$row['cat_pic']}' alt='{$row['cat_name']}' />";
            else {
                $htmlout.= htmlsafechars($row["cat_name"]);
            }
            $htmlout.= "</a>";
        } else {
            $htmlout.= "-";
        }
        $htmlout.= "</div></td>";

        //$smalldescr = (!empty($row['description']) ? "<br /><i>[" . htmlsafechars($row['description']) . "]</i>" : "");
        //$checked = ((!empty($row['checked_by']) && $CURUSER['class'] >= UC_USER) ? "<img src='{$TRINITY20['pic_base_url']}mod.gif' width='15' border='0' alt='Checked - by " . htmlsafechars($row['checked_by']) . "' title='Checked - by " . htmlsafechars($row['checked_by']) . "' />&nbsp;" : "");
        //$poster = empty($row["poster"]) ? "<img src=\'{$TRINITY20['pic_base_url']}noposter.png\' width=\'150\' height=\'220\' border=\'0\' alt=\'Poster\' title=\'poster\' />" : "<img src=\'" . htmlsafechars($row['poster']) . "\' width=\'150\' height=\'220\' border=\'0\' alt=\'Poster\' title=\'poster\' />";
        //$rating = empty($row["rating"]) ? "No votes yet":"".ratingpic($row["rating"])."";
        //$youtube = (!empty($row['youtube']) ? "<a href='" . htmlsafechars($row['youtube']) . "' target='_blank'><i class='fab fa-youtube' style='color:red' title='Youtube Trailer'></i></a>&nbsp;" : "");
        //if (isset($row["descr"])) $descr = str_replace("\"", "&quot;", readMore($row["descr"], 350, "details.php?id=" . (int)$row["id"] . "&amp;hit=1"));
        //$descr = str_replace('&', '&amp;', $descr);
        //$bump = ($row['bump'] == "yes" ? "<img src='{$TRINITY20['pic_base_url']}up.gif' width='12px' alt='Re-Animated torrent' title='This torrent was ReAnimated!' />&nbsp;" : "");
        //$nuked = ($row["nuked"] == "yes" ? "<i class='fas fa-radiation-alt' style='background:yellow;color:black;border-radius:50%;' title='Reason: " . htmlsafechars($row["nukereason"]) . "'></i>&nbsp;" : "");
        //$release_group = ($row['release_group'] == "scene" ? "<img src='{$TRINITY20['pic_base_url']}scene.gif' title='Scene' alt='Scene' style='border:none' />&nbsp;" : ($row['release_group'] == "p2p" ? "<img src='{$TRINITY20['pic_base_url']}p2p.gif' title='P2P' alt='P2P' />&nbsp;" : ""));
        /* slots mod */
        /*if (!empty($slot)) foreach ($slot as $sl) {
            if ($sl['torrentid'] == $id && $sl['free'] == 'yes') $free_slot = 1;
            if ($sl['torrentid'] == $id && $sl['doubleup'] == 'yes') $double_slot = 1;
            if ($free_slot && $double_slot) break;
        }
        $free_slot = ($free_slot == 1 ? '<br /><img src="' . $TRINITY20['pic_base_url'] . 'freedownload.gif" width="12px" alt="Free Slot" title="Free Slot in Use" /><small>Free Slot</small>&nbsp;' : '');
        $double_slot = ($double_slot == 1 ? '<img src="' . $TRINITY20['pic_base_url'] . 'doubleseed.gif" width="12px" alt="Double Upload Slot" title="Double Upload Slot in Use" />&nbsp;<small>Double Slot</small>' : '');
        */

        /*Genres and Tags*/
        $newgenre = '';
        if (!empty($row['newgenre'])) {
            $newgenre = array();
            $row['newgenre'] = explode(',', $row['newgenre']);
            foreach ($row['newgenre'] as $foo) $newgenre[] = '<a href="browse.php?search=' . trim(strtolower($foo)) . '&amp;searchin=genre&amp;incldead=0">' . $foo . '</a>';
            $newgenre = '&nbsp;•&nbsp;&nbsp;<i class="fa fa-tag"></i>&nbsp;<i>' . join(', ', $newgenre) . '</i>';
        }
        $tags = '';
        if (!empty($row['tags'])) {
            $tags = array();
            $row['tags'] = explode(',', $row['tags']);
            foreach ($row['tags'] as $boo) $tags[] = '<a href="browse.php?search=' . trim(strtolower($boo)) . '&amp;searchin=tags&amp;incldead=0">' . $boo . '</a>';
            $tags = '<i>' . join(', ', $tags) . '</i>';
        }
        $alltags = '';
        $alltags = "{$newgenre}". (!empty($newgenre) ? "&comma;&nbsp;{$tags}" : (!empty($tags) ? "&nbsp;•&nbsp;&nbsp;<i class='fa fa-tag'></i>&nbsp;{$tags}" : ""));
        /*vip torrent*/
        $viponly = ($row["vip"] == 1 ? "<i class='fas fa-star' style='color:yellow' title='Vip Torrent'></i>&nbsp;" : "");        
        /*freeleech Torrent*/
        if (XBT_TRACKER == true)
        $freetorrent = ($row["freetorrent"] >= 1 ? "<span class='label warning' style='margin-left: 6px;padding: 2px 4px;border-radius:2px;'>FREELEECH</span>&nbsp;" : "");
        else
        $freetorrent = ($row["free"] != 0 ? "<span class='label secondary' style='margin-left: 6px;padding: 2px 4px;border-radius:2px;' title='" . ($row['free'] > 1 ? "Expires: ". get_date($row['free'], 'DATE') . "&nbsp;(" . mkprettytime($row['free'] - TIME_NOW) . " to go)'>FREELEECH</span>" : "Unlimited'>FREELEECH</span>") : "");
        /** Silver Torrent **/
        $silver_tag = ($row['silver'] != 0 ? '<br /><a class="info" href="#"><b>[SILVER]</b>&nbsp;<span>' . ($row['silver'] > 1 ? 'Expires: ' . get_date($row['silver'], 'DATE') . '<br />(' . mkprettytime($row['silver'] - TIME_NOW) . ' to go)' : 'Unlimited') . '</span></a>&nbsp;' : '');
        $sticky = ($row['sticky'] == "yes" ? "&nbsp;<i class='fas fa-thumbtack' style='color:#6699ff;' title='Sticky!' data-fa-transform='rotate-30'></i>&nbsp;" : "");
        $uploader = (isset($row["username"]) ? (($row["anonymous"] == "yes" && $CURUSER['class'] < UC_STAFF && $row['owner'] != $CURUSER['id']) ? "<i>" . $lang['torrenttable_anon'] . "</i>" : "<a href='userdetails.php?id=" . (int)$row["owner"] . "'><b>" . htmlsafechars($row["username"]) . "</b></a>") : "<i>(" . $lang["torrenttable_unknown_uploader"] . ")</i>");
        $newbutton = ($row['added'] >= $CURUSER['last_browse'] ? "<span class='label alert' style='margin-left: 6px;padding: 2px 4px;border-radius:2px;'>new</span>" : "");
        /*print torrent*/
        $htmlout.= "<td class='text-left' style='padding-top: 0;padding-right: 0;padding-bottom: 0;padding-left: 2px;white-space: nowrap;'><div style='font-size: 14px;font-weight: 600;padding: 5px 0 2px 0;'>{$sticky}<a href='details.php?id={$id}".($variant == 'index' ? '&amp;hit=1' : '')."' title='".htmlsafechars($row["name"])."'>" . CutName(htmlsafechars($row["name"]), 45) . "</a>$newbutton$freetorrent</div>
        <div style='font-size: 12px;color: #978f8f;'><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=2&amp;type={$link2}'><i class='fa fa-user-circle'></i></a>&nbsp;{$uploader}&nbsp;in&nbsp;{$row['cat_name']}{$alltags}</div></td>";
        /*Edit Link*/
        $htmlout.= ($variant == "mytorrents" ? "<td class='text-center'><a href='download.php?torrent={$id}'><i class='fas fa-save'></i></a></td><td class='text-center'><a href='edit.php?id={$id}'><i class='fas fa-edit' title='{$lang["torrenttable_edit"]}'></i></a></td>" : "");
        /*Download Link*/
        $htmlout.= ($variant == "index" ? "<td class='text-center' style='padding-top: 0;padding-right: 0;padding-bottom: 0;padding-left: 0;'><a href='download.php?torrent={$id}'><i class='fas fa-save'></i></a></td>" : "");
        /*visible?*/
        $htmlout.= ($variant == "mytorrents" ? ("<td class='text-center'>". ($row['visible'] == "no" ? "{$lang["torrenttable_not_visible"]}" : "{$lang["torrenttable_isvisible"]}")."</td>") : "");
        /*Bookmarks*/
        $booked = '';
        if (!empty($book)) foreach ($book as $bk) {
            if ($bk['torrentid'] == $id) $booked = 1;
        }
        $rm_status = (!$booked ? ' style="display:none;"' : ' style="display:inline;"');
        $bm_status = ($booked ? ' style="display:none;"' : ' style="display:inline;"');
        $bookmark = '<span id="bookmark' . $id . '"' . $bm_status . '>
                    <a href="bookmarks.php?torrent=' . $id . '&amp;action=add" class="bookmark" name="' . $id . '">
                    <span title="Bookmark it" class="add_bookmark_b"><i class="fas fa-check-circle" style="color:green"></i></span>
                    </a>
                    </span>
                    <span id="remove' . $id . '"' . $rm_status . '>
                    <a href="bookmarks.php?torrent=' . $id . '&amp;action=delete" class="remove" name="' . $id . '">
                    <span title="Remove Bookmark" class="remove_bookmark_b"><i class="fas fa-times" style="color:red"></i>
                    </span>
                    </a>
                    </span>';
        $htmlout.= ($variant == "index" ? "<td class='text-center' style='padding-top: 0;padding-right: 0;padding-bottom: 0;padding-left: 0;'>{$bookmark}</td>" : "");
        /*num files*/
        //$htmlout.= "<td class='text-center'>".($row["type"] == "single" ? (int)$row["numfiles"] : "<b><a href='filelist.php?id=$id'>" . (int)$row["numfiles"] . "</a></b>")."</td>";
        /*comments*/
        $htmlout.= "<td class='text-center' style='padding-top: 0;padding-right: 0;padding-bottom: 0;padding-left: 0;'><b>".($row['comments'] == 0 ? "0" : "<a href='details.php?id=$id&amp;".($variant == "index" ? "hit=1" : "")."&amp;#startcomments'>" . (int)$row["comments"] . "</a>")."</b></td>";
        /*Added*/
        $htmlout.= "<td class='text-center' style='padding-top: 0;padding-right: 0;padding-bottom: 0;padding-left: 0;'>" . str_replace(",", "<br />", get_date( $row['added'],'TTABLE')) . "</td>";
        if ($CURUSER["class"] < UC_VIP && $TRINITY20['wait_times'] == 1){
        /*wait times*/
        $elapsed = floor((TIME_NOW - $row["added"]) / 3600);
        if ($elapsed < $wait) {
        $ttl = $elapsed == 1 ? "<br />".$lang["torrenttable_hour_singular"] : "<br />".$lang["torrenttable_hour_plural"];
        $color = dechex(floor(127*($wait - $elapsed)/48 + 128)*65536);
        $htmlout .= "<td class='text-center' style='padding-top: 0;padding-right: 0;padding-bottom: 0;padding-left: 0;'><font color='$color'>" . number_format($wait - $elapsed) . "<br />".$ttl."</font></td>";
        } else
        $htmlout .= "<td class='text-center' style='padding-top: 0;padding-right: 0;padding-bottom: 0;padding-left: 0;'>{$lang["torrenttable_wait_none"]}</td>";
        }
        /*size*/
        $htmlout.= "<td class='text-center' style='padding-top: 0;padding-right: 0;padding-bottom: 0;padding-left: 0;'>" . str_replace(" ", "&nbsp;", mksize($row["size"])) . "</td>";
        /*snatches*/
        $What_Script_S = (XBT_TRACKER == true ? 'snatches_xbt.php?id=' : 'snatches.php?id=');
        $htmlout.= "<td class='text-center' style='padding-top: 0;padding-right: 0;padding-bottom: 0;padding-left: 0;'><a href='$What_Script_S"."$id'>" . number_format($row["times_completed"]) . "</a></td>";
        /*seeders - leechers*/
        $What_Script_P = (XBT_TRACKER == true ? 'peerlist_xbt.php?id=' : 'peerlist.php?id=' );
        $ratio = ($row['leechers'] != 0 && $row['seeders'] != 0 ? $row['seeders'] / $row['leechers'] : 1);
        $htmlout.= "<td class='text-center' style='padding-top: 0;padding-right: 0;padding-bottom: 0;padding-left: 0;'><b>".($row['seeders'] !=0 ? "<a href='$What_Script_P"."$id#seeders'><font color='".get_slr_color($ratio)."'>".(int)$row["seeders"]."</font></a>" : "<font color='".get_slr_color($ratio)."'>".(int)$row["seeders"]."</font>")."</b></td>";
        $htmlout.= "<td class='text-center' style='padding-top: 0;padding-right: 0;padding-bottom: 0;padding-left: 0;'><b>".($row['leechers'] !=0 ? "<a href='$What_Script_P"."$id#leechers'><font color='" . linkcolor($row["leechers"]) . "'>".(int)$row["leechers"]."</font></a>" : "<font color='" . linkcolor($row["leechers"]) . "'>".(int)$row["leechers"]."</font>")."</b></td>";
        /*staff tools*/
        if ($CURUSER['class'] >= UC_STAFF && $variant == "index")  {
                $url = "edit.php?id=" .(int)$row["id"];
        $editlink = "a href=\"$url\" class=\"sublink\"";
        $del_link = ($CURUSER['class'] === UC_MAX ? "<a href='fastdelete.php?id=".(int)$row['id']."'>&nbsp;<i class='fas fa-trash'></i></a>" : "");
        $htmlout .= "<td class='text-center' style='padding-top: 0;padding-right: 0;padding-bottom: 0;padding-left: 0;'><$editlink><i class='fas fa-edit'></i></a>{$del_link}</td>";
        }
        /*end ttable*/
        $htmlout.= "</tr>";
    }
    $htmlout.= "</tbody></table></div>";
    return $htmlout;
}
?>
