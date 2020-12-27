<?php
/*
 |--------------------------------------------------------------------------|
 |   https://github.com/iseeyoucopy/                                        |
 |--------------------------------------------------------------------------|
 |   Licence Info: WTFPL                                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2020 U-232 Codename Trinity                              |
 |--------------------------------------------------------------------------|
 |   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon.   |
 |--------------------------------------------------------------------------|
 |   Project Leaders: iseeyoucopy, stonebreath, GodFather                   |
 |--------------------------------------------------------------------------|
  _   _   _   _   _     _   _   _   _   _   _     _   _   _   _
 / \ / \ / \ / \ / \   / \ / \ / \ / \ / \ / \   / \ / \ / \ / \
( U | - | 2 | 3 | 2 )-( S | o | u | r | c | e )-( C | o | d | e )
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
*/
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'bbcode_functions.php');
class foreachClass implements Iterator
    {
    private
        $_array = array();
    public function __construct(array $array)
        {
        $this->_array = $array;
        }
    public function rewind()
        {
        reset($this->_array);
        }
    public function current()
        {
        return current($this->_array);
        }
    public function key()
        {
        return key($this->_array);
        }
    public function next()
        {
        next($this->_array);
        }
    public function valid()
        {
        return $this->key() !== null;
        }
    }
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('faq'));
$HTMLOUT = '';
$HTMLOUT.= '<div class="callout">';
$HTMLOUT.= "<div class='card-heading'>{$lang['faq_welcome']}</div>";
$HTMLOUT.= "<h2>{$lang['faq_contents_header']}</h2>
<ul class='tabs' data-tabs id='faq-tabs'>";
$count = 0;
$cats = array();
$query = "SELECT * FROM faq_cat WHERE min_view <=" . sqlesc($CURUSER['class']);
if ($result = $db->query($query)) {
    /* fetch associative array */
    while ($item = $result->fetch_assoc()) {
        $cats[] = $item;
    }
}
foreach ($cats as $row) {
    if ($count == 9) 
		$HTMLOUT.= "";
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
    $faqs = new foreachClass(array());
    $query2 = "SELECT * FROM faq";
    if ($result2 = $db->query($query2)) {
        /* fetch associative array */
        while ($row = $result->fetch_assoc()) {
            $faqs[] = $row;
        }
    }
    $cache->set('faqs__', $faqs, $INSTALLER09['expires']['faqs']);
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
                "{$INSTALLER09['site_name']}",
                "{$INSTALLER09['pic_base_url']}",
                "{$INSTALLER09['baseurl']}",
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