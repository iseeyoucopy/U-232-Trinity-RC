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
$body = '';
//=== don't allow direct access
if (!defined('BUNNY_PM_SYSTEM')) {
    $HTMLOUT = '';
    $HTMLOUT.= '<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ERROR</title>
    <link rel="stylesheet" href="/../../foundation/dist/assets/css/app.css">
  </head>
  <body>
  <div class="grid-container">
        <div class="grid-x grid-padding-x align-center-middle text-center margin-top-3">
        <div class="callout alert margin-top-3">
          <h5>You are not allowed to enter in here</h5>
          <p>Please go back to<a href="/../../index.php"> Homepage</a></p>
        </div> 
        </div>
</div>
  </body>
</html>';
    echo $HTMLOUT;
    exit();
}
//=== Get the info
$res = sql_query('SELECT * FROM messages WHERE id=' . sqlesc($pm_id)) or sqlerr(__FILE__, __LINE__);
$message = mysqli_fetch_assoc($res);
if ($message['sender'] == $CURUSER['id'] && $message['sender'] == $CURUSER['id'] || mysqli_num_rows($res) === 0) stderr($lang['pm_error'], $lang['pm_forward_err']);
//=== if not from curuser then get who from
if ($message['sender'] !== $CURUSER['id']) {
    $res_forward = sql_query('SELECT username FROM users WHERE id=' . sqlesc($message['sender'])) or sqlerr(__FILE__, __LINE__);
    $arr_forward = mysqli_fetch_assoc($res_forward);
    $forwarded_username = ($message['sender'] === 0 ? $lang['pm_forward_system'] : (mysqli_num_rows($res_forward) === 0 ? $lang['pm_forward_unknow'] : $arr_forward['username']));
} else $forwarded_username = htmlsafechars($CURUSER['username']);
//=== print out the forwarding page
$HTMLOUT.= '<h1>' . $lang['pm_forward_fwd'] . '' . htmlsafechars($message['subject']) . '</h1>
        <form name="compose" action="pm_system.php" method="post">
        <input type="hidden" name="id" value="' . $pm_id . '" />
        <input type="hidden" name="action" value="forward_pm" />
    <table class="table table-striped">
    <tr>
        <td colspan="2" class="text-left" valign="top"><h1>' . $lang['pm_forward_fwd_msg'] . '
        <img src="pic/arrow_next.gif" alt=":" />' . $lang['pm_forward_fwd'] . '' . htmlsafechars($message['subject']) . '</h1></td>
    </tr>
    <tr>
        <td class="text-rigt" valign="top"><span style="font-weight: bold;">' . $lang['pm_forward_to'] . '</span></td>
        <td class="text-left" valign="top"><input type="text" name="to" value="' . $lang['pm_forward_user'] . '" class="member" onfocus="this.value=\'\';" /></td>
    </tr>
    <tr>
        <td class="text-right" valign="top"><span style="font-weight: bold;">' . $lang['pm_forward_original'] . '</span></td>
        <td class="text-left" valign="top"><span style="font-weight: bold;">' . $forwarded_username . '</span></td>
    </tr>
    <tr>
        <td class="text-right" valign="top"><span style="font-weight: bold;">' . $lang['pm_forward_from'] . '</span></td>
        <td class="text-left" valign="top"><span style="font-weight: bold;">' . $CURUSER['username'] . '</span></td>
    </tr>
    <tr>
        <td class="text-right" valign="top"><span style="font-weight: bold;">' . $lang['pm_forward_subject'] . '</span></td>
        <td class="text-left" valign="top"><input type="text" class="text_default" name="subject" value="' . $lang['pm_forward_fwd'] . '' . htmlsafechars($message['subject']) . '" /></td>
    </tr>
    <tr>
        <td class="text-center"></td>
        <td class="text-left">' . $lang['pm_forward_org_msg'] . '' . $forwarded_username . '' . $lang['pm_forward_org_msg1'] . '<br />' . format_comment($message['msg']) . '</td>
    </tr>
    <tr>
        <td class="text-right" valign="top"></td>
        <td class="text-left"><span style="font-weight: bold;">' . $lang['pm_forward_appear'] . '</span></td>
    </tr>
    <tr>
        <td class="text-right" valign="top"><span style="font-weight: bold;">' . $lang['pm_forward_message'] . '</span></td>
        <td class="text-left" valign="top">' . textbbcode('compose', 'body') . '</td>
    </tr>
    <tr>
        <td colspan="2" class="text-center">' . ($CURUSER['class'] >= UC_STAFF ? '<span class="label label-danger">' . $lang['pm_forward_mark'] . '</span>
        <input type="checkbox" name="urgent" value="yes" />&nbsp' : '') . '' . $lang['pm_forward_save'] . '
        <input type="checkbox" name="save" value="1" />
        <input type="hidden" name="first_from" value="' . $forwarded_username . '" /> 
        <input type="submit" class="btn btn-primary" name="move" value="Foward" /></td>
    </tr>
    </table></form>';
?>
