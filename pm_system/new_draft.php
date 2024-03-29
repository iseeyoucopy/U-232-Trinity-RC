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
$preview = $subject = $draft = '';
//=== don't allow direct access
if (!defined('BUNNY_PM_SYSTEM')) {
    $HTMLOUT .= '<!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" lang="en">
        <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
        <title>ERROR</title>
        </head><body>
        <h1 class="text-center">ERROR</h1>
        <p class="text-center">How did you get here? silly rabbit Trix are for kids!.</p>
        </body></html>';
    echo $HTMLOUT;
    exit();
}
if (isset($_POST['buttonval']) && $_POST['buttonval'] == 'save draft') {
    //=== make sure they wrote something :P
    if (empty($_POST['subject'])) {
        stderr($lang['pm_error'], $lang['pm_draft_err']);
    }
    if (empty($_POST['body'])) {
        stderr($lang['pm_error'], $lang['pm_draft_err1']);
    }
    $body = sqlesc($_POST['body']);
    $subject = sqlesc(strip_tags($_POST['subject']));
    sql_query('INSERT INTO messages (sender, receiver, added, msg, subject, location, draft, unread, saved) VALUES  
                                                                        ('.sqlesc($CURUSER['id']).', '.sqlesc($CURUSER['id']).','.TIME_NOW.', '.$body.', '.$subject.', \'-2\', \'yes\',\'no\',\'yes\')') || sqlerr(__FILE__,
        __LINE__);
    $cache->delete($cache_keys['inbox_new'].$CURUSER['id']);
    $cache->delete($cache_keys['inbox_new_sb'].$CURUSER['id']);
    $new_draft_id = $mysqli->insert_id;
    //=== Check if messages was saved as draft
    if ($mysqli->affected_rows === 0) {
        stderr($lang['pm_error'], $lang['pm_draft_err2']);
    }
    header('Location: pm_system.php?action=view_message&new_draft=1&id='.$new_draft_id);
    die();
} //=== end save draft
//=== Code for preview Retros code
if (isset($_POST['buttonval']) && $_POST['buttonval'] == 'preview') {
    $subject = htmlsafechars(trim($_POST['subject']));
    $draft = htmlsafechars(trim($_POST['body']));
    $preview = '
    <table class="table table-striped">
    <tr>
        <td colspan="2" class="text-left"><span style="font-weight: bold;">subject: </span>'.htmlsafechars($subject).'</td>
    </tr>
    <tr>
        <td valign="top" class="text-center" width="80px" id="photocol">'.avatar_stuff($CURUSER).'</td>
        <td class="text-left" style="min-width:400px;padding:10px;vertical-align: top;">'.format_comment($draft).'</td>
    </tr>
    </table><br>';
}
//=== print out the page
$HTMLOUT .= '
<h1>'.$lang['pm_draft_new'].'</h1>'.$top_links.$preview.'
        <form name="compose" action="pm_system.php" method="post">
        <input type="hidden" name="id" value="'.$pm_id.'">
        <input type="hidden" name="action" value="new_draft">
    <table class="table table-striped">
    <tr>
        <td class="text-left" colspan="2">'.$lang['pm_draft_add'].'</td>
    </tr>
    <tr>
        <td class="text-right" valign="top"><span style="font-weight: bold;">'.$lang['pm_draft_subject'].'</span></td>
        <td class="text-left" valign="top"><input type="text" class="text_default" name="subject" value="'.$subject.'"></td>
    </tr>
    <tr>
        <td class="text-right" valign="top"><span style="font-weight: bold;">'.$lang['pm_draft_body'].'</span></td>
        <td class="text-left" valign="top">'.textbbcode('compose', 'body').'</td>
    </tr>
    <tr>
		<div class="btn-group">
        <td colspan="2" class="text-center">
        <input type="submit" class="btn btn-primary" name="buttonval" value="preview"/>
        <input type="submit" class="btn btn-primary" name="buttonval" value="save draft"/></td>
		</div>
    </tr>
    </table></form>';
?>
