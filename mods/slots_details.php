<?php
//==Memcached freeslots made by pdq updated 2020 by iseeyoucopy
$slot = make_freeslots($CURUSER['id'], 'fllslot_');
$torrent['addedfree'] = $torrent['addedup'] = $free_slot = $double_slot = '';
if (!empty($slot)) foreach ($slot as $sl) {
    if ($sl['torrentid'] == $id && $sl['free'] == 'yes') {
        $free_slot = 1;
        $torrent['addedfree'] = $sl['addedfree'];
    }
    if ($sl['torrentid'] == $id && $sl['doubleup'] == 'yes') {
        $double_slot = 1;
        $torrent['addedup'] = $sl['addedup'];
    }
    if ($free_slot && $double_slot) break;
}
$torrent['addup'] = get_date($torrent['addedup'], 'DATE');
$torrent['addfree'] = get_date($torrent['addedfree'], 'DATE');
$torrent['idk'] = (TIME_NOW + 14 * 86400);
$torrent['freeimg'] = '<img src="' . $TRINITY20['pic_base_url'] . 'freedownload.gif" alt="" />';
$torrent['doubleimg'] = '<img src="' . $TRINITY20['pic_base_url'] . 'doubleseed.gif" alt="" />';
$torrent['free_color'] = 'alert';
$torrent['silver_color'] = 'secondary';
//== Display when freeleech and double slot will expire
$info_freeslot = '' . $torrent['freeimg'] . $lang['details_bal1_free1'] . get_date($torrent['idk'], 'DATE');
$info_doubleslot = $torrent['doubleimg'] . $lang['details_bal2_free1'] . get_date($torrent['idk'], 'DATE');
$info_slots = 
//== Display freeslots links
$double_torrent = "<a href='download.php?torrent={$id}&amp;slot=double'>{$info_doubleslot}</a>";
$double_torrent_zip = "<a href='download.php?torrent={$id}&amp;slot=double&amp;zip=1'>{$info_doubleslot}</a>";
$double_torrent_text = "<a href='download.php?torrent={$id}&amp;slot=double&amp;text=1'>{$info_doubleslot}</a>";
$d_free_torrent = "<a href='download.php?torrent={$id}&amp;slot=free'>{$info_freeslot}</a>";
$d_free_torrent_zip = "<a href='download.php?torrent={$id}&amp;slot=free&amp;zip=1'>{$info_freeslot}</a>";
$d_free_torrent_text = "<a href='download.php?torrent={$id}&amp;slot=free&amp;text=1'>{$info_freeslot}</a>";
if ($free_slot && !$double_slot) {
	$HTMLOUT.= '<div class="callout">
        ' . $torrent['freeimg'] . ' 
		<b><font color="' . $torrent['free_color'] . '">'.$lang['details_add_slots2'].'</font></b>'.$lang['details_add_slots3'].'' . $torrent['addfree'] . '</div>';
        $freeslot = ($CURUSER['freeslots'] >= 1 ? "{$double_torrent}" : "");
        $freeslot_zip = ($CURUSER['freeslots'] >= 1 ? "{$double_torrent_zip}" : "");
        $freeslot_text = ($CURUSER['freeslots'] >= 1 ? "{$double_torrent_text}" : "");
    } elseif (!$free_slot && $double_slot) {
        $HTMLOUT.= '<div class="callout"</div>' . $torrent['doubleimg'] . ' <b><font color="' . $torrent['free_color'] . '">'.$lang['details_add_slots8'].'</font></b>'.$lang['details_add_slots3'].'' . $torrent['addup'] . '</div>';
        $freeslot = ($CURUSER['freeslots'] >= 1 ? "{$d_free_torrent}" : "");
        $freeslot_zip = ($CURUSER['freeslots'] >= 1 ? "{$d_free_torrent_zip}" : "");
        $freeslot_text = ($CURUSER['freeslots'] >= 1 ? "{$d_free_torrent_text}" : "");
    }
	elseif ($free_slot && $double_slot) {
        $HTMLOUT.= '<div class="callout">
		<p>
        ' . $torrent['freeimg'] . ' ' . $torrent['doubleimg'] . ' <b><font color="' . $torrent['free_color'] . '">'.$lang['details_add_slots9'].'</font></b>'.$lang['details_add_slots10'].'' . $torrent['addfree'] . ''.$lang['details_add_slots11'].'' . $torrent['addup'] . '</p></div>';
        $freeslot = $freeslot_zip = $freeslot_text = '';
    }
	else {
    $freeslot = "".($CURUSER['freeslots'] >= 1 ? "{$d_free_torrent} <br> {$double_torrent}" : "")."";
	$freeslot_zip = ($CURUSER['freeslots'] >= 1 ? "{$d_free_torrent_zip} <br> {$double_torrent_zip}" : "");
    $freeslot_text = ($CURUSER['freeslots'] >= 1 ? "{$d_free_torrent_text} <br> {$double_torrent_text}" : "");
    }
?>