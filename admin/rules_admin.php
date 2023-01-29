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
require_once(INCL_DIR.'password_functions.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$cache->delete($keys['rules']);

$lang = array_merge($lang, load_language('ad_rules'));

$params = array_merge($_GET, $_POST);
$params['mode'] = isset($params['mode']) ? htmlspecialchars($params['mode']) : '';
switch ($params['mode']) {
    case 'cat_new':
        New_Cat_Form();
        break;
    case 'cat_add':
        Do_Cat_Add();
        break;
    case 'cat_edit':
        Show_Cat_Edit_Form();
        break;
    case 'takeedit_cat':
        Do_Cat_Update();
        break;
    case 'cat_delete':
        Cat_Delete();
        break;
    case 'cat_delete_chk':
        Cat_Delete(true);
        break;
    case 'rules_new':
        New_Rules_Form();
        break;
    case 'rules_edit':
        Show_Rules_Edit();
        break;
    case 'takeedit_rules':
        Do_Rules_Update();
        break;
    case 'takeadd_rules':
        Do_Rules_Add();
        break;
    case 'rules_delete':
        Do_Rules_Delete();
        break;
    default:
        Do_show();
        break;
}
function Do_show()
{
    global $TRINITY20, $lang;
    ($sql = sql_query("SELECT * FROM rules_cat")) || sqlerr(__FILE__, __LINE__);
    if (!$sql->num_rows) {
        stderr("Error",
            "There Are No Categories. <span><a class='tiny button' href='{$TRINITY20['baseurl']}/staffpanel.php?tool=rules_admin&amp;mode=cat_new'>Add Category</a></span>");
    }
    $htmlout = '';
    $htmlout .= "
        <div class='card'>
            <div class='card-divider'>{$lang['rules_cat_title']}</div>
            <div class='card-section'>
                <div class='small button-group'>
                    <a class='button' href='{$TRINITY20['baseurl']}/staffpanel.php?tool=rules_admin&amp;mode=cat_new'>{$lang['rules_btn_newcat']}</a>
                    <a class='button' href='{$TRINITY20['baseurl']}/staffpanel.php?tool=rules_admin&amp;mode=rules_new'>{$lang['rules_btn_newrule']}</a>
                </div>
            <table>
            <thead>
                <tr>
                    <td>Id</td>
                    <td>Name</td>
                    <td>Shortcut</td>
                    <td>Min Class</td>
                    <td>Tools</td>
                </tr>
            </thead>";
    while ($arr = $sql->fetch_assoc()) {
        $htmlout .= "
        <tbody>
            <tr>
                <td>".(int)$arr['id']."</td>
                <td><a href='{$TRINITY20['baseurl']}/staffpanel.php?tool=rules_admin&amp;mode=rules_edit&amp;catid=".(int)$arr['id']."'>".htmlspecialchars($arr['name'])."</a></td>
                <td>".htmlspecialchars($arr['shortcut'])."</td>
                <td>".htmlspecialchars($arr['min_view'])."</td>
                <td>
                    <a href='{$TRINITY20['baseurl']}/staffpanel.php?tool=rules_admin&amp;mode=cat_edit&amp;catid=".(int)$arr['id']."'>
                        <span data-tooltip tabindex='1' title='{$lang['rules_edit']}'>
                            <i class='fas fa-edit'></i>
                        </span>
                    </a>
                    <a href='{$TRINITY20['baseurl']}/staffpanel.php?tool=rules_admin&amp;mode=cat_delete&amp;catid=".(int)$arr['id']."'>
                        <span data-tooltip tabindex='1' title='{$lang['rules_delete']}'>
                            <i class='fas fa-trash-alt'></i>
                        </span>
                    </a>
                </td>
            </tr>
        </tbody>";
    }
    $htmlout .= "</table></div></div>";
    echo stdhead("{$lang['rules_rules']}").$htmlout.stdfoot();
    exit();
}

// ===added delete
function Do_Rules_Delete()
{
    global $cache;
    if (!isset($_POST['fdata']) || !is_array($_POST['fdata'])) {
        stderr("Error", "Bad data!");
    }
    $id = [];
    foreach ($_POST['fdata'] as $k => $v) {
        if (isset($v['rules_id']) && !empty($v['rules_id'])) {
            $id[] = sqlesc((int)$v['rules_id']);
        }
    }
    if (count($id) === 0) {
        stderr("Error", "No rules selected!");
    }
    sql_query("DELETE FROM rules WHERE id IN( ".implode(',', $id)." )") || sqlerr(__FILE__, __LINE__);
    $cache->delete($keys['rules']);
    header("Refresh: 3; url=staffpanel.php?tool=rules_admin");
    stderr("Info", "Rules successfully Deleted! Please wait while you are redirected.");
}

// ====end
function Cat_Delete($chk = false)
{
    global $cache;
    $id = isset($_GET['catid']) ? (int)$_GET['catid'] : 0;
    if (!is_valid_id($id)) {
        stderr("Error", "Bad ID!");
    }
    if (!$chk) {
        stderr("Sanity Check!", "You're about to delete a rules category, this will delete ALL content within that category!
        <a class='button' href='staffpanel.php?tool=rules_admin&amp;catid={$id}&amp;mode=cat_delete_chk'><span style='font-weight: bold; color: green'>Continue?</span></a>
or <a href='staffpanel.php?tool=rules_admin'><span style='font-weight: bold; color: red'>Cancel?</span></a>");
    }
    sql_query("DELETE FROM rules WHERE type = ".sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    sql_query("DELETE FROM rules_cat WHERE id = ".sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    $cache->delete($keys['rules']);
    header("Refresh: 3; url=staffpanel.php?tool=rules_admin");
    stderr("Info", "Rules category deleted successfully! Please wait while you are redirected.");
}

function Show_Cat_Edit_Form()
{
    global $lang, $CURUSER;
    $htmlout = '';
    $maxclass = (int)$CURUSER['class'];
    if (!isset($_GET['catid']) || empty($_GET['catid']) || !is_valid_id($_GET['catid'])) {
        $htmlout .= Do_Error("Error", "No Section selected");
    }
    $cat_id = (int)$_GET['catid'];
    ($sql = sql_query("SELECT * FROM rules_cat WHERE id = ".sqlesc($cat_id))) || sqlerr(__FILE__, __LINE__);
    if (!$sql->num_rows) {
        stderr("SQL Error", "Nothing doing here!");
    }
    $htmlout .= "<div class='card'><table>
            <thead>
                <tr>
                <td class='colhead'>Name</td>
                <td class='colhead'>Shortcut</td>
                <td class='colhead'>Min Class</td></tr>
            </thead>";
    while ($row = $sql->fetch_assoc()) {
        $htmlout .= "<div class='card-divider'>Title No.".(int)$row['id']."</div>
        <div class='card-section'>
        <form name='inputform' method='post' action='staffpanel.php?tool=rules_admin'>
        <input type='hidden' name='mode' value='takeedit_cat'>
        <input type='hidden' name='cat' value='".(int)$row['id']."'>
        <tbody>
        <tr><td><input type='text' value='".htmlspecialchars($row['name'])."' name='name'></td>
        <td><input type='text' value='".htmlspecialchars($row['shortcut'])."' name='shortcut'></td>

        <td>
            <div class='input-group'>
                <select class='input-group-field'>name='min_view'>";
        for ($i = 0; $i <= $maxclass; ++$i) {
            $htmlout .= '<option value="'.$i.'"'.($row['min_view'] == $i ? " selected='selected'" : "").'">'.get_user_class_name($i).'</option>';
        }
        $htmlout .= "</select>
                <div class='input-group-button'>
                    <input class='button' type='submit' name='submit' value='Edit'>
                </div>
            </div></td>
        </tr>
        </tbody></form>
        </div>";
    }
    $htmlout .= "</table></div>";
    echo stdhead("Edit options").$htmlout.stdfoot();
    exit();
}

function Show_Rules_Edit()
{
    global $lang, $CURUSER;
    $htmlout = '';
    $maxclass = $CURUSER['class'];
    if (!isset($_GET['catid']) || empty($_GET['catid']) || !is_valid_id($_GET['catid'])) {
        stderr("Error", "No Section selected");
    }
    $cat_id = (int)$_GET['catid'];
    ($sql = sql_query("SELECT * FROM rules WHERE type = ".sqlesc($cat_id))) || sqlerr(__FILE__, __LINE__);
    if (!$sql->num_rows) {
        stderr("SQL Error", "Nothing doing here!");
    }
    $htmlout .= "<div class='card'><form name='compose' method='post' action='staffpanel.php?tool=rules_admin'>";
    while ($row = $sql->fetch_assoc()) {
        $htmlout .= "<div class='card-divider'><strong>Rules No.".(int)$row['id']."</strong></div>";
        $htmlout .= "<div class='card-section'>
            <div class='input-group'>
                <input class='input-group-field' type='text' value='".htmlspecialchars($row['title'])."' name='fdata[{$row['id']}][title]'>
                <input class='input-group-field' type='checkbox' name='fdata[{$row['id']}][rules_id]' value='".(int)$row['id']."'>
            </div>
            <textarea name='fdata[{$row['id']}][text]' rows='10' cols='20'>".htmlspecialchars($row['text'])."</textarea>
        </div>";
    }
    $htmlout .= "<div class='input-group'>
        <select class='input-group-field'  name='mode'>
            <option value=''>--- Select One ---</option>
            <option value='takeedit_rules'>Update Rules</option>
            <option value='rules_delete'>Delete Rules</option>
        </select>
        <div class='input-group-buttons'>
            <input type='submit' name='submit' value='With Selected' class='button'>
        </div>
        </div>
         </form></div>";
    echo stdhead("Edit options").$htmlout.stdfoot();
    exit();
}

function Do_Rules_Update()
{
    global $cache, $mysqli;
    $time = TIME_NOW;
    $updateset = [];
    if (!isset($_POST['fdata']) || !is_array($_POST['fdata'])) {
        stderr("Error", "Don't leave any fields blank");
    }
    foreach ($_POST['fdata'] as $k => $v) {
        $holder = '';
        if (isset($v['rules_id']) && !empty($v['rules_id'])) {
            foreach ([
                         'title',
                         'text',
                     ] as $x) {
                isset($v[$x]) && (empty($v[$x]) ? stderr('Error', "{$x} is empty") : ($holder .= "{$x} = ".sqlesc($v[$x]).", "));
            }
            $holder = substr($holder, 0, -1);
            $holder = rtrim($holder, ',');
            $updateset[] = "UPDATE rules SET {$holder} WHERE id = ".sqlesc((int)$v['rules_id']);
        }
    }

    foreach ($updateset as $x) {
        sql_query($x) || sqlerr(__FILE__, __LINE__);
    }
    if ($mysqli->affected_rows == -1) {
        stderr("SQL Error", "Update failed");
    }
    header("Refresh: 3; url=staffpanel.php?tool=rules_admin");
    $cache->delete($keys['rules']);
    stderr("Info", "Updated successfully! Please wait while you are redirected.");
}

function Do_Cat_Update()
{
    global $cache, $mysqli;
    $cat_id = (int)$_POST['cat'];
    $min_view = sqlesc((int)$_POST['min_view']);
    if (!is_valid_id($cat_id)) {
        stderr("Error", "No values");
    }
    if (empty($_POST['name']) || (strlen($_POST['name']) > 100)) {
        stderr("Error", "No value or value too big");
    }
    if (empty($_POST['shortcut']) || (strlen($_POST['shortcut']) > 100)) {
        stderr("Error", "No value or value too big");
    }
    $sql = "UPDATE rules_cat SET name = ".sqlesc(strip_tags($_POST['name'])).", shortcut = ".sqlesc($_POST['shortcut']).", min_view=$min_view WHERE id=".sqlesc($cat_id);
    sql_query($sql) || sqlerr(__FILE__, __LINE__);
    if ($mysqli->affected_rows == -1) {
        stderr("Warning", "Could not carry out that request");
    }
    header("Refresh: 3; url=staffpanel.php?tool=rules_admin");
    $cache->delete($keys['rules']);
    stderr("Info", "Updated successfully! Please wait while you are redirected.");
}

function Do_Cat_Add()
{
    global $TRINITY20, $cache, $mysqli;
    $htmlout = '';
    if (empty($_POST['name']) || strlen($_POST['name']) > 100) {
        stderr("Error", "Field is blank or length too long!");
    }
    if (empty($_POST['shortcut']) || strlen($_POST['shortcut']) > 100) {
        stderr("Error", "Field is blank or length too long!");
    }
    $cat_name = sqlesc(strip_tags($_POST['name']));
    $cat_scut = sqlesc(strip_tags($_POST['shortcut']));
    $min_view = sqlesc(strip_tags($_POST['min_view']));
    $sql = "INSERT INTO rules_cat (name, shortcut, min_view) VALUES ($cat_name, $cat_scut, $min_view)";
    sql_query($sql) || sqlerr(__FILE__, __LINE__);
    if ($mysqli->affected_rows == -1) {
        stderr("Warning", "Couldn't forefill that request");
    }
    $cache->delete($keys['rules']);
    $htmlout .= New_Cat_Form(1);
    echo stdhead("Add New Title").$htmlout.stdfoot();
    exit();
}

function Do_Rules_Add()
{
    global $lang, $cache, $mysqli;
    $cat_id = sqlesc((int)$_POST['cat']);
    if (!is_valid_id($cat_id)) {
        stderr("Error", "No id");
    }
    if (empty($_POST['title']) || empty($_POST['text']) || strlen($_POST['title']) > 100) {
        stderr("Error", "Field is blank or length too long! <a href='staffpanel.php?tool=rules_admin'>Go Back</a>");
    }
    $title = sqlesc(strip_tags($_POST['title']));
    $text = sqlesc($_POST['text']);
    $sql = "INSERT INTO rules (type, title, text) VALUES ($cat_id, $title, $text)";
    sql_query($sql) || sqlerr(__FILE__, __LINE__);
    if ($mysqli->affected_rows == -1) {
        stderr("Warning", "Couldn't forefill that request");
    }
    $cache->delete($keys['rules']);
    New_Rules_Form(1);
    exit();
}

function New_Cat_Form()
{
    global $CURUSER, $lang;
    $htmlout = '';
    $maxclass = $CURUSER['class'];
    $htmlout .= "<div class='card'>
        <div class='card-divider'>Add A New Title</div>
        <div class='card-section'>
            <form class='form-inline' name='inputform' method='post' action='staffpanel.php?tool=rules_admin'>
                <table>
                    <thead>
                        <tr>
                            <input type='hidden' name='mode' value='cat_add'>
                            <td>
                                <input placeholder='NAME' type='text' value='' name='name'>
                            </td>
                            <td>
                                <input placeholder='SHORTCUT' type='text' value='' name='shortcut'>
                            </td>
                            <td>
                                <select name='min_view'>";
    for ($i = 0; $i <= $maxclass; ++$i) {
        $htmlout .= '<option value="'.$i.'">'.get_user_class_name($i).'</option>';
    }
    $htmlout .= "</select>
                            </td>
                            <td colspan='3'>
                                <input class='form-control' type='submit' name='submit' value='Add' class='btn btn-default'>
                            </td>
                        </tr>
                    </thead>
                </table>
            </form>
        </div>
    </div>";
    echo stdhead("Add New Category").$htmlout.stdfoot();
    exit();
}

function New_Rules_Form()
{
    global $TRINITY20;
    $htmlout = '';
    ($sql = sql_query("SELECT * FROM rules_cat")) || sqlerr(__FILE__, __LINE__);
    if (!$sql->num_rows) {
        stderr("Error",
            "There Are No Categories. <a class='tiny button' href='{$TRINITY20['baseurl']}/staffpanel.php?tool=rules_admin&amp;mode=cat_add'>Add Category</a>");
    }
    $htmlout .= "<div class='card'>
        <div class='card-divider'>Add A New section</div>
        <div class='card-section'>
            <form name='inputform' method='post' action='staffpanel.php?tool=rules_admin'>
                <div class='input-group'>
                    <input class='input-group-field' type='hidden' name='mode' value='takeadd_rules'>
                    <input class='input-group-field' placeholder='TYPE' type='hidden'type='text' value='' name='type'>
                    <input class='input-group-field' placeholder='TITLE' type='text' value='' name='title'>
                <select class='input-group-field' name='cat'>
                <option value=''>--Select--</option>";
    while ($v = $sql->fetch_assoc()) {
        $htmlout .= "<option value='".(int)$v['id']."'>".htmlspecialchars($v['name'])."</option>";
    }
    $htmlout .= "</select>
                </div>
                <textarea name='text' rows='15' cols='20' class='textbox'></textarea><br />
                <input type='submit' name='save_cat' value='Add' class='button'>
            </form>
        </div>
    </div>";
    echo stdhead("Add New Rule").$htmlout.stdfoot();
    exit();
}

function Do_Info($text)
{
    global $TRINITY20;
    $info = "<div class='infohead'><img src='{$TRINITY20['pic_base_url']}warned0.gif' alt='Info' title='Info' /> Info</div><div class='infobody'>\n";
    $info .= $text;
    $info .= "</div>";
    $info .= "<a href='staffpanel.php?tool=rules_admin'>Go Back To Admin</a> Or Add another?";
    return $info;
}

function Do_Error($heading, $text)
{
    global $TRINITY20, $HTMLOUT;
    $htmlout = '';
    $htmlout .= "<div class='errorhead'><img src='{$TRINITY20['pic_base_url']}warned.gif' alt='Warned' /> $heading</div><div class='errorbody'>\n";
    $htmlout .= "$text\n";
    $htmlout .= "</div>";
    return $htmlout;
    echo stdhead("Error").$HTMLOUT.stdfoot();
    exit;
}

?>
