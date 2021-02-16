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
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once INCL_DIR . 'user_functions.php';
require_once INCL_DIR . 'bbcode_functions.php';
require_once INCL_DIR . 'html_functions.php';
require_once ROOT_DIR . 'polls.php';
require_once (CLASS_DIR . 'class_user_options.php');
require_once (CLASS_DIR . 'class_user_options_2.php');
dbconn(true);
loggedinorreturn();
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'global_html_functions.php'); 
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'navigation_html_functions.php'); 

$stdhead = array(
    /** include css **/
    'css' => array(
        'bbcode',
    )
);
$stdfoot = array(
    /** include js **/
    'js' => array(
	/*'gallery',*/
    'shout'
    )
);
$keys['forum_stats'] = 'forum_statistics';
$HTMLOUT = "";
if (($forum_stats_cache = $cache->get($keys['forum_stats'])) === false) {
    ($$count_query = sql_query("SELECT COUNT (id) FROM posts")) || sqlerr(__FILE__, __LINE__);
    $count_arr = $count_query->fetch_row();
    $count = $count_arr[0];
    $cache->set($keys['forum_stats'], $forum_stats_cache, $TRINITY20['expires']['site_stats']);
}
$HTMLOUT.= "<div class='callout'>
 <p>forum topics ".$forum_stats_cache['id']."</p>
</div>";
echo stdhead('Home', true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
?>
