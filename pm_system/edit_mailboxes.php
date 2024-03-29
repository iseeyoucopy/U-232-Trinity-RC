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
//== Php poop
$all_my_boxes = $curuser_cache = $user_cache = $categories = '';
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
if (isset($_POST['action2'])) {
    $good_actions = [
        'add',
        'edit_boxes',
        'change_pm',
        'message_settings',
    ];
    $action2 = (isset($_POST['action2']) ? strip_tags($_POST['action2']) : '');
    $worked = $deleted = '';
    if (!in_array($action2, $good_actions)) {
        stderr($lang['pm_error'], $lang['pm_edmail_error']);
    }
    //=== add more boxes...
    switch ($action2) {
        case 'change_pm':
            $change_pm_number = (isset($_POST['change_pm_number']) ? (int)$_POST['change_pm_number'] : 20);
            sql_query('UPDATE users SET pms_per_page = '.sqlesc($change_pm_number).' WHERE id = '.sqlesc($CURUSER['id'])) || sqlerr(__FILE__,
                __LINE__);
            $cache->update_row($cache_keys['user'].$CURUSER['id'], [
                'pms_per_page' => $change_pm_number,
            ], $TRINITY20['expires']['user_cache']);
            $cache->update_row($cache_keys['my_userid'].$CURUSER['id'], [
                'pms_per_page' => $change_pm_number,
            ], $TRINITY20['expires']['curuser']);
            header('Location: pm_system.php?action=edit_mailboxes&pm=1');
            die();
            break;

        case 'add':
            //=== make sure they posted something...
            if ($_POST['new'] === '') {
                stderr($lang['pm_error'], $lang['pm_edmail_err']);
            }
            //=== Get current highest box number
            ($res = sql_query('SELECT boxnumber FROM pmboxes WHERE userid = '.sqlesc($CURUSER['id']).' ORDER BY boxnumber  DESC LIMIT 1')) || sqlerr(__FILE__,
                __LINE__);
            $box_arr = $res->fetch_row();
            $box = ($box_arr[0] < 2 ? 2 : ($box_arr[0] + 1));
            //=== let's add the new boxes to the DB
            $new_box = $_POST['new'];
            foreach ($new_box as $key => $add_it) {
                if (validusername($add_it) && $add_it !== '') {
                    $name = htmlsafechars($add_it);
                    sql_query('INSERT INTO pmboxes (userid, name, boxnumber) VALUES ('.sqlesc($CURUSER['id']).', '.sqlesc($name).', '.sqlesc($box).')') || sqlerr(__FILE__,
                        __LINE__);
                    $cache->delete($cache_keys['get_all_boxes'].$CURUSER['id']);
                    $cache->delete($cache_keys['insertJumpTo'].$CURUSER['id']);
                }
                ++$box;
                $worked = '&boxes=1';
            }
            //=== redirect back with messages :P
            header('Location: pm_system.php?action=edit_mailboxes'.$worked);
            die();
            break;
        //=== edit boxes

        case 'edit_boxes':
            //=== get info
            ($res = sql_query('SELECT * FROM pmboxes WHERE userid='.sqlesc($CURUSER['id']))) || sqlerr(__FILE__, __LINE__);
            if ($res->num_rows === 0) {
                stderr($lang['pm_error'], $lang['pm_edmail_err1']);
            }
            while ($row = $res->fetch_assoc()) {
                //=== if name different AND safe, update it
                if (validusername($_POST['edit'.$row['id']]) && $_POST['edit'.$row['id']] !== '' && $_POST['edit'.$row['id']] !== $row['name']) {
                    $name = htmlsafechars($_POST['edit'.$row['id']]);
                    sql_query('UPDATE pmboxes SET name='.sqlesc($name).' WHERE id='.sqlesc($row['id']).' LIMIT 1') || sqlerr(__FILE__, __LINE__);
                    $cache->delete($cache_keys['get_all_boxes'].$CURUSER['id']);
                    $cache->delete($cache_keys['insertJumpTo'].$CURUSER['id']);
                    $worked = '&name=1';
                }
                //=== if name is empty, delete the box(es) and send the PMs back to the inbox..
                if ($_POST['edit'.$row['id']] == '') {
                    //=== get messages to move
                    ($remove_messages_res = sql_query('SELECT id FROM messages WHERE location='.sqlesc($row['boxnumber']).'  AND receiver='.sqlesc($CURUSER['id']))) || sqlerr(__FILE__,
                        __LINE__);
                    //== move the messages to the inbox
                    while ($remove_messages_arr = $remove_messages_res->fetch_assoc()) {
                        sql_query('UPDATE messages SET location=1 WHERE id='.sqlesc($remove_messages_arr['id'])) || sqlerr(__FILE__, __LINE__);
                    }
                    //== delete the box
                    sql_query('DELETE FROM pmboxes WHERE id='.sqlesc($row['id']).'  LIMIT 1') || sqlerr(__FILE__, __LINE__);
                    $cache->delete($cache_keys['get_all_boxes'].$CURUSER['id']);
                    $cache->delete($cache_keys['insertJumpTo'].$CURUSER['id']);
                    $deleted = '&box_delete=1';
                }
            }
            //=== redirect back with messages :P
            header('Location: pm_system.php?action=edit_mailboxes'.$deleted.$worked);
            die();
            break;
        //=== message settings     yes/no/friends

        case 'message_settings':
            $updateset = [];
            $change_pm_number = (isset($_POST['change_pm_number']) ? (int)$_POST['change_pm_number'] : 20);
            $updateset[] = 'pms_per_page = '.sqlesc($change_pm_number);
            $curuser_cache['pms_per_page'] = $change_pm_number;
            $user_cache['pms_per_page'] = $change_pm_number;
            $show_pm_avatar = ((isset($_POST['show_pm_avatar']) && $_POST['show_pm_avatar'] == 'yes') ? 'yes' : 'no');
            $updateset[] = 'show_pm_avatar = '.sqlesc($show_pm_avatar);
            $curuser_cache['show_pm_avatar'] = $show_pm_avatar;
            $user_cache['show_pm_avatar'] = $show_pm_avatar;
            $acceptpms = ((isset($_POST['acceptpms']) && $_POST['acceptpms'] == 'yes') ? 'yes' : ((isset($_POST['acceptpms']) && $_POST['acceptpms'] == 'friends') ? 'friends' : 'no'));
            $updateset[] = 'acceptpms = '.sqlesc($acceptpms);
            $curuser_cache['acceptpms'] = $acceptpms;
            $user_cache['acceptpms'] = $acceptpms;
            $save_pms = ((isset($_POST['save_pms'])) ? 'yes' : 'no');
            $updateset[] = 'savepms = '.sqlesc($save_pms);
            $curuser_cache['savepms'] = $save_pms;
            $user_cache['savepms'] = $save_pms;
            $deletepms = ((isset($_POST['deletepms']) && $_POST['deletepms'] == 'yes') ? 'yes' : 'no');
            $updateset[] = 'deletepms = '.sqlesc($deletepms);
            $curuser_cache['deletepms'] = $deletepms;
            $user_cache['deletepms'] = $deletepms;
            $pmnotif = ($_POST['pmnotif'] ?? '');
            $emailnotif = ($_POST['emailnotif'] ?? '');
            $notifs = ($pmnotif == 'yes' ? $lang['pm_edmail_pm_1'] : '');
            $notifs .= ($emailnotif == 'yes' ? $lang['pm_edmail_email_1'] : '');
            $cats = genrelist();
            //==cats
            ($r = sql_query("SELECT id FROM categories WHERE min_class < ".sqlesc($CURUSER['class']))) || sqlerr(__FILE__, __LINE__);
            $rows = $r->num_rows;
            for ($i = 0; $i < $rows; ++$i) {
                $a = $r->fetch_assoc();
                if (isset($_POST["cat{$a['id']}"]) && $_POST["cat{$a['id']}"] == 'yes') {
                    $notifs .= "[cat{$a['id']}]";
                }
            }
            $updateset[] = "notifs = ".sqlesc($notifs)."";
            $curuser_cache['notifs'] = $notifs;
            $user_cache['notifs'] = $notifs;
            if ($curuser_cache !== []) {
                $cache->update_row($cache_keys['my_userid'].$CURUSER['id'], $curuser_cache, $TRINITY20['expires']['curuser']);
            }
            if ($user_cache !== []) {
                $cache->update_row($cache_keys['user'].$CURUSER['id'], $user_cache, $TRINITY20['expires']['user_cache']);
            }
            sql_query('UPDATE users SET '.implode(', ', $updateset).' WHERE id = '.sqlesc($CURUSER['id'])) || sqlerr(__FILE__, __LINE__);
            $worked = '&pms=1';
            //=== redirect back with messages :P
            header('Location: pm_system.php?action=edit_mailboxes'.$worked);
            die();
            break;
    } //=== end of case / switch

} //=== end of $_POST stuff
//=== main page here :D
($res = sql_query('SELECT * FROM pmboxes WHERE userid='.sqlesc($CURUSER['id']).' ORDER BY name ASC')) || sqlerr(__FILE__, __LINE__);
if ($res->num_rows > 0) {
    //=== get all PM boxes for editing
    while ($row = $res->fetch_assoc()) {
        //==== get count from PM boxes
        ($res_count = sql_query('SELECT COUNT(id) FROM messages WHERE  location = '.sqlesc($row['boxnumber']).' AND receiver = '.sqlesc($CURUSER['id']))) || sqlerr(__FILE__,
            __LINE__);
        $arr_count = $res_count->fetch_row();
        $messages = (int)$arr_count[0];
        $all_my_boxes .= '
                    <tr>
                        <td>
                        <form action="pm_system.php" method="post">
                        <input type="hidden" name="action" value="edit_mailboxes">
                        <input type="hidden" name="action2" value="edit_boxes">'.$lang['pm_edmail_box'].''.((int)$row['boxnumber'] - 1).'<span style="font-weight: bold;">'.htmlsafechars($row['name']).':</span></td>
                        <td class="text-left" colspan="2"><input type="text" name="edit'.(0 + $row['id']).'" value="'.htmlsafechars($row['name']).'" style="text_default">'.$lang['pm_edmail_contain'].''.htmlsafechars($messages).''.$lang['pm_edmail_messages'].'</td>
                    </tr>';
    }
    $all_my_boxes .= '
                    <tr>
                        <td></td>
                        <td class="text-left" colspan="2">'.$lang['pm_edmail_names'].'<br>
                        '.$lang['pm_edmail_if'].'</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-left" colspan="2"><span style="font-weight: bold;">'.$lang['pm_edmail_note'].'</span>
                        <ul>
                            <li>'.$lang['pm_edmail_if1'].'</li>
                            <li>'.$lang['pm_edmail_if2'].'<a class="altlink" href="pm_system.php?action=view_mailbox">'.$lang['pm_edmail_main'].'</a>.</li>
                        </ul></td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="3">
                        <input type="submit" class="button" value="'.$lang['pm_edmail_edit'].'"</form></td>
                    </tr>';
} else {
    $all_my_boxes .= '
                    <tr>
                        <td></td>
                        <td class="text-center" colspan="2"><span style="font-weight: bold;">'.$lang['pm_edmail_nobox'].'</span><br></td>
                    </tr>';
}
//=== per page drop down
$per_page_drop_down = '<select name="change_pm_number">';
$i = 20;
while ($i <= ($maxbox > 200 ? 200 : $maxbox)) {
    $per_page_drop_down .= '<option class="body" value="'.$i.'" '.($CURUSER['pms_per_page'] == $i ? ' selected="selected"' : '').'>'.$i.''.$lang['pm_edmail_perpage'].'</option>';
    $i = ($i < 100 ? $i += 10 : ($i += 25));
}
$per_page_drop_down .= '</select>';
//==cats
($r = sql_query('SELECT id, image, name FROM categories WHERE min_class < '.sqlesc($CURUSER['class']).' ORDER BY name')) || sqlerr(__FILE__,
    __LINE__);
if ($r->num_rows > 0) {
    $categories .= "<table class='striped'><tr>";
    $i = 0;
    while ($a = $r->fetch_assoc()) {
        $categories .= ($i && $i % 2 == 0) ? "</tr><tr>" : "";
        $categories .= "<td class='bottom' style='padding-right: 5px'><input name='cat".(int)$a['id']."' type='checkbox' ".(strpos($CURUSER['notifs'],
                (string)"[cat{$a['id']}]") !== false ? " checked='checked'" : "")." value='yes'>&nbsp;<a class='catlink' href='browse.php?cat=".(int)$a['id']."'><img src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/".htmlsafechars($a['image'])."' alt='".htmlsafechars($a['name'])."' title='".htmlsafechars($a['name'])."'></a>&nbsp;".htmlsafechars($a["name"])."</td>\n";
        ++$i;
    }
    $categories .= "</tr></table>";
}
//=== make up page
$HTMLOUT .= '
<script type="text/javascript">
/*<![CDATA[*/
$(document).ready(function()	{
//=== cats
$("#cat_open").click(function() {
  $("#cat").slideToggle("slow", function() {

  });
});
});
/*]]>*/
</script>';
$HTMLOUT .= '<h1>'.$lang['pm_edmail_title'].'</h1>'.$h1_thingie.'
<div class="grid-x">
<div class="cell large-9 callout">
<div class="card-section">'.$lang['pm_edmail_add_mbox'].'<a data-toggle="info-message-box"><i class="fas fa-info-circle text-right"></i></a></div>
<div class="dropdown-pane" id="info-message-box" data-dropdown data-hover="true" data-hover-pane="true">
  '.$lang['pm_edmail_as_a'].''.get_user_class_name($CURUSER['class']).$lang['pm_edmail_you_may'].$maxboxes.$lang['pm_edmail_pm_box'].($maxboxes !== 1 ? $lang['pm_edmail_pm_boxes'] : '').''.$lang['pm_edmail_other'].'<br>'.$lang['pm_edmail_currently'].''.$res->num_rows.$lang['pm_edmail_custom'].($res->num_rows !== 1 ? $lang['pm_edmail_custom_es'] : '').$lang['pm_edmail_may_add'].($maxboxes - $res->num_rows).''.$lang['pm_edmail_more_extra'].'<br><br>
        <span style="font-weight: bold;">'.$lang['pm_edmail_following'].'</span>'.$lang['pm_edmail_chars'].'<br>
</div>';
$HTMLOUT .= '<form action="pm_system.php" method="post">
        <input type="hidden" name="action2" value="add">';
//=== make loop for oh let's say 5 boxes...
for ($i = 1; $i < 6; $i++) {
    $HTMLOUT .= '
<div class="input-group">
  <span class="input-group-label">'.$lang['pm_edmail_box'].''.$i.':</span>
  <input class="input-group-field" type="text" name="new[]" maxlength="100">
  <div class="input-group-button">
    <input type="submit" class="button" name="move" value="'.$lang['pm_edmail_add'].'">
  </div>
  <span class="input-group-label"><a data-toggle="info-input-box"><i class="fas fa-info-circle text-right"></i></a></span>
</div>
			<div class="dropdown-pane" id="info-input-box" data-dropdown data-hover="true" data-hover-pane="true">
			'.$lang['pm_edmail_only_fill'].'
		'.$lang['pm_edmail_blank'].'
			</div>';
}
$HTMLOUT .= '</form>';
$HTMLOUT .= '<form action="pm_system.php" method="post">
        <input type="hidden" name="action" value="edit_mailboxes">
<div class="card-section">'.$lang['pm_edmail_ed_del'].'</div>
        '.$all_my_boxes.'</form>
	</div>
	<div class="cell large-3">
	<ul class="vertical menu">'.$top_links.'</ul></div></div>';
?>
