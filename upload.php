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
$lang = array_merge(load_language('global') , load_language('upload') , load_language('ad_artefact'));
$stdhead = array(
    /** include css **/
    'css' => array(
        'style2',
        'bbcode'
    )
);
$stdfoot = array(
    /** include js **/
    'js' => array(
        'FormManager',
        'getname',
        'shout'
    )
);
if (function_exists('parked')) parked();
$newpage = new page_verify();
$newpage->create('taud');
$HTMLOUT = $offers = $subs_list = $request = $descr = '';
if ($CURUSER['class'] < UC_UPLOADER OR $CURUSER["uploadpos"] == 0 || $CURUSER["uploadpos"] > 1 || $CURUSER['suspended'] == 'yes') stderr($lang['upload_sorry'], $lang['upload_no_auth']);
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
    $offers.= "</select></div><p class='help-text' id='offerHelpText'>{$lang['upload_add_offer2']}";
}
$HTMLOUT.= "<script type='text/javascript'>
    window.onload = function() {
    setupDependencies('upload'); //name of form(s). Seperate each with a comma (ie: 'weboptions', 'myotherform' )
    };
    </script>
        <form role='form' name='upload' enctype='multipart/form-data' action='./takeupload.php' method='post'>
        <div class='grid-x grid-margin-x callout'>
        <div class='cell large-auto'>
            <input type='hidden' name='MAX_FILE_SIZE' value='{$TRINITY20['max_torrent_size']}'>
            <div class='input-group'>
                <span class='input-group-label'>{$lang['upload_announce_url']}:</span>
                <input class='input-group-field' type='text' value='" . $TRINITY20['announce_urls'] . "'>
            </div>";
            $descr = strip_tags(isset($_POST['descr']) ? trim($_POST['descr']) : '');
            $HTMLOUT.= "<div class='input-group'>
                <span class='input-group-label'>{$lang['upload_imdb_url']}</span>
                <input class='input-group-field' type='text' name='url' aria-describedby='imdbHelpText'>
            </div>
            <p class='help-text' id='imdbHelpText'>{$lang['upload_imdb_tfi']}{$lang['upload_imdb_rfmo']}</p>
            <div class='input-group'>
            <span class='input-group-label'>{$lang['upload_poster']}</span>
            <input class='input-group-field' type='text' name='poster' aria-describedby='posterHelpText'>
            </div>
            <p class='help-text' id='posterHelpText'>{$lang['upload_poster1']}</p>
            <div class='input-group'>
            <span class='input-group-label'>Youtube</span>
            <input class='input-group-field' type='text' name='youtube' aria-describedby='youtubeHelpText'>
            </div>
            <p class='help-text' id='youtubeHelpText'>{$lang['upload_youtube_info']}</p>
            <div class='input-group'>
            <span class='input-group-label'>{$lang['upload_bitbucket']}</span>
            <input class='input-group-field' type='text' placeholder='{$TRINITY20['baseurl']}/bitbucket.php'>
            </div>
            <p class='help-text' id='bitbucketHelpText'>{$lang['upload_bitbucket_1']}</p>
            <div class='input-group'>
            <span class='input-group-label'>{$lang['upload_torrent']}</span>
            <div class='input-group-label'>
                <input type='file' name='file' id='torrent' onchange='getname()'>
            </div>
            </div>
            <div class='input-group'>
            <span class='input-group-label'>{$lang['upload_name']}</span>
            <input class='input-group-field' type='text' id='name' name='name' aria-describedby='nameHelpText'>
            </div>
            <p class='help-text' id='nameHelpText'>{$lang['upload_filename']}</p>
            <div class='input-group'>
            <span class='input-group-label'>{$lang['upload_tags']}</span>
            <input class='input-group-field' type='text' name='tags' aria-describedby='tagHelpText'>
            </div>
            <p class='help-text' id='tagHelpText'>{$lang['upload_tag_info']}</p>
            <div class='input-group'>
            <span class='input-group-label'>{$lang['upload_small_description']}</span>
            <input class='input-group-field' type='text' name='description' aria-describedby='descriptionHelpText'>
            </div>
            <p class='help-text' id='descriptionHelpText'>{$lang['upload_small_descr']}</p>
            <div class='input-group'>
            <span class='input-group-label'>{$lang['upload_nfo']}</span>
            <div class='input-group-button'>
                <input type='file' name='nfo' aria-describedby='nfoHelpText'>
            </div>
            </div>
            <p class='help-text' id='nfoHelpText'>{$lang['upload_nfo_info']}</p>
            <span class='input-group-label'>{$lang['upload_description']}</span>
            ". textbbcode("upload","descr")."
            <p class='help-text'>{$lang['upload_html_bbcode']}</p>";
            $HTMLOUT.= "</div><div class='cell large-auto'>";
$s = "<div class='input-group'>
<span class='input-group-label'>{$lang['upload_type']}</span>
        <select class='input-group-field' name='type'>
            <option value='0'>({$lang['upload_choose_one']})</option>";       
$cats = genrelist();
foreach ($cats as $row) {
    $s.= "<option value='" . (int)$row["id"] . "'>" . htmlsafechars($row["name"]) . "</option>";
}
$s.= "</select></div>";
$rg = "<div class='input-group'>
    <span class='input-group-label'>{$lang['upload_add_typ']}</span>
        <select class='input-group-field' name='release_group'>
            <option value='none'>{$lang['upload_add_typnone']}</option>
            <option value='p2p'>{$lang['upload_add_typp2p']}</option>
            <option value='scene'>{$lang['upload_add_typscene']}</option>
        </select>
        </div>";
$HTMLOUT.= "$s";
$HTMLOUT.=$rg;
$HTMLOUT.=$request;
$HTMLOUT.= $offers;
if ($CURUSER['class'] >= UC_UPLOADER AND XBT_TRACKER == false) {
    $HTMLOUT.= "<div class='input-group'>
    <span class='input-group-label'>{$lang['upload_add_free']}</span>
    <select class='input-group-field' name='free_length'>
    <option value='0'>{$lang['upload_add_nofree']}</option>
    <option value='42'>{$lang['upload_add_day1']}</option>
    <option value='1'>{$lang['upload_add_week1']}</option>
    <option value='2'>{$lang['upload_add_week2']}</option>
    <option value='4'>{$lang['upload_add_week4']}</option>
    <option value='8'>{$lang['upload_add_week8']}</option>
    <option value='255'>{$lang['upload_add_unltd']}</option>
    </select>
    </div>
    <div class='input-group'>
    <span class='input-group-label'>{$lang['upload_add_silv']}</span>
    <select class='input-group-field' name='half_length'>
    <option value='0'>{$lang['upload_add_nosilv']}</option>
    <option value='42'>{$lang['upload_add_sday1']}</option>
    <option value='1'>{$lang['upload_add_sweek1']}</option>
    <option value='2'>{$lang['upload_add_sweek2']}</option>
    <option value='4'>{$lang['upload_add_sweek4']}</option>
    <option value='8'>{$lang['upload_add_sweek8']}</option>
    <option value='255'>{$lang['upload_add_unltd']}</option>
    </select>
  </div>
  <fieldset class='fieldset'>
  <legend>{$lang['upload_add_vip']}</legend>
    <input type='checkbox' name='vip' value='1' aria-describedby='vipHelpText'>
    <p class='help-text' id='vipHelpText'>{$lang['upload_add_vipchk']}</p></fieldset>";
}
$subs_list.= "";
$i = 0;
foreach ($subs as $s) {
    $subs_list.= ($i && $i % 4 == 0) ? "" : "";
    $subs_list.= "<input  id='checkbox". (int)$s["id"] ."' name=subs[]' type='checkbox' value= ". (int)$s["id"] ."><label for='checkbox". (int)$s["id"] ."'>" . htmlsafechars($s["name"]) . "</label>";
    ++$i;
}
$subs_list.= "";
$HTMLOUT.= "<fieldset class='fieldset'>
<legend>{$lang['upload_add_sub']}</legend>$subs_list</fieldset>";
//== 09 Genre mod no mysql by Traffic
$HTMLOUT.= "<fieldset class='fieldset'>
<legend>{$lang['upload_add_genre']}</legend>
    <input type='radio' name='genre' value='movie' id='moviegenre'><label for='moviegenre'>{$lang['upload_add_movie']}</label>
    <input type='radio' name='genre' value='music' id='musicgenre'><label for='musicgenre'>{$lang['upload_add_music']}</label>
    <input type='radio' name='genre' value='game' id='gamegenre'><label for='gamegenre'>{$lang['upload_add_game']}</label>
    <input type='radio' name='genre' value='apps' id='appgenre'><label for='appgenre'>{$lang['upload_add_apps']}</label>
    <input type='radio' name='genre' value='' checked='checked' id='nonegenre'><label for='nonegenre'>{$lang['upload_add_none']}</label>";
$movie = array(
    $lang['movie_mv1'],
    $lang['movie_mv2'],
    $lang['movie_mv3'],
    $lang['movie_mv4'],
    $lang['movie_mv5'],
    $lang['movie_mv6'],
    $lang['movie_mv7'],
);
for ($x = 0; $x < count($movie); $x++) {
    $HTMLOUT.= "<label><input type='checkbox' value='$movie[$x]'  name='movie[]' class='DEPENDS ON genre BEING movie'>$movie[$x]</label>";
}
$music = array(
    $lang['music_m1'],
    $lang['music_m2'],
    $lang['music_m3'],
    $lang['music_m4'],
    $lang['music_m5'],
    $lang['music_m6'],
);
for ($x = 0; $x < count($music); $x++) {
    $HTMLOUT.= "<label><input type='checkbox' value='$music[$x]' name='music[]' class='DEPENDS ON genre BEING music'>$music[$x]</label>";
}
$game = array(
    $lang['game_g1'],
    $lang['game_g2'],
    $lang['game_g3'],
    $lang['game_g4'],
    $lang['game_g5'],
);
for ($x = 0; $x < count($game); $x++) {
    $HTMLOUT.= "<label><input type='checkbox' value='$game[$x]' name='game[]' class='DEPENDS ON genre BEING game'>$game[$x]</label>";
}
$apps = array(
    $lang['app_mv1'],
    $lang['app_mv2'],
    $lang['app_mv3'],
    $lang['app_mv4'],
    $lang['app_mv5'],
    $lang['app_mv6'],
    $lang['app_mv7'],
);
for ($x = 0; $x < count($apps); $x++) {
    $HTMLOUT.= "<label><input type='checkbox' value='$apps[$x]' name='apps[]' class='DEPENDS ON genre BEING apps'>$apps[$x]</label>";
}
$HTMLOUT.= "</fieldset>";
//== End
$HTMLOUT.="<div class='row'>";
$HTMLOUT.="<p>{$lang['upload_anonymous']}</p>
<div class='switch large'>
  <input class='switch-input' id='chk1' type='checkbox' name='uplver' value='yes' aria-describedby='annonHelpText'>
  <label class='switch-paddle' for='chk1'>
    <span class='show-for-sr'>{$lang['upload_anonymous']}</span>
    <span class='switch-active' aria-hidden='true'>Yes</span>
    <span class='switch-inactive' aria-hidden='true'>No</span>
  </label>
</div>
<p class='help-text' id='annonHelpText'>{$lang['upload_anonymous1']}</p>";
/*
$HTMLOUT.= "<div class='col-sm-3'>{$lang['upload_anonymous']}<br /><input type='checkbox' name='uplver' value='yes' id='chk1'>
<br />{$lang['upload_anonymous1']}</div>";
*/
if ($CURUSER['class'] == UC_MAX) {
    $HTMLOUT.="<p>{$lang['upload_comment']}</p>
    <div class='switch large'>
      <input class='switch-input' id='chk2' type='checkbox' name='allow_commentd' value='yes' aria-describedby='discomHelpText'>
      <label class='switch-paddle' for='chk2'>
        <span class='show-for-sr'>{$lang['upload_comment']}</span>
        <span class='switch-active' aria-hidden='true'>Yes</span>
        <span class='switch-inactive' aria-hidden='true'>No</span>
      </label>
    </div>
    <p class='help-text' id='discomHelpText'>{$lang['upload_discom1']}</p>";
    /*
    $HTMLOUT.= "<div class='col-sm-3'>{$lang['upload_comment']}<br /><input type='checkbox' name='allow_commentd' value='yes' id='chk2'><br />{$lang['upload_discom1']}</div>";
    */
}
$HTMLOUT.="<p>{$lang['upload_add_ascii']}</p>
<div class='switch large'>
  <input class='switch-input' id='chk3' type='checkbox' name='strip' value='strip' checked='checked' aria-describedby='asciiHelpText'>
  <label class='switch-paddle' for='chk3'>
    <span class='show-for-sr'>{$lang['upload_add_ascii']}</span>
    <span class='switch-active' aria-hidden='true'>Yes</span>
    <span class='switch-inactive' aria-hidden='true'>No</span>
  </label>
</div>
<p class='help-text' id='asciiHelpText'><a href='http://en.wikipedia.org/wiki/ASCII_art' target='_blank'>{$lang['upload_add_wascii']}</a></p>";
/*
$HTMLOUT.= "<div class='col-sm-3'>{$lang['upload_add_ascii']}<br /><input type='checkbox' name='strip' value='strip' checked='checked' id='chk3'><br /><a href='http://en.wikipedia.org/wiki/ASCII_art' target='_blank'>{$lang['upload_add_wascii']}</a></div>";
*/
if (XBT_TRACKER == true) {
    $HTMLOUT.="<p>{$lang['upload_add_free']}</p>
    <div class='switch large'>
      <input class='switch-input' id='chk3' type='checkbox' name='freetorrent' value='1' aria-describedby='freeHelpText'>
      <label class='switch-paddle' for='chk3'>
        <span class='show-for-sr'>{$lang['upload_add_free']}</span>
        <span class='switch-active' aria-hidden='true'>Yes</span>
        <span class='switch-inactive' aria-hidden='true'>No</span>
      </label>
    </div>
    <p class='help-text' id='freeHelpText'>{$lang['upload_add_freeinf']}</p>";
    /*
    $HTMLOUT.= "<div class='col-sm-3'>{$lang['upload_add_free']}<br />
    <input type='checkbox' name='freetorrent' value='1'><br /></div>";
    */
    }
$HTMLOUT.="</div></div>";
$HTMLOUT.= "</div><div class='cell large-auto callout'><input type='submit' class='button' value='{$lang['upload_submit']}'></div></form>";
echo stdhead($lang['upload_stdhead'], true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
?>
