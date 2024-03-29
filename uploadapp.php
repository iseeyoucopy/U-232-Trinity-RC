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
require_once INCL_DIR.'pager_functions.php';
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('uploadapp'));
$HTMLOUT = '';
// Fill in application
if (isset($_POST["form"]) != 1) {
    ($res = sql_query("SELECT status FROM uploadapp WHERE userid = ".sqlesc($CURUSER['id']))) || sqlerr(__FILE__, __LINE__);
    $arr = $res->fetch_assoc();
    $status_arr = isset($arr['status']) ? (int)$arr['status'] : "";
    if ($CURUSER['class'] >= UC_UPLOADER) {
        stderr($lang['uploadapp_user_error'], $lang['uploadapp_alreadyup']);
    } elseif
    ($status_arr == 'pending') {
        stderr($lang['uploadapp_user_error'], $lang['uploadapp_pending']);
    } elseif
    ($status_arr == 'rejected') {
        stderr($lang['uploadapp_user_error'], $lang['uploadapp_rejected']);
    } else {
        $HTMLOUT .= "<h1 align='center'>{$lang['uploadapp_application']}</h1>
        <table class='table table-bordered'><tr><td>
        <form action='./uploadapp.php' method='post' enctype='multipart/form-data'>
        <table class='table table-bordered'>";
        $ratio = member_ratio($CURUSER['uploaded'], $CURUSER['downloaded']);
        if (XBT_TRACKER === false) {
            ($res = sql_query("SELECT connectable FROM peers WHERE userid=".sqlesc($CURUSER['id']))) || sqlerr(__FILE__, __LINE__);
        } else {
            ($res = sql_query("SELECT connectable FROM xbt_peers WHERE uid=".sqlesc($CURUSER['id']))) || sqlerr(__FILE__, __LINE__);
        }
        if ($row = $res->fetch_row()) {
            $Conn_Y = (XBT_TRACKER === true ? 1 : 'yes');
            $connect = $row[0];
            $connectable = $connect == $Conn_Y ? 'Yes' : 'No';
        } else {
            $connectable = 'Pending';
        }
        $HTMLOUT .= "<tr>
        <td class='rowhead'>{$lang['uploadapp_username']}</td>
        <td><input name='userid' type='hidden' value='".(int)$CURUSER['id']."'>".$CURUSER['username']."</td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['uploadapp_joined']}</td><td>".get_date($CURUSER['added'], '', 0, 1)."</td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['uploadapp_ratio']}</td><td>".($ratio >= 1 ? 'No' : 'Yes')."</td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['uploadapp_connectable']}</td><td><input name='connectable' type='hidden' value='$connectable'>$connectable</td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['uploadapp_upspeed']}</td><td><input type='text' name='speed' size='19'></td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['uploadapp_offer']}</td><td><textarea name='offer' cols='80' rows='1'></textarea></td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['uploadapp_why']}</td><td><textarea name='reason' cols='80' rows='2'></textarea></td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['uploadapp_uploader']}</td><td><input type='radio' name='sites' value='yes'>{$lang['uploadapp_yes']}
        <input name='sites' type='radio' value='no' checked='checked'>{$lang['uploadapp_no']}</td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['uploadapp_sites']}</td><td><textarea name='sitenames' cols='80' rows='1'></textarea></td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['uploadapp_scene']}</td><td><input type='radio' name='scene' value='yes'>{$lang['uploadapp_yes']}
	     <input name='scene' type='radio' value='no' checked='checked'>{$lang['uploadapp_no']}</td>
        </tr>
        <tr>
        <td colspan='2'>
        <br>
        &nbsp;&nbsp;{$lang['uploadapp_create']}
        <br>
        <input type='radio' name='creating' value='yes'>{$lang['uploadapp_yes']}
    	  <input name='creating' type='radio' value='no' checked='checked'>{$lang['uploadapp_no']}
        <br><br>
        &nbsp;&nbsp;{$lang['uploadapp_seeding']}
        <br>
        <input type='radio' name='seeding' value='yes'>{$lang['uploadapp_yes']}
     	  <input name='seeding' type='radio' value='no' checked='checked'>{$lang['uploadapp_no']}
        <br><br>
        <input name='form' type='hidden' value='1'>
        <div align='center'><input type='submit' name='Submit' value='{$lang['uploadapp_send']}'></div></td>
        </tr>
        </table></form>
        </td></tr></table>";
    }
    // Process application

} else {
    $app['userid'] = (int)$_POST['userid'];
    $app['connectable'] = htmlsafechars($_POST['connectable']);
    $app['speed'] = htmlsafechars($_POST['speed']);
    $app['offer'] = htmlsafechars($_POST['offer']);
    $app['reason'] = htmlsafechars($_POST['reason']);
    $app['sites'] = htmlsafechars($_POST['sites']);
    $app['sitenames'] = htmlsafechars($_POST['sitenames']);
    $app['scene'] = htmlsafechars($_POST['scene']);
    $app['creating'] = htmlsafechars($_POST['creating']);
    $app['seeding'] = htmlsafechars($_POST['seeding']);
    if (!is_valid_id($app['userid'])) {
        stderr($lang['uploadapp_error'], $lang['uploadapp_tryagain']);
    }
    if (!$app['speed']) {
        stderr($lang['uploadapp_error'], $lang['uploadapp_speedblank']);
    }
    if (!$app['offer']) {
        stderr($lang['uploadapp_error'], $lang['uploadapp_offerblank']);
    }
    if (!$app['reason']) {
        stderr($lang['uploadapp_error'], $lang['uploadapp_reasonblank']);
    }
    if ($app['sites'] == 'yes' && !$app['sitenames']) {
        stderr($lang['uploadapp_error'], $lang['uploadapp_sitesblank']);
    }
    $res = sql_query("INSERT INTO uploadapp(userid,applied,connectable,speed,offer,reason,sites,sitenames,scene,creating,seeding) VALUES(".implode(",",
            array_map("sqlesc", [
                $app['userid'],
                TIME_NOW,
                $app['connectable'],
                $app['speed'],
                $app['offer'],
                $app['reason'],
                $app['sites'],
                $app['sitenames'],
                $app['scene'],
                $app['creating'],
                $app['seeding'],
            ])).")");
    $cache->delete('new_uploadapp_');
    if (!$res) {
        if ($mysqli->errno) {
            stderr($lang['uploadapp_error'], $lang['uploadapp_twice']);
        } else {
            stderr($lang['uploadapp_error'], $lang['uploadapp_tryagain']);
        }
    } else {
        $subject = sqlesc("Uploader application");
        $msg = sqlesc("An uploader application has just been filled in by [url={$TRINITY20['baseurl']}/userdetails.php?id=".(int)$CURUSER['id']."][b]{$CURUSER['username']}[/b][/url]. Click [url={$TRINITY20['baseurl']}/staffpanel.php?tool=uploadapps&action=show][b]Here[/b][/url] to go to the uploader applications page.");
        $dt = TIME_NOW;
        ($subres = sql_query('SELECT id FROM users WHERE class = '.UC_STAFF)) || sqlerr(__FILE__, __LINE__);
        while ($arr = $subres->fetch_assoc()) {
            sql_query("INSERT INTO messages(sender, receiver, added, msg, subject, poster) VALUES(0, ".sqlesc($arr['id']).", $dt, $msg, $subject, 0)") || sqlerr(__FILE__,
                __LINE__);
        }
        $cache->delete($cache_keys['inbox_new'].$arr['id']);
        $cache->delete($cache_keys['inbox_new_sb'].$arr['id']);
        stderr($lang['uploadapp_appsent'], $lang['uploadapp_success']);
    }
}
echo stdhead('Uploader application page').$HTMLOUT.stdfoot();
?>
