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
if (!defined('IN_TRINITY20_FORUM')) {
    $HTMLOUT = '';
    $HTMLOUT .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
        <head>
        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
        <title>ERROR</title>
        </head><body>
        <h1 style="text-align:center;">Error</h1>
        <p style="text-align:center;">How did you get here? silly rabbit Trix are for kids!.</p>
        </body></html>';
    echo $HTMLOUT;
    exit();
}
($res = sql_query("SELECT p.*, pa.id AS pa_id, pa.selection FROM postpolls AS p LEFT JOIN postpollanswers AS pa ON pa.pollid = p.id AND pa.userid = ".sqlesc($CURUSER['id'])." WHERE p.id=".sqlesc($pollid))) || sqlerr(__FILE__,
    __LINE__);
if ($res->num_rows > 0) {
    $arr1 = $res->fetch_assoc();
    $userid = (int)$CURUSER['id'];
    $question = htmlsafechars($arr1["question"]);
    $o = [
        $arr1["option0"],
        $arr1["option1"],
        $arr1["option2"],
        $arr1["option3"],
        $arr1["option4"],
        $arr1["option5"],
        $arr1["option6"],
        $arr1["option7"],
        $arr1["option8"],
        $arr1["option9"],
        $arr1["option10"],
        $arr1["option11"],
        $arr1["option12"],
        $arr1["option13"],
        $arr1["option14"],
        $arr1["option15"],
        $arr1["option16"],
        $arr1["option17"],
        $arr1["option18"],
        $arr1["option19"],
    ];
    $HTMLOUT .= "<table cellpadding='5' width='{$Multi_forum['configs']['forum_width']}' align='center'>
		  <tr><td class='colhead' align='left'><h2>Poll";
    if ($userid == $t_userid || $CURUSER['class'] >= UC_STAFF) {
        $HTMLOUT .= "<font class='small'> - [<a href='{$TRINITY20['baseurl']}/forums.php?action=makepoll&amp;subaction=edit&amp;pollid=".$pollid."'><b>Edit</b></a>]</font>";
        if ($CURUSER['class'] >= UC_STAFF) {
            $HTMLOUT .= "<font class='small'> - [<a href='{$TRINITY20['baseurl']}/forums.php?action=deletepoll&amp;pollid=".$pollid."'><b>Delete</b></a>]</font>";
        }
    }
    $HTMLOUT .= "</h2></td></tr>";
    $HTMLOUT .= "<tr><td align='center' class='clearalt7'>
		  <table width='55%'><tr><td class='clearalt6'><div align='center'><b>{$question}</b></div>";
    $voted = ((bool)is_valid_id($arr1['pa_id']));
    if (($locked && $CURUSER['class'] < UC_STAFF) ? true : $voted) {
        $uservote = ($arr1["selection"] != '' ? (int)$arr1["selection"] : -1);
        $res_v = sql_query("SELECT selection FROM postpollanswers WHERE pollid=".sqlesc($pollid)." AND selection < 20");
        $tvotes = $res_v->num_rows;
        $vs = $os = [];
        for ($i = 0; $i < 20; $i++) {
            $vs[$i] = 0;
        }
        while ($arr_v = $res_v->fetch_row) {
            $vs[$arr_v[0]] += 1;
        }
        reset($o);
        $oCount = count($o);
        for ($i = 0; $i < $oCount; ++$i) {
            if ($o[$i]) {
                $os[$i] = [$vs[$i], $o[$i]];
            }
        }
        function srt($a, $b)
        {
            if ($a[0] > $b[0]) {
                return -1;
            }
            if ($a[0] < $b[0]) {
                return 1;
            }
            return 0;
        }

        if ($arr1["sort"] == "yes") {
            usort($os, "srt");
        }
        $HTMLOUT .= "<br>
			  <table width='100%' style='border:none;' cellpadding='5'>";
        foreach ($os as $a) {
            if ($i === $uservote) {
                $a[1] .= " *";
            }
            $p = ($tvotes == 0 ? 0 : round($a[0] / $tvotes * 100));
            $c = ($i % 2 !== 0 ? '' : "poll");
            $p = ($tvotes == 0 ? 0 : round($a[0] / $tvotes * 100));
            $c = ($i % 2 !== 0 ? '' : "poll");
            $HTMLOUT .= "<tr>";
            $HTMLOUT .= "<td width='1%' style='padding:3px;white-space:nowrap;' class='embedded".$c."'>".htmlsafechars($a[1])."</td>
					<td width='99%' class='embedded".$c."' align='center'>
					<img src='{$TRINITY20['pic_base_url']}bar_left.gif' alt='bar_left.gif'>
					<img src='{$TRINITY20['pic_base_url']}bar.gif' alt='bar.gif'  height='9' width='".($p * 3)."'>
					<img src='{$TRINITY20['pic_base_url']}bar_right.gif'  alt='bar_right.gif'>&nbsp;".$p."%</td>
					</tr>";
        }
        $HTMLOUT .= "</table>
				  <p align='center'>Votes: <b>".number_format($tvotes)."</b></p>";
    } else {
        $HTMLOUT .= "<form method='post' action='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=".$topicid."'>
				  <input type='hidden' name='pollid' value='".$pollid."'>";
        for ($i = 0; $a = $o[$i]; ++$i) {
            $HTMLOUT .= "<input type='radio' name='choice' value='$i'>".htmlsafechars($a)."<br>
				  <br>
				  <p align='center'><input type='submit' value='Vote!'></p>";
        }
    }
    $HTMLOUT .= "</form></td></tr></table>";
    $listvotes = (isset($_GET['listvotes']));
    if ($CURUSER['class'] >= UC_ADMINISTRATOR) {
        if (!$listvotes) {
            $HTMLOUT .= "[<font class='small'><a href='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=$topicid&amp;listvotes'>List Voters</a></font>]";
        } else {
            ($res_vv = sql_query("SELECT pa.userid, u.username, u.anonymous FROM postpollanswers AS pa LEFT JOIN users AS u ON u.id = pa.userid WHERE pa.pollid=".sqlesc($pollid))) || sqlerr(__FILE__,
                __LINE__);
            $voters = '';
            while ($arr_vv = $res_vv->fetch_assoc()) {
                if (!empty($voters) && !empty($arr_vv['username'])) {
                    $voters .= ', ';
                }
                if ($arr_vv["anonymous"] == "yes") {
                    if ($CURUSER['class'] < UC_STAFF && $arr_vv["userid"] != $CURUSER["id"]) {
                        $voters = "<i>Anonymous</i>";
                    } else {
                        $voters = "<i>Anonymous</i>[<a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$arr_vv['userid']."'><b>".htmlsafechars($arr_vv['username'])."</b></a>]";
                    }
                } else {
                    $voters .= "<a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$arr_vv['userid']."'><b>".htmlsafechars($arr_vv['username'])."</b></a>";
                }
            }
            $HTMLOUT .= $voters."<br>[<font class='small'><a href='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=$topicid'>hide</a></font>]";
        }
    }
    $HTMLOUT .= "</td></tr></table>";
} else {
    $HTMLOUT .= "<br>";
    stderr('Sorry', "Poll doesn't exist");
}
$HTMLOUT .= "<br>";
