<?php

require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'bbcode_functions.php');
require_once (INCL_DIR . 'pager_functions.php');
require_once (INCL_DIR . 'comment_functions.php');
require_once (INCL_DIR . 'add_functions.php');
require_once (INCL_DIR . 'html_functions.php');
require_once (INCL_DIR . 'function_rating.php');
//require_once (INCL_DIR . 'tvrage_functions.php');
//require_once (INCL_DIR . 'tvmaze_functions.php');// uncomment to use tvmaze
require_once (IMDB_DIR . 'imdb.class.php');
require_once (INCL_DIR . 'getpre.php');

$IMDB = new IMDB('https://www.imdb.com/title/tt1051906/');
if ($IMDB->isReady) {
    $cast = ($IMDB->getCastAndCharacter(0,false));
    $country =($IMDB->getCountry());
    $country = explode("/",$country);
    $cast = explode("/",$cast);
    $description = $IMDB->getDescription();
    $director = $IMDB->getDirector();
    $director = explode("/",$director);
    $genre =$IMDB->getGenre();
    $genre = explode("/",$genre);
    $location =$IMDB->getLocation();
    $location = explode(",",$location);
    $plot =$IMDB->getPlot();
    $poster =$IMDB->getPoster("small",true);
    $poster ="./imdb/".$poster;
    $rating =$IMDB->getRating();
    $releasedate = $IMDB->getReleaseDate();
    $releasedates = $IMDB->getReleaseDates();
    $runtime =$IMDB->getRuntime();
    $season = $IMDB->getSeasons();
	$season = explode("/",$season);
    $title = $IMDB->getTitle();
    $url =$IMDB->getUrl();
    $year = $IMDB->getYear();
	$rating = $IMDB->getRating();
	$writer = $IMDB->getWriter();
	$cast_images = $IMDB->getCastImages();
	$HTMLOUT = '';
	 $HTMLOUT.= $description;
	 $HTMLOUT.= "<br> <br>";
	$HTMLOUT.= '
	<table class="table">
  <thead>
    <tr>
      <th scope="col"></th>
      <th scope="col"></th>
      <th scope="col"></th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row"></th>
      <td>';
	foreach (array_slice($cast_images, 0, 5) as $cst_img) {
	$HTMLOUT.= '<img src="'.$cst_img.'" alt="..." class="img-thumbnail">'. '</br>';
}
$HTMLOUT.='</td>
      <td>';
	  	foreach (array_slice($cast, 0, 5) as $cst) {
	$HTMLOUT.= '<p>'.$cst.'</p>';
}
	  $HTMLOUT.= '</td>
      <td>@mdo</td>
    </tr>
  </tbody>
</table>';
	$HTMLOUT.= "<br> <br>";
	$HTMLOUT.= $writer;
	$HTMLOUT.= "<br> <br>";
	$HTMLOUT.= $rating;
	$HTMLOUT.= "<br> <br>";
$HTMLOUT.= "<br> <br>";
	foreach ($country as $cntry) {
	$HTMLOUT.= $cntry . ',';
}
$HTMLOUT.= "<br> <br>";
	foreach ($director as $dir) {
	$HTMLOUT.= $dir;
}
	$HTMLOUT.= "<br> <br>";

// foreach loop
foreach ($genre as $gen) {
	$HTMLOUT.= $gen . '/';
}
$HTMLOUT.= "<br> <br>";
    //print_r($genre);
	foreach ($location as $loc) {
	$HTMLOUT.= $loc . ',';
}
    $HTMLOUT.= $plot;$HTMLOUT.= "<br> <br>";
    $HTMLOUT.= $poster;$HTMLOUT.= "<br> <br>";
    $HTMLOUT.= $rating;$HTMLOUT.= "<br> <br>";
    $HTMLOUT.= $releasedate;$HTMLOUT.= "<br> <br>";
    $HTMLOUT.= $releasedates;$HTMLOUT.= "<br> <br>";
    $HTMLOUT.= $runtime;$HTMLOUT.= "<br> <br>";
    //print_r($season);$HTMLOUT.= "<br> <br>";
    $HTMLOUT.= $title;$HTMLOUT.= "<br> <br>";
    $HTMLOUT.= $year;$HTMLOUT.= "<br> <br>";
    $HTMLOUT.= $url;
} else {
    $HTMLOUT.= 'Movie not found. 😞';
}


echo $HTMLOUT;

?>
