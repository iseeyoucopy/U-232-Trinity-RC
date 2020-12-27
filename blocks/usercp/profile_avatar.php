<?php
if (!empty($CURUSER['avatar']) && $CURUSER['av_w'] > 5 && $CURUSER['av_h'] > 5) 
	$HTMLOUT.= "<div class='col-md-2'>
	<table class='table table-bordered'>
		<tr><td><img class='img-polaroid' src='{$CURUSER['avatar']}' width='{$CURUSER['av_w']}' height='{$CURUSER['av_h']}' alt='' /></td></tr></table></div>";
else $HTMLOUT.= "<div class='col-md-2'>
	<table class='table table-bordered'><tr><td><img class='img-polaroid' src='{$INSTALLER09['pic_base_url']}forumicons/default_avatar.gif' alt='' /></td></tr>
		</table>
	</div>";