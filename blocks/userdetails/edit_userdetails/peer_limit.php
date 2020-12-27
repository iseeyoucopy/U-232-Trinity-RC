<?php
        // == Wait time
	$HTMLOUT.= "<br><div class='row'>
			<div class='col-sm-1'>{$lang['userdetails_waittime']}<input class='form-control' type='text' name='wait_time' value='" . (int)$user['wait_time'] . "'></div>";
        // ==end
        // == Peers limit
        if ($CURUSER['class'] >= UC_STAFF) $HTMLOUT.= "<div class='col-sm-1'>{$lang['userdetails_peerslimit']}<input class='form-control' type='text'' name='peers_limit' value='" . (int)$user['peers_limit'] . "'></div>";
        // ==end
        // == Torrents limit
        if ($CURUSER['class'] >= UC_STAFF) $HTMLOUT.= "<div class='col-sm-1'>{$lang['userdetails_torrentslimit']}<input class='form-control' type='text' name='torrents_limit' value='" . (int)$user['torrents_limit'] . "'></div><!--</div>-->";
        // ==end
?>