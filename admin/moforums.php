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
	<div style='font-size:33px;color:white;background-color:red;text-align:center;'>Incorrect access<br />You cannot access this file directly.</div>
	</body></html>";
    echo $HTMLOUT;
    exit();
}
require_once(INCL_DIR.'user_functions.php');
require_once(INCL_DIR.'html_functions.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);


$lang = array_merge($lang, load_language('forums'));
$id = isset($_GET['id']) && is_valid_id($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) && is_valid_id($_POST['id']) ? (int)$_POST['id'] : 0);
$v_do = ['edit', 'process_edit', 'process_add', 'delete', ''];
$do = isset($_GET['do']) && in_array($_GET['do'], $v_do) ? htmlspecialchars($_GET['do']) : (isset($_POST['do']) && in_array($_POST['do'],
    $v_do) ? htmlspecialchars($_POST['do']) : '');
$this_url = 'staffpanel.php?tool=moforums&action=moforums';
switch ($do) {
    case 'delete' :
        if ($id === 0) {
            stderr($lang['forum_mngr_err1'], $lang['forum_mngr_warn3']);
        }
        if (sql_query('DELETE FROM over_forums where id = '.sqlesc($id))) {
            header('Refresh:2; url='.$this_url);
            stderr($lang['forum_mngr_succ'], $lang['forum_mngr_rdct1']);
        } else {
            stderr($lang['forum_mngr_err1'], $lang['forum_mngr_err2'].$mysqli->error);
        }
        break;
    case 'process_add' :
    case 'process_edit' :

        foreach (['name' => 1, 'description' => 1, 'minclassview' => 0, 'sort' => 0] as $key => $empty_check) {
            if ($empty_check && empty($_POST[$key])) {
                stderr($lang['forum_mngr_err1'], $lang['forum_mngr_err3']);
            } else {
                ${$key} = sqlesc($_POST[$key]);
            }
        }
        $explode_do = explode('_', $do);
        switch (end($explode_do)) {
            case 'add':
                $res = 'INSERT INTO over_forums(name,description,forum_id,min_class_view,sort) VALUES('.$name.','.$description.', 1,'.$minclassview.','.$sort.')';
                $msg = $lang['forum_mngr_rdct2'];
                break;
            case 'edit':
                $res = 'UPDATE over_forums set name = '.$name.', description = '.$description.',forum_id = 1, min_class_view = '.$minclassview.', sort = '.$sort.' WHERE id = '.sqlesc($id);
                $msg = $lang['forum_mngr_rdct3'];
                break;
        }
        if (sql_query($res)) {
            header('Refresh:2; url='.$this_url);
            stderr($lang['forum_mngr_succ'], $msg);
        } else {
            stderr($lang['forum_mngr_err1'], $lang['forum_mngr_err2'].$mysqli->error);
        }
        break;
    case 'edit' :
    default :
        $htmlout = "<div class='row'><div class='col-md-12'><h2>{$lang['forum_mngr_title']}</h2>";
        ($r1 = sql_query('select name, id, description, min_class_view, forum_id, sort FROM over_forums ORDER BY sort')) || sqlerr(__FILE__,
            __LINE__);
        $f_count = $r1->num_rows;
        if (!$f_count) {
            $htmlout .= "<h2>{$lang['forum_mngr_err4']}</h2>";
        } else {
            $htmlout .= "<script type='text/javascript'>
				/*<![CDATA[*/
					function confirm_delete(id)
					{
						if(confirm(\"{$lang['forum_mngr_confirm']}\"))
						{
							self.location.href=\"".$this_url."&do=delete&id=\"+id;
						}
					}
				/*]]>*/
				</script>
				<table class='table table-bordered'>
					<tr>
						<td class='colhead' align='left'>{$lang['forum_mngr_name']}</td>
						<td class='colhead'>{$lang['forum_mngr_rd']}</td>
						<td class='colhead' colspan='2'>{$lang['forum_mngr_md']}</td>
					</tr>";
            while ($a = $r1->fetch_assoc()) {
                $htmlout .= "<tr onmouseover=\"this.bgColor='#999';\" onmouseout=\"this.bgColor='';\">
						<td align='left'><a href='forums.php?action=viewforum&amp;forumid=".(int)$a['id']."'>".htmlspecialchars($a['name'])."</a><br/><span class='small'>".htmlspecialchars($a['description'])."</span></td>
						<td>".get_user_class_name($a['min_class_view'])."</td>
					
						<td><a href='".$this_url."&amp;do=edit&amp;id=".(int)$a['id']."#edit'>{$lang['forum_mngr_edt']}</a></td>
						<td><a href='javascript:confirm_delete(".(int)$a['id'].");'>{$lang['forum_mngr_del']}</a></td>
					</tr>";
            }
            $htmlout .= "</table>";
        }
        $edit_action = false;
        if ($do == 'edit' && !$id) {
            $htmlout .= "<h2>{$lang['forum_mngr_warn3']}</h2>";
        }
        if ($do == 'edit' && $id) {
            ($r3 = sql_query('select name, id, description , min_class_view ,forum_id, sort FROM over_forums WHERE id ='.sqlesc($id))) || sqlerr(__FILE__,
                __LINE__);
            if (!$r3->num_rows) {
                $htmlout .= "<h2>{$lang['forum_mngr_warn4']}</h2>";
            } else {
                $edit_action = true;
                $a3 = $r3->fetch_assoc();
            }
        }

        $htmlout .= "<div class='row'><div class='col-md-12'><h2>".($edit_action ? ''.$lang['forum_mngr_edt1'].' <u>'.htmlspecialchars($a3['name']).'</u>' : $lang['forum_mngr_adn'])."</h2>";
        $htmlout .= "<form action='".$this_url."' method='post'>
	<table class='table table-bordered' id='edit'>
	<tr><td colspan='2' align='center' class='colhead'> ".($edit_action ? ''.$lang['forum_mngr_edt1'].' <u>'.htmlspecialchars($a3['name']).'</u>' : $lang['forum_mngr_adn'])."</td></tr>
	<tr><td align='right' valign='top'>{$lang['forum_mngr_name2']}</td><td align='left'><input type='text' value='".($edit_action ? htmlspecialchars($a3['name']) : '')."'name='name' size='40' /></td></tr>
	<tr><td align='right' valign='top'>{$lang['forum_mngr_desc']}</td><td align='left'><textarea rows='3' cols='38' name='description'>".($edit_action ? htmlspecialchars($a3['description']) : '')."</textarea></td></tr>";

        $classes = "<select name='#name'>";
        for ($i = UC_USER; $i <= UC_MAX; $i++) {
            $classes .= "<option value='".$i."'>".get_user_class_name($i)."</option>";
        }
        $classes .= "</select>";

        if ($edit_action) {
            $htmlout .= "
	<tr><td align='right' valign='top'>{$lang['forum_mngr_min']}</td><td align='left'>".str_replace([
                    '#name',
                    'value=\''.htmlspecialchars($a3['min_class_view']).'\'',
                ], ['minclassview', 'value=\''.htmlspecialchars($a3['min_class_view']).'\' selected=\'selected\''], $classes)."</td></tr>";
        } else {
            $htmlout .= "
	<tr><td align='right' valign='top'>{$lang['forum_mngr_name']}</td><td align='left'>".str_replace('#name', 'minclassview', $classes)."</td></tr>";
        }
        $htmlout .= "<tr><td align='right' valign='top'>{$lang['forum_mngr_rank']}</td>
	<td align='left'><select name='sort'>";
        for ($i = 0; $i <= $f_count + 1; $i++) {
            $htmlout .= "<option value='".$i."' ".($edit_action && $a3['sort'] == $i ? 'selected=\'selected\'' : '').">".(int)$i."</option>";
        }
        $htmlout .= "</select></td></tr>
	<tr><td align='center' class='colhead' colspan='2'>".($edit_action ? "<input type='hidden' name='do' value='process_edit' /><input type='hidden' name='id' value='".(int)$a3['id']."'/><input type='submit' value='{$lang['forum_mngr_edt2']}' />" : "<input type='hidden' name='do' value='process_add' /><input type='submit' value='{$lang['forum_mngr_add']}' />")."</td></tr>
	</table></form>";
        $htmlout .= "</div></div>";
        echo(stdhead($lang['forum_mngr_title']).$htmlout.stdfoot());
}

?>
