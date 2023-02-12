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
if (!defined('IN_TRINITY20_ADMIN')) {
    $HTMLOUT = '';
    $HTMLOUT .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<title>Error!</title>
		</head>
		<body>
	<div style='font-size:33px;color:white;background-color:red;text-align:center;'>Incorrect access<br>You cannot access this file directly.</div>
	</body></html>";
    echo $HTMLOUT;
    exit();
}
require_once(INCL_DIR.'user_functions.php');
require_once(INCL_DIR.'html_functions.php');
require_once(INCL_DIR.'pager_functions.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('ad_banemail'));
/* Ban emails by x0r @tbdev.net */
$HTMLOUT = '';
$remove = isset($_GET['remove']) ? (int)$_GET['remove'] : 0;
if (is_valid_id($remove)) {
    sql_query("DELETE FROM bannedemails WHERE id = ".sqlesc($remove)) || sqlerr(__FILE__, __LINE__);
    write_log("{$lang['ad_banemail_log1']} $remove {$lang['ad_banemail_log2']} {$CURUSER['username']}");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlsafechars(trim($_POST["email"]));
    $comment = htmlsafechars(trim($_POST["comment"]));
    if (!$email || !$comment) {
        stderr("{$lang['ad_banemail_error']}", "{$lang['ad_banemail_missing']}");
    }
    sql_query("INSERT INTO bannedemails (added, addedby, comment, email) VALUES(".TIME_NOW.", ".sqlesc($CURUSER['id']).", ".sqlesc($comment).", ".sqlesc($email).")") || sqlerr(__FILE__,
        __LINE__);
    header("Location: staffpanel.php?tool=bannedemails");
    die;
}
$HTMLOUT .= "<div class='card'>
    <div class='card-divider'>Add email to ban list</div>
    <div class='card-section'>
        <form method='post' action='staffpanel.php?tool=bannedemails'>
            <div class='input-group' aria-describedby='email_info'>
                <span class='input-group-label'>{$lang['ad_banemail_email']}</span>
                <input class='input-group-field' type='text' name='email'>
            </div>
            <div class='input-group'>
                <span class='input-group-label'>{$lang['ad_banemail_comment']}</span>
                <input class='input-group-field' type='text' name='comment'>
            </div>
            <p class='help-text' id='email_info'>{$lang['ad_banemail_info']}</p>
            <input class='button float-center' type='submit' value='{$lang['ad_banemail_ok']}'>
        </form>
    </div>
</div>";
$count1 = get_row_count('bannedemails');
$perpage = 15;
$pager = pager($perpage, $count1, 'staffpanel.php?tool=bannedemails&amp;');
($res = sql_query("SELECT b.id, b.added, b.addedby, b.comment, b.email, u.username FROM bannedemails AS b LEFT JOIN users AS u ON b.addedby=u.id ORDER BY added DESC ".$pager['limit'])) || sqlerr(__FILE__,
    __LINE__);
$HTMLOUT.= "<div class='card'>
    <div class='card-divider'>{$lang["ad_banemail_current"]}</div>
    <div class='card-section'>";
if ($res->num_rows == 0) {
    $HTMLOUT .= "<div class='callout alert-callout-border alert'>
    <strong>{$lang['ad_banemail_nothing']}</strong>
    </div>";
} else {
    $HTMLOUT .= "
    <div class='table-scroll'>
    <table>
        <thead>
        <tr>
            <td>{$lang['ad_banemail_add1']}</td>
            <td>{$lang['ad_banemail_email']}</td>
            <td>{$lang['ad_banemail_by']}</td>
            <td>{$lang['ad_banemail_comment']}</td>
            <td>{$lang['ad_banemail_remove']}</td>
        </tr>
        </thead>";
    while ($arr = $res->fetch_assoc()) {
        $HTMLOUT .= "<tbody>
            <tr>
                <td>".get_date($arr['added'], '')."</td>
                <td>".htmlsafechars($arr['email'])."</td>
                <td><a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$arr['addedby']."'>".htmlsafechars($arr['username'])."</a></td>
                <td>".htmlsafechars($arr['comment'])."</td>
                <td><a href='staffpanel.php?tool=bannedemails&amp;remove=".(int)$arr['id']."'>{$lang['ad_banemail_remove1']}</a></td>
            </tr>
        </tbody>";
    }
    $HTMLOUT .= "</table>
    </div>";
}
if ($count1 > $perpage) {
    $HTMLOUT .= $pager['pagerbottom'];
}
$HTMLOUT .= "</div></div>";
echo stdhead("{$lang['ad_banemail_head']}").$HTMLOUT.stdfoot();
?>
