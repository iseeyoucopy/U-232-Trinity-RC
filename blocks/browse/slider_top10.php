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
$HTMLOUT .= '<div class="grid-x grid-margin-x">
  <div class="cell large-6 large-offset-3">
  <div class="orbit" role="region" aria-label="Favorite Space Pictures" data-orbit>
  <div class="orbit-wrapper">
    <div class="orbit-controls">
      <button class="orbit-previous"><span class="show-for-sr">Previous Slide</span>&#9664;&#xFE0E;</button>
      <button class="orbit-next"><span class="show-for-sr">Next Slide</span>&#9654;&#xFE0E;</button>
    </div>
    <ul class="orbit-container">
      <li class="is-active orbit-slide">';
require_once(BLOCK_DIR.'browse/top10_torrents_24.php');
$HTMLOUT .= '
      </li>
      <li class="orbit-slide">';
require_once(BLOCK_DIR.'browse/top10_movies_24.php');
$HTMLOUT .= '
      </li>
      <li class="orbit-slide">';
require_once(BLOCK_DIR.'browse/top10_movies_week.php');
$HTMLOUT .= '
      </li>
      <li class="orbit-slide">';
require_once(BLOCK_DIR.'browse/top10_movies_all.php');
$HTMLOUT .= '
      </li>
      <li class="orbit-slide">';
require_once(BLOCK_DIR.'browse/top10_tv_24.php');
$HTMLOUT .= '
      </li>
      <li class="orbit-slide">';
require_once(BLOCK_DIR.'browse/top10_tv_week.php');
$HTMLOUT .= '
      </li>
      <li class="orbit-slide">';
require_once(BLOCK_DIR.'browse/top10_tv_all.php');
$HTMLOUT .= '
      </li>
      <li class="orbit-slide">';
require_once(BLOCK_DIR.'browse/top10_music_24.php');
$HTMLOUT .= '
      </li>
      <li class="orbit-slide">';
require_once(BLOCK_DIR.'browse/top10_music_week.php');
$HTMLOUT .= '
      </li>
      <li class="orbit-slide">';
require_once(BLOCK_DIR.'browse/top10_music_all.php');
$HTMLOUT .= '
      </li>
      <li class="orbit-slide">';
require_once(BLOCK_DIR.'browse/top10_other_all.php');
$HTMLOUT .= '
      </li>
      <li class="orbit-slide">';
require_once(BLOCK_DIR.'browse/mow.php');
$HTMLOUT .= '
      </li>
    </ul>
  </div>
  <nav class="orbit-bullets">
    <button class="is-active" data-slide="0">
      <span class="show-for-sr">First slide details.</span>
      <span class="show-for-sr" data-slide-active-label>Current Slide</span>
    </button>
    <button data-slide="1"><span class="show-for-sr">Second slide details.</span></button>
    <button data-slide="2"><span class="show-for-sr">Third slide details.</span></button>
    <button data-slide="3"><span class="show-for-sr">Fourth slide details.</span></button>
    <button data-slide="4"><span class="show-for-sr"></span></button>
    <button data-slide="5"><span class="show-for-sr"></span></button>
    <button data-slide="6"><span class="show-for-sr"></span></button>
    <button data-slide="7"><span class="show-for-sr"></span></button>
    <button data-slide="8"><span class="show-for-sr"></span></button>
    <button data-slide="9"><span class="show-for-sr"></span></button>
    <button data-slide="10"><span class="show-for-sr"></span></button>   
  </nav>
</div></div></div>';
