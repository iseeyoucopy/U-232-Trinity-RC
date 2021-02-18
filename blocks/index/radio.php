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
require_once ROOT_DIR.'radio.php';
$HTMLOUT .= "<div class='callout'>
	<h4 class='subheader'>{$TRINITY20['site_name']} Radio</h4>";
$HTMLOUT .= radioinfo($radio);
$HTMLOUT .= "</div>";
//==
// End Class
// End File
