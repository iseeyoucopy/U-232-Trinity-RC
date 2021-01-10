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
//==Stats Begin
if (($stats_cache = $cache->get($keys['site_stats'])) === false) {
    $stats_cache = mysqli_fetch_assoc(sql_query("SELECT *, seeders + leechers AS peers, seeders / leechers AS ratio, unconnectables / (seeders + leechers) AS ratiounconn FROM stats WHERE id = '1' LIMIT 1"));
    $stats_cache['seeders'] = (int)$stats_cache['seeders'];
    $stats_cache['leechers'] = (int)$stats_cache['leechers'];
    $stats_cache['regusers'] = (int)$stats_cache['regusers'];
    $stats_cache['unconusers'] = (int)$stats_cache['unconusers'];
    $stats_cache['torrents'] = (int)$stats_cache['torrents'];
    $stats_cache['torrentstoday'] = (int)$stats_cache['torrentstoday'];
    $stats_cache['ratiounconn'] = (int)$stats_cache['ratiounconn'];
    $stats_cache['unconnectables'] = (int)$stats_cache['unconnectables'];
    $stats_cache['ratio'] = (int)$stats_cache['ratio'];
    $stats_cache['peers'] = (int)$stats_cache['peers'];
    $stats_cache['numactive'] = (int)$stats_cache['numactive'];
    $stats_cache['donors'] = (int)$stats_cache['donors'];
    $stats_cache['forumposts'] = (int)$stats_cache['forumposts'];
    $stats_cache['forumtopics'] = (int)$stats_cache['forumtopics'];
    $stats_cache['torrentsmonth'] = (int)$stats_cache['torrentsmonth'];
    $stats_cache['gender_na'] = (int)$stats_cache['gender_na'];
    $stats_cache['gender_male'] = (int)$stats_cache['gender_male'];
    $stats_cache['gender_female'] = (int)$stats_cache['gender_female'];
    $stats_cache['powerusers'] = (int)$stats_cache['powerusers'];
    $stats_cache['disabled'] = (int)$stats_cache['disabled'];
	$stats_cache['vips'] = (int)$stats_cache['vips'];
    $stats_cache['uploaders'] = (int)$stats_cache['uploaders'];
    $stats_cache['moderators'] = (int)$stats_cache['moderators'];
    $stats_cache['administrators'] = (int)$stats_cache['administrators'];
    $stats_cache['sysops'] = (int)$stats_cache['sysops'];
    $cache->set($keys['site_stats'], $stats_cache, $TRINITY20['expires']['site_stats']);
}
//==End
//==Installer 09 stats
$HTMLOUT.= "
<div class='card'>
    <div class='card-divider primary'>
        <label for='checkbox_4' class='text-left'>{$lang['index_stats_title']}</label>
    </div>
    <div class='card-section'>
        <div class='grid-x grid-margin-x'>
            <div class='cell medium-3 callout'>
                <ul class='no-bullet'>
                    <li><b>{$lang['index_stats_uinfo']}</b></li>
                    <li>{$lang['index_stats_regged']}<span class='badge float-right'>{$stats_cache['regusers']}</span></li>
                    <li>{$lang['index_stats_max']}<span class='badge float-right'>{$TRINITY20['maxusers']}</span></li>
                    <li>{$lang['index_stats_online']}<span class='badge float-right'>{$stats_cache['numactive']}</span></li>
                    <li>{$lang['index_stats_uncon']}<span class='badge float-right'>{$stats_cache['unconusers']}</span></li>
                    <li>{$lang['index_stats_gender_na']}<span class='badge float-right'>{$stats_cache['gender_na']}</span></li>
                    <li>{$lang['index_stats_gender_male']}<span class='badge float-right'>{$stats_cache['gender_male']}</span></li>
                    <li>{$lang['index_stats_gender_female']}<span class='badge float-right'>{$stats_cache['gender_female']}</span></li>
                </ul>
            </div>
            <div class='cell medium-3 callout'>
                <ul class='no-bullet'>
                    <li><b>{$lang['index_stats_cinfo']}</b></li>                                                                    
                    <li>{$lang['index_stats_powerusers']}<span class='badge float-right'>{$stats_cache['powerusers']}</span></li>
                    <li>{$lang['index_stats_banned']}<span class='badge float-right'>{$stats_cache['disabled']}</span></li>
                    <li>{$lang['index_stats_uploaders']}<span class='badge float-right'>{$stats_cache['uploaders']}</span></li>
                    <li>{$lang['index_stats_moderators']}<span class='badge float-right'>{$stats_cache['moderators']}</span></li>
                    <li>{$lang['index_stats_admin']}<span class='badge float-right'>{$stats_cache['administrators']}</span></li>
                    <li>{$lang['index_stats_sysops']}<span class='badge float-right'>{$stats_cache['sysops']}</span></li>
                </ul>
            </div>
            <div class='cell medium-3 callout'>
                <ul class='no-bullet'>
                    <li><b>{$lang['index_stats_finfo']}</b></li>                                                                    
                    <li>{$lang['index_stats_topics']}<span class='badge float-right'>{$stats_cache['forumtopics']}</span></li>
                    <li>{$lang['index_stats_posts']}<span class='badge float-right'>{$stats_cache['forumposts']}</span></li>
                </ul>
            </div>
            <div class='cell medium-3 callout'>
                <ul class='no-bullet'>
                    <li class='list-group-item btn btn-default'><b>{$lang['index_stats_tinfo']}</b></li>                                                                                                                                            <li>{$lang['index_stats_torrents']}<span class='badge float-right'>{$stats_cache['torrents']}</span></li>
                    <li>{$lang['index_stats_newtor']}<span class='badge float-right'>{$stats_cache['torrentstoday']}</span></li>
                    <li>{$lang['index_stats_peers']}<span class='badge float-right'>{$stats_cache['peers']}</span></li>
                    <li>{$lang['index_stats_unconpeer']}<span class='badge float-right'>{$stats_cache['unconnectables']}</span></li>
                    <li>{$lang['index_stats_seeders']}<span class='badge float-right'>{$stats_cache['seeders']}</span></li>
                    <li>{$lang['index_stats_unconratio']}<span class='badge float-right'>" . round($stats_cache['ratiounconn'] * 100) . "</span></li>
                    <li>{$lang['index_stats_leechers']}<span class='badge float-right'>{$stats_cache['leechers']}</span></li>
                    <li>{$lang['index_stats_slratio']}<span class='badge float-right'>" . round($stats_cache['ratio'] * 100) . "</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>";
//==End
// End Class
// End File
