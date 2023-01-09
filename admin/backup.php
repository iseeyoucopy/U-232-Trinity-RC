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
/**
 * Updated Database Backup Manager
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
class_check(UC_MAX);
$lang = array_merge($lang, load_language('ad_backup'));
/* add your ids and uncomment this check*/
$allowed_ids = [
    1,
];
if (!in_array($CURUSER['id'], $allowed_ids)) {
    stderr($lang['backup_stderr'], $lang['backup_stderr1']);
}
$HTMLOUT = '';
/**
 * Configs Start
 */
/**
 * Change to the class allowed to access this page, use an array for more classes
 *
 * example: $required_class = array(UC_SYSOP, UC_ADMINISTRATOR);
 */
$required_class = UC_MAX;
/**
 * Set to true to compress the backed up database using gzip
 */
$use_gzip = false;
/**
 * Set's the document root, change only if you know what you are doing
 */
$ROOT = $_SERVER['DOCUMENT_ROOT'].'/';
/**
 * The path to the gzip.exe file, no begining slash
 */
$gzip_path = $ROOT.'include/gzip/gzip.exe';
/**
 * The path to your backup folder, no begining/ending slash
 *
 * example: $backupdir = $ROOT.'include/backups';
 */
$backupdir = $TRINITY20['backup_dir'];
/**
 * The path to the mysqldump file, used to backup the databases
 */
//$mysqldump_path = 'c:/webdev/mysql/bin/mysqldump';
$mysqldump_path = '/usr/bin/mysqldump'; //==Linux

/**
 * Set to true, to be redirected to the download page after backup
 */
$autodl = false;
/**
 * Set to true, to automatically delete de file after download
 */
$autodel = false;
/**
 * Set to false if you don't want to write the actions to the log
 */
$write2log = true;
/**
 * Configs End
 */
if (is_array($required_class)) {
    if (!in_array($CURUSER['class'], $required_class)) {
        stderr($lang['backup_stderr'], $lang['backup_stderr']);
    }
} elseif ($CURUSER['class'] != $required_class) {
    stderr($lang['backup_stderr'], $lang['backup_stderr1']);
}
$mode = ($_GET['mode'] ?? $_POST['mode'] ?? '');
if (empty($mode)) {
    ($res = sql_query('SELECT db.id, db.name, db.added, u.id AS uid, u.username '.'FROM dbbackup AS db '.'LEFT JOIN users AS u ON u.id = db.userid '.'ORDER BY db.added DESC')) || sqlerr(__FILE__,__LINE__);
    if ($res->num_rows > 0) {
        $HTMLOUT .= "<div class='card'>
        <div class='card-divider'>".$lang['backup_welcome']."</div>
        <div class='card-section>
            <form method='post' action='staffpanel.php?tool=backup&amp;mode=delete'>
                <input type='hidden' name='action' value='delete'>
                <div class='table-scroll'>
                    <table>
                        <thead>
                            <tr>
                                <td>{$lang['backup_name']}</td>
                                <td>{$lang['backup_addedon']}</td>
                                <td>{$lang['backup_addedby']}</td>
                            </tr>
                        </thead>";
                    while ($arr = $res->fetch_assoc()) {
                        $HTMLOUT .= "
                        <tbody>
                            <tr>
                                <td>
                                    <a href='staffpanel.php?tool=backup&amp;mode=download&amp;id=".(int)$arr['id']."'>".htmlspecialchars($arr['name'])."</a>
                                </td>
                                <td>".get_date($arr['added'], 'DATE', 1, 0)."</td>
                                <td>";
                                if (!empty($arr['username'])) {
                                    $HTMLOUT .= "<a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$arr['uid']."'>".htmlspecialchars($arr['username'])."</a>";
                                } else {
                                    $HTMLOUT .= "unknown[".(int)$arr['uid']."]";
                                }
                                $HTMLOUT .= "</td>
                            </tr>
                        </tbody>";
                    }
                    $HTMLOUT .= "
                    </table>
                </div>
            </form>
        </div>
    </div>";
    } else {
        $HTMLOUT .= "<div class='card'>
            <div class='card-section'>".$lang['backup_nofound']."</div>
        </div>";
    }
    $HTMLOUT .="<div class='callout alert-callout-border primary'>
        <strong>".$lang['backup_options']."</strong>
        <p><a href='staffpanel.php?tool=backup&amp;mode=backup'>{$lang['backup_dbbackup']}</a>
            &nbsp;&nbsp;-&nbsp;&nbsp;<a href='staffpanel.php?tool=backup&amp;mode=check'>{$lang['backup_settingschk']}</a></p>
        </div>";
    if (!empty($_GET)) {
        $HTMLOUT .= "";
    }
    if (isset($_GET['backedup'])) {
        $HTMLOUT .= stdmsg($lang['backup_success'], $lang['backup_backedup']);
    } elseif (isset($_GET['deleted'])) {
        $HTMLOUT .= stdmsg($lang['backup_success'], $lang['backup_deleted']);
    } elseif (isset($_GET['noselection'])) {
        $HTMLOUT .= stdmsg($lang['backup_stderr'], $lang['backup_selectb']);
    }
    $HTMLOUT .= end_main_frame();
    echo stdhead($lang['backup_stdhead']).$HTMLOUT.stdfoot();
} elseif ($mode == "backup") {
    global $TRINITY20;
    $mysql_host = $TRINITY20['mysql_host'];
    $mysql_user = $TRINITY20['mysql_user'];
    $mysql_pass = $TRINITY20['mysql_pass'];
    $mysql_db = $TRINITY20['mysql_db'];
    $ext = $mysql_db.'-'.date('d').'-'.date('m').'-'.date('Y').'_'.date('H').'-'.date('i').'-'.date('s').'_'.date('D').".sql";
    $filepath = $backupdir.'/'.$ext;
    exec("$mysqldump_path --default-character-set=latin1 -h $mysql_host -u $mysql_user -p$mysql_pass $mysql_db > $filepath");
    if ($use_gzip) {
        exec($gzip_path.' '.$filepath);
    }
    sql_query("INSERT INTO dbbackup (name, added, userid) VALUES (".sqlesc($ext.($use_gzip ? '.gz' : '')).", ".TIME_NOW.", ".sqlesc($CURUSER['id']).")") || sqlerr(__FILE__,
        __LINE__);
    $location = 'mode=backup';
    if ($autodl) {
        $id = $mysqli->insert_id;
        $location = 'mode=download&id='.$id;
    }
    if ($write2log) {
        write_log($CURUSER['username'].'('.get_user_class_name($CURUSER['class']).') '.$lang['backup_successfully'].'');
    }
    header("Location: staffpanel.php?tool=backup");
} elseif ($mode == "download") {
    $id = (isset($_GET['id']) ? (int)$_GET['id'] : 0);
    if (!is_valid_id($id)) {
        stderr($lang['backup_stderr'], $lang['backup_id']);
    }
    ($res = sql_query("SELECT name FROM dbbackup WHERE id = ".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    $arr = $res->fetch_assoc();
    $filename = $backupdir.'/'.$arr['name'];
    //print $filename;
    //exit();
    if (!is_file($filename)) {
        stderr($lang['backup_stderr'], $lang['backup_inexistent']);
    }
    $file_extension = strtolower(substr(strrchr($filename, "."), 1));
    switch ($file_extension) {
        case "sql":
            $ctype = "application/sql";
            break;

        case "sql.gz":
        case "gz":
            $ctype = "application/x-gzip";
            break;

        default:
            $ctype = "application/force-download";
    }
    if ($write2log) {
        write_log($CURUSER['username'].'('.get_user_class_name($CURUSER['class']).') downloaded a database('.htmlspecialchars($arr['name']).').');
    }
    header('Refresh: 0; url=staffpanel.php'.($autodl && !$autodel ? '' : '?tool=backup&mode=delete&id='.$id));
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    header("Content-Type: $ctype");
    header("Content-Disposition: attachment; filename=\"".basename($filename)."\";");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".filesize($filename));
    readfile($filename);
} elseif ($mode == 'delete') {
    $ids = ($_POST["ids"] ?? (isset($_GET['id']) ? [
            $_GET['id'],
        ] : []));
    if (!empty($ids)) {
        foreach ($ids as $id) {
            if (!is_valid_id($id)) {
                stderr($lang['backup_stderr'], $lang['backup_id']);
            }
        }
        ($res = sql_query("SELECT name FROM dbbackup WHERE id IN (".implode(', ', array_map('sqlesc', $ids)).")")) || sqlerr(__FILE__, __LINE__);
        $count = $res->num_rows;
        if ($count > 0) {
            while ($arr = $res->fetch_assoc()) {
                $filename = $backupdir.'/'.$arr['name'];
                if (is_file($filename)) {
                    unlink($filename);
                }
            }
            sql_query('DELETE FROM dbbackup WHERE id IN ('.implode(', ', array_map('sqlesc', $ids)).')') || sqlerr(__FILE__, __LINE__);
            if ($write2log) {
                write_log($CURUSER['username'].'('.get_user_class_name($CURUSER['class']).') '.$lang['backup_deleted1'].' '.$count.($count > 1 ? $lang['backup_database_plural'] : $lang['backup_database_singular']).'.');
            }
            $location = 'backup';
        } else {
            $location = 'noselection';
        }
    } else {
        $location = 'noselection';
    }
    header('Location:staffpanel.php?tool=backup&mode='.$location);
} elseif ($mode == "check") {
    $HTMLOUT .= "<div class='card'>
        <div class='card-divider'>{$lang['backup_settingschk']} (<a href='staffpanel.php?tool=backup'>{$lang['backup_goback']}</a>)</div>
        <div class='card-section'>
            <div class='grid-x grid-padding-x small-up-2 medium-up-3 large-up-4'>
                <div class='cell'>
                    <div class='card'>
                        <div class='card-divider'>{$lang['backup_qzip']}</div>
                        <div class='card-section'>
                            {$lang['backup_optional']}
                            <b>".($use_gzip ? "<font color='green'>{$lang['backup_yes']}</font>" : "<font color='red'>{$lang['backup_no']}</font>")."</b>
                        </div>
                    </div>
                </div>
                <div class='cell'>
                    <div class='card'>
                        <div class='card-divider'>{$lang['backup_pathfolder']}</div>
                        <div class='card-section'>
                                ".$backupdir."
                            <b>".(is_dir($backupdir) ? "<font color='green'>{$lang['backup_yes']}</font>" : "<font color='red'>{$lang['backup_no']}</font>")."</b>
                        </div>
                    </div>
                </div>
                <div class='cell'>
                    <div class='card'>
                        <div class='card-divider'>{$lang['backup_readfolder']}</div>
                        <div class='card-section'>
                            <b>".(is_readable($backupdir) ? "<font color='green'>{$lang['backup_yes']}</font>" : "<font color='red'>{$lang['backup_no']}</font>")."</b>
                        </div>
                    </div>
                </div>
                <div class='cell'>
                    <div class='card'>
                        <div class='card-divider'>{$lang['backup_writable']}</div>
                        <div class='card-section'>
                            <b>".(is_writable($backupdir) ? "<font color='green'>{$lang['backup_yes']}</font>" : "<font color='red'>{$lang['backup_no']}</font>")."</b>
                        </div>
                    </div>
                </div>
                <div class='cell'>
                    <div class='card'>
                        <div class='card-divider'>{$lang['backup_mysqldump']}</div>
                        <div class='card-section'>
                            ".$mysqldump_path."
                            <b>".(false !== stripos(exec($mysqldump_path),
                                    "mysqldump") ? "<font color='green'>{$lang['backup_yes']}</font>" : "<font color='red'>{$lang['backup_no']}</font>")."</b>
                        </div>
                    </div>
                </div>
                <div class='cell'>
                    <div class='card'>
                        <div class='card-divider'>{$lang['backup_downafter']}</div>
                        <div class='card-section'>
                            <b>".($autodl ? "<font color='green'>{$lang['backup_yes']}</font>" : "<font color='red'>{$lang['backup_no']}</font>")."</b>
                        </div>
                    </div>
                </div>
                <div class='cell'>
                    <div class='card'>
                        <div class='card-divider'>{$lang['backup_delafter']}</div>
                        <div class='card-section'>
                            <b>".($autodel ? "<font color='green'>{$lang['backup_yes']}</font>" : "<font color='red'>{$lang['backup_no']}</font>")."</b>
                        </div>
                    </div>
                </div>
                <div class='cell'>
                    <div class='card'>
                        <div class='card-divider'>{$lang['backup_writeact']}</div>
                        <div class='card-section'>
                            <b>".($write2log ? "<font color='green'>{$lang['backup_yes']}</font>" : "<font color='red'>{$lang['backup_no']}</font>")."</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>";
    echo stdhead($lang['backup_stdhead']).$HTMLOUT.stdfoot();
} else {
    stderr($lang['backup_srry'], $lang['backup_unknow']);
}
?>
