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
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('ajax_status'));
function url2short($x)
{
    preg_match_all('/((http|https)\:\/\/[^()<>\s]+)/i', $x, $t);
    if (isset($t[0])) {
        foreach ($t[0] as $l) {
            if (strpos($l, 'is.gd')) {
                continue;
            }
            $shorturls[1][] = file_get_contents('http://is.gd/api.php?longurl='.urlencode($l));
            $shorturls[0][] = $l;
        }
        if (isset($shorturls)) {
            $x = str_replace($shorturls[0], $shorturls[1], $x);
        }
    }
    return $x;
}

function jsonmsg($arr)
{
    global $cache, $CURUSER, $cache_keys;
    $cache->delete($cache_keys['userstatus'].$CURUSER['id']);
    $cache->delete($cache_keys['user_status'].$CURUSER['id']);
    return json_encode([
        'msg' => $arr[0],
        'status' => $arr[1],
    ], JSON_THROW_ON_ERROR);
}

$vdo = [
    'edit' => 1,
    'delete' => 1,
    'new' => 1,
];
$do = isset($_POST['action']) && isset($vdo[$_POST['action']]) ? $_POST['action'] : '';
$id = isset($_POST['id']) ? 0 + $_POST['id'] : '';
$ss = isset($_POST['ss']) && !empty($_POST['ss']) ? $_POST['ss'] : '';
switch ($do) {
    case 'edit':
        if (!empty($ss)) {
            if (sql_query('UPDATE ustatus SET last_status = '.sqlesc(url2short($ss)).', last_update = '.TIME_NOW.' WHERE userid ='.sqlesc($CURUSER['id']))) {
                $return = jsonmsg([
                    $ss,
                    true,
                ]);
            } else {
                $return = jsonmsg([
                    $lang['ajaxstatus_err'].$mysqli->error,
                    false,
                ]);
            }
        } else {
            $return = jsonmsg([
                $lang['ajaxstatus_err1'],
                false,
            ]);
        }
        break;

    case 'delete':
        $status_history = unserialize($CURUSER['archive']);
        if (isset($status_history[$id])) {
            unset($status_history[$id]);
            if (sql_query('UPDATE ustatus SET archive = '.sqlesc(serialize($status_history)).' WHERE userid = '.sqlesc($CURUSER['id']))) {
                $return = jsonmsg([
                    'ok',
                    true,
                ]);
            } else {
                $return = jsonmsg([
                    $lang['ajaxstatus_err2'],
                    false,
                ]);
            }
        } else {
            $return = jsonmsg([
                $lang['ajaxstatus_err3'],
                false,
            ]);
        }
        break;

    case 'new':
        $status_archive = ((isset($CURUSER['archive']) && is_array(unserialize($CURUSER['archive']))) ? unserialize($CURUSER['archive']) : []);
        if (!empty($CURUSER['last_status'])) {
            $status_archive[] = [
                'status' => $CURUSER['last_status'],
                'date' => $CURUSER['last_update'],
            ];
        }
        if (sql_query('INSERT INTO ustatus(userid,last_status,last_update,archive) VALUES('.sqlesc($CURUSER['id']).','.sqlesc(url2short($ss)).','.TIME_NOW.','.sqlesc(serialize($status_archive)).') ON DUPLICATE KEY UPDATE last_status=values(last_status),last_update=values(last_update),archive=values(archive)')) {
            $return = jsonmsg([
                '<h2>'.$lang['ajaxstatus_successfully'].'</h2>',
                true,
            ]);
        } else {
            $return = jsonmsg([
                $lang['ajaxstatus_err'].$mysqli->error,
                false,
            ]);
        }
        break;

    default:
        $return = jsonmsg([
            $lang['ajaxstatus_err4'],
            false,
        ]);
}
echo $return;
?>
