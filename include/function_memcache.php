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
// removetorrentfromhash djGrrr <3
function remove_torrent($infohash)
{
    global $cache, $keys;
    if (strlen($infohash) != 20 || !bin2hex($infohash)) {
        return false;
    }
    $torrent = $cache->get($keys['torrent_hash'].md5($infohash));
    if ($torrent === false) {
        return false;
    }
    $cache->delete($keys['torrent_hash'].md5($infohash));
    if (is_array($torrent)) {
        remove_torrent_peers($torrent['id']);
    }
    return true;
}

function remove_torrent_peers($id)
{
    global $cache, $keys;
    if (!is_int($id) || $id < 1) {
        return false;
    }
    $delete = 0;
    $delete += $cache->delete($keys['torrents_seeds'].$id);
    $delete += $cache->delete($keys['torrents_leechs'].$id);
    $delete += $cache->delete($keys['torrents_comps'].$id);
    return (bool)$delete;
}

?>
