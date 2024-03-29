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
//==TRINITY20 MemCached News
$adminbutton = '';
if ($CURUSER['class'] >= UC_STAFF) {
    $adminbutton = "<a class='float-right' href='staffpanel.php?tool=news&amp;mode=news'>{$lang['index_news_title']}</a>";
}
$HTMLOUT .= "<div class='card'>
	<div class='card-divider'>{$lang['news_title']}{$adminbutton}</div>";
$prefix = 'min5l3ss';
$news = $cache->get($cache_keys['latest_news']);
if ($news === false) {
    ($res = sql_query("SELECT ".$prefix.".id AS nid, ".$prefix.".userid, ".$prefix.".added, ".$prefix.".title, ".$prefix.".body, ".$prefix.".sticky, ".$prefix.".anonymous, u.username, u.id, u.class, u.warned, u.chatpost, u.pirate, u.king, u.leechwarn, u.enabled, u.donor FROM news AS ".$prefix." LEFT JOIN users AS u ON u.id = ".$prefix.".userid WHERE ".$prefix.".added + ( 3600 *24 *45 ) > ".TIME_NOW." ORDER BY sticky, ".$prefix.".added DESC LIMIT 10")) || sqlerr(__FILE__,
        __LINE__);
    while ($array = $res->fetch_assoc()) {
        $news = (array)$news;
        $news[] = $array;
    }
    $cache->set($cache_keys['latest_news'], $news, $TRINITY20['expires']['latest_news']);
}
$news_flag = 0;
if ($news) {
    foreach ($news as $array) {
        if (is_array($array)) {
            $button = '';
            if ($CURUSER['class'] >= UC_STAFF) {
                $hash = md5('the@@saltto66??'.$array['nid'].'add'.'@##mu55y==');
                $button = "
        <div class='float-right'>
        <a href='staffpanel.php?tool=news&amp;mode=edit&amp;newsid=".(int)$array['nid']."'>
        <i class='icon-edit' title='{$lang['index_news_ed']}' ></i></a>&nbsp;
        <a href='staffpanel.php?tool=news&amp;mode=delete&amp;newsid=".(int)$array['nid']."&amp;h={$hash}'>
        <i class='icon-remove' title='{$lang['index_news_del']}' ></i></a>
        </div>";
            }
            $HTMLOUT .= "";
            if ($news_flag < 2) {
                $HTMLOUT .= "
    <ul>
    <label class='text-left'>".get_date($array['added'], 'DATE')."{$lang['index_news_txt']}"."".htmlsafechars($array['title'])."
    {$lang['index_news_added']}<b>".(($CURUSER['opt1'] & user_options::ANONYMOUS && $CURUSER['class'] < UC_STAFF && $array['userid'] != $CURUSER['id']) ? "<i>{$lang['index_news_anon']}</i>" : format_username($array))."</b>
        {$button}</label>";
                $HTMLOUT .= "<div id=\"ka".(int)$array['nid']."\" style=\"display:".($array['sticky'] == "yes" ? "" : "none").";\"> ".format_comment($array['body'],
                        0)."</div></ul><br>";
                $news_flag += 1;
            } else {
                $HTMLOUT .= "<div class='card-section'>
    <ul>
    <label class='text-left'>".get_date($array['added'],
                        'DATE')."{$lang['index_news_txt']}"."".htmlsafechars($array['title'])."</a>{$lang['index_news_added']}<b>".(($array['opt1'] & user_options::ANONYMOUS && $CURUSER['class'] < UC_STAFF && $array['userid'] != $CURUSER['id']) ? "<i>{$lang['index_news_anon']}</i>" : format_username($array))."</b>
        {$button}</label>";
                $HTMLOUT .= "<div id=\"ka".(int)$array['nid']."\" style=\"display:".($array['sticky'] == "yes" ? "" : "none").";\"> ".format_comment($array['body'],
                        0)."</div>
        </div></ul><br>";
            }
        }
    }
}
$HTMLOUT .= "</div>";
if (empty($news)) {
    $HTMLOUT .= "<div class='card-section'>{$lang['index_news_not']}</div>";
}
//==End
// End Class
// End File
