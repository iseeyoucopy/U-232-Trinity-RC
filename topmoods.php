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
// topmoods.php for PTF by pdq 2011
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'user_functions.php');
dbconn(false);
loggedinorreturn();
$HTMLOUT = '';
$lang = array_merge(load_language('global'), load_language('usermood'));
$stdhead = [
    'js' => [
        'popup',
    ],
];

if ($TRINITY20['mood_sys_on'] == false) {
    stderr($lang['user_mood_err'], $lang['user_mood_off']);
    exit();
}

$HTMLOUT .= '<div class="container"><div class="row"><div class="col-md-8 col-md-offset-2"><br><table class="table table-bordered">
      <tr>
      <td class="text-center">
You may select your mood by clicking on the smiley in the left side menu or clicking <a href="javascript:;" onclick="PopUp(\'usermood.php\',\'Mood\',530,500,1,1);">here</a>.
     </td>
     </tr>
     </table></div><div><div>';
$abba = '<div class="container well"><div class="row"><div class="col-md-6 col-md-push-4"><h2>Top Moods</h2>
         <table  class="table-condensed table-striped">
         <tr><td class="text-center">Count</td>
         <td class="text-center">Mood</td>
         <td class="text-center">Icon</td>
         </tr>';
$topmoods = $cache->get($cache_keys['topmoods']);
if ($topmoods === false) {
    ($res = sql_query('SELECT moods.*, users.mood, COUNT(users.mood) as moodcount '.'FROM users LEFT JOIN moods ON (users.mood = moods.id) GROUP BY users.mood '.'ORDER BY moodcount DESC, moods.id ASC')) || sqlerr(__FILE__,
        __LINE__);
    while ($arr = $res->fetch_assoc()) {
        $topmoods .= '<tr><td class="text-center">'.(int)$arr['moodcount'].'</td>
                 <td class="text-center">'.htmlsafechars($arr['name']).' '.($arr['bonus'] == 1 ? '<a href="/mybonus.php" style="color:lime">(bonus)</a>' : '').'</td>
                 <td class="text-center"><img src="'.$TRINITY20['pic_base_url'].'smilies/'.htmlsafechars($arr['image']).'" alt=""></td>
                 </tr>';
    }
    $cache->set($cache_keys['topmoods'], $topmoods, 0);
}
$HTMLOUT .= $abba.$topmoods.'</table></div></div></div><br>';
echo stdhead("Top Moods").$HTMLOUT.stdfoot($stdhead);
?>
