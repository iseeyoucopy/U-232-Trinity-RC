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
if (!empty($user_stats['modcomment'])) {
    $HTMLOUT .= '<div class="reveal" id="system-comments" data-reveal>
 '.format_comment($user_stats['modcomment']).'
 </div>';
}