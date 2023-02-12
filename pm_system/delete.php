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
//=== don't allow direct access
if (!defined('BUNNY_PM_SYSTEM')) {
    $HTMLOUT = '';
    $HTMLOUT .= '<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
//=== Delete a single message first make sure it's not an unread urgent staff message
($res = sql_query('SELECT receiver, sender, urgent, unread, saved, location FROM messages WHERE id='.sqlesc($pm_id))) || sqlerr(__FILE__, __LINE__);
$message = $res->fetch_assoc();
//=== make sure they aren't deleting a staff message...
if ($message['receiver'] == $CURUSER['id'] && $message['urgent'] == 'yes' && $message['unread'] == 'yes') {
    stderr($lang['pm_error'],
        ''.$lang['pm_delete_err'].'<a class="altlink" href="pm_system.php?action=view_message&id='.$pm_id.'">'.$lang['pm_delete_back'].'</a>'.$lang['pm_delete_msg'].'');
}
//=== make sure message isn't saved before deleting it, or just update location
if ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'no' || $message['sender'] == $CURUSER['id'] && $message['location'] == PM_DELETED) {
    sql_query('DELETE FROM messages WHERE id='.sqlesc($pm_id)) || sqlerr(__FILE__, __LINE__);
    $cache->delete($cache_keys['inbox_new'].$message['receiver']);
    $cache->delete($cache_keys['inbox_new_sb'].$message['receiver']);
} elseif ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'yes') {
    sql_query('UPDATE messages SET location=0, unread=\'no\' WHERE id='.sqlesc($pm_id)) || sqlerr(__FILE__, __LINE__);
    $cache->delete($cache_keys['inbox_new'].$message['receiver']);
    $cache->delete($cache_keys['inbox_new_sb'].$message['receiver']);
} elseif ($message['sender'] == $CURUSER['id'] && $message['location'] != PM_DELETED) {
    sql_query('UPDATE messages SET saved=\'no\' WHERE id='.sqlesc($pm_id)) || sqlerr(__FILE__, __LINE__);
    $cache->delete($cache_keys['inbox_new'].$message['sender']);
    $cache->delete($cache_keys['inbox_new_sb'].$message['sender']);
}
//=== see if it worked :D
if ($mysqli->affected_rows === 0) {
    stderr($lang['pm_error'],
        ''.$lang['pm_error'].'<a class="altlink" href="pm_system.php?action=view_message&id='.$pm_id.'>'.$lang['pm_delete_back'].'</a>'.$lang['pm_delete_msg'].'');
}
header('Location: pm_system.php?action=view_mailbox&deleted=1');
die();
?>
