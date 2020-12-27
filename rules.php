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
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'bbcode_functions.php');
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('rules'));
$HTMLOUT = '';
$HTMLOUT.= '<div class="callout">';
$HTMLOUT.= "<h6 class='sub-header text-center'>{$lang['rules_welcome']}</h6>";
$HTMLOUT.= "<ul class='tabs' data-tabs id='rules-tabs'>";
$count = 0;
$rules = array();
if (($rules = $cache->get('rules__')) === false) {
$q = sql_query("SELECT rules_cat.id, rules_cat.name, rules_cat.shortcut, rules_cat.min_view, rules.type, rules.title, rules.text FROM rules_cat LEFT JOIN rules ON rules.type=rules_cat.id WHERE rules_cat.min_view <=" . sqlesc($CURUSER['class']));
while ($item = mysqli_fetch_assoc($q)) $rules[] = $item;
$cache->set('rules__', $rules, $INSTALLER09['expires']['rules']);
}
foreach ($rules as $row) {
    if ($count == 6) $HTMLOUT.= "<div style='display:block;height:50px;'></div>";
    if ($count == 0) $HTMLOUT.= "<li class='tabs-title is-active'>";
    else $HTMLOUT.= "<li class='tabs-title'>";
    $HTMLOUT.= "<a data-tabs-target='div".htmlsafechars($row['shortcut'])."' href='#".htmlsafechars($row['shortcut'])."'>".htmlsafechars($row['name'])."</a></li>";
    $count++;
}
$HTMLOUT.= "</ul>
<div class='tabs-content' data-tabs-content='rules-tabs'>";
$count = 0;
foreach ($rules as $row) {
    $HTMLOUT.= "<div class='tabs-panel " . ($count == 0 ? "is-active" : "") . "' id='div".htmlsafechars($row['shortcut'])."'>
      <p><h4 class='sub-header'>".htmlsafechars($row['name'])."</h4></p><p>";
    $HTMLOUT.= "<b>".htmlsafechars($row['title'])."</b>".htmlspecialchars_decode($row['text'])."";
    $HTMLOUT.= "</p></div>";
    $count++;
}
$HTMLOUT.= "</div></div>";
echo stdhead($lang['rules_rules']) . $HTMLOUT . stdfoot();
?>
