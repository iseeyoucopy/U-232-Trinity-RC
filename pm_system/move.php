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
sql_query('UPDATE messages SET location = '.sqlesc($mailbox).' WHERE id='.sqlesc($pm_id).' AND receiver = '.sqlesc($CURUSER['id']));
if ($mysqli->affected_rows === 0) {
    stderr($lang['pm_error'],
        ''.$lang['pm_move_err'].'<a class="altlink" href="pm_system.php?action=view_message&id='.$pm_id.'>'.$lang['pm_move_back'].'</a>'.$lang['pm_move_msg'].'');
}
header('Location: pm_system.php?action=view_mailbox&singlemove=1&box='.$mailbox);
die();
?>
