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
    global $cache;
    if (strlen($infohash) != 20 || !bin2hex($infohash)) return false;
    $key = 'torrent::hash:::' . md5($infohash);
    $torrent = $cache->get($key);
    if ($torrent === false) return false;
    $cache->delete($key);
    if (is_array($torrent)) remove_torrent_peers($torrent['id']);
    return true;
}
function remove_torrent_peers($id)
{
    global $cache;
    if (!is_int($id) || $id < 1) return false;
    $delete = 0;
    $seed_key = 'torrents::seeds:::' . $id;
    $leech_key = 'torrents::leechs:::' . $id;
    $comp_key = 'torrents::comps:::' . $id;
    $delete+= $cache->delete($seed_key);
    $delete+= $cache->delete($leech_key);
    $delete+= $cache->delete($comp_key);
    return (bool)$delete;
}
?>
