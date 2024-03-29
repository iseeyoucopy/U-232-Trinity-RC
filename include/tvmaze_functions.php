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
define('TBUCKET_DIR', BITBUCKET_DIR . DIRECTORY_SEPARATOR . 'tvmaze');
if (!is_dir(TBUCKET_DIR)) {
    mkdir(TBUCKET_DIR, 0777, true);
}

function tvmaze_format($tvmaze_data, $tvmaze_type)
{
    $tvmaze_display['show'] = [
        'name' => '<b>%s</b>',
        'url' => '%s',
        'premiered' => 'Started: %s',
        'origin_country' => 'Country: %s',
        'status' => 'Status: %s',
        'type' => 'Classification: %s',
        'summary' => 'Summary:<br/> %s',
        'runtime' => 'Runtime %s min',
        'genres2' => 'Genres: %s',
    ];
    foreach ($tvmaze_display[$tvmaze_type] as $key => $value) {
        if (isset($tvmaze_data[$key])) {
            $tvmaze_display[$tvmaze_type][$key] = sprintf($value, $tvmaze_data[$key]);
        } else {
            $tvmaze_display[$tvmaze_type][$key] = sprintf($value, 'None Found');
        }

    }
    return implode('<br/><br/>', $tvmaze_display[$tvmaze_type]);
}

function tvmaze(&$torrents)
{
    global $cache, $cache_keys, $TRINITY20;
    $tvmaze_data = '';
    $row_update = [];
    if (preg_match("/^(.*)(?:\.| |_)(?:(?:S\d{1,2}(?:E\d{1,2})?)|20\d\d\.\d\d\.\d\d|Part.\d|CHapters)/i", $torrents['name'], $tmp)) {
        $tvmaze = [
            'name' => preg_replace('/ $/', '', str_replace(['.', '_'], ' ', $tmp[1])),
        ];
    } else {
        $tvmaze = [
            'name' => preg_replace('/ $/', '', str_replace(['.', '_'], ' ', $torrents['name'])),
        ];
    }

    $memkey = 'tvmaze::' . strtolower(str_replace(' ', '', $tvmaze['name']));
    if (($tvmaze_id = $cache->get($memkey)) === false) {
        //get tvmaze id
        $tvmaze_link = sprintf('https://api.tvmaze.com/singlesearch/shows?q=%s', urlencode($tvmaze['name']));
        $tvmaze_array = json_decode(file_get_contents($tvmaze_link), true, 512, JSON_THROW_ON_ERROR);
        if ($tvmaze_array) {
            $tvmaze_id = $tvmaze_array['id'];
            $cache->set($memkey, $tvmaze_id, 0);
        } else {
            return false;
        }

    }
    $force_update = false;
    if (empty($torrents['newgenre']) || empty($torrents['poster'])) {
        $force_update = true;
    }

    if ($force_update || ($tvmaze_showinfo = $cache->get($cache_keys['tvmaze'] . $tvmaze_id)) === false) {
        //get tvmaze show info
        $tvmaze['name'] = preg_replace('/\d{4}.$/', '', $tvmaze['name']);
        $tvmaze_link = sprintf('http://api.tvmaze.com/shows/%d', $tvmaze_id);
        $tvmaze_array = json_decode(file_get_contents($tvmaze_link), true, 512, JSON_THROW_ON_ERROR);
        $tvmaze_array['origin_country'] = $tvmaze_array['network']['country']['name'];
        if (!empty($tvmaze_array['genres'])) {
            $tvmaze_array['genres2'] = implode(", ", array_map('strtolower', $tvmaze_array['genres']));
        }

        if (empty($torrents['newgenre'])) {
            $row_update[] = 'newgenre = ' . sqlesc(ucwords($tvmaze_array['genres2']));
        }

        if ($tvmaze_array["image"]["original"] != "") {
            if (!file_exists(TBUCKET_DIR . "/$tvmaze_id.jpg")) {
                file_put_contents(TBUCKET_DIR . "/$tvmaze_id.jpg", file_get_contents($tvmaze_array["image"]["original"]));
            }

            $img = "img.php/tvmaze/$tvmaze_id.jpg";
        }
        //==The torrent cache
        $cache->update_row($cache_keys['torrent_details'] . $torrents['id'], [
            'newgenre' => ucwords($tvmaze_array['genres2']),
        ], 0);
        if (empty($torrents['poster'])) {
            $row_update[] = 'poster = ' . sqlesc($img);
        }

        //==The torrent cache
        $cache->update_row($cache_keys['torrent_details'] . $torrents['id'], [
            'poster' => $img,
        ], 0);
        if ((is_countable($row_update) ? count($row_update) : 0) > 0) {
            sql_query('UPDATE torrents set ' . implode(', ', $row_update) . ' WHERE id = ' . $torrents['id']) || sqlerr(__FILE__, __LINE__);
        }

        $tvmaze_showinfo = tvmaze_format($tvmaze_array, 'show') . '<br/>';
        $cache->set($cache_keys['tvmaze'] . $tvmaze_id, $tvmaze_showinfo, 0);
        $tvmaze_data .= $tvmaze_showinfo;
    } else {
        //var_dump('Show from mem'); //debug
        $tvmaze_data .= $tvmaze_showinfo;
    }
    return $tvmaze_data;
}

?>
