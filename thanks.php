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
sleep(1);
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
dbconn();
loggedinorreturn();
if (!isset($CURUSER)) {
    stderr("Error", "Sorry but you cant add a thank you on your own torrent");
}
$uid = (int) $CURUSER['id'];
$tid = isset($_POST['torrentid']) ? (int) $_POST['torrentid'] : (isset($_GET['torrentid']) ? (int) $_GET['torrentid'] : 0);
$do = isset($_POST['action']) ? htmlsafechars($_POST['action']) : (isset($_GET['action']) ? htmlsafechars($_GET['action']) : 'list');
$ajax = isset($_POST['ajax']) && $_POST['ajax'] == 1;
function print_list()
{
    global $uid, $tid, $ajax;
    $target = $ajax ? '_self' : '_parent';
    ($qt = sql_query("SELECT th.userid, u.username, u.seedbonus FROM thanks as th INNER JOIN users as u ON u.id=th.userid WHERE th.torrentid=" . sqlesc($tid) . " ORDER BY u.class DESC")) || sqlerr(__FILE__, __LINE__);
    $list = [];
    $hadTh = false;
    if ($qt->num_rows > 0) {
        while ($a = $qt->fetch_assoc()) {
            $list[] = '<a href=\'userdetails.php?id=' . (int) $a['userid'] . '\' target=\'' . $target . '\'>' . htmlsafechars($a['username']) . '</a>';
            $ids[] = (int) $a['userid'];
        }
        $hadTh = in_array($uid, $ids);
    }
    if ($ajax) {
        return json_encode([
            'list' => (count($list) > 0 ? implode(', ', $list) : 'Not yet') ,
            'hadTh' => $hadTh,
            'status' => true
        ], JSON_THROW_ON_ERROR);
    }

    $form = $hadTh ? "" : "<br/><form action='thanks.php' method='post'><input type='submit' class='btn' name='submit' value='Say thanks' /><input type='hidden' name='torrentid' value='{$tid}' /><input type='hidden' name='action' value='add' /></form>";
    $out = (count($list) > 0 ? implode(', ', $list) : 'Not yet');

    return <<<IFRAME
        
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<style type='text/css'>
body { margin:0;padding:0; 
	   font-size:12px;
	   font-family:arial,sans-serif;
	   color: #FFFFFF;
}
a, a:link, a:visited {
  text-decoration: none;
  color: #FFFFFF;
  font-size:12px;
}
a:hover {
  color: #FFFFFF
  text-decoration:underline;
  
}
.btn {
background-color:#890537;
border:1px solid #000000;
color:#FFFFFF;
font-family:arial,sans-serif;
font-size:12px;
padding:1px 3px;
}
</style>
<title>::</title>
</head>
<body>
{$out}{$form}
</body>
</html>

IFRAME;
}
switch ($do) {
case 'list':
    print(print_list());
    break;

case 'add':
    if ($uid > 0 && $tid > 0) {
        $c = 'SELECT count(id) FROM thanks WHERE userid = ' . sqlesc($uid) . ' AND torrentid = ' . sqlesc($tid);
        $result = sql_query($c);
        $arr = $result->fetch_row();
        if ($arr[0] == 0) {
            if (sql_query('INSERT INTO thanks(userid,torrentid) VALUES(' . sqlesc($uid) . ',' . sqlesc($tid) . ')')) {
                echo(print_list());
            } else {
                $msg = 'There was an error with the query,contact the staff. Mysql error ' . $mysqli->error;
                echo($ajax ? json_encode([
                    'status' => false,
                    'err' => $msg
                ], JSON_THROW_ON_ERROR) : $msg);
            }
        }
    }
    header("Refresh: 0; url=details.php?id=$tid");
    if ($TRINITY20['seedbonus_on'] == 1) {
        // ===add karma
        sql_query("UPDATE users SET seedbonus = seedbonus+" . sqlesc($TRINITY20['bonus_per_thanks']) . " WHERE id =" . sqlesc($uid)) || sqlerr(__FILE__, __LINE__);
        ($sql = sql_query('SELECT seedbonus ' . 'FROM users ' . 'WHERE id = ' . sqlesc($uid))) || sqlerr(__FILE__, __LINE__);
        $User = $sql->fetch_assoc();
        $update['seedbonus'] = ($User['seedbonus'] + $TRINITY20['bonus_per_thanks']);
        //header("Refresh: 1; url=details.php?id=$id");
        $cache->update_row($keys['user_stats'] . $uid, [
            'seedbonus' => $update['seedbonus']
        ], $TRINITY20['expires']['u_stats']);
        $cache->update_row('user_stats_' . $uid, [
            'seedbonus' => $update['seedbonus']
        ], $TRINITY20['expires']['user_stats']);
        // ===end
    }
    break;
}
