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
//==Staff tools quick link - Stoner
if ($CURUSER['class'] >= UC_STAFF) {
    $htmlout .= '<div class="off-canvas position-left" id="StaffPanel" data-off-canvas>
    <!-- Close button -->
    <button class="close-button" aria-label="Close menu" type="button" data-close>
      <span aria-hidden="true">&times;</span>
    </button>';
    $mysql_data = $cache->get('is_staff_'.$CURUSER['class']);
    if ($mysql_data === false || is_null($mysql_data)) {
        ($res = sql_query('SELECT * FROM staffpanel WHERE av_class <= '.sqlesc($CURUSER['class']).' ORDER BY page_name ASC')) || sqlerr(__FILE__,
            __LINE__);
        while ($arr = $res->fetch_assoc()) {
            $mysql_data = (array)$mysql_data;
            $mysql_data[] = $arr;
    }
    $cache->set('is_staff_'.$CURUSER['class'], $mysql_data, $TRINITY20['expires']['staff_check']);
  }
    if ($mysql_data) {
        $htmlout .= '<div class="card-divider">User</div><ul class="menu vertical">';
        foreach ($mysql_data as $key => $value) {
          if (is_array($value)) {
            if ($value['av_class'] <= $CURUSER['class'] && $value['type'] == 'user') {
                $htmlout .= '<li><a href="'.htmlsafechars($value["file_name"]).'">'.htmlsafechars($value["page_name"]).'</a></li>';
            }
          }
        }
        $htmlout .= '</ul>';
        $htmlout .= '<div class="card-divider">Settings</div><ul class="menu vertical">';
        foreach ($mysql_data as $key => $value) {
          if (is_array($value)) {
            if ($value['av_class'] <= $CURUSER['class'] && $value['type'] == 'settings') {
                $htmlout .= '<li><a href="'.htmlsafechars($value["file_name"]).'">'.htmlsafechars($value["page_name"]).'</a></li>';
            }
          }
        }
        $htmlout .= '</ul>';
        $htmlout .= '<div class="card-divider">Stats</div><ul class="menu vertical">';
        foreach ($mysql_data as $key => $value) {
          if (is_array($value)) {
            if ((int)$value['av_class'] <= $CURUSER['class'] && htmlsafechars($value['type']) == 'stats') {
                $htmlout .= '<li><a href="'.htmlsafechars($value["file_name"]).'">'.htmlsafechars($value["page_name"]).'</a></li>';
            }
          }
        }
        $htmlout .= '</ul>';
        $htmlout .= '<div class="card-divider">Other</div><ul class="menu vertical">';
        foreach ($mysql_data as $key => $value) {
          if (is_array($value)) {
            if ((int)$value['av_class'] <= $CURUSER['class'] && htmlsafechars($value['type']) == 'other') {
                $htmlout .= '<li><a href="'.htmlsafechars($value["file_name"]).'">'.htmlsafechars($value["page_name"]).'</a></li>';
            }
          }
        }
        $htmlout .= '</ul>';
    }
}
$htmlout .= '</div>';
//== End
// End Class
// End File
