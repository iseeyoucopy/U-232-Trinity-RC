<?php
if (in_array($torrents['category'], $TRINITY20['tv_cats'])) {
    $tvmaze_info = tvmaze($torrents);
    if ($tvmaze_info) {
        $HTMLOUT .= "<div class='card-section'>".$tvmaze_info."</div>";
    }
}