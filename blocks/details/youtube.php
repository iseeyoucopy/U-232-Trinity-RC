<?php
if (!empty($torrents['youtube'])) {
    $HTMLOUT .= $lang['details_youtube'] .'<hr>

        <object type="application/x-shockwave-flash" style="width:200px; height:120px;" data="'.str_replace('watch?v=', 'v/',
            $torrents['youtube']).'"><param name="movie" value="'.str_replace('embed/', 'v/', $torrents['youtube']).'" /></object><br /><a 
href=\''.htmlspecialchars($torrents['youtube']).'\' target=\'_blank\'>'.$lang['details_youtube_link'].'</a>';
} else {
    $HTMLOUT .= "No youtube data found";
}