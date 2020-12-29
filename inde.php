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

$lang = array_merge(load_language('global') , load_language('index'), load_language('chat'));
$HTMLOUT = '';
//if (($redis->cmd('HGET', 'hash', 'active_users_')->get()) === false) {
if (($active_users_cache = $cache->get('active_users_')) === false) {
    $dt = $_SERVER['REQUEST_TIME'] - 180;
    $activeusers = '';
    $active_users_cache = array();
    $res = sql_query('SELECT id, username, class, donor, title, warned, enabled, chatpost, leechwarn, pirate, king, perms ' . 'FROM users WHERE last_access >= ' . $dt . ' ' . 'AND perms < ' . bt_options::PERMS_STEALTH . ' ORDER BY username ASC') or sqlerr(__FILE__, __LINE__);
    $actcount = mysqli_num_rows($res);
    $v = ($actcount != 1 ? 's' : '');
    while ($arr = mysqli_fetch_assoc($res)) {
        if ($activeusers) $activeusers.= ",";
        $activeusers.= '<b>' . format_username($arr) . '</b>';
    }
    $active_users_cache['activeusers'] = $activeusers;
    $active_users_cache['actcount'] = $actcount;
    $active_users_cache['au'] = number_format($actcount);
    $last24_cache['v'] = $v;
    /*
    $redis->cmd('SET', 'active_users_')
      ->cmd('HSET', $activeusers)
      ->cmd('EXPIRE', $TRINITY20['expires']['activeusers'])
      ->set();
      */
    $cache->set('active_users_', $active_users_cache, $TRINITY20['expires']['activeusers']);
}
if (!$active_users_cache['activeusers']) $active_users_cache['activeusers'] = $lang['index_active_users_no'];

$active_users = '
<div class="panel panel-default">
<div class="panel-heading">
<label for="checkbox_4" class="text-left">' . $lang['index_active'] . '&nbsp;&nbsp;<span class="badge btn btn-success disabled" style="color:#fff">' . $active_users_cache['actcount'] . '</span></label>
	</div>
<div class="panel-body">
<p>' . $active_users_cache['activeusers'] . '</p>
</div></div>';
$HTMLOUT.= $active_users."";
echo stdhead('Home', $stdhead) . $HTMLOUT . stdfoot($stdfoot);