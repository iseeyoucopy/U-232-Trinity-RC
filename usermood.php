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
// usermood.php for PTF by pdq 2011
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
dbconn(false);
$HTMLOUT = '';
$lang = array_merge(load_language('global'), load_language('usermood'));

if ($TRINITY20['mood_sys_on'] == false) {
    stderr($lang['user_mood_err'], $lang['user_mood_off']);
    exit();
}

if (!isset($CURUSER['id'])) {
    die($lang['user_mood_log']);
}
$more = ((($CURUSER['perms'] & bt_options::UNLOCK_MORE_MOODS) !== 0) ? 2 : 1);
if (isset($_GET['id'])) {
    $moodid = (isset($_GET['id']) ? (int)$_GET['id'] : 1);
    ($res_moods = sql_query('SELECT * FROM moods WHERE bonus < '.sqlesc($more).' AND id = '.sqlesc($moodid))) || sqlerr(__file__, __line__);
    if ($res_moods->num_rows) {
        $rmood = $res_moods->fetch_assoc();
        sql_query('UPDATE users SET mood = '.sqlesc($moodid).' WHERE id = '.sqlesc($CURUSER['id'])) || sqlerr(__FILE__, __LINE__);
        $cache->update_row($keys['my_userid'].$CURUSER['id'], [
            'mood' => $moodid,
        ], $TRINITY20['expires']['curuser']);
        $cache->update_row($keys['user'].$CURUSER['id'], [
            'mood' => $moodid,
        ], $TRINITY20['expires']['user_cache']);
        $cache->delete('topmoods');
        write_log('<b>'.$lang['user_mood_change'].'</b> '.$CURUSER['username'].' '.htmlsafechars($rmood['name']).'<img src="'.$TRINITY20['pic_base_url'].'smilies/'.htmlsafechars($rmood['image']).'" alt="" />');
        $HTMLOUT .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">		
      <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
         <meta http-equiv="Content-Language" content="en-us" />
         <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
         <title>'.$lang['user_mood_title'].'</title>
      <script type="text/javascript">	 
      <!--
      opener.location.reload(true);
      self.close();
      // -->
      </script>';
    } else {
        die($lang['user_mood_hmm']);
    }
}
$HTMLOUT .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">		
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Language" content="en-us" />
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   <title>'.$lang['user_mood_title'].'</title>
   <link rel="stylesheet" href="./templates/'.$CURUSER['stylesheet'].'/'.$CURUSER['stylesheet'].'.css" type="text/css" />
</head>
<body>
<h3 align="center">'.$CURUSER['username'].'\''.$lang['user_mood_s'].'</h3>
<table width="500px">';
($res = sql_query('SELECT * FROM moods WHERE bonus < '.sqlesc($more).' ORDER BY id ASC')) || sqlerr(__FILE__, __LINE__);
$count = 0;
while ($arr = $res->fetch_assoc()) {
    if ($count % 3 == 0) {
        $HTMLOUT .= '<tr>';
    }
    $HTMLOUT .= '<td>
         <a href="?id='.(int)$arr['id'].'">
         <img src="'.$TRINITY20['pic_base_url'].'smilies/'.htmlsafechars($arr['image']).'" alt="" />'.htmlsafechars($arr['name']).'
         </a>
         </td>';
    $count++;
    if ($count % 3 == 0) {
        $HTMLOUT .= '</tr>';
    }
}
$HTMLOUT .= '</table>
      <p><br /></p>
      <a href="javascript:self.close();"><font color="#FF0000">'.$lang['user_mood_close'].'</font></a>
      <noscript><a href="/index.php">'.$lang['user_mood_back'].'</a></noscript>
      </body>
      </html>';
echo $HTMLOUT;
?>
