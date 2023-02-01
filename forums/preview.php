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
        <meta charset="UTF-8" />
        <title>ERROR</title>
        </head><body>
        <h1 style="text-align:center;">Error</h1>
        <p style="text-align:center;">How did you get here? silly rabbit Trix are for kids!.</p>
        </body></html>';
    echo $HTMLOUT;
    exit();
}
$body = (isset($_POST['body']) ? htmlsafechars($_POST['body']) : '');
$HTMLOUT .= '<div class="card">
    <div class="card-divider">Preview Post</div>
    <div class="card-section">
        <div class="divTable">
            <div class"divTableBody">
                <div class="divTableRow">
                    <div class="divTableCell">'.avatar_stuff($CURUSER).'</div>
                    <div class="divTableCell">'.format_comment($body).'</div>
                </div>
            </div>
        </div>
    </div>
</div>';
echo stdhead('Preview').$HTMLOUT.stdfoot();
