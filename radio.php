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
//require_once (INCL_DIR . 'html_functions.php');

$radio_host = '127.0.0.1';
$radio_port = (int)8080;
$radio_password = 'somepassword';
$langs = [
    'CURRENTLISTENERS' => 'Current listeners: <b>%d</b>',
    'SERVERTITLE' => 'Server: <b>%s</b>',
    'SERVERURL' => 'Server url: <b>%s</b>',
    'SONGTITLE' => 'Current song: <b>%s</b>',
    'BITRATE' => 'Bitrate: <b>%s kb</b>',
    'BITRATE' => 'Bitrate: <b>%s kb</b>',
    'PEAKLISTENERS' => 'Peak listeners: <b>%d</b>',
];
function radioinfo($radio)
{
    global $langs, $TRINITY20, $cache,$cache_keys, $CURUSER, $radio_host, $radio_port, $radio_password;
    $xml = $html = $history = '';
    if ($hand = @fsockopen($radio_host, $radio_port, $errno, $errstr, 30)) {
        fwrite($hand,
            "GET /admin.cgi?pass=".$radio_password."&mode=viewxml HTTP/1.1\nUser-Agent:Mozilla/5.0 "."(Windows; U; Windows NT 6.1; en-GB; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6\n\n");
        while (!feof($hand)) {
            $xml .= fgets($hand, 1024);
        }
        preg_match_all('/\<(SERVERTITLE|SERVERURL|SONGTITLE|STREAMSTATUS|BITRATE|CURRENTLISTENERS|PEAKLISTENERS)\>(.*?)<\/\\1\>/iU', $xml, $tempdata,
            PREG_SET_ORDER);
        foreach ($tempdata as $t2) {
            $data[$t2[1]] = isset($langs[$t2[1]]) ? sprintf($langs[$t2[1]], $t2[2]) : $t2[2];
        }
        unset($tempdata);
        preg_match_all('/\<SONG>(.*?)<\/SONG\>/', $xml, $temph);
        unset($temph[0][0], $temph[1]);
        $history = [];
        foreach ($temph[0] as $temph2) {
            preg_match_all('/\<(TITLE|PLAYEDAT)>(.*?)<\/\\1\>/i', $temph2, $temph3, PREG_PATTERN_ORDER);
            $history[] = '<b>&nbsp;'.$temph3[2][1].'</b> <sub>('.get_date($temph3[2][0], 'DATE').')</sub>';
        }
        preg_match_all('/\<HOSTNAME>(.*?)<\/HOSTNAME>/', $xml, $temph);
        if ((is_countable($temph[1]) ? count($temph[1]) : 0) > 0) {
            $users_ip = implode(', ', array_map('sqlesc', $temph[1]));
        }
        if ($data['STREAMSTATUS'] == 0) {
            return 'Sorry '.$CURUSER['username'].'... : Server '.$radio_host.' is online but there is no stream';
        }

        unset($data['STREAMSTATUS']);
        $md5_current_song = md5($data['SONGTITLE']);
        $current_song = $cache->get($cache_keys['current_radio_song']);
        if ($current_song === false || $current_song != $md5_current_song) {
            autoshout(str_replace(['<', '>'], ['[', ']'],
                $data['SONGTITLE'].' playing on '.strtolower($data['SERVERTITLE']).' - '.strtolower($data['SERVERURL'])));
            $cache->set($cache_keys['current_radio_song'], $md5_current_song, 0);
        }
        $html = '<fieldset>
            <legend>'.$TRINITY20['site_name'].' radio</legend><ul>';
        foreach ($data as $d) {
            $html .= '<li><b>'.$d.'</b></li>';
        }
        $html .= '<li><b>Playlist history: </b> '.(count($history) > 0 ? implode(', ', $history) : 'No playlist history').'</li>';
        if (!empty($users_ip)) {
            ($q1 = sql_query('SELECT id, username FROM users WHERE ip IN ('.$users_ip.') ORDER BY username ASC')) || sqlerr(__FILE__, __LINE__);
            if ($q1->num_rows == 0) {
                $html .= '<li><b>Listeners</b>: currently no listener from site </li>';
            } else {
                $users = [];
                while ($a1 = $q1->fetch_assoc()) {
                    $users[] = ($CURUSER['id'] == $a1['id'] || $CURUSER['class'] >= UC_STAFF) ? sprintf('<a href="/userdetails.php?id=%d">%s</a>',
                        $a1['id'], $a1['username']) : 'Anonymous';
                }
                $html .= '<li><b>Listeners</b>: '.implode(', ', $users).'</li>';
            }
        }
        return $html.'</ul></fieldset>';
    } else {
        $html .= '<fieldset><legend>'.$TRINITY20['site_name'].' radio</legend>
    <font size="3" color="red"><img src="'.$TRINITY20['pic_base_url'].'off1.gif" alt="Off" title="Off" border="0"><br>
    <b>Sorry '.$CURUSER['username'].' Radio is currently Offline</b></font></fieldset><br>';
    }
    //echo strval ($STREAMSTATUS);
    return $html;
}

?>
