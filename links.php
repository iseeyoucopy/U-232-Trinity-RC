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
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('links'));
function add_link($url, $title, $description = "")
{
    $text = "<a class='altlink' href=$url>$title</a>";
    if ($description) {
        $text = "$text - $description";
    }
    return "<li>$text</li>\n";
}

$HTMLOUT = '';
if ($CURUSER) {
    $HTMLOUT .= "{$lang['links_dead']}";
}
$HTMLOUT .= "<table class='table table-bordered'><tr><td class='embedded'>";
$HTMLOUT .= "{$lang['links_other_pages_header']}
    <table class='table table-bordered'><tr><td class='text'><ul>
    {$lang['links_other_pages_body']}
    </ul></td></tr></table>";
$HTMLOUT .= "{$lang['links_bt_header']}
    <table class='table table-bordered'><tr><td class='text'><ul>
    {$lang['links_bt_body']}
    </ul></td></tr></table>";
$HTMLOUT .= "{$lang['links_software_header']}
    <table class='table table-bordered'><tr><td class='text'><ul>
    {$lang['links_software_body']}
    </ul></td></tr></table>";
$HTMLOUT .= "{$lang['links_download_header']}
    <table class='table table-bordered'><tr><td class='text'><ul>
    {$lang['links_download_body']}
    </ul></td></tr></table>";
$HTMLOUT .= "{$lang['links_forums_header']}
    <table class='table table-bordered'><tr><td class='text'><ul>
   {$lang['links_forums_body']}
    </ul></td></tr></table>";
$HTMLOUT .= "{$lang['links_other_header']}
    <table class='table table-bordered'><tr><td class='text'><ul>
    {$lang['links_other_body']}
    </ul></td></tr></table>";
$HTMLOUT .= "{$lang['links_tbdev_header']}>
    <table class='table table-bordered'><tr><td class='text'>
    {$lang['links_tbdev_body']}
    </td></tr></table>";
$HTMLOUT .= "</td></tr></table>";
echo stdhead("Links").$HTMLOUT.stdfoot();
?>
