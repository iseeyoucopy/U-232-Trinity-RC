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
$lang = array_merge(load_language('global'), load_language('faq'));
$HTMLOUT = '';
$HTMLOUT.= '<div class="callout">';
$HTMLOUT.= "<h6 class='sub-header text-center'>{$lang['faq_welcome']}</h6>";
$HTMLOUT.= "<h2>{$lang['faq_contents_header']}</h2>
<ul class='tabs' data-tabs id='faq-tabs'>";
$count = 0;
$cats = array();
$q = sql_query("SELECT * FROM faq_cat WHERE min_view <=" . sqlesc($CURUSER['class'])) or sqlerr (__file__, __line__);
while ($item = mysqli_fetch_assoc($q)) {
    $cats[] = $item;
}
foreach ($cats as $row) {
    if ($count == 9) 
		$HTMLOUT.= "<div style='display:block;height:50px;'></div>";
    if ($count == 0) 
		$HTMLOUT.= "<li class='tabs-title is-active'>";
    else 
		$HTMLOUT.= "<li class='tabs-title'>";
    $HTMLOUT.= "<a data-tabs-target='div".htmlsafechars($row['shortcut'])."' href='#".htmlsafechars($row['shortcut'])."'>".htmlsafechars($row['name'])."</a></li>";
    $count++;
}
$HTMLOUT.= "</ul>
<div class='tabs-content' data-tabs-content='faq-tabs'>";
$count = 0;
if (($faqs = $cache->get('faqs__')) === false) {
    $faqs = array();
    $q2 = sql_query("SELECT * FROM faq")  or sqlerr (__FILE__, __LINE__);
    while ($row = mysqli_fetch_assoc($q2)) $faqs[] = $row;
    $cache->set('faqs__', $faqs, $TRINITY20['expires']['faqs']);
}
foreach ($cats as $row) {
    $HTMLOUT.= "<div class='tabs-panel " . ($count == 0 ? "is-active" : "") . "' id='div".htmlsafechars($row['shortcut'])."'>
      <p><h2>".htmlsafechars($row['name'])."</h2></p>";    
    foreach ($faqs as $item) {
        if($item['type'] == $row['id']){
            $item['text'] = str_replace(array(
                "SITE_NAME",
                "SITE_PIC_URL",
                "BASE_URL",
                "  "
                ) , array(
                "{$TRINITY20['site_name']}",
                "{$TRINITY20['pic_base_url']}",
                "{$TRINITY20['baseurl']}",
                "&nbsp; "
                ) , $item['text']); 
        
                $HTMLOUT.= "<strong>".htmlspecialchars_decode($item['title'])."</strong>
            <p>".htmlspecialchars_decode($item['text'])."</p>";
        }
    }
    $HTMLOUT.= "</div>";
    $count++;
}
$HTMLOUT.= "</div>";
$HTMLOUT.= "</div>";
echo stdhead('FAQ') . $HTMLOUT . stdfoot();
?>