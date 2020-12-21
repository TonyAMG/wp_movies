<?php

$movies = unserialize(file_get_contents(__DIR__ .'/movies.txt'));
//ksort($movies, SORT_NUMERIC);



for ($i = 1; $i <= 1000; $i++) {

    $movie = array_shift($movies);


    $poster_path = 'https://image.tmdb.org/t/p/w342'.$movie['poster_path'];
    $genres = extractArrayParam('genres', 'name', $movie);
    $budget = prepareFinance('budget', $movie);
    $revenue = prepareFinance('revenue', $movie);
    $runtime = prepareRuntime($movie['runtime']);
    $countries = extractArrayParam('production_countries', 'iso_3166_1', $movie);

    $post_arr = [
        'id'           => $movie['id'],
        'post_title'   => $movie['title'],
        'post_content' => '<img src="https://image.tmdb.org/t/p/w342'.$movie['poster_path'].'" alt="'.$movie['original_title'].'">'.'<p>'.$movie['overview'].'</p>',
        'post_name'    => $movie['original_title'],
        'post_status'  => 'publish',
        'post_author'  => '1',
        'post_type'    => 'movie',
        //'tax_input'    => array(
        //    "video_category" => $taxnomy_ids //Video Cateogry is Taxnmony Name and being used as key of array.
        //),
        'meta_input'   => [
            'title'                  => $movie['title'],
            'original_title'         => $movie['original_title'],
            'poster_path'            => $poster_path,
            'overview'               => $movie['overview'],
            'genres'                 => $genres,
            'release_date'           => $movie['release_date'],
            'budget'                 => $budget,
            'revenue'                => $revenue,
            'runtime'                => $runtime,
            'production_countries'   => $countries,
            'original_id'            => $movie['id']
        ],
    ];

    wp_insert_post( $post_arr, true);
}




function extractArrayParam($type, $subtype, array $response)
{
    if (empty($response[$type]))
        return 'неизвестно';
    $param = '';
    foreach ($response[$type] as $key => $value) {
        $param .= $value[$subtype].' ';
    }
    return str_replace(' ', ', ', trim($param));
}


function prepareFinance($type, array $response): string
{
    if ($response[$type] === 0 || !isset($response[$type])) {
        return 'неизвестно';
    } else {
        return '$'.number_format($response[$type], "0", "", ", ");
    }
}


function prepareRuntime($runtime): string
{
    if ($runtime === 0){
        return 'неизвестно';
    } else {
        return $runtime.' мин. / '.floor($runtime / 60) .' ч. '. $runtime % 60 .' мин.';
    }
}