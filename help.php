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
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'user_functions.php');
require_once(INCL_DIR.'bbcode_functions.php');
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('help'));
$HTMLOUT = '';
$HTMLOUT .= '<div class="grid-container">
<div class="grid-x">
  <div class="cell medium-3">';
$HTMLOUT .= "<ul class='vertical menu drilldown' data-drilldown data-auto-height=true data-animate-height='true' style = 'width: 200px'>
<li>
<a href='#'>FAQ</a>
<ul class='menu vertical nested'>";
$count = 0;
$cats = [];
($q = sql_query("SELECT * FROM faq_cat WHERE min_view <=".sqlesc($CURUSER['class']))) || sqlerr(__file__, __line__);
while ($item = $q->fetch_assoc()) {
    $cats[] = $item;
}
foreach ($cats as $row) {
    if ($count == 9) {
        $HTMLOUT .= "";
    }
    if ($count == 0) {
        $HTMLOUT .= "";
    } else {
        $HTMLOUT .= "";
    }
    $HTMLOUT .= "<li><a data-toggle='div".htmlsafechars($row['shortcut'])."' href='#".htmlsafechars($row['shortcut'])."'>".htmlsafechars($row['name'])."</a></li>";
    $count++;
}
$HTMLOUT .= "</ul></li>";
$count = 0;
if (($faqs = $cache->get('faqs__')) === false) {
    $faqs = [];
    ($q2 = sql_query("SELECT * FROM faq")) || sqlerr(__FILE__, __LINE__);
    while ($row = $q2->fetch_assoc()) {
        $faqs[] = $row;
    }
    $cache->set('faqs__', $faqs, $TRINITY20['expires']['faqs']);
}
$rules = [];
if (($rules = $cache->get('rules__')) === false) {
    $q = sql_query("SELECT rules_cat.id, rules_cat.name, rules_cat.shortcut, rules_cat.min_view, rules.type, rules.title, rules.text FROM rules_cat LEFT JOIN rules ON rules.type=rules_cat.id WHERE rules_cat.min_view <=".sqlesc($CURUSER['class']));
    while ($item1 = $q->fetch_assoc()) {
        $rules = (array)$rules;
        $rules[] = $item1;
    }
    $cache->set('rules__', $rules, $TRINITY20['expires']['rules']);
}
$HTMLOUT .= "<li><a href='#'>Rules</a>
<ul class='menu vertical nested'>";
foreach ($rules as $row_rules) {
    if (is_array($row_rules)) {
        $HTMLOUT .= "<li><a data-toggle='div".htmlsafechars($row_rules['shortcut'])."' href='#".htmlsafechars($row_rules['shortcut'])."'>".htmlsafechars($row_rules['name'])."</a></li>";
    }
}
$HTMLOUT .= "</ul></li>";
$HTMLOUT .= "</ul></div>
<div class='cell medium-9'>
<h6 class='sub-header text-center'>{$lang['faq_welcome']}</h6>
<h6 class='sub-header text-center'>{$lang['rules_welcome']}</h6>
</div>";
//** Start Rules reveal/modal **//
foreach ($rules as $row_rules) {
    $HTMLOUT .= "<div class='reveal' id='div".htmlsafechars($row_rules['shortcut'] ?? '')."' data-reveal>
      <p><h4 class='sub-header'>".htmlsafechars($row_rules['name'] ?? '')."</h4></p>";
    $HTMLOUT .= "<ul class='accordion' data-accordion data-allow-all-closed='true'>
    <li class='accordion-item is-active' data-accordion-item>
      <a href='#' class='accordion-title'>".htmlsafechars($row_rules['title'] ?? '')."</strong></a>
      <div class='accordion-content' data-tab-content>
      <p>".htmlspecialchars_decode($row_rules['text'] ?? '')."</p></div>";
    $HTMLOUT .= "</div>";
}
//** End Rules reveal/modal **//
//** Start FAQ reveal/modal **//
foreach ($cats as $row) {
    $HTMLOUT .= "<div class='reveal' id='div".htmlsafechars($row['shortcut'])."'  data-reveal>
      <p><h2>".htmlsafechars($row['name'])."</h2></p>";
    foreach ($faqs as $item) {
        if ($item['type'] == $row['id']) {
            $item['text'] = str_replace([
                "SITE_NAME",
                "SITE_PIC_URL",
                "BASE_URL",
                "  ",
            ], [
                "{$TRINITY20['site_name']}",
                "{$TRINITY20['pic_base_url']}",
                "{$TRINITY20['baseurl']}",
                "&nbsp; ",
            ], $item['text']);
            $HTMLOUT .= "<ul class='accordion' data-accordion data-allow-all-closed='true'>
                <li class='accordion-item is-active' data-accordion-item>
                  <a href='#' class='accordion-title'><strong>".htmlspecialchars_decode($item['title'])."</strong></a>
                  <div class='accordion-content' data-tab-content>
                    <p>".htmlspecialchars_decode($item['text'])."</p>
                  </div>
                </li>
              </ul>";
        }
    }
    $HTMLOUT .= '<button class="close-button" data-close aria-label="Close reveal" type="button">
    <span aria-hidden="true">&times;</span>
  </button></div>';
}
//** End FAQ reveal/modal **//
$HTMLOUT .= '</div></div>';
echo stdhead('FAQ & Rules').$HTMLOUT.stdfoot();
?>
