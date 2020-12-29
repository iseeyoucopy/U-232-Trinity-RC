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
if ($user["avatar"]) 
	$HTMLOUT.= "<div class='col-md-2'>
	<table class='table table-bordered'><tr><td><img class='img-polaroid' src='" . htmlsafechars($user["avatar"]) . "'></td></tr>
		</table>
	</div>";
else
		$HTMLOUT.= "<div class='col-md-2'>
	<table class='table table-bordered'><tr><td><img class='img-polaroid' src='{$TRINITY20['pic_base_url']}forumicons/default_avatar.gif'></td></tr>
		</table>
	</div>";
//==end
// End Class
// End File
