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
require_once INCL_DIR . 'html_functions.php';
require_once INCL_DIR . 'bbcode_functions.php';
require_once CLASS_DIR . 'page_verify.php';
require_once (CACHE_DIR . 'subs.php');
dbconn(true);
loggedinorreturn();
$lang = array_merge(load_language('global') , load_language('upload'));
$stdhead = array(
    /** include css **/
    'css' => array(
        'bbcode'
    )
);
$stdfoot = array(
    /** include js **/
    'js' => array(
        'FormManager',
        'getname',
        'multiupload'
    )
);
if (function_exists('parked')) parked();
$newpage = new page_verify();
$newpage->create('tamud');
$HTMLOUT = $offers = $subs_list = $request = $descr = '';
if ($CURUSER['class'] < UC_UPLOADER || ($CURUSER["uploadpos"] == 0 || $CURUSER["uploadpos"] > 1 || $CURUSER['suspended'] == 'yes')) stderr($lang['upload_sorry'], $lang['upload_no_auth']);
//==== request dropdown
$res_request = sql_query('SELECT id, request_name FROM requests WHERE filled_by_user_id = 0 ORDER BY request_name ASC');
$request ="<div class='input-group'>
<span class='input-group-label'>{$lang['gl_requests']}</span>
    <select class='input-group-field' name='request' aria-describedby='requestHelpText'>
        <option value='0'></option>";
if ($res_request) {
    while ($arr_request = $res_request->fetch_assoc()) {
        $request.= '<option value="' . (int)$arr_request['id'] . '">' . htmlsafechars($arr_request['request_name']) . '</option>';
    }
} else {
    $request.= "<option value='0'>{$lang['upload_add_noreq']}</option>";
}
$request.= "</select></div><p class='help-text' id='requestHelpText'>{$lang['upload_add_fill']}</p>";
//=== offers list if member has made any offers
$res_offer = sql_query('SELECT id, offer_name FROM offers WHERE offered_by_user_id = ' . sqlesc($CURUSER['id']) . ' AND status = \'approved\' ORDER BY offer_name ASC');
if ($res_offer->num_rows > 0) {
    $offers = "  
    <div class='input-group'>
    <span class='input-group-label'>{$lang['gl_offers']}</span>
    <select class='input-group-field' name='offer'  aria-describedby='offerHelpText'>
        <option value='0'></option>";
    $message = "<option value='0'>{$lang['upload_add_offer']}</option>";
    while ($arr_offer = $res_offer->fetch_assoc()) {
        $offers.= '<option value="' . (int)$arr_offer['id'] . '">' . htmlsafechars($arr_offer['offer_name']) . '</option>';
    }
    $offers.= "</select></div><p class='help-text' id='offerHelpText'>{$lang['upload_add_offer2']}</p>";
}
$HTMLOUT.= "
    <script type='text/javascript'>
    window.onload = function() {
    setupDependencies('upload'); //name of form(s). Seperate each with a comma (ie: 'weboptions', 'myotherform' )
    };
    </script>
    <form role='form' name='upload' enctype='multipart/form-data' action='./takemultiupload.php' method='post'>
        <div class='grid-x grid-margin-x callout'>
            <div class='cell large-auto'>
                <input type='hidden' name='MAX_FILE_SIZE' value='{$TRINITY20['max_torrent_size']}'>
                <div class='input-group'>
                    <span class='input-group-label'>{$lang['upload_announce_url']}</span>
                    <input class='input-group-field' type='text' value='" . $TRINITY20['announce_urls'] . "'>
                </div>";
$descr = strip_tags(isset($_POST['descr']) ? trim($_POST['descr']) : '');
$HTMLOUT.= "<div class='torrent-seperator clone-me'>
    <div class='input-group'>
        <span class='input-group-label'>{$lang['upload_torrent']}</span>
        <div class='input-group-button'>
            <input type='file' name='file' id='torrent' onchange='getname()'>
        </div>
    </div>
    <div class='input-group'>
        <span class='input-group-label'>{$lang['upload_nfo']}</span>
        <div class='input-group-button'>
            <input type='file' name='nfo' aria-describedby='nfoHelpText'>
        </div>
    </div>";
$HTMLOUT .= "<div class='input-group'>
<span class='input-group-label'>{$lang['upload_type']}</span>
        <select class='input-group-field' name='type[]'>
        <option value='0'>({$lang['upload_choose_one']})</option>";
$cats = genrelist();
foreach ($cats as $row) {
    $HTMLOUT.= "<option value='" . (int)$row["id"] . "'>" . htmlsafechars($row["name"]) . "</option>\n";
}
$HTMLOUT.= "</select></div></div>";
$HTMLOUT .= "<div class='torrent-seperator'>
<a href='#' class='button clone-torrent-form'>Add another torrent</a>
</div>";
$HTMLOUT.= "<input type='submit' class='button float-right' value='{$lang['upload_submit']}'></div></div></form>";
echo stdhead($lang['upload_stdhead'], true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
?>
