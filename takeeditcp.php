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
require_once(CLASS_DIR.'page_verify.php');
require_once(INCL_DIR.'password_functions.php');
require_once(CLASS_DIR.'class_user_options.php');
require_once(CLASS_DIR.'class_user_options_2.php');
dbconn();
loggedinorreturn();
$curuser_cache = $user_cache = $urladd = $changedemail = $birthday = '';
$lang = array_merge(load_language('global'), load_language('takeeditcp'));
$newpage = new page_verify();
$newpage->check('tkepe');
function resize_image($in)
{
    $out = [
        'img_width' => $in['cur_width'],
        'img_height' => $in['cur_height'],
    ];
    if ($in['cur_width'] > $in['max_width']) {
        $out['img_width'] = $in['max_width'];
        $out['img_height'] = ceil(($in['cur_height'] * (($in['max_width'] * 100) / $in['cur_width'])) / 100);
        $in['cur_height'] = $out['img_height'];
        $in['cur_width'] = $out['img_width'];
    }
    if ($in['cur_height'] > $in['max_height']) {
        $out['img_height'] = $in['max_height'];
        $out['img_width'] = ceil(($in['cur_width'] * (($in['max_height'] * 100) / $in['cur_height'])) / 100);
    }
    return $out;
}

$action = isset($_POST["action"]) ? htmlspecialchars(trim($_POST["action"])) : '';
$updateset = $updateset_block = $curuser_cache = $user_cache = [];
$setbits = $clrbits = 0;
//== Avatars stuffs
if ($action == "avatar") {
    $avatars = (isset($_POST['avatars']) && $_POST['avatars'] === 'yes' ? 'yes' : 'no');
    $offensive_avatar = (isset($_POST['offensive_avatar']) && $_POST['offensive_avatar'] === 'yes' ? 'yes' : 'no');
    $view_offensive_avatar = (isset($_POST['view_offensive_avatar']) && $_POST['view_offensive_avatar'] === 'yes' ? 'yes' : 'no');
    if (!($CURUSER["avatarpos"] == 0 || $CURUSER["avatarpos"] != 1)) {
        $avatar = trim(urldecode($_POST["avatar"]));
        if (preg_match("/^http:\/\/$/i", $avatar) || preg_match("/[?&;]/", $avatar) || preg_match("#javascript:#is",
                $avatar) || !preg_match("#^https?://(?:[^<>*\"]+|[a-z0-9/\._\-!]+)$#iU", $avatar)) {
            $avatar = '';
        }
    }
    if (!empty($avatar)) {
        $img_size = @GetImageSize($avatar);
        if ($img_size == false || !in_array($img_size['mime'], $TRINITY20['allowed_ext'])) {
            stderr($lang['takeeditcp_user_error'], $lang['takeeditcp_image_error']);
        }
        if ($img_size[0] < 5 || $img_size[1] < 5) {
            stderr($lang['takeeditcp_user_error'], $lang['takeeditcp_small_image']);
        }
        sql_query("UPDATE usersachiev SET avatarset=avatarset+1 WHERE id=".sqlesc($CURUSER["id"])." AND avatarset = '0'") || sqlerr(__FILE__,
            __LINE__);
        if ($img_size[0] > $TRINITY20['av_img_width'] || $img_size[1] > $TRINITY20['av_img_height']) {
            $image = resize_image([
                'max_width' => $TRINITY20['av_img_width'],
                'max_height' => $TRINITY20['av_img_height'],
                'cur_width' => $img_size[0],
                'cur_height' => $img_size[1],
            ]);
        } else {
            $image['img_width'] = $img_size[0];
            $image['img_height'] = $img_size[1];
        }
        $updateset[] = "av_w = ".sqlesc($image['img_width']);
        $updateset[] = "av_h = ".sqlesc($image['img_height']);
        $curuser_cache['av_w'] = ($image['img_width']);
        $user_cache['av_w'] = ($image['img_width']);
        $curuser_cache['av_h'] = ($image['img_height']);
        $user_cache['av_h'] = ($image['img_height']);
    }
    $updateset[] = 'offensive_avatar = '.sqlesc($offensive_avatar);
    $updateset[] = 'view_offensive_avatar = '.sqlesc($view_offensive_avatar);
    if (!($CURUSER["avatarpos"] == 0 || $CURUSER["avatarpos"] != 1)) {
        $updateset[] = "avatar = ".sqlesc($avatar);
    }
    $updateset[] = 'avatars = '.sqlesc($avatars);
    $curuser_cache['offensive_avatar'] = $offensive_avatar;
    $user_cache['offensive_avatar'] = $offensive_avatar;
    $curuser_cache['view_offensive_avatar'] = $view_offensive_avatar;
    $user_cache['view_offensive_avatar'] = $view_offensive_avatar;
    $curuser_cache['avatar'] = $avatar;
    $user_cache['avatar'] = $avatar;
    $curuser_cache['avatars'] = $avatars;
    $user_cache['avatars'] = $avatars;
    //if (isset($_POST['offensive_avatar'])) $setbits|= user_options::OFFENSIVE_AVATAR;
    // else $clrbits|= user_options::OFFENSIVE_AVATAR;
    //if (isset($_POST['view_offensive_avatar'])) $setbits|= user_options::VIEW_OFFENSIVE_AVATAR;
    //else $clrbits|= user_options::VIEW_OFFENSIVE_AVATAR;
    //if (isset($_POST['avatars'])) $setbits|= user_options::AVATARS;
    //else $clrbits|= user_options::AVATARS;
    $action = "avatar";
} elseif ($action == "signature") {
//== Signature stuffs
    if (isset($_POST["info"]) && (($info = $_POST["info"]) != $CURUSER["info"])) {
        $updateset[] = "info = ".sqlesc($info);
        $curuser_cache['info'] = $info;
        $user_cache['info'] = $info;
    }
    $signatures = (isset($_POST['signatures']) && $_POST['signatures'] === 'yes' ? 'yes' : 'no');
    $signature = trim(urldecode($_POST["signature"]));
    if (preg_match("/^http:\/\/$/i", $signature) || preg_match("/[?&;]/", $signature) || preg_match("#javascript:#is",
            $signature) || !preg_match("#^https?://(?:[^<>*\"]+|[a-z0-9/\._\-!]+)$#iU", $signature)) {
        $signature = '';
    }
    if (!empty($signature)) {
        $img_size = @GetImageSize($signature);
        if ($img_size == false || !in_array($img_size['mime'], $TRINITY20['allowed_ext'])) {
            stderr($lang['takeeditcp_uerr'], $lang['takeeditcp_img_unsupported']);
        }
        if ($img_size[0] < 5 || $img_size[1] < 5) {
            stderr($lang['takeeditcp_uerr'], $lang['takeeditcp_img_to_small']);
        }
        sql_query("UPDATE usersachiev SET sigset=sigset+1 WHERE id=".sqlesc($CURUSER["id"])." AND sigset = '0'") || sqlerr(__FILE__, __LINE__);
        if ($img_size[0] > $TRINITY20['sig_img_width'] || $img_size[1] > $TRINITY20['sig_img_height']) {
            $image = resize_image([
                'max_width' => $TRINITY20['sig_img_width'],
                'max_height' => $TRINITY20['sig_img_height'],
                'cur_width' => $img_size[0],
                'cur_height' => $img_size[1],
            ]);
        } else {
            $image['img_width'] = $img_size[0];
            $image['img_height'] = $img_size[1];
        }
        $updateset[] = "sig_w = ".sqlesc($image['img_width']);
        $updateset[] = "sig_h = ".sqlesc($image['img_height']);
        $curuser_cache['sig_w'] = ($image['img_width']);
        $user_cache['sig_w'] = ($image['img_width']);
        $curuser_cache['sig_h'] = ($image['img_height']);
        $user_cache['sig_h'] = ($image['img_height']);
        $updateset[] = "signature = ".sqlesc("[img]".$signature."[/img]\n");
        $curuser_cache['signature'] = ("[img]".$signature."[/img]\n");
        $user_cache['signature'] = ("[img]".$signature."[/img]\n");
    }
    $updateset[] = "signatures = '$signatures'";
    $curuser_cache['signatures'] = $signatures;
    $user_cache['signatures'] = $signatures;
    //if (isset($_POST['signatures'])) $setbits|= user_options::SIGNATURES;
    //else $clrbits|= user_options::SIGNATURES;
    $action = "signature";
} elseif ($action == "security") {
    if (!mkglobal("email:chpassword:passagain:chmailpass:secretanswer")) {
        stderr($lang['takeeditcp_err'], $lang['takeeditcp_no_data']);
    }

    if ($chpassword != "") {
        if (strlen($chpassword) > 64) {
            stderr($lang['takeeditcp_err'], $lang['takeeditcp_pass_long']);
        }
        if ($chpassword != $passagain) {
            stderr($lang['takeeditcp_err'], $lang['takeeditcp_pass_not_match']);
        }
        $secret = mksecret();
        $hash1 = t_Hash($CURUSER['email'], $CURUSER['username'], $CURUSER['added']);
        $hash2 = t_Hash($CURUSER['birthday'], $secret, $CURUSER['pin_code']);
        $hash3 = t_Hash($CURUSER['birthday'], $CURUSER['username'], $CURUSER['email']);
        $passhash = make_passhash($hash1, hash("ripemd160", $chpassword), $hash2);
        $updateset[] = "secret = ".sqlesc($secret);
        $updateset[] = "passhash = ".sqlesc($passhash);
        $updateset[] = "hash3 = ".sqlesc($hash3);
        $curuser_cache['secret'] = $secret;
        $user_cache['secret'] = $secret;
        $curuser_cache['passhash'] = $passhash;
        $user_cache['passhash'] = $passhash;
        $curuser_cache['hash3'] = $hash3;
        $user_cache['hash3'] = $hash3;

        $passh = h_cook($CURUSER['hash3'], $_SERVER["REMOTE_ADDR"], $CURUSER["id"]);
        logincookie($CURUSER["id"], $passh);
    }
    if ($email != $CURUSER["email"]) {
        $hash1 = t_Hash($CURUSER['email'], $CURUSER['username'], $CURUSER['added']);
        $hash2 = t_Hash($CURUSER['birthday'], $CURUSER['secret'], $CURUSER['pin_code']);
        if (!validemail($email)) {
            stderr($lang['takeeditcp_err'], $lang['takeeditcp_not_valid_email']);
        }
        ($r = sql_query("SELECT id FROM users WHERE email=".sqlesc($email))) || sqlerr(__FILE__, __LINE__);
        if ($r->num_rows > 0 || !password_verify($hash1.hash("ripemd160", $chmailpass).$hash2, $CURUSER['passhash'])) {
            stderr($lang['takeeditcp_err'], $lang['takeeditcp_address_taken']);
        }
        $changedemail = 1;
    }
    if ($secretanswer != '') {
        if (strlen($secretanswer) > 40) {
            stderr($lang['takeeditcp_sorry'], $lang['takeeditcp_secret_long']);
        }
        if (strlen($secretanswer) < 6) {
            stderr($lang['takeeditcp_sorry'], $lang['takeeditcp_secret_short']);
        }
        $new_secret_answer = h_store($secretanswer.$CURUSER['email']);
        $updateset[] = "hintanswer = ".sqlesc($new_secret_answer);
        $curuser_cache['hintanswer'] = $new_secret_answer;
        $user_cache['hintanswer'] = $new_secret_answer;
    }
    /*Parked */
    if (isset($_POST['parked'])) {
        $setbits |= user_options::PARKED;
    } else {
        $clrbits |= user_options::PARKED;
    }
    /**Anonymous */
    if (isset($_POST['anonymous'])) {
        $setbits |= user_options::ANONYMOUS;
    } else {
        $clrbits |= user_options::ANONYMOUS;
    }
    /** Hide Current seed and leech */
    if (isset($_POST['hidecur'])) {
        $setbits |= user_options::HIDECUR;
    } else {
        $clrbits |= user_options::HIDECUR;
    }
    /** Show Email */
    if (isset($_POST['show_email'])) {
        $setbits |= user_options::SHOW_EMAIL;
    } else {
        $clrbits |= user_options::SHOW_EMAIL;
    }

    if (isset($_POST["changeq"]) && (($changeq = (int)$_POST["changeq"]) != $CURUSER["passhint"]) && is_valid_id($changeq)) {
        $updateset[] = "passhint = ".sqlesc($changeq);
        $curuser_cache['passhint'] = $changeq;
        $user_cache['passhint'] = $changeq;
    }
    if ($changedemail) {
        $sec = mksecret();
        $hash = h_store($sec.$email.$sec);
        $obemail = urlencode($email);
        $updateset[] = "editsecret = ".sqlesc($sec);
        $curuser_cache['editsecret'] = $sec;
        $user_cache['editsecret'] = $sec;
        $thishost = empty($_SERVER["HTTP_HOST"]) ? '' : $_SERVER["HTTP_HOST"];
        $thisdomain = preg_replace('/^www\./is', "", $thishost);
        $body = str_replace([
            '<#USERNAME#>',
            '<#SITENAME#>',
            '<#USEREMAIL#>',
            '<#IP_ADDRESS#>',
            '<#CHANGE_LINK#>',
        ], [
            $CURUSER['username'],
            $TRINITY20['site_name'],
            $email,
            $_SERVER['REMOTE_ADDR'],
            "{$TRINITY20['baseurl']}/confirmemail.php?uid={$CURUSER['id']}&key=$hash&email=$obemail",
        ], $lang['takeeditcp_email_body']);
        mail($email, "$thisdomain {$lang['takeeditcp_confirm']}", $body, "{$lang['takeeditcp_email_from']}{$TRINITY20['site_email']}");
        ($emailquery = sql_query("SELECT id, username, email FROM users WHERE id=".sqlesc($CURUSER['id']))) || sqlerr(__FILE__, __LINE__);
        $spm = $emailquery->fetch_assoc();
        $dt = TIME_NOW;
        $subject = sqlesc($lang['takeeditcp_email_alert']);
        $msg = sqlesc("{$lang['takeeditcp_email_user']}[url={$TRINITY20['baseurl']}/userdetails.php?id=".(int)$spm['id']."][b]".htmlspecialchars($spm['username'])."[/b][/url]{$lang['takeeditcp_email_changed']}{$lang['takeeditcp_email_old']}".htmlspecialchars($spm['email'])."{$lang['takeeditcp_email_new']}$email{$lang['takeeditcp_email_check']}");
        ($pmstaff = sql_query('SELECT id FROM users WHERE class = '.UC_ADMINISTRATOR)) || sqlerr(__FILE__, __LINE__);
        while ($arr = $pmstaff->fetch_assoc()) {
            sql_query("INSERT INTO messages(sender, receiver, added, msg, subject) VALUES(0, ".sqlesc($arr['id']).", $dt, $msg, $subject)") || sqlerr(__FILE__,
                __LINE__);
        }
        $cache->delete($keys['inbox_new'].$arr['id']);
        $cache->delete($keys['inbox_new_sb'].$arr['id']);
        $urladd .= "&mailsent=1";
    }
    $action = "security";
} //== Torrent stuffs
elseif ($action == "torrents") {

    //==
    if (isset($_POST["torrentsperpage"]) && (($torrentspp = min(100, 0 + $_POST["torrentsperpage"])) != $CURUSER["torrentsperpage"])) {
        $updateset[] = "torrentsperpage = $torrentspp";
        $curuser_cache['torrentsperpage'] = $torrentspp;
        $user_cache['torrentsperpage'] = $torrentspp;
    }
    //** Split Torrents by day */
    if (isset($_POST['browse_split'])) {
        $setbits |= block_browse::SPLIT;
    } else {
        $clrbits |= block_browse::SPLIT;
    }

    //** Categories as images */
    if (isset($_POST['browse_icons'])) {
        $setbits |= block_browse::ICONS;
    } else {
        $clrbits |= block_browse::ICONS;
    }

    //** Search Cloud */
    if (isset($_POST['browse_viewscloud'])) {
        $setbits |= block_browse::VIEWSCLOUD;
    } else {
        $clrbits |= block_browse::VIEWSCLOUD;
    }

    //** Top 10 torrents Slider */
    if (isset($_POST['browse_slider'])) {
        $setbits |= block_browse::SLIDER;
    } else {
        $clrbits |= block_browse::SLIDER;
    }

    //** Manually Clear New Tag */
    if (isset($_POST['browse_clear_tags'])) {
        $setbits |= block_browse::CLEAR_NEW_TAG_MANUALLY;
    } else {
        $clrbits |= block_browse::CLEAR_NEW_TAG_MANUALLY;
    }

    if (isset($_POST['categorie_icon']) && (($categorie_icon = (int)$_POST['categorie_icon']) != $CURUSER['categorie_icon']) && is_valid_id($categorie_icon)) {
        $updateset[] = 'categorie_icon = '.sqlesc($categorie_icon);
        $curuser_cache['categorie_icon'] = $categorie_icon;
        $user_cache['categorie_icon'] = $categorie_icon;
    }
    $action = "torrents";
} //== Personal stuffs
elseif ($action == "personal") {
    //custom-title check
    if (isset($_POST["title"]) && $CURUSER["class"] >= UC_VIP && ($title = $_POST["title"]) != $CURUSER["title"]) {
        $notallow = [
            "sysop",
            "administrator",
            "admin",
            "mod",
            "moderator",
            "vip",
            "motherfucker",
        ];
        if (in_array(strtolower($title), ($notallow))) {
            stderr($lang['takeeditcp_err'], $lang['takeeditcp_invalid_custom']);
        }
        $updateset[] = "title = ".sqlesc($title);
        $curuser_cache['title'] = $title;
        $user_cache['title'] = $title;
    }
    //status update
    if (isset($_POST['status']) && ($status = $_POST['status']) && !empty($status)) {
        $status_archive = ((isset($CURUSER['archive']) && is_array(unserialize($CURUSER['archive']))) ? unserialize($CURUSER['archive']) : []);
        if (!empty($CURUSER['last_status'])) {
            $status_archive[] = [
                'status' => $CURUSER['last_status'],
                'date' => $CURUSER['last_update'],
            ];
        }
        sql_query('INSERT INTO ustatus(userid,last_status,last_update,archive) VALUES('.sqlesc($CURUSER['id']).','.sqlesc($status).','.TIME_NOW.','.sqlesc(serialize($status_archive)).') ON DUPLICATE KEY UPDATE last_status=values(last_status),last_update=values(last_update),archive=values(archive)') || sqlerr(__FILE__,
            __LINE__);
        $cache->delete($keys['user_status'].$CURUSER['id']);
        $cache->delete('user_status_'.$CURUSER['id']);
    }
    //end status update;
    if (isset($_POST['stylesheet']) && (($stylesheet = (int)$_POST['stylesheet']) != $CURUSER['stylesheet']) && is_valid_id($stylesheet)) {
        $updateset[] = 'stylesheet = '.sqlesc($stylesheet);
        $curuser_cache['stylesheet'] = $stylesheet;
        $user_cache['stylesheet'] = $stylesheet;
    }
    if (isset($_POST["topicsperpage"]) && (($topicspp = min(100, 0 + $_POST["topicsperpage"])) != $CURUSER["topicsperpage"])) {
        $updateset[] = "topicsperpage = $topicspp";
        $curuser_cache['topicsperpage'] = $topicspp;
        $user_cache['topicsperpage'] = $topicspp;
    }
    if (isset($_POST["postsperpage"]) && (($postspp = min(100, 0 + $_POST["postsperpage"])) != $CURUSER["postsperpage"])) {
        $updateset[] = "postsperpage = $postspp";
        $curuser_cache['postsperpage'] = $postspp;
        $user_cache['postsperpage'] = $postspp;
    }
    if (isset($_POST["forum_sort"]) && ($forum_sort = $_POST["forum_sort"]) != $CURUSER["forum_sort"]) {
        $updateset[] = "forum_sort= ".sqlesc($forum_sort);
        $curuser_cache['forum_sort'] = $forum_sort;
        $user_cache['forum_sort'] = $forum_sort;
    }
    if (isset($_POST["gender"]) && ($gender = $_POST["gender"]) != $CURUSER["gender"]) {
        $updateset[] = "gender = ".sqlesc($gender);
        $curuser_cache['gender'] = $gender;
        $user_cache['gender'] = $gender;
    }
    if (isset($_POST["website"]) && ($website = $_POST["website"]) != $CURUSER["website"]) {
        $updateset[] = "website= ".sqlesc($website);
        $curuser_cache['website'] = $website;
        $user_cache['website'] = $website;
    }
    if ($CURUSER['birthday'] == '0000-00-00' || $CURUSER['birthday'] == '1801-01-01') {
        $year = isset($_POST["year"]) ? 0 + $_POST["year"] : 0;
        $month = isset($_POST["month"]) ? 0 + $_POST["month"] : 0;
        $day = isset($_POST["day"]) ? 0 + $_POST["day"] : 0;
        $birthday = date("$year.$month.$day");
        if ($year == '0000') {
            stderr($lang['takeeditcp_err'], $lang['takeeditcp_birth_year']);
        }
        if ($month == '00') {
            stderr($lang['takeeditcp_err'], $lang['takeeditcp_birth_month']);
        }
        if ($day == '00') {
            stderr($lang['takeeditcp_err'], $lang['takeeditcp_birth_day']);
        }
        if (!checkdate($month, $day, $year)) {
            stderr($lang['takeeditcp_err'],
                "<br /><div id='error' align='center'><font color='red' size='+1'>{$lang['takeeditcp_birth_not']}</font></div><br />");
        }
        $updateset[] = "birthday = ".sqlesc($birthday);
        $curuser_cache['birthday'] = $birthday;
        $user_cache['birthday'] = $birthday;
        $cache->delete('birthdayusers');
    }
    $action = "personal";
} elseif ($action == "location") {
    if (isset($_POST["country"]) && (($country = $_POST["country"]) != $CURUSER["country"]) && is_valid_id($country)) {
        $updateset[] = "country = $country";
        $curuser_cache['country'] = $country;
        $user_cache['country'] = $country;
    }
    if (isset($_POST['language']) && (($language = (int)$_POST['language']) != $CURUSER['language'])) {
        $updateset[] = 'language = '.sqlesc($language);
        $curuser_cache['language'] = $language;
        $user_cache['language'] = $language;
    }
    if (isset($_POST["user_timezone"]) && preg_match('#^\-?\d{1,2}(?:\.\d{1,2})?$#', $_POST['user_timezone'])) {
        $updateset[] = "time_offset = ".sqlesc($_POST['user_timezone']);
        $updateset[] = "auto_correct_dst = ".(isset($_POST['checkdst']) ? 1 : 0);
        $updateset[] = "dst_in_use = ".(isset($_POST['manualdst']) ? 1 : 0);
        $curuser_cache['time_offset'] = $_POST['user_timezone'];
        $user_cache['time_offset'] = $_POST['user_timezone'];
        $curuser_cache['auto_correct_dst'] = (isset($_POST['checkdst']) ? 1 : 0);
        $user_cache['auto_correct_dst'] = (isset($_POST['checkdst']) ? 1 : 0);
        $curuser_cache['dst_in_use'] = (isset($_POST['manualdst']) ? 1 : 0);
        $user_cache['dst_in_use'] = (isset($_POST['manualdst']) ? 1 : 0);
    }
    $action = "location";
} elseif ($action == "default") {
//== Pm stuffs
    $acceptpms_choices = [
        'yes' => 1,
        'friends' => 2,
        'no' => 3,
    ];
    $pmnotif = $_POST["pmnotif"] ?? '';
    $pmnotifs = ($pmnotif == 'yes' ? "[pm]" : "");
    $updateset[] = "notifs = ".sqlesc($pmnotifs)."";
    $curuser_cache['notifs'] = $pmnotifs;
    $user_cache['notifs'] = $pmnotifs;
    //== Accept PM
    $acceptpms = ($_POST['acceptpms'] ?? 'all');
    if (isset($acceptpms_choices[$acceptpms])) {
        $updateset[] = "acceptpms = ".sqlesc($acceptpms);
    }
    $curuser_cache['acceptpms'] = $acceptpms;
    $user_cache['acceptpms'] = $acceptpms;
    //== Delete PM on reply
    $deletepms = isset($_POST["deletepms"]) ? "yes" : "no";
    $updateset[] = "deletepms = '$deletepms'";
    $curuser_cache['deletepms'] = $deletepms;
    $user_cache['deletepms'] = $deletepms;
    //if (isset($_POST['deletepms'])) $setbits|= user_options::DELETEPMS;
    //else $clrbits|= user_options::DELETEPMS;
    //==Save Pm
    $savepms = (isset($_POST['savepms']) && $_POST["savepms"] != "" ? "yes" : "no");
    $updateset[] = "savepms = '$savepms'";
    $curuser_cache['savepms'] = $savepms;
    $user_cache['savepms'] = $savepms;
    //if (isset($_POST['savepms'])) $setbits|= user_options::SAVEPMS;
    //else $clrbits|= user_options::SAVEPMS;
    //==Forum Subscribe PM
    $subscription_pm = (isset($_POST["subscription_pm"]) && $_POST["subscription_pm"] != "" ? "yes" : "no");
    $updateset[] = "subscription_pm = ".sqlesc($subscription_pm);
    $curuser_cache['subscription_pm'] = $subscription_pm;
    $user_cache['subscription_pm'] = $subscription_pm;
    //==Force Read Pm
    $pm_forced = (isset($_POST["pm_forced"]) && $_POST["pm_forced"] != "" ? "yes" : "no");
    $updateset[] = "pm_forced = ".sqlesc($pm_forced);
    $curuser_cache['pm_forced'] = $pm_forced;
    $user_cache['pm_forced'] = $pm_forced;
    //if (isset($_POST['subscription_pm'])) $setbits|= user_options::SUBSCRIPTION_PM;
    //else $clrbits|= user_options::SUBSCRIPTION_PM;
    //== Torrent deletion Pm
    $pm_on_delete = (isset($_POST["pm_on_delete"]) && $_POST["pm_on_delete"] != "" ? "yes" : "no");
    $updateset[] = "pm_on_delete = ".sqlesc($pm_on_delete);
    $curuser_cache['pm_on_delete'] = $pm_on_delete;
    $user_cache['pm_on_delete'] = $pm_on_delete;
    //if (isset($_POST['pm_on_delete'])) $setbits|= user_options_2::PM_ON_DELETE;
    //else $clrbits|= user_options_2::PM_ON_DELETE;
    $commentpm = (isset($_POST['commentpm']) && $_POST["commentpm"] != "" ? "yes" : "no");
    $updateset[] = "commentpm = '$commentpm'";
    $curuser_cache['commentpm'] = $commentpm;
    $user_cache['commentpm'] = $commentpm;
    //if (isset($_POST['commentpm'])) $setbits|= user_options_2::COMMENTPM;
    //else $clrbits|= user_options_2::COMMENTPM;
    $action = "default";
}
//== End == then update the sets :)
if ($curuser_cache) {
    $cache->update_row($keys['my_userid'].$CURUSER['id'], $curuser_cache, $TRINITY20['expires']['curuser']);
}
if ($user_cache) {
    $cache->update_row($keys['user'].$CURUSER['id'], $user_cache, $TRINITY20['expires']['user_cache']);
}
if ((is_countable($updateset) ? count($updateset) : 0) > 0) {
    sql_query("UPDATE users SET ".implode(",", $updateset)." WHERE id = ".sqlesc($CURUSER["id"])) || sqlerr(__FILE__, __LINE__);
}
//** Browse Page */
if ($setbits !== 0) {
    $updateset_block[] = 'browse_page = (browse_page | '.$setbits.')';
}
if ($clrbits !== 0) {
    $updateset_block[] = 'browse_page = (browse_page & ~'.$clrbits.')';
}
if ((is_countable($updateset_block) ? count($updateset_block) : 0) > 0) {
    sql_query('UPDATE user_blocks SET '.implode(',', $updateset_block).' WHERE userid = '.sqlesc($CURUSER["id"])) || sqlerr(__FILE__, __LINE__);
}
$cache->delete('blocks::'.$CURUSER["id"]);
if ($setbits || $clrbits) {
    sql_query('UPDATE users SET opt1 = ((opt1 | '.$setbits.') & ~'.$clrbits.'), opt2 = ((opt2 | '.$setbits.') & ~'.$clrbits.') WHERE id = '.sqlesc($CURUSER["id"])) || sqlerr(__file__,
        __line__);
}
// grab current data
($res = sql_query('SELECT opt1, opt2 FROM users WHERE id = '.sqlesc($CURUSER["id"]).' LIMIT 1')) || sqlerr(__file__, __line__);
$row = $res->fetch_assoc();
$row['opt1'] = (int)$row['opt1'];
$row['opt2'] = (int)$row['opt2'];
$cache->update_row($keys['my_userid'].$CURUSER["id"], [
    'opt1' => $row['opt1'],
    'opt2' => $row['opt2'],
], $TRINITY20['expires']['curuser']);
$cache->update_row('user_'.$CURUSER["id"], [
    'opt1' => $row['opt1'],
    'opt2' => $row['opt2'],
], $TRINITY20['expires']['user_cache']);
header("Location: {$TRINITY20['baseurl']}/usercp.php?edited=1&action=$action".$urladd);
?>
