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
if (!defined('IN_TRINITY20_FORUM')) {
    $HTMLOUT = '';
    $HTMLOUT .= '<!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" lang="en">
        <head>
        <meta charset="'.charset().'">
        <title>ERROR</title>
        </head><body>
        <h1 style="text-align:center;">Error</h1>
        <p style="text-align:center;">How did you get here? silly rabbit Trix are for kids!.</p>
        </body></html>';
    echo $HTMLOUT;
    exit();
}
@ini_set('zlib.output_compression', 'Off');
@set_time_limit(0);
if (@ini_get('output_handler') == 'ob_gzhandler' && @ob_get_length() !== false) {
    @ob_end_clean();
    header('Content-Encoding:');
}
$id = (int)$_GET['attachmentid'];
if (!is_valid_id($id)) {
    die('Invalid Attachment ID!');
}
($at = sql_query("SELECT file_name, user_id, extension FROM attachments WHERE id=".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
($resat = $at->fetch_assoc()) || die('No attachment with that ID!');
$filename = $Multi_forum['configs']['attachment_dir'].'/'.$resat['file_name'];
if (!is_file($filename)) {
    die('Inexistent attachment.');
}
if (!is_readable($filename)) {
    die('Attachment is unreadable.');
}
if ((isset($_GET['subaction']) ? htmlsafechars($_GET['subaction']) : '') == 'delete') {
    if ($CURUSER['id'] != $resat["owner"] && $CURUSER['class'] < UC_STAFF) {
        die('Not your attachment to delete.');
    }
    unlink($filename);
    sql_query("DELETE attachments, attachmentdownloads ".
        "FROM attachments ".
        "LEFT JOIN attachmentdownloads ON attachmentdownloads.file_id = attachments.id ".
        "WHERE attachments.id=".sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    die("<font color='red'>File successfully deleted...</font>");
}
sql_query("UPDATE attachments SET times_downloaded=times_downloaded+1 WHERE id=".sqlesc($id)) || sqlerr(__FILE__, __LINE__);
($res = sql_query("SELECT file_id FROM attachmentdownloads WHERE file_id=".sqlesc($id)." AND user_id=".sqlesc($CURUSER['id']))) || sqlerr(__FILE__,
    __LINE__);
if ($res->num_rows == 0) {
    sql_query("INSERT INTO attachmentdownloads (file_id, username, user_id, date, times_downloaded) VALUES (".sqlesc($id).", ".sqlesc($CURUSER['username']).", ".sqlesc($CURUSER['id']).", ".TIME_NOW.", 1)") || sqlerr(__FILE__,
        __LINE__);
} else {
    sql_query("UPDATE attachmentdownloads SET times_downloaded = times_downloaded + 1 WHERE file_id = ".sqlesc($id)." AND user_id = ".sqlesc($CURUSER['id'])) || sqlerr(__FILE__,
        __LINE__);
}
$arr = 0;
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false); // required for certain browsers
header("Content-Type: ".$arr['extension']."");
header("Content-Disposition: attachment; filename=\"".basename($filename)."\";");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($filename));
readfile($filename);
exit();
