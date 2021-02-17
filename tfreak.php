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
function rsstfreakinfo() {
    global $TRINITY20;
    $html = '';
    $use_limit = true;
    $limit = 5;
    $xml = file_get_contents('http://feed.torrentfreak.com/Torrentfreak/');
   	$html ='';
    $icount = 1;
    $doc = new DOMDocument();
    @$doc->loadXML($xml);
    $items = $doc->getElementsByTagName('item');
    foreach ($items as $item) {
        $html.= '<h3><u>' . $item->getElementsByTagName('title')->item(0)->nodeValue . '</u></h3><font class="small">by ' . str_replace(array('<![CDATA[', ']]>'), '', $item->getElementsByTagName('creator')->item(0)->nodeValue) . ' on ' . $item->getElementsByTagName('pubDate')->item(0)->nodeValue . '</font><br />' . str_replace(array('<![CDATA[', ']]>'), '', $item->getElementsByTagName('description')->item(0)->nodeValue) . '<br /><a href="' . $item->getElementsByTagName('link')->item(0)->nodeValue . '" target="_blank"><span class=" btn btn-primary">Read more</span></a>';
        if ($use_limit && $icount == $limit) {
            break;
        }
        $icount++;
    }
    $html = str_replace(array('“','”'), '"', $html);
    //$html.='';
    return str_replace(["’", "‘", "‘", "–"], ["'", "'", "'", "-"], $html);
}

?>
