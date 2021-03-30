<?php
if ((in_array($torrents['category'], $TRINITY20['movie_cats'])) && $torrents['url'] != '') {
    $IMDB = new IMDB($torrents['url']);
    $description = $IMDB->getDescription();
    $genre = $IMDB->getGenre();
    $genre = explode(",", $genre);
    $plot = $IMDB->getPlot();
    $poster = $IMDB->getPoster("small", true);
    $release_date = $IMDB->getReleaseDate();
    $poster = "/".$poster;
    $poster = "imgw.php".$poster;
    $runtime = $IMDB->getRuntime();
    $title = $IMDB->getTitle();
    $year = $IMDB->getYear();
    $rating = $IMDB->getRating();
    $writer = $IMDB->getWriter();
    $cast = ($IMDB->getCastAndCharacter(6, false));
    $cast_images = $IMDB->getCastImages(6, true, "mid");
    if (empty($torrents['poster'])) {
        sql_query('UPDATE torrents SET poster = '.sqlesc($poster).' WHERE id = '.$torrents['id']) || sqlerr(__FILE__, __LINE__);
    }

    //==The torrent cache
    $cache->update_row($keys['torrent_details'].$torrents['id'], [
        'poster' => $poster,
    ], 0);

    $imdb = '';
    $imdb .= "<div class='card-section'>";
    $imdb .= "<h4><strong>".$title." (".$year.")</strong>
        <strong><i class='far fa-star dandelion'></i>".$rating."</strong>/10
        </h4>
    <hr class='margin-0'>";
    $imdb .= ''.$runtime.'<strong> | </strong>'.$release_date.'<strong> | </strong> ';
    foreach ($genre as $gen) {
        $imdb .= $gen.'';
    }
    $imdb .= "<hr class='margin-0'>
         
    <hr class='margin-0'> 
    ".strip_tags($plot)."
    <div class='grid-x grid-margin-x'>";
    foreach($cast_images as $val) {
        $imdb .= "<div class='card-user-container'>
        <div class='cell auto'>
        <div class='card-user-avatar'>
            <img src='".$val."' class='user-image'></div>
            </div></div>";
    }
    $imdb .= "</div></div>";
    $HTMLOUT .= $imdb;
} 
if (empty($imdb) && (in_array($torrents['category'], $TRINITY20['movie_cats']))){
    $HTMLOUT .= "No Imdb data";
}